# syntax=docker/dockerfile:1

# Stage 1: Base image with PHP and Composer
FROM php:8.4-fpm AS base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libzip-dev \
    unzip \
    git \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libpng-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_mysql gd

# Install Composer
COPY --link --from=composer:2 /usr/bin/composer /usr/bin/composer

# Stage 2: Application setup
FROM base AS app-setup

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY --link . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions
RUN chown -R www-data:www-data /var/www/html

# Stage 3: Final image
FROM base AS final

# Copy application from the setup stage
COPY --from=app-setup /var/www/html /var/www/html

# Expose port 9000 for PHP-FPM
EXPOSE 9090

# Set the entrypoint to PHP-FPM
CMD ["php-fpm"]
