#!/bin/bash

set -e

echo "Deploying..."

git pull origin main

php8.2 composer.phar install --no-dev --optimize-autoloader

php8.2 artisan migrate

php8.2 artisan config:cache

php8.2 artisan route:cache

php8.2 artisan storage:link

echo "Done!"