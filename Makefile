PROJECT_NAME := "democi4"

.PHONY: all

all: build-image run-dev

build-image:
	@docker build -t democi4:v1 -f Dockerfile .

run-dev:
	@docker run -it --rm --name democi4 -p 8081:80 -e CI_ENVIRONMENT=development -v $(pwd):/var/www/html democi4:v1