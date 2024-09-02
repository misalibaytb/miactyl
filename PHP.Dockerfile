# Use the official PHP 8.1 FPM base image
FROM php:8.1-fpm

# Install necessary packages and PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip && \
    docker-php-ext-install mysqli zip pdo pdo_mysql && \
    pecl install xdebug && \
    docker-php-ext-enable xdebug

# Optionally, clean up apt cache to reduce image size
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
