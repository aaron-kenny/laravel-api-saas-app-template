# PHP dependencies
######################################
FROM composer:2.0.9 AS vendor

WORKDIR /app

COPY composer* /app/
COPY database/ /app/database/

ARG COMPOSER_OPTIONS

RUN composer install $COMPOSER_OPTIONS

# Application
######################################
FROM php:8.0.2-fpm-alpine3.13

RUN docker-php-ext-install \
    bcmath \
    pdo_mysql

COPY --chown=www-data:www-data --from=vendor /app/vendor/ /var/www/html/vendor/
COPY --chown=www-data:www-data . /var/www/html/
COPY --chown=www-data:www-data --from=vendor /app/composer* /var/www/html/