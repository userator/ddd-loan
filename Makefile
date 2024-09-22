build:
	docker compose build

status:
	docker compose ps

start:
	docker compose up --detach --no-build --remove-orphans --force-recreate

stop:
	docker compose down --remove-orphans

restart:
	docker compose restart

init: build start status

sh_php:
	docker compose exec -i php bash

stan:
	php ./vendor/bin/phpstan analyse --configuration=phpstan.dist.neon