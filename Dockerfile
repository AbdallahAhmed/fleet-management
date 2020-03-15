FROM php:7.2-fpm-alpine

COPY . /var/www

COPY composer.lock composer.json /var/www/

WORKDIR /var/www

RUN docker-php-ext-install pdo pdo_mysql

RUN apk add nano

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer