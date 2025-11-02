#!/bin/bash

# Cloudways Deployment Script for Laravel
# This script automates the deployment process

set -e

echo "🚀 Starting deployment..."

# Navigate to project directory
cd /home/1543265.cloudwaysapps.com/kxvekkgpkz/public_html

# Put application in maintenance mode
echo "📦 Enabling maintenance mode..."
php artisan down || true

# Pull latest changes (if using git)
if [ -d ".git" ]; then
    echo "📥 Pulling latest changes from git..."
    git pull origin deployment/laravel-cloud
fi

# Install/Update PHP dependencies
echo "📦 Installing composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Clear all caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Run database migrations
echo "🗄️  Running database migrations..."
php artisan migrate --force

# Optimize application
echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set correct permissions
echo "🔒 Setting file permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

# Restart services
echo "♻️  Restarting services..."
# Uncomment if using supervisor/queue workers
# supervisorctl restart all

# Bring application back online
echo "✅ Disabling maintenance mode..."
php artisan up

echo "🎉 Deployment completed successfully!"
echo "📊 Application version:"
php artisan --version
