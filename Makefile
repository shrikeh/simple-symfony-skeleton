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

run: down base-build
  # doing it in this order solves race condition but it isn't a great solution.
  # containers should wait until amqp is available ideally.
	docker-compose run -d --name rabbitmq rabbitmq
	docker-compose build --parallel consumer cli
	echo 'Sleeping 10 seconds to allow rabbitrmq to fire up...'
	sleep 10s
	docker-compose up

composer:
	./tools/bin/run_test.sh composer

build-docker: down base-dev
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml build --parallel

test: base-dev security-check phpcs phpspec infection behat

down:
	docker-compose down

base-build:
	echo 'Running base PHP image...';
	docker-compose --log-level ERROR run base-php

base-dev: base-build
	echo 'Running base dev PHP image...'
	docker-compose -f docker-compose.yml -f docker-compose.dev.yml --log-level ERROR run dev-php-base

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

behat:
	./tools/bin/run_test.sh behat
