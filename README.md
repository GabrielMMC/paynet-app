# 📝 Documentação da API PayNet - Guia de Instalação

## 🐳 Configuração do Ambiente Laravel com Docker

Este guia explica como configurar e executar um projeto Laravel 12 com PHP 8.3, PostgreSQL, Redis e Nginx usando Docker Compose.

## 📋 Pré-requisitos
- Docker instalado ([Instalação no Ubuntu](#instalando-docker-no-ubuntu))
- Docker Compose

```bash
# 1. Clone o repositório do projeto
git clone https://github.com/GabrielMMC/paynet-app

# 2. Execute a rede de containers
docker-compose up --build

# 3. Liste os containers que estão de pé
docker ps

# Visualize os containers em funcionamento, deve ser algo como:
gabrielfacioni@DESKTOP-UUC2MV5:/projects/paynet-app$ docker ps
CONTAINER ID   IMAGE            COMMAND                  CREATED          STATUS          PORTS                                           NAMES
f14f34de4332   nginx:alpine     "/docker-entrypoint.…"   14 seconds ago   Up 13 seconds   0.0.0.0:8000->80/tcp, [::]:8000->80/tcp         laravel_webserver
1e5c119b68c9   paynet-app-app   "entrypoint.sh"          14 seconds ago   Up 13 seconds   9000/tcp                                        laravel_app
51609f76de00   postgres:15      "docker-entrypoint.s…"   14 seconds ago   Up 14 seconds   0.0.0.0:54327->5432/tcp, [::]:54327->5432/tcp   laravel_postgres
141dfdc511b1   redis:7          "docker-entrypoint.s…"   14 seconds ago   Up 14 seconds   0.0.0.0:63797->6379/tcp, [::]:63797->6379/tcp   redis

# 4. Entre no container da aplicação Laravel
docker exec -it 1e5 /bin/bash

# 5. Rode os testes
vendor/bin/pest
```

## 🐞 Possíveis Erros e Soluções com Docker

### ⚠️ Erro: Permissão negada ao rodar containers, especialmente em pastas como `storage` ou `bootstrap/cache`
```bash
 Solução:
  sudo chown -R $USER:$USER . 
  chmod -R 775 storage bootstrap/cache
```

### ⚠️ Erro: permission denied ao copiar arquivos durante o build 
Causa: Arquivos como logs ou relatórios têm permissões restritas no host. Solução:
```
sudo chown -R $USER:$USER .
sudo chmod -R 775 .
```
### ⚠️ Erro: Nginx com erro 502 (Bad Gateway)
Causa: O container app ainda não está pronto ou o php-fpm está fora do ar.
Solução: Aguarde alguns segundos após `docker-compose up` ou valide se o Supervisor está rodando o `php-fpm`.

## 🚀 Subindo a aplicação localmente (sem Docker)

```bash
# 1. Clone o repositório do projeto
git clone https://github.com/GabrielMMC/paynet-app
```

```bash
# 2. Instale as dependências do Composer
composer install
```

```bash
# 3. Configure o ambiente
cp .env.example .env  # Crie seu arquivo .env
nano .env             # Edite as configurações necessárias
```

```bash
# 4. Crie os bancos de dados
# (Sugestão: paynet_db para desenvolvimento e paynet_tests_db para testes)
# Não se esqueça de configurar o .env apontando para o DB correto configurado nos containers, lembrando que no .env.example as chaves DB_HOST e REDIS_HOST estão apontadas para o container do docker, em caso de uso local devem ser definidas como localhost ou 127.0.0.1
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=paynet_db
DB_USERNAME=postgres
DB_PASSWORD=123456
```

```bash
# 5. Execute as migrações e seeders
php artisan migrate --seed               # Para o ambiente de desenvolvimento
php artisan migrate --seed --env=testing # Para o ambiente de testes
```

```bash
# 6. Gere a documentação Swagger/OpenAPI
php artisan l5-swagger:generate
```

```bash
# 7. Inicie o servidor de desenvolvimento
php artisan serve
```

```bash
# 8. Processamento de filas (em outro terminal)
php artisan queue:work --queue=risk-analysis
```

```bash
# 9. Inicie o Horizon para gerenciamento de filas (em outro terminal)
php artisan horizon
```

## 🔧 Dicas Extras

- ✨ Execute `php artisan key:generate` se for a primeira instalação
- 🔍 Configure seu `.env` com as credenciais corretas de banco de dados
- 🧪 Para executar testes: `vendor/bin/pest`
- 📊 Acesse a documentação Swagger em: `http://localhost:8000/api/documentation`
- 🌅 Acesse o dashboard do Laravel Horizons em: `http://localhost:8000/horizon`

## 💻 Instalando Docker no Ubuntu
```bash
# Remova versões antigas
sudo apt remove docker docker-engine docker.io containerd runc

# Instale dependências
sudo apt update
sudo apt install -y ca-certificates curl gnupg lsb-release

# Adicione a chave GPG
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo gpg --dearmor -o /etc/apt/keyrings/docker.gpg

# Adicione o repositório
echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable" | sudo tee /etc/apt/sources.list.d/docker.list > /dev/null

# Instale o Docker
sudo apt update
sudo apt install -y docker-ce docker-ce-cli containerd.io docker-compose-plugin

# Configure permissões
sudo groupadd docker
sudo usermod -aG docker $USER
newgrp docker

# Teste a instalação
docker run hello-world
```