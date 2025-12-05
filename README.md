# ⚡ Solar Projeto - Sistema de Gerenciamento de Orçamentos

[![Laravel](https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat-square&logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=flat-square&logo=php)](https://php.net)
[![Docker](https://img.shields.io/badge/Docker-✓-2496ED?style=flat-square&logo=docker)](https://docker.com)

Sistema de backend API para gerenciamento de clientes, equipamentos solares e orçamentos.

## 🐳 Setup Docker com Laravel 10 e PHP 8.1

### Passo a Passo

1. **Baixe o projeto**
```bash
git clone https://github.com/clayasf/solar-projeto.git
cd solar-projeto
Crie o arquivo .env

bash
cp .env.example .env
Atualize as variáveis no .env:

env
APP_NAME="Solar Project Management"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost:8989

# Configurações do Docker
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sistema_gestao
DB_USERNAME=root
DB_PASSWORD=root

# Outras configurações...
Suba os containers

bash
docker-compose up -d
Acesse o container app

bash
docker-compose exec app bash
Instale as dependências (dentro do container)

bash
composer install
Gere a chave do Laravel

bash
php artisan key:generate
Execute migrações e seeders

bash
php artisan migrate
php artisan db:seed
Acesse o projeto
👉 http://localhost:8989

📡 Endpoints da API
👥 Clientes
text
GET    /api/clientes          # Listar todos
POST   /api/clientes          # Criar novo
🔧 Equipamentos
text
GET    /api/equipamentos      # Listar (com categorias)
POST   /api/equipamentos      # Criar novo
💰 Orçamentos
text
POST   /api/orcamentos        # Criar com equipamentos
GET    /api/orcamentos/{id}   # Ver com total calculado
Exemplo criação orçamento:

json
{
  "cliente_id": 1,
  "equipamentos": [
    {"equipamento_id": 1, "quantidade": 2}
  ]
}
🎯 Features
✅ CRUD completo (Clientes, Equipamentos, Orçamentos)

✅ Enum TipoEquipamento com categorias automáticas

✅ Cálculo automático de total em orçamentos

✅ Validações com FormRequests

✅ Seeders para dados iniciais

✅ Docker pronto para uso

🔧 Comandos Úteis (dentro do container)
bash
# Servidor
php artisan serve --host=0.0.0.0 --port=8989

# Banco de dados
php artisan migrate
php artisan db:seed

# Limpeza
php artisan config:clear
php artisan cache:clear

# Testar API
curl http://localhost:8989/api/clientes
🧪 Testando
bash
# Listar clientes
curl http://localhost:8989/api/clientes

# Criar orçamento
curl -X POST http://localhost:8989/api/orcamentos \
  -H "Content-Type: application/json" \
  -d '{"cliente_id":1,"equipamentos":[{"equipamento_id":1,"quantidade":2}]}'
👤 Autor: Clayton Freitas
📁 Repositório: github.com/clayasf/solar-projeto
