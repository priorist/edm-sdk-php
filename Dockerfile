FROM php:7-alpine

RUN apk --no-cache add pcre-dev ${PHPIZE_DEPS} \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del pcre-dev ${PHPIZE_DEPS}

COPY ./res/service-config/test/php/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini
COPY ./res/service-config/test/php/development.ini /usr/local/etc/php/conf.d/development.ini
