#!/bin/bash

echo "Setting up the project with Docker Sail..."

if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
    echo "REDIS_HOST=redis" >> .env
    echo "DB_CONNECTION=mysql" >> .env
    echo "DB_HOST=mysql" >> .env
    echo "DB_PORT=3306" >> .env
    echo "DB_DATABASE=laravel" >> .env
    echo "DB_USERNAME=sail" >> .env
    echo "DB_PASSWORD=password" >> .env
fi

if [ ! -d "vendor" ]; then
    echo "Installing Composer dependencies using Docker..."
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer install --no-dev --prefer-dist --optimize-autoloader
else
    echo "Vendor folder already exists, skipping Composer install."
fi

if [ ! -f "vendor/bin/sail" ]; then
    echo "Sail not found. Installing Sail dependencies..."
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php83-composer:latest \
        composer require laravel/sail --dev
fi

echo "Starting Docker containers"
./vendor/bin/sail up -d

echo "Waiting for MySQL service to be ready..."
./vendor/bin/sail exec mysql bash -c 'until mysqladmin ping -h "mysql" --silent; do echo "Waiting for database connection..."; sleep 5; done'

echo "Generating application key..."
./vendor/bin/sail artisan key:generate

echo "Running database migrations..."
./vendor/bin/sail artisan migrate

echo "Seeding the database..."
./vendor/bin/sail artisan db:seed

if [ ! -L "public/storage" ]; then
    echo "Linking storage directory..."
    ./vendor/bin/sail artisan storage:link
else
    echo "Storage directory link already exists."
fi

echo "Starting the queue worker..."
./vendor/bin/sail artisan queue:work

echo "Setup complete!"
