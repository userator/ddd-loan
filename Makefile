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

sh_mysql:
	docker compose exec -i mysql bash

sh_postgres:
	docker compose exec -i postgres bash

stan:
	php8.2 ./vendor/bin/phpstan analyse --configuration=phpstan.dist.neon