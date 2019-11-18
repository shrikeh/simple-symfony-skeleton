ARG PHP_TAG="7.4.0RC5-cli-alpine"

FROM php:${PHP_TAG} as phpunit

RUN apk add --no-cache git
RUN apk add --no-cache $PHPIZE_DEPS && pecl install xdebug && docker-php-ext-enable xdebug

WORKDIR /phpunit
ENTRYPOINT ["php", "vendor/bin/phpunit", "--configuration", "./phpunit.xml"]

