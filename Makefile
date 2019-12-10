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
  # doing it in this order solves race condition but it isn't a great solution.
  # containers should wait until amqp is available ideally.
	docker-compose run -d rabbitmq
	docker-compose build --parallel consumer cli
	docker-compose run -d consumer
	docker-compose run cli

composer:
	composer install

build-docker: down
	docker-compose build --parallel

test: security-check phpcs phpspec infection

down:
	docker-compose down
phpspec:
	./tools/bin/run_test.sh phpspec
# Runs infection. Depends on phpunit so we run that first
infection: phpunit
	./tools/bin/run_test.sh infection
phpcs:
	./tools/bin/run_test.sh phpcs
# Runs phpunit
phpunit:
	./tools/bin/run_test.sh phpunit

security-check:
	./tools/bin/run_test.sh security-check