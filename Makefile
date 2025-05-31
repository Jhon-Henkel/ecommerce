be-bash:
	@echo "Starting backend bash..."
	docker compose start && docker exec -it ec_backend bash

fe-sh:
	@echo "Starting frontend sh..."
	docker compose start && docker exec -it ec_frontend sh

be-logs:
	@echo "Tailing logs..."
	tail -f -n 100 backend/storage/logs/laravel.log

install:
	@echo "Installing project..."
	cp config/develop/docker/docker-compose.yml docker-compose.yml && \
	docker compose up -d --build && \
    docker exec ec_backend /bin/bash -c "composer install" && \
    cp backend/.env.example backend/.env && \
    cp frontend/.env.example frontend/.env && \
    docker exec ec_backend /bin/bash -c "php artisan key:generate" && \
    docker exec ec_backend /bin/bash -c "php artisan migrate --seed --force" && \
    chown www-data:www-data -R backend/storage/logs/ && \
    chown www-data:www-data -R backend/storage/framework && \
    chown www-data:www-data backend/database/ && \
    chown www-data:www-data backend/database/database.sqlite

PHONY: be-bash fe-bash be-logs install
