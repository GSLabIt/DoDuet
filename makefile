env_vars = COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1

.PHONY: build
build:
	$(env_vars) docker-compose build --compress --parallel

.PHONY: run
run:
	$(env_vars) docker-compose up --build --remove-orphans

.PHONY: start
start:
	$(env_vars) docker-compose up -d --build --remove-orphans

.PHONY: dev
dev: dev-build dev-up dev-fresh

.PHONY: dev-up
dev-up:
	$(env_vars) docker-compose -f docker-compose.dev.yml up -d

.PHONY: dev-build
dev-build:
	$(env_vars) docker-compose --log-level DEBUG --verbose -f docker-compose.dev.yml build

.PHONY: dev-migrate
dev-migrate:
	docker exec ethera-dev php artisan migrate

.PHONY: dev-fresh
dev-fresh:
	sleep 5
	docker exec ethera-dev php artisan migrate:fresh

.PHONY: dev-shell
dev-shell:
	docker exec -it ethera-dev sh

.PHONY: kill-all
kill-all:
	docker kill `docker ps -q`
