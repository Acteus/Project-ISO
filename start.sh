#!/bin/bash

echo "🚀 Starting Laravel application..."

# Set default port
export PORT=${PORT:-8000}

# Set sane production defaults if not provided by environment
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export LOG_CHANNEL=${LOG_CHANNEL:-stderr}
export SESSION_DRIVER=${SESSION_DRIVER:-database}
export SESSION_SECURE_COOKIE=${SESSION_SECURE_COOKIE:-true}
export SESSION_SAME_SITE=${SESSION_SAME_SITE:-lax}
export SESSION_HTTP_ONLY=${SESSION_HTTP_ONLY:-true}

# Bridge Railway MySQL env vars to Laravel if DB_* not explicitly set
export DB_CONNECTION=${DB_CONNECTION:-mysql}
if [ -n "$MYSQLHOST" ] && [ -z "$DB_HOST" ]; then export DB_HOST="$MYSQLHOST"; fi
if [ -n "$MYSQLPORT" ] && [ -z "$DB_PORT" ]; then export DB_PORT="$MYSQLPORT"; fi
if [ -n "$MYSQLDATABASE" ] && [ -z "$DB_DATABASE" ]; then export DB_DATABASE="$MYSQLDATABASE"; fi
if [ -n "$MYSQLUSER" ] && [ -z "$DB_USERNAME" ]; then export DB_USERNAME="$MYSQLUSER"; fi
if [ -n "$MYSQLPASSWORD" ] && [ -z "$DB_PASSWORD" ]; then export DB_PASSWORD="$MYSQLPASSWORD"; fi

# Ensure required storage directories exist for sessions/views/cache
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views

# Generate an APP_KEY at runtime if missing to avoid boot failures
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating APP_KEY for runtime..."
    export APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
fi

# Run critical migrations first to ensure sessions table exists for health checks
echo "📊 Running critical migrations (sessions table)..."
php artisan migrate --force --no-interaction --path=database/migrations/0001_01_01_000000_create_sessions_table.php 2>&1 | while IFS= read -r line; do
    echo "  [Migration] $line"
done || echo "⚠️  Session migration may have already run or not exist"

# Start PHP server immediately in the background
echo "🌐 Starting PHP server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT &
SERVER_PID=$!

# Give server a moment to start
sleep 2

# Run remaining migrations and seed default admin in the background (non-blocking)
echo "📊 Running remaining database migrations and seeder in background..."
(
    php artisan migrate --force --no-interaction 2>&1 | while IFS= read -r line; do
        echo "  [Migration] $line"
    done
    php artisan db:seed --force --no-interaction 2>&1 | while IFS= read -r line; do
        echo "  [Seeder] $line"
    done
) &

# Wait for the PHP server process
echo "✅ Server started with PID $SERVER_PID"
wait $SERVER_PID
