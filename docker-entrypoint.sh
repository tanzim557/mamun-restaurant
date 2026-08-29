#!/bin/bash
set -e

# Replace Apache default port with Render's dynamic $PORT
if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf
fi

# Ensure storage directories and permissions exist
mkdir -p /var/www/html/storage/framework/{sessions,views,cache} /var/www/html/storage/logs /var/www/html/bootstrap/cache
chmod -R 777 /var/www/html/storage /var/www/html/bootstrap/cache

# If APP_KEY is not provided via Render environment, set default key
if [ -z "$APP_KEY" ]; then
    export APP_KEY="base64:KW/Q6aIQgKxlPXSnPEC8d0IMpa1ow+UYUfBIUiQkicg="
fi

# Auto-fallback to SQLite if no remote MySQL DB_HOST is configured
if [ -z "$DB_HOST" ] || [ "$DB_HOST" = "127.0.0.1" ] || [ "$DB_HOST" = "localhost" ]; then
    touch /var/www/html/database/database.sqlite
    chmod 777 /var/www/html/database/database.sqlite
    export DB_CONNECTION=sqlite
    export DB_DATABASE=/var/www/html/database/database.sqlite
fi

# Clear and rebuild cache
php artisan config:clear || true
php artisan view:clear || true
php artisan route:clear || true

# Run migrations and initial seeds safely
php artisan migrate --force || true
php artisan db:seed --force || true

exec apache2-foreground
