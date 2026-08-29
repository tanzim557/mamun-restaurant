#!/bin/bash
set -e

# Replace Apache default port with Render's dynamic $PORT
if [ -n "$PORT" ]; then
    sed -i "s/80/$PORT/g" /etc/apache2/ports.conf
    sed -i "s/:80/:$PORT/g" /etc/apache2/sites-available/*.conf
fi

# Run database migrations if DB is configured
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec apache2-foreground
