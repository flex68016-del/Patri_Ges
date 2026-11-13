FROM php:8.4-cli-alpine

RUN apk add --no-cache \
    postgresql-dev \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    composer

RUN docker-php-ext-install pdo pdo_pgsql zip intl mbstring

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

COPY . .

RUN mkdir -p /app/storage
RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app/storage

RUN composer install --no-dev --optimize-autoloader

EXPOSE 9000
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
