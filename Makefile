SHELL := /bin/bash

.DEFAULT: help
.PHONY: help

infection:
	./vendor/bin/infection

phpunit:
	docker-compose run phpunit

security-check:
	symfony security:check