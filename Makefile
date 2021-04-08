PROJECT_NAME := "democi4"

.PHONY: all

all: build-image run-dev

migration-create:
	@docker exec -it ${PROJECT_NAME} php spark migrate:create $(FILE)
	@chown $(USER) ./app/Database/Migrations/*

migration-run:
	@docker exec -it ${PROJECT_NAME} php spark migrate

build-image:
	@docker build -t ${PROJECT_NAME}:v1 -f Dockerfile .

run-dev:
	@sh run.sh

run-bundle-watch:
	@yarn run build-watch

link-dist:
	@rm -rf public/dist
	@ln -s src/dist public/dist

