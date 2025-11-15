# 🚗 FCL RentACar - Sistema de Gestão de Aluguer de Carros

Sistema completo de gestão de aluguer de carros desenvolvido em Laravel, oferecendo uma plataforma moderna e intuitiva para clientes e administradores gerenciarem reservas, veículos, pagamentos e muito mais.

## 📋 Índice

- [Sobre o Projeto](#sobre-o-projeto)
- [Funcionalidades](#funcionalidades)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Estrutura do Projeto](#estrutura-do-projeto)
- [Requisitos](#requisitos)
- [Instalação](#instalação)
- [Configuração](#configuração)
- [Uso](#uso)
- [Estrutura de Dados](#estrutura-de-dados)
- [API](#api)
- [Contribuição](#contribuição)
- [Licença](#licença)

## 🎯 Sobre o Projeto

O **FCL RentACar** é uma aplicação web completa desenvolvida para facilitar o processo de aluguer de veículos. O sistema oferece uma interface pública para clientes realizarem reservas e uma área administrativa robusta para gestão completa da operação.

### Objetivos

- Simplificar o processo de reserva de veículos
- Automatizar a gestão de reservas, pagamentos e manutenções
- Fornecer uma experiência de usuário moderna e intuitiva
- Oferecer ferramentas administrativas completas para gestão da frota

## ✨ Funcionalidades

### 🌐 Área Pública

- **Catálogo de Veículos**: Visualização de todos os carros disponíveis com informações detalhadas
- **Sistema de Reservas**: Criação de reservas online com seleção de datas, locais e serviços adicionais
- **Autenticação**: Sistema de login e registro para clientes
- **Visualização de Detalhes**: Páginas detalhadas para cada veículo disponível

### 👤 Área do Cliente

- **Dashboard Personalizado**: Visão geral das reservas e informações do cliente
- **Gestão de Reservas**: Visualização, acompanhamento e cancelamento de reservas
- **Sistema de Notificações**: Notificações em tempo real sobre status de reservas e pagamentos
- **Avaliações**: Sistema de avaliação e feedback após conclusão de reservas
- **Sistema de Recompensas**: Programa de pontos e descontos baseado em avaliações
- **Perfil do Usuário**: Gestão de informações pessoais e preferências

### 🔧 Área Administrativa (Filament)

- **Gestão de Veículos**: CRUD completo para carros (marca, modelo, preço, status, imagens)
- **Gestão de Reservas**: Controle completo de reservas com atualização de status
- **Gestão de Clientes**: Administração de contas de clientes
- **Gestão de Funcionários**: Administração de funcionários (gerentes, caixas, técnicos, motoristas)
- **Gestão de Motoristas**: Cadastro e gestão de motoristas disponíveis
- **Gestão de Locais**: Administração de pontos de recolha e devolução
- **Gestão de Pagamentos**: Controle de pagamentos e depósitos
- **Relatórios de Manutenção**: Registro e acompanhamento de manutenções dos veículos
- **Sistema de Notificações**: Gestão de notificações enviadas aos clientes
- **Avaliações**: Visualização e gestão de avaliações dos clientes

### 🔐 Sistema de Autenticação e Permissões

- Autenticação completa com Laravel Breeze
- Sistema de roles e permissões com Spatie Permission
- Diferentes níveis de acesso (Admin, Gerente, Caixa, Técnico, Motorista, Cliente)
- Proteção de rotas baseada em roles

### 📱 API REST

- API completa para integração com aplicações móveis ou externas
- Endpoints para veículos, reservas, motoristas, locais e notificações
- Autenticação via Laravel Sanctum

## 🛠 Tecnologias Utilizadas

### Backend

- **Laravel 12** - Framework PHP moderno e robusto
- **PHP 8.2+** - Linguagem de programação
- **MySQL/PostgreSQL** - Banco de dados relacional
- **Laravel Sanctum** - Autenticação API
- **Spatie Laravel Permission** - Gestão de roles e permissões

### Frontend

- **Filament 4** - Painel administrativo moderno
- **Tailwind CSS** - Framework CSS utilitário
- **Alpine.js** - Framework JavaScript leve
- **Blade Templates** - Sistema de templates do Laravel
- **Inertia.js** - Bridge entre backend e frontend

### Ferramentas de Desenvolvimento

- **Laravel Breeze** - Scaffolding de autenticação
- **Laravel Pail** - Visualização de logs em tempo real
- **Laravel Pint** - Code style fixer
- **PHPUnit** - Framework de testes
- **Composer** - Gerenciador de dependências PHP
- **NPM** - Gerenciador de pacotes Node.js

### Arquitetura

- **Repository Pattern** - Separação de lógica de acesso a dados
- **Service Layer** - Lógica de negócio centralizada
- **Observer Pattern** - Eventos e notificações automáticas
- **Factory Pattern** - Criação de dados de teste

## 📁 Estrutura do Projeto

```
fcl-rentacar/
├── app/
│   ├── Filament/Resources/      # Recursos do painel administrativo
│   │   ├── Cars/                # Gestão de veículos
│   │   ├── Reservations/        # Gestão de reservas
│   │   ├── Clients/             # Gestão de clientes
│   │   ├── Employees/           # Gestão de funcionários
│   │   ├── Drivers/             # Gestão de motoristas
│   │   ├── Payments/            # Gestão de pagamentos
│   │   ├── Locations/           # Gestão de locais
│   │   └── ...
│   ├── Http/Controllers/
│   │   ├── Public/              # Controllers da área pública
│   │   ├── Client/              # Controllers da área do cliente
│   │   └── Api/                 # Controllers da API
│   ├── Models/                  # Modelos Eloquent
│   ├── Services/                # Camada de serviços
│   ├── Repositories/            # Repositórios de dados
│   ├── Observers/               # Observers para eventos
│   └── Notifications/           # Notificações do sistema
├── database/
│   ├── migrations/              # Migrações do banco de dados
│   ├── seeders/                 # Seeders para dados iniciais
│   └── factories/               # Factories para testes
├── resources/
│   ├── views/
│   │   ├── public/              # Views da área pública
│   │   └── client/              # Views da área do cliente
│   └── js/                      # Assets JavaScript
└── routes/
    ├── web.php                  # Rotas web
    ├── api_rentacar.php         # Rotas da API
    └── auth.php                 # Rotas de autenticação
```

## 📋 Requisitos

- PHP >= 8.2
- Composer >= 2.0
- Node.js >= 18.x e NPM
- MySQL >= 8.0 ou PostgreSQL >= 13
- Extensões PHP: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML

## 🚀 Instalação

### 1. Clonar o Repositório

```bash
git clone https://github.com/migueltchiyovosapalalo827/fcl-rentacar.git
cd fcl-rentacar
```

### 2. Instalar Dependências

```bash
# Instalar dependências PHP
composer install

# Instalar dependências Node.js
npm install
```

### 3. Configurar Ambiente

```bash
# Copiar arquivo de ambiente
cp .env.example .env

# Gerar chave da aplicação
php artisan key:generate
```

### 4. Configurar Banco de Dados

Edite o arquivo `.env` e configure as credenciais do banco de dados:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fcl_rentacar
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
```

### 5. Executar Migrações e Seeders

```bash
# Executar migrações
php artisan migrate

# Popular banco de dados com dados iniciais
php artisan db:seed
```

### 6. Compilar Assets

```bash
# Desenvolvimento
npm run dev

# Produção
npm run build
```

### 7. Iniciar Servidor

```bash
php artisan serve
```

A aplicação estará disponível em `http://localhost:8000`

## ⚙️ Configuração

### Configurar Permissões de Armazenamento

```bash
php artisan storage:link
```

### Configurar Filas (Opcional)

Para processar notificações e emails em background:

```bash
php artisan queue:work
```

### Configurar Agendamento de Tarefas

Adicione ao crontab para executar comandos agendados:

```bash
* * * * * cd /caminho/do/projeto && php artisan schedule:run >> /dev/null 2>&1
```

## 💻 Uso

### Acesso ao Sistema

- **Área Pública**: `http://localhost:8000`
- **Área do Cliente**: `http://localhost:8000/cliente/dashboard` (requer login)
- **Painel Administrativo**: `http://localhost:8000/admin` (requer login admin)

### Credenciais Padrão

Após executar os seeders, você pode usar:

- **Admin**: `admin@rentacar.com` / `password`
- **Gerente**: `manager@rentacar.com` / `password`
- **Cliente**: Criar conta através do registro público

### Principais Fluxos

1. **Cliente faz reserva**: Navega pelo catálogo → Seleciona veículo → Preenche formulário → Confirma reserva
2. **Admin gerencia**: Acessa painel → Visualiza reservas → Atualiza status → Processa pagamentos
3. **Sistema notifica**: Mudanças de status geram notificações automáticas para clientes

## 🗄 Estrutura de Dados

### Principais Entidades

- **Users**: Usuários do sistema (clientes, funcionários, admin)
- **Cars**: Veículos da frota
- **Reservations**: Reservas realizadas
- **Payments**: Pagamentos processados
- **Deposits**: Depósitos/cauções
- **Drivers**: Motoristas disponíveis
- **Locations**: Locais de recolha/devolução
- **Notifications**: Notificações do sistema
- **ReservationReviews**: Avaliações de reservas
- **Rewards**: Recompensas e descontos
- **MaintenanceReports**: Relatórios de manutenção

## 🔌 API

A API REST está disponível em `/api/v1/` com os seguintes endpoints:

- `GET /api/v1/cars` - Listar veículos
- `GET /api/v1/reservations` - Listar reservas
- `POST /api/v1/reservations` - Criar reserva
- `PUT /api/v1/reservations/{id}/status` - Atualizar status
- E mais...

Consulte `routes/api_rentacar.php` para a lista completa de endpoints.

## 🧪 Testes

```bash
# Executar todos os testes
php artisan test

# Executar testes específicos
php artisan test --filter NomeDoTeste
```

## 📝 Scripts Disponíveis

```bash
# Setup completo do projeto
composer run setup

# Modo desenvolvimento (servidor + queue + logs + vite)
composer run dev

# Executar testes
composer run test
```

## 🤝 Contribuição

Contribuições são bem-vindas! Por favor:

1. Faça um Fork do projeto
2. Crie uma branch para sua feature (`git checkout -b feature/AmazingFeature`)
3. Commit suas mudanças (`git commit -m 'Add some AmazingFeature'`)
4. Push para a branch (`git push origin feature/AmazingFeature`)
5. Abra um Pull Request

## 📄 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 👥 Autores

- **Miguel Sapalo** - Desenvolvimento inicial

## 🙏 Agradecimentos

- Laravel Framework
- Filament Team
- Comunidade Open Source

---

**Desenvolvido com ❤️ usando Laravel e Filament**
