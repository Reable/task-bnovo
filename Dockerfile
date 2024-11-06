FROM php:8.3-fpm

# Установка необходимых пакетов
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zlib1g-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mysqli gd

WORKDIR /var/www