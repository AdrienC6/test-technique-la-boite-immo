.PHONY: help lint lint-fix type-check qa start stop build

COMPOSE := docker compose
YARN := $(COMPOSE) exec php yarn
PHP_CS_FIXER := $(COMPOSE) exec php vendor/bin/php-cs-fixer

##———————————— Fullstack Challenge

help: ## Show this help page
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' Makefile | awk 'BEGIN {FS = ":.*?##"}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

##———————————— Code Quality

lint: ## Launch ESLint
	$(YARN) lint
	$(PHP_CS_FIXER) check

lint-fix: ## Launch ESLint with autofix
	$(YARN) lint --fix
	$(PHP_CS_FIXER) fix

type-check: ## Launch TypeScript type checking
	$(YARN) type-check

qa: lint-fix type-check ## Launch quality automation (lint, type checking...)

##———————————— Helpers

start: ## Start the project
	$(COMPOSE) up --pull always -d --wait

stop: ## Stop the project
	$(COMPOSE) down

build: ## Build the project
	$(COMPOSE) build
