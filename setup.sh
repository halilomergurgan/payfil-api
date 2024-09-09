#!/bin/bash

echo "Setting up the project with Docker Sail..."

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

if [ ! -f .env ]; then
    echo "Creating .env file..."
    cp .env.example .env
else
    echo ".env file already exists."
fi

echo "Generating application key..."
./vendor/bin/sail artisan key:generate

echo "Running database migrations..."
./vendor/bin/sail artisan migrate

echo "Seeding the database..."
./vendor/bin/sail artisan db:seed

echo "Linking storage directory..."
./vendor/bin/sail artisan storage:link

echo "Starting the queue worker..."
./vendor/bin/sail artisan queue:work

echo "Setup complete!"
