SHELL := /bin/sh

CORE_PINT := ./vendor/bin/pint
CORE_PHPSTAN := ./vendor/bin/phpstan
CORE_PEST := ./vendor/bin/pest

.PHONY: help format analyse test qa core-format core-analyse core-test core-qa

help:
	@printf '%s\n' \
		'Available targets:' \
		'  make format   - Format the package code' \
		'  make analyse  - Run PHPStan on the package' \
		'  make test     - Run Pest for the package' \
		'  make qa       - Run format, analysis and tests'

format: core-format
analyse: core-analyse
test: core-test
qa: core-qa

core-format:
	$(CORE_PINT) src tests

core-analyse:
	$(CORE_PHPSTAN) analyse --memory-limit=1G

core-test:
	$(CORE_PEST)

core-qa: core-format core-analyse core-test
