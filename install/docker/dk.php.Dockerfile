ARG PHP_VERSION=8.1-fpm

FROM numberninecms/php:$PHP_VERSION

COPY --chown=www-data:www-data . /var/www/html/

RUN rm -rf /var/www/html/docker

WORKDIR /var/www/html
