FROM shrikeh/php7.4-cli-dev:latest as security-check

WORKDIR /security-check

ENTRYPOINT ["./application/bin/symfony", "security:check"]