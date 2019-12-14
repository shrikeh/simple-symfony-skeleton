ARG PHP_TAG="7.4.0RC5-cli-alpine"
FROM php:${PHP_TAG} as symfony-cli

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apk add --no-cache git bash
RUN apk add --no-cache ${PHPIZE_DEPS}

RUN curl -sS https://get.symfony.com/cli/installer | bash

CMD mv /root/.symfony/bin/symfony /code/application/bin
