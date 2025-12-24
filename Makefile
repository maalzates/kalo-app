up:
	docker-compose up -d

down:
	docker-compose down

shell:
	docker-compose exec php bash

install-laravel:
	docker-compose exec php composer create-project laravel/laravel .
