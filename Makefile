PROJECT_NAME := "democi4"

.PHONY: all

all: build-image run-production

migration-create:
	@docker exec -it ${PROJECT_NAME} php spark migrate:create $(FILE)
	@chown $(USER) ./app/Database/Migrations/*

migration-run:
	@docker exec -it ${PROJECT_NAME} php spark migrate

build-image:
	@docker build -t ${PROJECT_NAME}:v1 -f Dockerfile .
	@sleep 3
	@docker image prune -f

run-dev:
	@sh run.sh

run-production:
	@sh run-production.sh

run-bundle-watch:
	@yarn run build-watch
