FROM composer:2.9.5 AS build

WORKDIR /app

COPY app/composer.json .
COPY app/composer.lock .

# ADD IF CHECK BASED ON ENV VARIABLE IF PRODUCTION OR NOT
RUN composer install --no-scripts


FROM php:8.4.20-fpm-alpine AS base

WORKDIR /app

COPY --from=build /usr/bin/composer /usr/bin/composer

COPY app/ .

COPY --from=build /app/vendor/ vendor/

CMD ["php-fpm"]
