# --- COMPOSER ---
FROM composer:latest AS composer-stage
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --optimize-autoloader --ignore-platform-reqs

# --- VITE (NODE) ---
FROM node:22-alpine AS node-stage
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# --- RUNTIME ---
FROM php:8.3-fpm-alpine
WORKDIR /var/www/html

# System Dependencies
RUN apk add --no-cache \
    icu-dev sqlite-dev libxml2-dev libpng-dev \
    bash git unzip zip

# PHP Extensions
RUN docker-php-ext-install pdo pdo_sqlite bcmath gd xml intl

# Setup Laravel Env
COPY --from=composer-stage /app/vendor /var/www/html/vendor
COPY --from=node-stage /app/public/build /var/www/html/public/build
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Port settings
EXPOSE 9000

CMD ["php-fpm"]
