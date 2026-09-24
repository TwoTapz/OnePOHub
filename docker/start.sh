#!/bin/sh
set -e

# Generate app key if not set
if [ -z "$APP_KEY" ]; then
    export APP_KEY=$(php artisan key:generate --show --no-interaction)
fi

# Write .env from environment variables
cat > /var/www/html/.env <<EOF
APP_NAME=OnePOHub
APP_ENV=${APP_ENV:-production}
APP_KEY=${APP_KEY}
APP_DEBUG=${APP_DEBUG:-false}
APP_URL=${APP_URL:-http://localhost}

LOG_CHANNEL=stderr
LOG_LEVEL=error

SESSION_DRIVER=file
SESSION_LIFETIME=120

CACHE_STORE=file
EOF

cd /var/www/html

php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start php-fpm in background
php-fpm -D

# Start nginx in foreground
nginx -g "daemon off;"
