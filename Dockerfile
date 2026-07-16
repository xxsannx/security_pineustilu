# Stage 1: Install PHP dependencies (Composer)
FROM composer:2 AS composer_builder

WORKDIR /app
COPY composer.json composer.lock ./
COPY . .
RUN composer install --no-dev --optimize-autoloader --no-interaction --ignore-platform-reqs

# Stage 2: Build frontend assets (Node) — butuh folder vendor/ dari stage 1
FROM node:20-slim AS node_builder

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
# vendor diperlukan karena app.css mengimport file dari vendor/livewire/flux
COPY --from=composer_builder /app/vendor ./vendor
RUN npm run build

# Stage 3: Final PHP application image
FROM php:8.2-fpm-alpine AS base

RUN apk add --no-cache \
    nginx supervisor gettext \
    openssl \
    ca-certificates \
    libpng-dev libjpeg-turbo-dev libwebp-dev freetype-dev \
    libzip-dev zip unzip git curl-dev \
    oniguruma-dev icu-dev libxml2-dev

RUN update-ca-certificates
    
RUN docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install \
        pdo_mysql \
        mbstring \
        zip \
        exif \
        pcntl \
        gd \
        intl \
        bcmath \
        xml \
        curl

WORKDIR /var/www/html

COPY . .

# Copy hasil build asset dari stage node_builder
COPY --from=node_builder /app/public/build ./public/build

# Copy vendor (PHP dependencies) dari stage composer_builder
COPY --from=composer_builder /app/vendor ./vendor

RUN mkdir -p storage/framework/{sessions,views,cache} storage/logs bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

COPY docker/nginx.conf /etc/nginx/http.d/default.conf
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080

ENTRYPOINT ["/entrypoint.sh"]
