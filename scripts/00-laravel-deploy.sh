#!/usr/bin/env bash

echo "=================================="
echo "Starting Laravel Deployment..."
echo "=================================="

# Install dependencies
echo "Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# Clear old cache
echo "Clearing cache..."
php artisan optimize:clear

# Generate key if not exists
if [ -z "$APP_KEY" ]; then
    echo "Generating application key..."
    php artisan key:generate --force
fi

# Run migrations
echo "Running database migrations..."
php artisan migrate --force

# Create storage link
echo "Creating storage link..."
php artisan storage:link --force

# Optimize Laravel
echo "Optimizing Laravel..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "Setting permissions..."
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache

echo "=================================="
echo "Deployment Complete!"
echo "=================================="
