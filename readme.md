
docker-compose.ymlがある場所で
```
docker-compose build
docker-compose up
```

上記完了後、別のターミナルを起動して
```
docker exec -it docker_compose_laravel_web_1 /bin/bash
composer install
chmod -R 777 ./storage
chmod -R 777 ./bootstrap/cache 
systemctl start nginx
systemctl start php-fpm
```

JWT
```
php artisan cache:clear
php artisan config:clear
php artisan jwt:secret
```
