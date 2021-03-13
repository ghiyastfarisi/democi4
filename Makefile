PROJECT_NAME := "democi4"

.PHONY: all

all: build-image run-dev

build-image:
	@docker build -t democi4:v1 -f Dockerfile .

run-dev:
	@sh run.sh