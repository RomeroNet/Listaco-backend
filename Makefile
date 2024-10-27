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
	docker compose -f docker-compose.yml run --rm php vendor/bin/pest
