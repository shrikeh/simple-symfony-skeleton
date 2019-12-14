FROM shrikeh/php7.4-cli-dev:latest as infection

WORKDIR /code
ENTRYPOINT php vendor/bin/infection --debug -j2 --coverage=build/coverage