ARG PHP_TAG="7.4.0RC5-cli-alpine"
FROM php:${PHP_TAG} as infection

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
RUN apk add --no-cache git
RUN apk add --no-cache ${PHPIZE_DEPS} && pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /code
ENTRYPOINT php vendor/bin/infection --debug -j2 --coverage=build/coverage