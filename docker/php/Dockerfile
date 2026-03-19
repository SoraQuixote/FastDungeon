FROM php:8.2-apache

# Extensions nécessaires à Symfony + MariaDB
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libzip-dev \
    zip unzip git \
    && docker-php-ext-install intl pdo pdo_mysql zip opcache
    
# Installer Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Activer rewrite pour Symfony
RUN a2enmod rewrite

# Config Apache
COPY apache/vhost.conf /etc/apache2/sites-available/000-default.conf

COPY --link \
    --from=ghcr.io/symfony-cli/symfony-cli:latest \
    /usr/local/bin/symfony /usr/local/bin/symfony
    
WORKDIR /var/www/html
