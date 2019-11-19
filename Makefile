SHELL := /bin/bash

.DEFAULT: help
.PHONY: help

infection:
	./vendor/bin/infection

phpunit:
	@./tools/bin/phpunit.sh

security-check:
	@./tools/bin/security-check.sh