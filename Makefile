##-
##-	Usage | make [option]
##-
help:		##- Show this help.
	@sed -e '/#\{2\}-/!d; s/\\$$//; s/:[^#\t]*/:/; s/#\{2\}- *//' $(MAKEFILE_LIST)

##-

CONTAINER = php-web
PHPQA = docker run --init -it --user "$(shell id -u):$(shell id -g)" --rm -v "$(CURDIR)/:/var/www" -v "$(CURDIR)/tmp-phpqa:/tmp" -w /var/www jakzal/phpqa:php8.0-alpine

##-		-- Docker Commands --
##-

start: 		##- Start Docker
	@ docker-compose up -d --build

stop: 		##- Stop Docker
	@ docker-compose down

composer-i:	##- Composer install
	@ docker exec -it $(CONTAINER) composer install

preset:		##- preset laravel
	@ docker exec -it $(CONTAINER) cp .env.example .env && chmod -R 777 storage && chmod -R 777 bootstrap && php artisan migrate:refresh --seed

##-
##-		-- QA Task Runners --
##-
test:		##- Run Tests with PHP Unit
	@ mkdir -p $(CURDIR)/tmp-phpqa/ && chmod 775 $(CURDIR)/tmp-phpqa/
	@ mkdir -p $(CURDIR)/tmp-phpqa/coverage && chmod 775 $(CURDIR)/tmp-phpqa/coverage
	@ docker exec -it $(CONTAINER) php artisan test --debug -vvv

stan:		##- Verify Code with PHPStan
	@ mkdir -p $(CURDIR)/tmp-phpqa/ && chmod 775 $(CURDIR)/tmp-phpqa/
	@ $(PHPQA) phpstan

format:		##- Verify format code
	@ mkdir -p $(CURDIR)/tmp-phpqa/ && chmod 775 $(CURDIR)/tmp-phpqa/
	@ $(PHPQA) ecs --fix --clear-cache

##-
##-
