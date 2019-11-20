SHELL := /usr/bin/env bash

.DEFAULT: help
.PHONY: help
ifndef VERBOSE
.SILENT:
endif

run: build-docker
	docker-compose up

build-docker: down
	docker-compose build

down:
	docker-compose down
infection:
	./vendor/bin/infection

phpunit:
	./tools/bin/phpunit.sh

security-check:
	./tools/bin/security-check.sh