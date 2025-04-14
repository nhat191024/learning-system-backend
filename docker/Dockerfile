FROM php:8.3-fpm-alpine AS composer

WORKDIR /app

COPY composer.lock composer.json

RUN composer install --no-dev --optimize-autoloader

FROM node:slim AS builder

WORKDIR /app

COPY package*.json ./
COPY vite.config.js ./

RUN npm install
COPY resources resources
COPY public public

RUN npm run build


FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

# Cài đặt các extension PHP cần thiết
RUN docker-php-ext-install pdo pdo_mysql bcmath gd

# Cài đặt composer (nếu chưa có)
COPY --from=composer /usr/local/bin/composer /usr/local/bin/composer
COPY --from=composer /app /var/www/html

# Sao chép mã nguồn Laravel
COPY . .

# Sao chép thư mục public đã build từ builder stage
COPY --from=builder /app/public /var/www/html/public

# Chỉnh quyền thư mục
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

# Cài đặt và kích hoạt extension opcache
RUN docker-php-ext-install opcache
RUN docker-php-ext-enable opcache

# Cấu hình opcache (tùy chọn)
COPY docker/php/conf.d/docker-php-ext-opcache.ini /usr/local/etc/php/conf.d/docker-php-ext-opcache.ini

# Expose cổng 80 (hoặc cổng bạn cấu hình trong Laravel)
EXPOSE 80

# Lệnh chạy artisan migrate và server
CMD php artisan migrate && php artisan serve --host=0.0.0.0 --port=80
