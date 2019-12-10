SHELL := /usr/bin/env bash

.DEFAULT: help
.PHONY: help
ifndef VERBOSE
.SILENT:
endif

vagrant-rebuild:
	vagrant halt
	vagrant destroy -f
	vagrant up
run:
	docker-compose up

build-docker: down
	docker-compose build --parallel

down:
	docker-compose down
infection:
	./vendor/bin/infection --debug -j2 --coverage=build/coverage --show-mutations

phpunit:
	./tools/bin/phpunit.sh

security-check:
	./tools/bin/security-check.sh