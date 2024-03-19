FROM php:8.2-fpm-alpine

WORKDIR /var/www/html

RUN apk --update add ca-certificates

RUN apk --no-cache add shadow

RUN docker-php-ext-install mysqli pdo_mysql && docker-php-ext-enable pdo_mysql

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

COPY entrypoint.sh /bin/entrypoint.sh
RUN chmod +x /bin/entrypoint.sh

ENTRYPOINT /bin/entrypoint.sh
