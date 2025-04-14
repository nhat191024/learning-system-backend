FROM php:8.3-fpm-alpine AS composer

WORKDIR /app

COPY composer.lock composer.json

RUN /usr/local/bin/composer install --no-dev --optimize-autoloader

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

RUN docker-php-ext-install pdo pdo_mysql bcmath gd

COPY --from=composer /usr/local/bin/composer /usr/local/bin/composer
COPY --from=composer /app /var/www/html

COPY . .

COPY --from=builder /app/public /var/www/html/public

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 755 /var/www/html/storage /var/www/html/bootstrap/cache

RUN docker-php-ext-install opcache
RUN docker-php-ext-enable opcache

EXPOSE 80

CMD php artisan serve --host=0.0.0.0 --port=80
