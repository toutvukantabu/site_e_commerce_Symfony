# Parameters
SHELL         = bash
PROJECT       = E_commerce_Symfony
GIT_AUTHOR    = Gwendal Bescont
HTTP_PORT     = 8741

# Executables (local)
DOCKER = docker
DOCKER_RUN = $(DOCKER) run
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP_CONT) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY       =

#---PHPQA---#
PHPQA = jakzal/phpqa:php8.2
PHPQA_RUN = $(DOCKER_RUN) --init --rm -v $(PWD):/project -w /project $(PHPQA)

## === 🐛  PHPQA =================================================
qa-cs-fixer-dry-run: ## Run php-cs-fixer in dry-run mode.
	$(PHPQA_RUN) php-cs-fixer fix ./src --rules=@Symfony --verbose --dry-run
.PHONY: qa-cs-fixer-dry-run

qa-cs-fixer: ## Run php-cs-fixer.
	$(PHPQA_RUN) php-cs-fixer fix --ansi ./src --rules=@Symfony --verbose
.PHONY: qa-cs-fixer

qa-phpstan: ## Run phpstan.
	$(PHPQA_RUN) phpstan analyse --ansi ./src --level=3
.PHONY: qa-phpstan

qa-security-checker: ## Run security-checker.
	$(SYMFONY) security:check --ansi --no-interaction
.PHONY: qa-security-checker

qa-phpcpd: ## Run phpcpd (copy/paste detector).
	$(PHPQA_RUN) phpcpd ./src
.PHONY: qa-phpcpd

qa-php-metrics: ## Run php-metrics.
	$(PHPQA_RUN) phpmetrics --report-html=var/phpmetrics ./src
.PHONY: qa-php-metrics

qa-lint-yaml: ## Lint yaml files.
	$(SYMFONY_LINT)yaml ./config
.PHONY: qa-lint-yaml

qa-lint-container: ## Lint container.
	$(SYMFONY_LINT)container
.PHONY: qa-lint-container

qa-lint-schema: ## Lint Doctrine schema.
	$(SYMFONY_CONSOLE) doctrine:schema:validate --skip-sync -vvv --no-interaction
.PHONY: qa-lint-schema

qa-audit: ## Run composer audit.
	$(COMPOSER) audit
.PHONY: qa-audit

phpunit: ## Run PHPUnit
	@$(PHP_CONT) ./vendor/bin/phpunit --testdox --colors=always tests

qa-rector: ## Lint container.
	$(PHPQA_RUN) rector process
.PHONY: qa-lint-container

## === ⭐  OTHERS =================================================
before-commit: qa-cs-fixer qa-phpstan   qa-audit test ## Run before commit.
.PHONY: before-commit

make test: ## Run PHPUnit
	@$(PHP_CONT)  php bin/console doctrine:database:create --env=test --if-not-exists
	@$(PHP_CONT) ./vendor/bin/phpunit --testdox --colors=always tests



## —— 🐝 The Strangebuzz Symfony Makefile 🐝 ———————————————————————————————————

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
install: ## Install vendors according to the current composer.lock file
	$(COMPOSER) install --no-progress --prefer-dist --optimize-autoloader

update:## update composer
	$(COMPOSER) update --dev --no-interaction -o

## —— PHP 🐘 (linux with sudo apt-get) —————————————————————————————————————————————————
php-upgrade: ## Upgrade PHP to the last version
	$(apt-get) upgrade php

php-set-7-3: ## Set php 7.3 as the current PHP version
	$(apt-get) unlink php
	$(apt-get) link --overwrite php@7.3

php-set-7-4: ## Set php 7.4 as the current PHP version
	$(apt-get) unlink php
	$(apt-get) link --overwrite php@7.4

php-set-8-0: ## Set php 8.0 as the current PHP version
	$(apt-get) unlink php
	$(apt-get) link --overwrite php@8.0

## —— Symfony environnement🎵 ———————————————————————————————————————————————————————————————

sf: ## List all Symfony commands
	$(SYMFONY)

cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY)  c:c

warmup: ## Warmup the cache
	$(SYMFONY)  cache:warmup

fix-perms: ## Fix permissions of all var files
	sudo chmod 777 ./var ./vendor

assets: purge ## Install the assets with symlinks in the public folder
	$(SYMFONY)  assets:install public/ --symlink --relative

purge: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

entity: ## create Entity (before using this command, connect on your container with make:bash)
	$(SYMFONY)  make:entity

migration: ## make migration (before using this command, connect on your container with make:bash)
	$(SYMFONY)  make:migration --no-interaction

migrate: ## doctrine migration migrate (before using this command, connect on your container with make:bash)
	$(SYMFONY)  doctrine:migration:migrate --no-interaction

migrate-force: ## doctrine migration migrate (before using this command, connect on your container with make:bash)
	$(SYMFONY)  doctrine:schema:update --force

crud : ## make crud (create reset delete)(before using this command, connect on your container with make:bash)
	$(SYMFONY)  make:crud

controller : ## make controller (before using this command, connect on your container with make:bash)
	$(SYMFONY)  make:controller

router : ## debugging App routing
	$(SYMFONY)  debug:router

dispatcher : ## see dispatcher event
	$(SYMFONY)  debug:event-dispatcher

framework : ## see framework config
	$(SYMFONY)  debug:config framework

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
up: ## Start the docker hub (MySQL,phpMyadmin,php)
	$(DOCKER_COMP) up -d

docker-build: ## Builds the PHP image
	$(DOCKER_COMP) build

down: ## Stop the docker hub
	$(DOCKER_COMP) down --remove-orphans

destroy:## destroy  docker containers
	$(DOCKER_COMP) rm -v --force --stop || true

restart:
	$(DOCKER_COMP) restart $$(docker  -l -c )

sh: ## Connect to the application container
	$(DOCKER) exec -it  site_e_commerce_symfony-php-1  bash

kill-r-containers: ## Kill all running containers
	$(DOCKER) kill $$(docker ps -q)

delete-s-containers: ## Delete all stopped containers
	$(DOCKER) rm $$(docker ps -a -q)

delete-images: ## Delete Delete all images
	$(DOCKER) rmi $$(docker images -q)

stop-containers: ## Stop all containers
	docker stop `docker ps -q`

## —— Stripe 💳 ————————————————————————————————————————————————————————

stripe : ## install stripe
	$(COMPOSER) require stripe/stripe-php

## —— Project 🐝 ———————————————————————————————————————————————————————————————
build : docker-build up  install update stripe   ## Build project, Install vendors according to the current composer.lock file, install symfony cli, Stripe

start: build load-fixtures  ##load-fixtures  serve ## build project,Start docker, load fixtures and start the webserver

reload: stop restart load-fixtures	 ## Load fixtures

stop: down  ## Stop docker and the Symfony binary server

commands: ## Display all commands in the project namespace
	$(SYMFONY)  list site_e_commerce_symfony-php-1

load-fixtures: ## Build the DB, control the schema validity, load fixtures and check the migration status
	$(SYMFONY)  --env=dev doctrine:cache:clear-metadata
	$(SYMFONY)  --env=dev doctrine:database:create --if-not-exists
	$(SYMFONY)  --env=dev doctrine:schema:drop --force
	$(SYMFONY)  --env=dev doctrine:schema:create
	$(SYMFONY)  --env=dev doctrine:schema:validate
	$(SYMFONY)  --env=dev doctrine:fixtures:load --no-interaction

rebuild-database: drop-db create-db migration migrate-force load-fixtures ## Drop database, create database, Doctrine migration migrate,reload fixtures

create-db:## Create the database
	$(SYMFONY)   --env=dev doctrine:database:create --if-not-exists --no-interaction

reload-fixtures:## reload just fixtures
	$(SYMFONY)  --env=dev doctrine:fixtures:load --no-interaction

drop-db:## Drop the  database (before using this command, connect on your container with make:bash)
	$(SYMFONY)   doctrine:database:drop --force --no-interaction

