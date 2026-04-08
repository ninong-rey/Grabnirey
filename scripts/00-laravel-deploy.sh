#!/usr/bin/env bash

echo "=================================="
echo "Starting Laravel Deployment..."
echo "=================================="

composer install --no-dev --optimize-autoloader --no-interaction
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

# Create and run migrations including sessions
php artisan session:table
php artisan migrate --force

php artisan storage:link --force
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "=================================="
echo "Deployment Complete!"
echo "=================================="
