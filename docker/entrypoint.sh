#!/bin/sh

set -e

# Ajustar permissões
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

echo 'rodour'

# Executar comandos como www-data
su - www-data -s /bin/sh -c '
    composer install --no-interaction --optimize-autoloader
    php artisan optimize:clear
    php artisan migrate --seed --force
    php artisan l5-swagger:generate
'
# Iniciar Supervaisor
exec supervisord -n -c /etc/supervisor/conf.d/supervisord.conf