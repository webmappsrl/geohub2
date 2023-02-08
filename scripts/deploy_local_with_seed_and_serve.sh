#!/bin/bash
set -e

echo "Deployment started ..."

composer install
composer dump-autoload
php artisan optimize
php artisan migrate:fresh --seed
php artisan serve --host 0.0.0.0

echo "Deployment finished!"