#!/usr/bin/env bash
# Install PHP and Composer
apt-get update && apt-get install -y php-cli php-mbstring unzip curl php-xml

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# Install Node.js dependencies and build assets
npm install && npm run build

# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force
