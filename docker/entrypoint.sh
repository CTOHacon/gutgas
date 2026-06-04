#!/bin/sh
set -e
cd /var/www/html

# Storage symlink (public/storage -> storage/app/public)
if [ ! -L public/storage ]; then
    ln -sf /var/www/html/storage/app/public /var/www/html/public/storage
fi

# Ensure writable runtime dirs (storage/app is a mounted volume)
mkdir -p storage/framework/sessions storage/framework/cache storage/framework/views \
         storage/logs storage/app/public bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

# Cache config/routes/views with the runtime environment present.
# NOTE: migrations are NOT run here — they run as an explicit deploy step.
php artisan config:cache || true
php artisan route:cache || true
php artisan view:cache || true

exec /usr/bin/supervisord -c /etc/supervisord.conf
