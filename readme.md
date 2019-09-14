## Docker 起動

```sh
docker-compose build
docker-compose up
```

## 一括セットアップ

```sh
docker exec -it docker_compose_laravel_web_1 /bin/bash
./setup.sh
```

## 手動でやるとき用

```sh
docker exec -it docker_compose_laravel_web_1 /bin/bash
composer install
chmod -R 777 ./storage
chmod -R 777 ./bootstrap/cache
systemctl start nginx
systemctl start php-fpm
```

### JWT

```sh
php artisan cache:clear
php artisan config:clear
php artisan jwt:secret
```

## Local Machineのセットアップ

```sh
echo "127.0.0.1 s3" >> /etc/hosts
```

アクセス権の変更(mac)

```sh
brew install minio/stable/mc
mc policy set public local/data
```
