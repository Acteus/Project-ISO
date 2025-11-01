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
    export DB_CONNECTION=mysql
    export DB_HOST="$MYSQLHOST"
    export DB_PORT="${MYSQLPORT:-3306}"
    export DB_DATABASE="$MYSQLDATABASE"
    export DB_USERNAME="$MYSQLUSER"
    export DB_PASSWORD="$MYSQLPASSWORD"
    echo "✅ Using MySQL database from Railway"
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
echo "   Database: ${DB_DATABASE}"
echo "   User: ${DB_USERNAME}"

# Try to connect with retries
MAX_DB_RETRIES=5
DB_RETRY_COUNT=0
DB_CONNECTED=false

while [ $DB_RETRY_COUNT -lt $MAX_DB_RETRIES ]; do
    if php artisan db:show 2>&1 | grep -q "Connection:"; then
        echo "   ✅ Database connection successful"
        DB_CONNECTED=true
        break
    else
        DB_RETRY_COUNT=$((DB_RETRY_COUNT + 1))
        echo "   ⚠️  Database connection attempt $DB_RETRY_COUNT/$MAX_DB_RETRIES failed"
        if [ $DB_RETRY_COUNT -lt $MAX_DB_RETRIES ]; then
            echo "   Retrying in 3 seconds..."
            sleep 3
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
    echo "   ⚠️  Database connection FAILED after $MAX_DB_RETRIES attempts!"
    echo "   Starting server with file-based sessions as fallback."
    echo "   The application will work but sessions won't persist across instances."
    echo ""
    echo "   Troubleshooting:"
    echo "   1. Verify MySQL service is attached in Railway"
    echo "   2. Check Railway environment variables"
    echo "   3. Ensure MYSQLHOST, MYSQLDATABASE, MYSQLUSER, MYSQLPASSWORD are set"
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
