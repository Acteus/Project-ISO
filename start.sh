#!/bin/bash
set -e  # Exit on error

echo "🚀 Starting Laravel application..."
echo "================================================"

# Set default port (Railway sets this automatically)
export PORT=${PORT:-8080}

# Set sane production defaults if not provided by environment
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export LOG_CHANNEL=${LOG_CHANNEL:-stderr}
export LOG_LEVEL=${LOG_LEVEL:-info}
export SESSION_DRIVER=${SESSION_DRIVER:-database}
export SESSION_SECURE_COOKIE=${SESSION_SECURE_COOKIE:-true}
export SESSION_SAME_SITE=${SESSION_SAME_SITE:-lax}
export SESSION_HTTP_ONLY=${SESSION_HTTP_ONLY:-true}
export SESSION_DOMAIN=${SESSION_DOMAIN:-null}
export CACHE_STORE=${CACHE_STORE:-database}
export QUEUE_CONNECTION=${QUEUE_CONNECTION:-database}

# Set APP_URL from Railway's automatic domain if not set
if [ -z "$APP_URL" ]; then
    if [ -n "$RAILWAY_PUBLIC_DOMAIN" ]; then
        export APP_URL="https://${RAILWAY_PUBLIC_DOMAIN}"
        echo "   Setting APP_URL from Railway: $APP_URL"
    elif [ -n "$RAILWAY_STATIC_URL" ]; then
        export APP_URL="$RAILWAY_STATIC_URL"
        echo "   Setting APP_URL from Railway static: $APP_URL"
    else
        export APP_URL="http://localhost:${PORT}"
        echo "   ⚠️  Using fallback APP_URL: $APP_URL"
    fi
fi

echo "📋 Environment: $APP_ENV"
echo "🐛 Debug Mode: $APP_DEBUG"
echo "📝 Log Channel: $LOG_CHANNEL"

# Bridge Railway database env vars to Laravel if DB_* not explicitly set
# Support both PostgreSQL (PGHOST, etc.) and MySQL (MYSQLHOST, etc.)
if [ -n "$PGHOST" ] && [ -z "$DB_HOST" ]; then
    export DB_CONNECTION=pgsql
    export DB_HOST="$PGHOST"
    export DB_PORT="${PGPORT:-5432}"
    export DB_DATABASE="$PGDATABASE"
    export DB_USERNAME="$PGUSER"
    export DB_PASSWORD="$PGPASSWORD"
    echo "✅ Using PostgreSQL database from Railway"
elif [ -n "$MYSQLHOST" ] && [ -z "$DB_HOST" ]; then
    echo "🔍 Detected MySQL variables from Railway:"
    echo "   MYSQLHOST=${MYSQLHOST}"
    echo "   MYSQLPORT=${MYSQLPORT:-<not set>}"
    echo "   MYSQLDATABASE=${MYSQLDATABASE:-<not set>}"
    echo "   MYSQL_DATABASE=${MYSQL_DATABASE:-<not set>}"
    echo "   MYSQLUSER=${MYSQLUSER:-<not set>}"
    echo "   Has MYSQLPASSWORD: $([ -n "$MYSQLPASSWORD" ] && echo 'Yes' || echo 'No')"
    echo "   Has MYSQL_URL: $([ -n "$MYSQL_URL" ] && echo 'Yes' || echo 'No')"
    echo "   Has MYSQL_PRIVATE_URL: $([ -n "$MYSQL_PRIVATE_URL" ] && echo 'Yes' || echo 'No')"

    export DB_CONNECTION=mysql

    # Try to use MYSQL_PRIVATE_URL first if available (better for Railway private networking)
    if [ -n "$MYSQL_PRIVATE_URL" ]; then
        echo "   ℹ️  Parsing MYSQL_PRIVATE_URL for connection details..."
        # Extract components from URL: mysql://user:pass@host:port/database
        export DB_HOST=$(echo "$MYSQL_PRIVATE_URL" | sed -n 's|.*@\([^:]*\):.*|\1|p')
        export DB_PORT=$(echo "$MYSQL_PRIVATE_URL" | sed -n 's|.*:\([0-9]*\)/.*|\1|p')
        export DB_DATABASE=$(echo "$MYSQL_PRIVATE_URL" | sed -n 's|.*/\([^?]*\).*|\1|p')
        export DB_USERNAME=$(echo "$MYSQL_PRIVATE_URL" | sed -n 's|.*://\([^:]*\):.*|\1|p')
        export DB_PASSWORD=$(echo "$MYSQL_PRIVATE_URL" | sed -n 's|.*://[^:]*:\([^@]*\)@.*|\1|p')
        echo "   ℹ️  Parsed from MYSQL_PRIVATE_URL: host=$DB_HOST, port=$DB_PORT, db=$DB_DATABASE"
    elif [ -n "$MYSQL_URL" ]; then
        echo "   ℹ️  Parsing MYSQL_URL for connection details..."
        export DB_HOST=$(echo "$MYSQL_URL" | sed -n 's|.*@\([^:]*\):.*|\1|p')
        export DB_PORT=$(echo "$MYSQL_URL" | sed -n 's|.*:\([0-9]*\)/.*|\1|p')
        export DB_DATABASE=$(echo "$MYSQL_URL" | sed -n 's|.*/\([^?]*\).*|\1|p')
        export DB_USERNAME=$(echo "$MYSQL_URL" | sed -n 's|.*://\([^:]*\):.*|\1|p')
        export DB_PASSWORD=$(echo "$MYSQL_URL" | sed -n 's|.*://[^:]*:\([^@]*\)@.*|\1|p')
        echo "   ℹ️  Parsed from MYSQL_URL: host=$DB_HOST, port=$DB_PORT, db=$DB_DATABASE"
    else
        # Fallback to individual variables
        export DB_HOST="$MYSQLHOST"
        export DB_PORT="${MYSQLPORT:-3306}"

        # Railway might use MYSQLDATABASE or MYSQL_DATABASE, check both
        if [ -n "$MYSQLDATABASE" ]; then
            export DB_DATABASE="$MYSQLDATABASE"
        elif [ -n "$MYSQL_DATABASE" ]; then
            export DB_DATABASE="$MYSQL_DATABASE"
        else
            export DB_DATABASE="railway"
            echo "   ⚠️  WARNING: No database name found, using default 'railway'"
        fi

        export DB_USERNAME="${MYSQLUSER:-root}"
        export DB_PASSWORD="$MYSQLPASSWORD"
    fi

    echo "✅ Using MySQL database from Railway"
    echo "   Final configuration:"
    echo "   - Host: $DB_HOST"
    echo "   - Port: $DB_PORT"
    echo "   - Database: $DB_DATABASE"
    echo "   - Username: $DB_USERNAME"
else
    export DB_CONNECTION=${DB_CONNECTION:-pgsql}
    echo "⚠️  Using DB_CONNECTION=${DB_CONNECTION} with custom credentials"
fi

# Ensure required storage directories exist with proper permissions
mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views storage/logs bootstrap/cache
chmod -R 775 storage bootstrap/cache
# Create empty .gitignore files to ensure directories are preserved
touch storage/framework/cache/.gitignore storage/framework/sessions/.gitignore storage/framework/views/.gitignore storage/logs/.gitignore

# Check if APP_KEY is set (CRITICAL for Laravel to work)
echo ""
echo "🔑 Checking APP_KEY..."
if [ -z "$APP_KEY" ]; then
    echo "🚨 CRITICAL ERROR: APP_KEY is not set!"
    echo "   This WILL cause a 500 error. Laravel REQUIRES an APP_KEY."
    echo ""
    echo "   To fix permanently:"
    echo "   1. Run locally: php artisan key:generate --show"
    echo "   2. Copy the output (including 'base64:' prefix)"
    echo "   3. Set in Railway Variables tab: APP_KEY=base64:your_key_here"
    echo ""
    echo "🔑 Generating temporary APP_KEY for this deployment..."
    echo "   ⚠️  WARNING: This key will change on next deployment!"
    echo "   ⚠️  This will invalidate all sessions and encrypted data!"
    echo "   ⚠️  Please set a permanent APP_KEY in Railway immediately!"
    export APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
    echo "   ✓ Temporary key generated: ${APP_KEY:0:25}..."
else
    echo "   ✓ APP_KEY is set: ${APP_KEY:0:25}..."
fi

# Clear any cached config that might cause issues
echo "🧹 Clearing config cache..."
php artisan config:clear 2>&1 || echo "⚠️  Config clear skipped"
php artisan route:clear 2>&1 || echo "⚠️  Route clear skipped"
php artisan view:clear 2>&1 || echo "⚠️  View clear skipped"

# Test database connection before running migrations
echo ""
echo "🔌 Testing database connection..."
echo "   Connection: ${DB_CONNECTION}"
echo "   Host: ${DB_HOST}"
echo "   Port: ${DB_PORT}"
echo "   Database: ${DB_DATABASE}"
echo "   User: ${DB_USERNAME}"
echo "   Has Password: $([ -n "$DB_PASSWORD" ] && echo 'Yes' || echo 'No')"

# First, test if we can reach the database host (network connectivity)
echo ""
echo "🌐 Testing network connectivity to database host..."
if command -v nc &> /dev/null; then
    if timeout 5 nc -z "$DB_HOST" "$DB_PORT" 2>/dev/null; then
        echo "   ✅ Network connection to $DB_HOST:$DB_PORT is reachable"
    else
        echo "   ⚠️  Cannot reach $DB_HOST:$DB_PORT (network issue or MySQL not ready)"
        echo "   This could mean:"
        echo "   - MySQL service is not running"
        echo "   - MySQL service is still starting up"
        echo "   - Private networking is not enabled/configured"
        echo "   - Firewall is blocking the connection"
    fi
else
    echo "   ℹ️  nc (netcat) not available, skipping network test"
fi

# Try to connect with retries
echo ""
echo "🔄 Attempting database authentication..."
MAX_DB_RETRIES=5
DB_RETRY_COUNT=0
DB_CONNECTED=false

while [ $DB_RETRY_COUNT -lt $MAX_DB_RETRIES ]; do
    DB_RETRY_COUNT=$((DB_RETRY_COUNT + 1))
    echo "   Attempt $DB_RETRY_COUNT/$MAX_DB_RETRIES..."

    # Capture both stdout and stderr
    DB_TEST_OUTPUT=$(php artisan db:show 2>&1)

    if echo "$DB_TEST_OUTPUT" | grep -q "Connection:"; then
        echo "   ✅ Database connection successful!"
        DB_CONNECTED=true
        break
    else
        echo "   ⚠️  Connection failed"

        # Show error details to help diagnose
        if echo "$DB_TEST_OUTPUT" | grep -q "SQLSTATE"; then
            ERROR_MSG=$(echo "$DB_TEST_OUTPUT" | grep "SQLSTATE" | head -n 1)
            echo "   Error: $ERROR_MSG"

            # Provide specific guidance based on error
            if echo "$ERROR_MSG" | grep -q "HY000.*2002"; then
                echo "   → Database host is unreachable or MySQL is not running"
            elif echo "$ERROR_MSG" | grep -q "HY000.*1045"; then
                echo "   → Authentication failed - check username/password"
            elif echo "$ERROR_MSG" | grep -q "HY000.*1049"; then
                echo "   → Database '$DB_DATABASE' does not exist"
            elif echo "$ERROR_MSG" | grep -q "HY000.*2006"; then
                echo "   → MySQL server has gone away - check MySQL is running"
            fi
        fi

        if [ $DB_RETRY_COUNT -lt $MAX_DB_RETRIES ]; then
            WAIT_TIME=$((3 * $DB_RETRY_COUNT))  # Progressive backoff
            echo "   Waiting ${WAIT_TIME}s before retry..."
            sleep $WAIT_TIME
        fi
    fi
done

if [ "$DB_CONNECTED" = true ]; then
    # Run critical migrations first to ensure sessions table exists for health checks
    echo ""
    echo "📊 Running database migrations..."
    if php artisan migrate --force --no-interaction 2>&1 | tee /tmp/migration.log; then
        echo "   ✅ Migrations completed successfully"
    else
        echo "   ⚠️  MIGRATION FAILED! Starting server anyway with file sessions..."
        echo "   Common causes:"
        echo "   - Incorrect database credentials"
        echo "   - Database doesn't exist"
        echo "   - Insufficient permissions"
        cat /tmp/migration.log
        # Switch to file-based sessions as fallback
        export SESSION_DRIVER=file
        export CACHE_STORE=file
        DB_CONNECTED=false
    fi
else
    echo ""
    echo "❌ Database connection FAILED after $MAX_DB_RETRIES attempts!"
    echo ""
    echo "   The application will start with file-based sessions as fallback."
    echo "   ⚠️  WARNING: Sessions won't persist across instances!"
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo "📋 TROUBLESHOOTING CHECKLIST"
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    echo "1. ✓ Verify MySQL service exists in Railway:"
    echo "   - Go to your Railway project dashboard"
    echo "   - Check if MySQL service is present and running (green status)"
    echo "   - If not, click '+ New' → 'Database' → 'MySQL'"
    echo ""
    echo "2. ✓ Check MySQL service status:"
    echo "   - Click on your MySQL service in Railway"
    echo "   - Check the 'Deployments' tab - should show 'Active'"
    echo "   - Check 'Metrics' tab - CPU/Memory should show activity"
    echo "   - If crashed, check logs for errors"
    echo ""
    echo "3. ✓ Verify environment variables are set:"
    echo "   - Go to Laravel service → 'Variables' tab"
    echo "   - Check if these variables exist:"
    echo "     • MYSQLHOST or DB_HOST"
    echo "     • MYSQLPORT or DB_PORT"
    echo "     • MYSQLDATABASE or DB_DATABASE"
    echo "     • MYSQLUSER or DB_USERNAME"
    echo "     • MYSQLPASSWORD or DB_PASSWORD"
    echo "   - Or check for MYSQL_URL / MYSQL_PRIVATE_URL"
    echo ""
    echo "4. ✓ Link MySQL service to Laravel service:"
    echo "   - In Railway, MySQL should be 'connected' to Laravel"
    echo "   - If not linked: Laravel service → Settings → scroll down"
    echo "   - Under 'Service Connections', add MySQL service"
    echo "   - Railway will auto-inject MYSQL* variables"
    echo ""
    echo "5. ✓ Enable Private Networking (REQUIRED for *.railway.internal):"
    echo "   - Go to Project Settings"
    echo "   - Enable 'Private Networking' if disabled"
    echo "   - Redeploy both services after enabling"
    echo ""
    echo "6. ✓ Check MySQL configuration:"
    echo "   - In MySQL service, check 'Variables' tab"
    echo "   - MYSQL_ROOT_PASSWORD should be set"
    echo "   - MYSQL_DATABASE should be set (default: 'railway')"
    echo ""
    echo "7. ✓ Verify credentials match:"
    echo "   Current config (from environment):"
    echo "   - Host: ${DB_HOST:-<not set>}"
    echo "   - Port: ${DB_PORT:-<not set>}"
    echo "   - Database: ${DB_DATABASE:-<not set>}"
    echo "   - Username: ${DB_USERNAME:-<not set>}"
    echo "   - Has Password: $([ -n "$DB_PASSWORD" ] && echo 'Yes' || echo 'No')"
    echo ""
    echo "8. ✓ Common fixes that work:"
    echo "   a) Unlink and re-link MySQL service to Laravel service"
    echo "   b) Redeploy both MySQL and Laravel services"
    echo "   c) Use MYSQL_PRIVATE_URL instead of individual vars"
    echo "   d) Check Railway Status page for platform issues"
    echo ""
    echo "━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━"
    echo ""
    # Switch to file-based sessions as fallback
    export SESSION_DRIVER=file
    export CACHE_STORE=file
fi

# Start PHP server immediately in the background
echo ""
echo "🌐 Starting PHP server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT &
SERVER_PID=$!

# Give server a moment to start
sleep 2

# Check if server is actually running
if ps -p $SERVER_PID > /dev/null; then
    echo "   ✅ Server started successfully with PID $SERVER_PID"
else
    echo "   ❌ Server failed to start!"
    exit 1
fi

# Seed default admin in the background (non-blocking, only if DB connected)
if [ "$DB_CONNECTED" = true ]; then
    echo ""
    echo "📊 Running database seeder in background..."
    (
        sleep 5  # Wait for server to be fully up
        echo "   Seeding admin account..."
        if php artisan db:seed --force --no-interaction 2>&1 | tee /tmp/seeder.log; then
            echo "   ✅ Database seeding completed"
        else
            echo "   ⚠️  Database seeding failed (non-critical)"
            cat /tmp/seeder.log || true
        fi
    ) &
else
    echo "   ⚠️  Skipping database seeding (no database connection)"
fi

echo ""
echo "================================================"
echo "✅ Application started successfully!"
echo "📍 Server listening on: http://0.0.0.0:$PORT"
echo "🏥 Health check endpoint: http://0.0.0.0:$PORT/up"
echo "📱 Public URL: ${APP_URL:-Railway will set this}"
echo "================================================"

# Wait for the PHP server process
wait $SERVER_PID
