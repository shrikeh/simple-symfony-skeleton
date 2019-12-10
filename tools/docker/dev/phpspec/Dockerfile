ARG PHP_TAG="7.4.0RC5-cli-alpine"
FROM php:${PHP_TAG} as phpunit

ENV XDEBUG_FILTER="./build/xdebug-filter.php"
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apk add --no-cache git
RUN apk add --no-cache ${PHPIZE_DEPS} && pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /code
ENTRYPOINT php ./vendor/bin/phpspec run

