ARG PHP_TAG="7.4.0RC5-cli-alpine"

FROM php:${PHP_TAG} as build-cli

RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

RUN apk add rabbitmq-c rabbitmq-c-dev
RUN apk add --no-cache $PHPIZE_DEPS && pecl install amqp ds && docker-php-ext-enable amqp ds

CMD exit 0;