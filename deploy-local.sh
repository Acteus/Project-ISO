#!/bin/bash

# Local deployment script - Run this from your local machine
# It will connect to Cloudways via SSH and run the deployment

set -e

# Cloudways SSH credentials - Update these with your actual values
SSH_USER="1543265"
SSH_HOST="[YOUR_SERVER_IP]"  # Get from Cloudways dashboard
SSH_PORT="22"  # Default SSH port, update if different
APP_PATH="/home/1543265.cloudwaysapps.com/kxvekkgpkz/public_html"

echo "🚀 Starting remote deployment to Cloudways..."

# Connect via SSH and run deployment commands
ssh -p $SSH_PORT $SSH_USER@$SSH_HOST << 'ENDSSH'
cd /home/1543265.cloudwaysapps.com/kxvekkgpkz/public_html

echo "📦 Enabling maintenance mode..."
php artisan down || true

echo "📥 Pulling latest changes from git..."
git pull origin deployment/laravel-cloud || echo "⚠️  Git pull skipped (not configured)"

echo "📦 Installing composer dependencies..."
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

echo "🗄️  Running database migrations..."
php artisan migrate --force

echo "⚡ Optimizing application..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "🔒 Setting file permissions..."
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/

echo "✅ Disabling maintenance mode..."
php artisan up

echo "🎉 Deployment completed!"
php artisan --version
ENDSSH

echo "✅ Remote deployment finished successfully!"
