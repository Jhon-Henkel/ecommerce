## Geral
```
    cp config/develop/docker/docker-compose.yml docker-compose.yml
    docker compose up -d
```
## Backend (dentro do container)
```
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate
```
