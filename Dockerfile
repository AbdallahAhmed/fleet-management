FROM php:7.2-fpm-alpine

WORKDIR /var/www

COPY composer.lock composer.json /var/www/

RUN docker-php-ext-install pdo pdo_mysql bcmath

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY .env.docker /var/www/.env

RUN chmod +rwx .env

COPY . /var/www

RUN php artisan key:generate

RUN php artisan migrate

RUN php artisan db:seed



