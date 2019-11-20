ARG PHP_TAG="7.4.0RC5-cli-alpine"
FROM php:${PHP_TAG} as build-consumer
ARG COMPOSER_BUILD='--no-dev'

RUN apk add --no-cache git
WORKDIR /bin

COPY ./tools/bin/install-composer.sh .

RUN sh ./install-composer.sh

WORKDIR /build
COPY ./composer.json .
COPY ./composer.lock .
COPY ./application ./application
RUN php /bin/composer.phar install ${COMPOSER_BUILD} --classmap-authoritative

FROM php:${PHP_TAG} as consumer

ENV APP_ENV dev
RUN apk add rabbitmq-c rabbitmq-c-dev

RUN apk add --no-cache $PHPIZE_DEPS && pecl install amqp && docker-php-ext-enable amqp

WORKDIR /app
COPY --from=build-consumer /build .

ENTRYPOINT ["php", "application/bin/console", "messenger:consume", "-vv"]