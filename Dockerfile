FROM php:8.3-fpm

# Update package list and install dependencies
RUN apt-get update && apt-get install -y --no-install-recommends \
    libzip-dev \
    libpng-dev \
    libpq-dev \
    nodejs \
    npm \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install zip pdo_mysql gd bcmath \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Xóa cache của apt để giảm kích thước image
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Sao chép mã nguồn của ứng dụng vào container
WORKDIR /var/www

COPY . /var/www

# Cấp quyền sở hữu cho user www-data đối với toàn bộ thư mục /var/www
RUN chown -R www-data:www-data /var/www


# Tạo file storage và cache directories và cấp quyền ghi
RUN mkdir -p storage bootstrap/cache
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache
RUN chmod -R a+rw storage

# Cài đặt các dependency của Laravel
RUN composer install --optimize-autoloader --no-dev

# Chạy các lệnh cần thiết cho Laravel (ví dụ: generate key)
RUN php artisan key:generate

# Chuyển quyền sở hữu cho user www-data
USER www-data

# Expose port 9000 cho PHP-FPM
EXPOSE 9000

# Lệnh mặc định khi container khởi chạy
CMD ["php-fpm"]
