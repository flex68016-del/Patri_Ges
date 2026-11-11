FROM node:20-alpine AS frontend

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

FROM php:8.2-fpm-alpine

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

COPY --from=frontend /app/public public/
COPY . .

RUN chown -R www-data:www-data /app
RUN chmod -R 755 /app/storage

CMD ["php-fpm"]
