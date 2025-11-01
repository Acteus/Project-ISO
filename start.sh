#!/bin/bash

# Exit on error
set -e

echo "🚀 Starting Laravel application..."

# Run migrations (Laravel will skip if nothing to migrate)
echo "📊 Running database migrations..."
php artisan migrate --force --no-interaction

echo "✨ Optimizing application..."
php artisan optimize:clear

# Start the PHP server
echo "🌐 Starting PHP server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
