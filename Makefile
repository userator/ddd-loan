build:
	docker compose build

status:
	docker compose ps

start:
	docker compose up --detach --no-build --remove-orphans --force-recreate

stop:
	docker compose down --remove-orphans

restart: stop start

init: build stop start status

sh_php:
	docker compose exec -i php bash

stan:
	docker compose exec -i php ./vendor/bin/phpstan analyse --configuration=phpstan.dist.neon

phpunit:
	docker compose exec -i php ./vendor/bin/phpunit --configuration=phpunit.xml.dist