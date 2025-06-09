# 📝 Documentação da API PayNet - Guia de Instalação Local

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
php artisan queue:work --queue=risk_analysis
```

```bash
# 9. Inicie o Horizon para gerenciamento de filas (em outro terminal)
php artisan horizon
```

## 🔧 Dicas Extras

- ✨ Execute `php artisan key:generate` se for a primeira instalação
- 🔍 Configure seu `.env` com as credenciais corretas de banco de dados
- 🧪 Para executar testes: `php artisan test`
- 📊 Acesse a documentação Swagger em: `http://localhost:8000/api/documentation`

# 🐳 Configuração do Ambiente Laravel com Docker

Este guia explica como configurar e executar um projeto Laravel 12 com PHP 8.3 e PostgreSQL usando Docker Compose.

## 📋 Pré-requisitos
- Docker instalado ([Instalação no Ubuntu](#instalando-docker-no-ubuntu))
- Docker Compose

## 🚀 Iniciando o Projeto

```bash
# 1. Clone o repositório do projeto
git clone https://github.com/GabrielMMC/paynet-app
```
## 🐳 Subindo containers que formam a rede

```bash
# 1. Execute a rede de containers
docker-compose up --build

# 2. Liste os containers que estão de pé
docker ps

# 3. Entre no container da aplicação Laravel
docker-compose exec -it b98 /bin/bash

# 4. Rode as migrations
php artisan migrate
```

```bash
# 5. Por fim, não se esqueça de configurar o .env apontando para o DB correto configurado nos containers
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=paynet_db
DB_USERNAME=postgres
DB_PASSWORD=123456
```

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