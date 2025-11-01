#!/bin/bash

echo "🚀 Starting Laravel application..."

# Set default port
export PORT=${PORT:-8000}

# Set sane production defaults if not provided by environment
export APP_ENV=${APP_ENV:-production}
export APP_DEBUG=${APP_DEBUG:-false}
export LOG_CHANNEL=${LOG_CHANNEL:-stderr}
export SESSION_DRIVER=${SESSION_DRIVER:-file}

# Ensure required storage directories exist for sessions/views/cache
mkdir -p storage/framework/cache storage/framework/sessions storage/framework/views

# Generate an APP_KEY at runtime if missing to avoid boot failures
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating APP_KEY for runtime..."
    export APP_KEY=$(php -r "echo 'base64:'.base64_encode(random_bytes(32));")
fi

# Start PHP server immediately in the background
echo "🌐 Starting PHP server on port $PORT..."
php artisan serve --host=0.0.0.0 --port=$PORT &
SERVER_PID=$!

# Give server a moment to start
sleep 2

# Run migrations in the background (non-blocking)
echo "📊 Running database migrations in background..."
(
    php artisan migrate --force --no-interaction 2>&1 | while IFS= read -r line; do
        echo "  [Migration] $line"
    done
) &

# Wait for the PHP server process
echo "✅ Server started with PID $SERVER_PID"
wait $SERVER_PID
