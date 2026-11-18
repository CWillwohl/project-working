# syntax=docker/dockerfile:1

ARG PHP_VERSION=8.2

FROM node:22-alpine AS assets
WORKDIR /app
COPY package.json package-lock.json ./
RUN HUSKY=0 npm ci
COPY vite.config.js tailwind.config.js postcss.config.js ./
COPY resources/ resources/
RUN npm run build

FROM php:${PHP_VERSION}-apache

ARG UID=1000
ARG GID=1000

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=mlocati/php-extension-installer:2 /usr/bin/install-php-extensions /usr/local/bin/

RUN apt-get update \
    && apt-get install -y --no-install-recommends git unzip \
    && rm -rf /var/lib/apt/lists/* \
    && install-php-extensions bcmath gd intl opcache pdo_mysql xdebug zip

RUN if ! getent group "${GID}" > /dev/null; then groupadd --gid "${GID}" app; fi \
    && useradd --uid "${UID}" --gid "${GID}" --create-home app \
    && app_group="$(id -gn app)" \
    && printf '\nexport APACHE_RUN_USER=app\nexport APACHE_RUN_GROUP=%s\n' "$app_group" >> /etc/apache2/envvars \
    && a2enmod rewrite

COPY docker/apache/working.conf /etc/apache2/sites-available/000-default.conf
COPY docker/php/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY --chmod=755 docker/php/entrypoint.sh /usr/local/bin/entrypoint
COPY --from=assets /app/public/build /opt/working-assets

WORKDIR /var/www/html

ENTRYPOINT ["entrypoint"]
CMD ["apache2-foreground"]
