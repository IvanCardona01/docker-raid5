FROM php:8.3-apache

# Instalar dependencias necesarias
RUN apt-get update && apt-get install -y \
    unzip git libpng-dev libonig-dev libxml2-dev libcurl4-openssl-dev pkg-config libssl-dev

# Instalar extensiones PHP: mysqli y mongodb
RUN docker-php-ext-install mysqli \
    && pecl install mongodb-1.15.0 \
    && docker-php-ext-enable mongodb

# Copiar archivos del proyecto
COPY www/ /var/www/html/

# Establecer directorio de trabajo
WORKDIR /var/www/html/
