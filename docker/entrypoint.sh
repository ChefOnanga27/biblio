#!/bin/sh
# Préparation de l'application au démarrage du conteneur.
set -e

cd /var/www/html

# Dossiers persistants (le volume de storage peut arriver vide)
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
chown -R www-data:www-data storage bootstrap/cache

# Port imposé par l'hébergeur (Render fournit $PORT) ; 80 par défaut
if [ -n "$PORT" ] && [ "$PORT" != "80" ]; then
    sed -ri "s/^Listen [0-9]+/Listen ${PORT}/" /etc/apache2/ports.conf
    sed -ri "s/<VirtualHost \*:[0-9]+>/<VirtualHost *:${PORT}>/" /etc/apache2/sites-available/000-default.conf
fi

if [ -z "$APP_KEY" ]; then
    echo "ERREUR : APP_KEY est vide. Générez-la avec « php artisan key:generate --show » et ajoutez-la à .env." >&2
    exit 1
fi

# Lien public/storage → storage/app/public, seulement si les médias restent en local
if [ "${MEDIA_DISK:-public}" = "public" ] && [ ! -L public/storage ]; then
    php artisan storage:link
fi

# Migrations automatiques si RUN_MIGRATIONS=true
if [ "${RUN_MIGRATIONS:-false}" = "true" ]; then
    echo "Attente de la base de données…"
    for i in $(seq 1 30); do
        php artisan migrate --force && break
        sleep 2
    done
fi

php artisan config:cache
php artisan view:cache

exec "$@"
