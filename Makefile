LARAVEL_CONTAINER=grocery-tracker-laravel.test-1

.PHONY: up down migrate seed fresh bash logs

up:
	docker compose up -d

down:
	docker compose down

migrate:
	docker exec $(LARAVEL_CONTAINER) php artisan migrate

seed:
	docker exec $(LARAVEL_CONTAINER) php artisan db:seed

fresh:
	docker exec $(LARAVEL_CONTAINER) php artisan migrate:fresh --seed

bash:
	docker exec -it $(LARAVEL_CONTAINER) bash

logs:
	docker logs -f $(LARAVEL_CONTAINER)
