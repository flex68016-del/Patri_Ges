FROM php:8.4-cli-alpine

WORKDIR /app
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
    libxml2-dev

RUN docker-php-ext-install pdo pdo_pgsql zip intl mbstring dom

RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY services/patrimoine-service/ /app/

RUN mkdir -p /app/storage
RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app/storage

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

EXPOSE 9000
CMD ["sh", "-c", "mkdir -p /app/storage/app/jwt && if [ -n \"$JWT_PRIVATE_KEY\" ]; then printf '%s' \"$JWT_PRIVATE_KEY\" | base64 -d > /app/storage/app/jwt/private.pem; fi && if [ -n \"$JWT_PUBLIC_KEY\" ]; then printf '%s' \"$JWT_PUBLIC_KEY\" | base64 -d > /app/storage/app/jwt/public.pem; fi && php artisan serve --host=0.0.0.0 --port=${PORT:-9000} --no-reload"]
