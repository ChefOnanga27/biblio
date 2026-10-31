# syntax=docker/dockerfile:1
#
# Image de production de la bibliothèque (Laravel 10 + Apache + PHP 8.4).
#   docker build -t biblio .
#   docker compose up -d            (voir docker-compose.yml)

# ── 1. Dépendances PHP ────────────────────────────────────────────────────────
FROM composer:2 AS vendor
WORKDIR /app
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction --ignore-platform-reqs
COPY . .
RUN composer dump-autoload --optimize --no-dev --classmap-authoritative

# ── 2. Serveur ────────────────────────────────────────────────────────────────
FROM php:8.4-apache

# Extensions : PostgreSQL (Supabase) et MySQL, GD (redimensionnement + WebP des images), EXIF (orientation des photos), zip, intl, opcache
RUN apt-get update \
    && apt-get install -y --no-install-recommends libpng-dev libjpeg62-turbo-dev libwebp-dev libfreetype6-dev libzip-dev libicu-dev libpq-dev unzip \
    && docker-php-ext-configure gd --with-jpeg --with-webp --with-freetype \
    && docker-php-ext-install -j"$(nproc)" pdo_pgsql pdo_mysql gd exif zip intl bcmath opcache \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/*

# Apache : le site est servi depuis public/, avec la réécriture d'URL de Laravel
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf \
    && a2enmod rewrite headers

COPY docker/php.ini /usr/local/etc/php/conf.d/biblio.ini

WORKDIR /var/www/html
COPY --from=vendor /app /var/www/html

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

COPY docker/entrypoint.sh /usr/local/bin/biblio-entrypoint
RUN sed -i 's/\r$//' /usr/local/bin/biblio-entrypoint && chmod +x /usr/local/bin/biblio-entrypoint

EXPOSE 80
ENTRYPOINT ["biblio-entrypoint"]
CMD ["apache2-foreground"]
