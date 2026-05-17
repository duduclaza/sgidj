# SGI TI UAI — Sistema de Gestão de Melhorias Contínuas

Sistema web para registro, acompanhamento e análise de melhorias contínuas.

## Stack Tecnológica

| Camada    | Tecnologia                        |
|-----------|-----------------------------------|
| Frontend  | HTML5, CSS3 (Tailwind CDN), JS    |
| Backend   | PHP 8.1+                          |
| Banco     | MariaDB / MySQL                   |
| Ícones    | Lucide Icons                      |

## Estrutura do Projeto

```
├── app/                    # Backend PHP
│   ├── Controllers/        # Controladores (Auth, Dashboard, etc.)
│   ├── Core/               # Framework core (Router, Database, Auth, etc.)
│   ├── Helpers/             # Funções auxiliares
│   ├── Middlewares/         # Middlewares de autenticação/autorização
│   ├── Models/              # Modelos de dados
│   └── Services/            # Serviços de negócio
├── assets/                 # Assets públicos (CSS, JS, imagens)
│   ├── css/app.css         # Estilos customizados
│   ├── js/app.js           # JavaScript principal
│   └── img/                # Imagens
├── config/                 # Configurações (app, database)
├── database/               # Migrações do banco
├── public/                 # Entry point HTTP (index.php)
├── routes/                 # Definição de rotas (web.php)
├── storage/                # Logs e uploads
├── views/                  # Views PHP (templates HTML)
│   ├── layouts/            # Layouts (app, auth, public)
│   ├── auth/               # Login e recuperação de senha
│   ├── dashboard/          # Dashboard principal
│   ├── melhorias/          # CRUD de melhorias
│   ├── usuarios/           # Gestão de usuários
│   ├── departamentos/      # Gestão de departamentos
│   ├── reunioes/           # Gestão de reuniões
│   ├── pdca/               # Ciclo PDCA
│   ├── swot/               # Análise SWOT
│   ├── 5w2h/               # Plano 5W2H
│   ├── notificacoes/       # Notificações
│   ├── relatorios/         # Relatórios
│   ├── ia/                 # Analista IA
│   ├── public/             # Formulário público de melhorias
│   └── errors/             # Páginas de erro (403, 404)
├── index.php               # Entry point (redireciona para public/)
├── .htaccess               # Regras de rewrite Apache
└── composer.json           # Dependências PHP
```

## Requisitos

- PHP 8.1+
- MariaDB 10.4+ ou MySQL 5.7+
- Apache com mod_rewrite habilitado

## Instalação

1. Clone o repositório no diretório web do servidor
2. Copie `.env.example` para `.env` e configure as credenciais
3. Execute `composer install` (se disponível)
4. Acesse o sistema pelo navegador

## Configuração (.env)

```env
APP_NAME="Sistema de Melhoria Contínua"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com.br

DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha

SESSION_NAME=melhoria_session
```

## Módulos

- **Dashboard** — Visão executiva com métricas e gráficos
- **Melhorias** — CRUD completo com tickets, comentários e anexos
- **Formulário Público** — Canal aberto para sugestões de melhoria
- **Departamentos** — Gestão de áreas/departamentos
- **Reuniões** — Registro de reuniões de melhoria
- **PDCA** — Ciclo Plan-Do-Check-Act
- **SWOT** — Análise de forças, fraquezas, oportunidades e ameaças
- **5W2H** — Plano de ação detalhado
- **Relatórios** — Exportação e análise de dados
- **Notificações** — Sistema de notificações
- **Analista IA** — Assistente inteligente de melhorias
- **Logs de Auditoria** — Rastreamento de ações (Super Admin)
