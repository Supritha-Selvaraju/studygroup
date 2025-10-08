# Use official PHP 8.2 image with Apache
FROM php:8.2-apache

# Install PHP extensions: mysqli, PDO MySQL, GD, ZIP
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install mysqli pdo pdo_mysql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Enable Apache mod_rewrite for clean URLs
RUN a2enmod rewrite

# Copy your public folder into Apache’s web root
COPY public/ /var/www/html/

# Set working directory (optional, but good practice)
WORKDIR /var/www/html

# Copy composer and install globally
RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer
