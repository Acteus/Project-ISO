#!/bin/bash

echo "🚀 Starting Laravel application..."

# Set default port
export PORT=${PORT:-8000}

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
if [ -z "$APP_KEY" ]; then
    echo "🚨 WARNING: APP_KEY is not set!"
    echo "   This will cause a 500 error. Laravel REQUIRES an APP_KEY."
    echo "   Generate one with: php artisan key:generate --show"
    echo "   Then set it in Railway Variables tab as APP_KEY"
    echo ""
    echo "🔑 Generating temporary APP_KEY for this deployment..."
    echo "   ⚠️  NOTE: This key will change on next deployment, invalidating sessions!"
    echo "   ⚠️  Please set a fixed APP_KEY in Railway to prevent this."
    export APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
    echo "   Generated: ${APP_KEY:0:20}..."
fi

# Clear any cached config that might cause issues
echo "🧹 Clearing config cache..."
php artisan config:clear 2>&1 || echo "⚠️  Config clear skipped"
php artisan route:clear 2>&1 || echo "⚠️  Route clear skipped"
php artisan view:clear 2>&1 || echo "⚠️  View clear skipped"

# Test database connection before running migrations
echo "🔌 Testing database connection..."
if php artisan db:show 2>&1 | grep -q "Connection:"; then
    echo "✅ Database connection successful"
    
    # Run critical migrations first to ensure sessions table exists for health checks
    echo "📊 Running database migrations..."
    php artisan migrate --force --no-interaction 2>&1 | while IFS= read -r line; do
        echo "  [Migration] $line"
    done
else
    echo "⚠️  Database connection failed - will retry migrations in background"
fi

# Start PHP server immediately in the background
echo "🌐 Starting PHP server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT &
SERVER_PID=$!

# Give server a moment to start
sleep 2

# Seed default admin in the background (non-blocking)
echo "📊 Running database seeder in background..."
(
    sleep 5  # Wait for server to be fully up
    php artisan db:seed --force --no-interaction 2>&1 | while IFS= read -r line; do
        echo "  [Seeder] $line"
    done
) &

# Wait for the PHP server process
echo "✅ Server started with PID $SERVER_PID"
wait $SERVER_PID
