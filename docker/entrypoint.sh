#!/bin/sh

# Modify www-data uid and gid form permission issues
UID=$(stat -c "%u" ".env.example")
GID=$(stat -c "%g" ".env.example")
usermod -u "$UID" www-data
groupmod -g "$GID" www-data
usermod --shell /bin/sh www-data

if [ ! -d vendor ]; then
    composer install
fi

if [ ! -f .env ]; then
    cp .env.docker.example .env
    cp .env.docker.testing.example .env.testing
    php artisan key:generate
    php artisan migrate
fi

chown -R "$UID:$GID" ./
php-fpm
exec "$@"
