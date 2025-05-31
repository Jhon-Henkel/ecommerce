be-bash:
	@echo "Starting bash..."
	docker compose start && docker exec -it ec_backend bash

be-logs:
	@echo "Tailing logs..."
	tail -f -n 100 storage/logs/laravel.log

PHONY: be-bash be-logs
