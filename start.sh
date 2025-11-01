#!/bin/bash

echo "🚀 Starting Laravel application..."

# Set default port
export PORT=${PORT:-8000}

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
