ARG PHP_TAG="7.4.0RC5-cli-alpine"

FROM php:${PHP_TAG} as phpunit

RUN apk add --no-cache git curl

WORKDIR /security-check
ENTRYPOINT ["./application/bin/symfony", "security:check"]