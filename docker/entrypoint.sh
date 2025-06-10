#!/bin/sh

set -e

# Corrige permissões do projeto (garante escrita em storage e bootstrap/cache)
echo "🔧 Ajustando permissões..."
chown -R www-data:www-data /var/www
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Corrige problema do Git com ownership "duvidoso"
echo "🔧 Configurando Git safe.directory..."
git config --global --add safe.directory /var/www

echo "⏳ Aguardando o PostgreSQL iniciar..."
until pg_isready -h postgres -p 5432 -U postgres; do
  sleep 1
done
echo "✅ PostgreSQL está pronto."

# Instala dependências e executa comandos Laravel como www-data
echo "🚀 Instalando dependências e executando comandos Laravel..."
su - www-data -s /bin/sh -c '
    composer install --no-interaction --optimize-autoloader
    php artisan optimize:clear
    php artisan migrate --seed --force
    php artisan migrate --seed --force --env=testing
    php artisan l5-swagger:generate || true
'

# # Roda testes com PestPHP
# echo "🧪 Executando testes com PestPHP..."
# ./vendor/bin/pest --colors


# Iniciar o PHP-FPM em segundo plano
php-fpm &

# Inicia o Supervisor (precisa estar instalado no Dockerfile)
echo "🧩 Iniciando Supervisor..."
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
