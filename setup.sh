#!/bin/bash

echo "Setting up the project with Docker Sail..."

echo "Starting Docker containers..."
./vendor/bin/sail up -d

echo "Installing composer dependencies..."
./vendor/bin/sail composer install --no-interaction --prefer-dist --optimize-autoloader

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
