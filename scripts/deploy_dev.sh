#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true
# if already is in maintenance mode
(php artisan down) || true

# Pull the latest version of the app
# git pull origin develop

# Install composer dependencies
composer install  --no-interaction --prefer-dist --optimize-autoloader
# php artisan nova:install

# Run database migrations
php artisan migrate:fresh --seed


# Clear the old cache
php artisan clear-compiled

composer dump-autoload
php artisan optimize

# Compile npm assets
# npm run prod
# cd vendor/laravel/nova && npm install
# Exit maintenance mode
php artisan up

echo "Deployment finished!"
