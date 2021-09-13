# Parameters
SHELL         = bash
PROJECT       = E_commerce_Symfony
GIT_AUTHOR    = Gwendal Bescont
HTTP_PORT     = 8741

# Executables
EXEC_PHP      = php
COMPOSER      = composer
GIT           = git

# Alias
#SYMFONY       = docker exec www_docker_symfony $(EXEC_PHP) bin/console
SYMFONY 	  = docker exec www_docker_symfony $(SYMFONY_BIN) console
# if you use php you can replace "with: $(EXEC_PHP) bin/console"

# Executables: vendors
# PHPUNIT       = ./vendor/bin/phpunit
# PHPSTAN       = ./vendor/bin/phpstan
# PHP_CS_FIXER  = ./vendor/bin/php-cs-fixer
# CODESNIFFER   = ./vendor/squizlabs/php_codesniffer/bin/phpcs

# Executables: local only
SYMFONY_BIN   = symfony 
apt-get       = sudo apt-get
DOCKER        = docker
DOCKER_COMP   = docker-compose

# Misc
.DEFAULT_GOAL = help
.PHONY       = 

## —— 🐝 The Strangebuzz Symfony Makefile 🐝 ———————————————————————————————————

help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Composer 🧙‍♂️ ————————————————————————————————————————————————————————————
install: ## Install vendors according to the current composer.lock file
	$(DOCKER) exec  www_docker_symfony composer install --no-progress --prefer-dist --optimize-autoloader
	
update:## update composer
	$(DOCKER) exec  www_docker_symfony composer update --dev --no-interaction -o

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

## —— Symfony or php 🎵 ———————————————————————————————————————————————————————————————

sf: ## List all Symfony commands
	$(SYMFONY)

cc: ## Clear the cache. DID YOU CLEAR YOUR CACHE????
	$(SYMFONY) c:c

warmup: ## Warmup the cache
	$(SYMFONY) cache:warmup

fix-perms: ## Fix permissions of all var files
	sudo chmod 777 ./var ./vendor ./php

assets: purge ## Install the assets with symlinks in the public folder
	$(SYMFONY) assets:install public/ --symlink --relative

purge: ## Purge cache and logs
	rm -rf var/cache/* var/logs/*

entity: ## create Entity (before using this command, connect on your container with make:bash)
	symfony console make:entity

migration: ## make migration (before using this command, connect on your container with make:bash)
	symfony console make:migration

migrate: ## doctrine migration migrate (before using this command, connect on your container with make:bash)
	symfony console doctrine:migration:migrate

migrate-force: ## doctrine migration migrate (before using this command, connect on your container with make:bash)
	symfony console doctrine:schema:update --force

crud : ## make crud (create reset delete)
	$(SYMFONY) make:crud 

controller : ## make controller
	$(SYMFONY) make:controller


## —— Symfony binary 💻 ————————————————————————————————————————————————————————

symfony-cli-linux: ## install symfony cli commands on linux
	wget https://get.symfony.com/cli/installer -O - | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

symfony-cli-mac: ## install symfony cli commands on linux
 	curl -sS https://get.symfony.com/cli/installer | bash && mv /root/.symfony/bin/symfony /usr/local/bin/symfony

cert-install: ## Install the local HTTPS certificates
	$(SYMFONY_BIN) server:ca:install

serve: ## Serve the application with HTTPS support (add "--no-tls" to disable https)
	$(SYMFONY_BIN) serve --daemon --port=$(HTTP_PORT)

unserve: ## Stop the webserver
	$(SYMFONY_BIN) server:stop

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

bash: ## Connect to the application container
	$(DOCKER) exec -it  www_docker_symfony  bash

kill-r-containers: ## Kill all running containers 
	$(DOCKER) kill $$(docker ps -q)

delete-s-containers: ## Delete all stopped containers
	$(DOCKER) rm $$(docker ps -a -q)

delete-images: ## Delete Delete all images
	$(DOCKER) rmi $$(docker images -q)

stop-containers: ## Stop all containers
	docker stop `docker ps -q`

## —— Project 🐝 ———————————————————————————————————————————————————————————————
build : docker-build up  install update  ## Build project, Install vendors according to the current composer.lock file, install symfony cli, Install the local HTTPS certificates

start: load-fixtures  ##load-fixtures  serve ## build project,Start docker, load fixtures and start the webserver

reload: unserve restart load-fixtures serve ## Load fixtures 

stop: down  ## Stop docker and the Symfony binary server

commands: ## Display all commands in the project namespace
	$(SYMFONY) list $(PROJECT)

load-fixtures: ## Build the DB, control the schema validity, load fixtures and check the migration status
	$(SYMFONY) --env=dev doctrine:cache:clear-metadata
	$(SYMFONY) --env=dev doctrine:database:create --if-not-exists
	$(SYMFONY) --env=dev doctrine:schema:drop --force
	$(SYMFONY) --env=dev doctrine:schema:create
	$(SYMFONY) --env=dev doctrine:schema:validate
	$(SYMFONY) --env=dev doctrine:fixtures:load --no-interaction

rebuild-database: drop-db create-db build-db load-fixtures ## Drop the database, create the database, Doctrine migration migrate,reload fixtures

create-db:## Create the database
	$(SYMFONY)  --env=dev doctrine:database:create --if-not-exists --no-interaction

reload-fixtures:## reload just fixtures
	$(SYMFONY) --env=dev doctrine:fixtures:load --no-interaction

drop-db:## Drop the  database (before using this command, connect on your container with make:bash)
	$(SYMFONY) --env=dev doctrine:database:drop --force --no-interaction

