#! /bin/bash

chmod -R 777 ./storage
chmod -R 777 ./bootstrap/cache
systemctl start nginx
systemctl start php-fp
composer install
php artisan config:clear
php artisan cache:clear
php artisan jwt:secret
php artisan migrate
php artisan db:seed

