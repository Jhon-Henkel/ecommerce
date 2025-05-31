## Geral
```
    cp config/develop/docker/docker-compose.yml docker-compose.yml
    docker compose up -d
```
- Usuário de teste: admin
- Senha de teste: admin

## Backend (dentro do container)
```
    composer install
    cp .env.example .env
    php artisan key:generate
    php artisan migrate --seed --force
    chown www-data:www-data -R storage/logs/
    chown www-data:www-data -R storage/framework
    chown www-data:www-data database/
    sudo chown www-data:www-data database/database.sqlite
```
