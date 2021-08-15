DOCKER_SEARCH_SERVICE = dto

env:
	cp .env.dist .env

new: kill
	docker-compose up -d --build --remove-orphans
	make install
up:
	docker-compose up -d
	make autoload
stop:
	docker-compose stop
kill:
	docker-compose kill

test:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer test

lint:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer lint
lint-static:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer lint:static
lint-fix:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer lint:fix
lint-style:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer lint:style
lint-fix-style:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer lint:fix-style

autoload:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer dump-autoload
install:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer install --no-interaction --prefer-dist
normalize:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer normalize
update-lock:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer update --lock
update:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer update
upgrade:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer upgrade
validate:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) composer validate

bash:
	docker-compose exec $(DOCKER_SEARCH_SERVICE) bash
