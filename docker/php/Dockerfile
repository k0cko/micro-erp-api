FROM composer:2.9.5 AS build

WORKDIR /app

COPY app/composer.json .
COPY app/composer.lock .

# ADD IF CHECK BASED ON ENV VARIABLE IF PRODUCTION OR NOT
RUN composer install --no-scripts


FROM php:8.4.20-fpm-alpine AS base

RUN docker-php-ext-install \
    pdo_mysql

# Install build dependencies required to compile the redis PHP extension via PECL
RUN apk add --no-cache pcre-dev $PHPIZE_DEPS \
        && pecl install redis \
        && docker-php-ext-enable redis.so

WORKDIR /app

COPY --from=build /usr/bin/composer /usr/bin/composer

COPY app/ .

COPY --from=build /app/vendor/ vendor/

COPY docker/php/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

ENTRYPOINT [ "/entrypoint.sh" ]
