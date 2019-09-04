docker-compose.yml がある場所で

```sh
docker-compose build
docker-compose up
```

上記完了後、別のターミナルを起動して

```sh
docker exec -it docker_compose_laravel_web_1 /bin/bash
composer install
chmod -R 777 ./storage
chmod -R 777 ./bootstrap/cache
systemctl start nginx
systemctl start php-fpm
```

JWT

```sh
php artisan cache:clear
php artisan config:clear
php artisan jwt:secret
```

Local Machine

```sh
echo "127.0.0.1 s3" >> /etc/hosts
```

アクセス権の変更

```sh
brew install minio/stable/mc
mc policy set public local/data
```
