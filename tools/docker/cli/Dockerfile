ARG PHP_TAG="7.4.0RC5-cli-alpine"
FROM php:${PHP_TAG} as build-cli
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

FROM php:${PHP_TAG} as cli

ENV APP_ENV dev

WORKDIR /app
COPY --from=build-cli /build .

ENTRYPOINT ["php", "application/bin/console", "shrikeh:test"]