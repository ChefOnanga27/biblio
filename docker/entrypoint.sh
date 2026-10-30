#!/bin/sh
# Préparation de l'application au démarrage du conteneur.
set -e

cd /var/www/html

# Dossiers persistants (le volume de storage peut arriver vide)
mkdir -p storage/app/public storage/framework/cache storage/framework/sessions storage/framework/views storage/logs
chown -R www-data:www-data storage bootstrap/cache

if [ -z "$APP_KEY" ]; then
    echo "ERREUR : APP_KEY est vide. Générez-la avec « php artisan key:generate --show » et ajoutez-la à .env." >&2
    exit 1
fi

# Lien public/storage → storage/app/public (couvertures, PDF, avatars)
[ -L public/storage ] || php artisan storage:link

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
