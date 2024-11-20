build:
	docker compose -f docker-compose.yml build
	docker compose -f docker-compose.yml run --rm php bash -c "composer install"
start:
	docker compose -f docker-compose.yml up -d
stop:
	docker compose -f docker-compose.yml down
shell:
	docker compose -f docker-compose.yml run --rm php bash
test:
	docker compose -f docker-compose.yml run --rm php php -dmemory_limit=-1 vendor/bin/pest --parallel
quality:
	docker compose -f docker-compose.yml run --rm php php -dmemory_limit=-1 vendor/bin/phpstan analyse
	docker compose -f docker-compose.yml run --rm php php -dmemory_limit=-1 vendor/bin/pest --mutate --parallel
