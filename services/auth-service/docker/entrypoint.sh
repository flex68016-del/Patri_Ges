#!/bin/sh

# Génération de la clé APP si elle n'existe pas
if [ -z "$APP_KEY" ]; then
    php artisan key:generate
fi

# Optimisation
php artisan config:cache
php artisan route:cache

# Démarrage de PHP-FPM et Nginx
php-fpm -D
nginx -g "daemon off;"
