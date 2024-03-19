#!/bin/bash

# Install PHP dependencies with Composer
composer install

# Install Node.js dependencies with npm
npm install

# Copy .env.example to .env
cp .env.example .env

# Copy .env.testing.example to .env.testing
cp .env.testing.example .env.testing

# Generate application key
php artisan key:generate

# Run database migrations and seed data
php artisan migrate --seed

# Create symbolic link for storage
php artisan storage:link

# Build assets with npm
npm run build

# Start the PHP development server
php artisan serve
