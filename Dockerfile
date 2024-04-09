FROM php:8-alpine

RUN apk --no-cache add --update pcre-dev linux-headers ${PHPIZE_DEPS} \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && apk del pcre-dev linux-headers ${PHPIZE_DEPS}

COPY ./res/service-config/test/php/development.ini /usr/local/etc/php/conf.d/development.ini
