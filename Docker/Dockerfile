FROM php:7.4-fpm

RUN apt-get update && apt-get install -y \
    git \
    zip \
    unzip

# Install composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql