FROM php:7.3-fpm-alpine

RUN apk add --update \
    && docker-php-ext-install pgsql pdo_pgsql

RUN curl -sS https://getcomposer.org/installer | php -- \
  --install-dir=/usr/bin --filename=composer

COPY . /app

RUN cd "/app" && cp .env.production .env && composer install

WORKDIR /app

EXPOSE 80

CMD php -S 0.0.0.0:80 -t public