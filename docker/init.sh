#!/bin/bash
pushd /usr/share/nginx/html/
composer install
php artisan storage:link
php artisan migrate 
php artisan db:seed 
npm install
popd
exit 0
