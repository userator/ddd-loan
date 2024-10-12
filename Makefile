build:
	docker compose build

status:
	docker compose ps

start:
	docker compose up --detach --no-build --remove-orphans --force-recreate

stop:
	docker compose down --remove-orphans

restart: stop start

env:
	touch ./.env.local

install:
	docker compose exec -i php composer install

migrate:
	docker compose exec -i php ./bin/console doctrine:migrations:sync-metadata-storage --no-interaction
	docker compose exec -i php ./bin/console doctrine:migrations:migrate --no-interaction
	docker compose exec -i php ./bin/console doctrine:migrations:status --no-interaction

init: build env start install migrate status

sh_php:
	docker compose exec -i php bash

sh_postgres:
	docker compose exec -i postgres bash

stan:
	docker compose exec -i php ./vendor/bin/phpstan analyse --configuration=phpstan.dist.neon

phpunit:
	docker compose exec -i php ./vendor/bin/phpunit --configuration=phpunit.xml.dist

rector-scan:
	docker compose exec -i php ./vendor/bin/rector process --config=rector.php --dry-run

rector-fix:
	docker compose exec -i php ./vendor/bin/rector process --config=rector.php

deptrac:
	docker compose exec -i php ./vendor/bin/deptrac analyse --config-file=deptrac.yaml --report-uncovered

check: stan deptrac phpunit
