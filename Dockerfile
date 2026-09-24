FROM php:8.4-fpm-alpine AS builder

RUN apk add --no-cache nodejs npm

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader --no-interaction --no-progress
RUN npm ci && npm run build

FROM php:8.4-fpm-alpine

RUN apk add --no-cache nginx

WORKDIR /var/www/html

COPY --from=builder /app .
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/start.sh /start.sh

RUN chmod +x /start.sh \
    && mkdir -p storage/logs storage/framework/sessions storage/framework/views storage/framework/cache/data bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

EXPOSE 10000

CMD ["/start.sh"]
