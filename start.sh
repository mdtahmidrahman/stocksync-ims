#!/bin/bash
set -e

echo "Starting deployment setup..."

# Cache configuration and routes for production
echo "Caching configuration and routes..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Run database migrations
echo "Running migrations..."
php artisan migrate --force

# Make sure permissions are correct for storage and cache
echo "Setting directory permissions..."
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Start apache
echo "Starting Apache..."
apache2-foreground
