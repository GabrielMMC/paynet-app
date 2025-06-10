# Use a imagem base do PHP 8.3 com FPM
FROM php:8.3-fpm

# Definir variáveis de ambiente
ENV DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1

# Instalar dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    libpq-dev \
    zip \
    unzip \
    postgresql-client \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões do PHP necessárias
RUN docker-php-ext-install \
    pdo \
    pdo_pgsql \
    pgsql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd \
    zip

# Instalar Supervisor
RUN apt-get update && apt-get install -y supervisor

# Instalar a extensão Redis via PECL
RUN pecl install redis && docker-php-ext-enable redis

# Criar pasta de logs do Supervisor
RUN mkdir -p /var/log/supervisor

# Copiar o arquivo de configuração do Supervisor
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Instalar o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurar o diretório de trabalho
WORKDIR /var/www

# Copiar os arquivos do projeto
COPY . .

# Instalar dependências do Laravel (ignorando scripts para rodar manualmente)
RUN composer install --no-scripts --no-autoloader \
    && composer clear-cache

# Gerar autoloader otimizado
RUN composer dump-autoload --optimize

# Copiar o entrypoint
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Definir entrypoint
ENTRYPOINT ["entrypoint.sh"]
