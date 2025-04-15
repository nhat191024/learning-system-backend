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

COPY . /var/www

# Sao chép mã nguồn của ứng dụng vào container
WORKDIR /var/www

# Cài đặt Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Cài đặt các dependency của Laravel
RUN composer install

# RUN chmod -R a+rw storage
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Cài đặt các dependency của Node.js
RUN npm install

# Chạy lệnh build cho vite
RUN npm run build

# tạo key cho ứng dụng Laravel
RUN php artisan key:generate

# Expose port 9000 cho PHP-FPM
EXPOSE 9000

# Lệnh mặc định khi container khởi chạy
CMD ["php-fpm"]
