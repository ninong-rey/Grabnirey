#!/usr/bin/env bash

echo "=================================="
echo "Starting Laravel Deployment..."
echo "=================================="

# Install dependencies
composer install --no-dev --optimize-autoloader --no-interaction

# Install and build NPM assets
echo "Installing NPM dependencies..."
npm ci --no-audit --no-fund

echo "Building Vite assets..."
npm run build

# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Generate key if not exists
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link --force

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=================================="
echo "Deployment Complete!"
echo "=================================="
