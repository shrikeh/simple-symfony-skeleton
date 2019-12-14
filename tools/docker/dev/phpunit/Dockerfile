FROM shrikeh/php7.4-cli-dev:latest as phpunit

ENV XDEBUG_FILTER="./build/xdebug-filter.php"

WORKDIR /code
ENTRYPOINT php ./vendor/bin/phpunit --prepend ${XDEBUG_FILTER} --configuration ./phpunit.xml
