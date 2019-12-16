FROM shrikeh/php7.4-cli:latest as php-dev-base

RUN apk add --no-cache git
RUN pecl install xdebug && docker-php-ext-enable xdebug

CMD exit 0;