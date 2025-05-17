FROM php:8.3-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    unzip git libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev pkg-config libssl-dev

# Instalar extensiones PHP (mysqli, mongodb)
RUN docker-php-ext-install mysqli \
    && pecl install mongodb-1.15.0 \
    && docker-php-ext-enable mongodb

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copiar archivos del proyecto
COPY www/ /var/www/html/
COPY composer.json /var/www/html/
COPY composer.lock /var/www/html/

# Establecer directorio de trabajo
WORKDIR /var/www/html/

# Instalar dependencias PHP
RUN composer install
