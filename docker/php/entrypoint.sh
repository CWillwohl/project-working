#!/bin/sh
set -e

if [ "$1" = "apache2-foreground" ]; then
    if [ ! -f .env ]; then
        echo "Arquivo .env não encontrado. Crie-o com: cp .env.dev .env" >&2
        exit 1
    fi

    runuser -u app -- composer install --no-interaction --prefer-dist --no-progress
    runuser -u app -- php artisan config:clear

    if ! grep -qE '^APP_KEY=.+' .env; then
        runuser -u app -- php artisan key:generate --ansi
    fi

    runuser -u app -- php artisan migrate --force
    runuser -u app -- mkdir -p public/build
    runuser -u app -- cp -R /opt/working-assets/. public/build/
    exec docker-php-entrypoint "$@"
fi

exec runuser -u app -- "$@"
