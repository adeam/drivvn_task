FROM php:7.4-fpm

RUN apt-get -y update \
    && apt-get install -y libicu-dev\
    && docker-php-ext-install mysqli pdo pdo_mysql
