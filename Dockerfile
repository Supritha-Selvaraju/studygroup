FROM php:8.2-apache

# Install mysqli and PDO MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Enable Apache rewrite (optional)
RUN a2enmod rewrite

# Copy public folder contents to Apache root
COPY public/ /var/www/html/
# Install dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    libzip-dev \
    libpng-dev \
    && docker-php-ext-install zip gd \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer
