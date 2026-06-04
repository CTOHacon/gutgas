# syntax=docker/dockerfile:1

# =============================================================================
# Stage 0 — composer vendor (PHP deps; also provides Ziggy's Vue plugin to the
# asset build, since that file ships inside the tightenco/ziggy composer package)
# =============================================================================
FROM composer:latest AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --ignore-platform-reqs

# =============================================================================
# Stage 1 — build frontend assets (client bundle + Inertia SSR bundle)
# =============================================================================
FROM node:20-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci
COPY . .
# app.ts / ssr.ts import ZiggyVue from vendor/tightenco/ziggy/dist/vue.m, which
# lives in the composer package (absent from the CI checkout). Bring it in so
# vite can resolve the import.
COPY --from=vendor /app/vendor/tightenco/ziggy ./vendor/tightenco/ziggy
# Skip vue-tsc here so type warnings never block a deploy; build client + SSR bundles
RUN npx vite build && npx vite build --ssr

# =============================================================================
# Stage 2 — PHP base: extensions + nginx + supervisor + node (SSR runtime)
# =============================================================================
FROM php:8.3-fpm-alpine AS base
RUN apk add --no-cache \
    git curl \
    libpng-dev libjpeg-turbo-dev freetype-dev libwebp-dev \
    libzip-dev zip unzip oniguruma-dev icu-dev \
    supervisor nginx nodejs
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
 && docker-php-ext-install -j"$(nproc)" \
        pdo pdo_mysql mbstring exif pcntl bcmath gd zip intl opcache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# =============================================================================
# Stage 3 — application image
# =============================================================================
FROM base AS app
WORKDIR /var/www/html

COPY docker/php.ini /usr/local/etc/php/conf.d/custom.ini
COPY docker/nginx.conf /etc/nginx/nginx.conf
COPY docker/supervisor/supervisord.conf /etc/supervisord.conf

# Reuse vendor from the composer stage, copy code, then optimize the autoloader
# (/vendor is .dockerignore'd, so `COPY . .` will not clobber the copied vendor)
COPY --from=vendor /app/vendor ./vendor
COPY . .
RUN composer dump-autoload --optimize --no-scripts

# Bring in built assets from stage 1 (client manifest + SSR bundle)
COPY --from=assets /app/public/build ./public/build
COPY --from=assets /app/bootstrap/ssr ./bootstrap/ssr

RUN chown -R www-data:www-data storage bootstrap/cache \
 && chmod -R 775 storage bootstrap/cache \
 && mkdir -p /run/nginx

COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 8080
ENTRYPOINT ["/entrypoint.sh"]
