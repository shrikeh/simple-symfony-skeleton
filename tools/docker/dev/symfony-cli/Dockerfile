FROM shrikeh/php7.4-cli-dev:latest as symfony-cli

RUN apk add --no-cache curl bash
RUN curl -sS https://get.symfony.com/cli/installer | bash

CMD mv /root/.symfony/bin/symfony /code/application/bin
