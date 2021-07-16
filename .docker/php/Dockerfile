FROM php:8.0-fpm-alpine

ARG USER_ID=1000

RUN apk update --quiet && \
    apk add --quiet --no-cache bash git shadow openssh openssl-dev p7zip && \
    apk add --quiet --no-cache pcre-dev ${PHPIZE_DEPS}

RUN mkdir -p /var/www/.ssh/ && \
    touch /var/www/.ssh/known_hosts && \
    ssh-keyscan github.com >> /var/www/.ssh/known_hosts

WORKDIR /var/www/html

COPY --chown=www-data:www-data --from=composer:2 /usr/bin/composer /usr/local/bin/composer

RUN usermod -u $USER_ID www-data && chown -R www-data:www-data /var/www/ .
USER www-data

CMD ["php-fpm"]
