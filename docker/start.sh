#!/bin/sh
set -e

cd /var/www/html

# Write .env first so artisan can boot
cat > .env <<EOF
APP_NAME=OnePOHub
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY:-}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stderr
LOG_LEVEL=error

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_STORE=file
EOF

# Generate app key if none was provided
if [ -z "$APP_KEY" ]; then
    php artisan key:generate --force
fi

php artisan config:cache
php artisan route:cache

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground
exec nginx -g "daemon off;"
