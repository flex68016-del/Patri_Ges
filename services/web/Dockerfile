FROM php:8.4-fpm-alpine

RUN apk add --no-cache \
    postgresql-dev \
    git \
    unzip \
    libzip-dev \
    icu-dev \
    oniguruma-dev

RUN docker-php-ext-install pdo pdo_pgsql zip intl mbstring

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

COPY . .

RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app/storage

CMD ["php-fpm"]
