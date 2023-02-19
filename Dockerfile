FROM php:7.4-apache

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_mysql

# Copy Apache virtual host file
COPY apache-vhost.conf /etc/apache2/sites-available/000-default.conf

# Enable Apache mod_rewrite
RUN a2enmod rewrite
