FROM shrikeh/php7.4-cli-dev:latest as phpcs

WORKDIR /code
ENTRYPOINT php ./vendor/bin/phpcs --runtime-set ignore_warnings_on_exit true --cache -p application tests
