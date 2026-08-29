#!/bin/bash
set -e

# Replace Apache default port with Render's dynamic $PORT
if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf
fi

# Ensure storage directories and permissions exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/storage/logs /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Ensure .env exists
if [ ! -f /var/www/html/.env ]; then
    cp /var/www/html/.env.example /var/www/html/.env
fi

# Ensure SQLite file exists if using sqlite
touch /var/www/html/database/database.sqlite
chmod 777 /var/www/html/database/database.sqlite

# Clear cache, run migrations, then cache for high performance
php artisan config:clear || true
php artisan view:clear || true
php artisan route:clear || true
php artisan migrate --force || true
php artisan db:seed --force || true

# Production optimization caches
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec apache2-foreground
