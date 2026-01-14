# Changelog

Todas as mudanças notáveis neste projeto serão documentadas neste arquivo.

O formato é baseado em [Keep a Changelog](https://keepachangelog.com/pt-BR/1.0.0/),
e este projeto adere ao [Semantic Versioning](https://semver.org/lang/pt-BR/).

## [Não Lançado]

### Planejado
- Sistema de permissões e roles avançado
- Recuperação de senha por e-mail
- Autenticação de dois fatores (2FA)
- API RESTful
- Logs de auditoria detalhados
- Dashboard com gráficos e estatísticas
- Exportação de relatórios (PDF, Excel)
- Sistema de notificações em tempo real
- Upload de avatar de usuário
- Tema escuro
- Paginação de resultados
- Busca avançada
- Filtros dinâmicos
- Importação de dados (CSV, Excel)
- Backup automático do banco de dados

---

## [1.0.0] - 2026-01-14

### Adicionado

#### Core do Sistema
- **PHP 8.4+** com Orientação a Objetos (compatível com PHP 8.4 e 8.5)
- **Arquitetura MVC** completa e organizada
  - Controllers com métodos auxiliares
  - Models com CRUD base
  - Views com Twig templates
  - Roteamento automático
- **Composer** para gerenciamento de dependências
- **Autoloader** PSR-4 customizado
- **Front Controller** (index.php) como ponto de entrada único

#### Recursos PHP 8.4+
- Tipagem forte (strict_types) em todos os arquivos
- Propriedades readonly para imutabilidade
- Union types (array|false, mixed)
- Enums para UserRole e UserStatus
- Atributos PHP 8 para rotas
- Arrow functions para código mais limpo
- Named arguments suportados
- Classes final para prevenir herança indevida
- Classes abstract para herança controlada
- Tratamento de erros com Throwable
- Match expressions
- Never type para funções que não retornam
- Constantes tipadas (const int, const string)
- Constructor property promotion

#### Template Engine - Twig 3.0
- Auto-escaping para prevenção de XSS
- Cache de templates para performance
- Sistema de herança de templates (extends, blocks)
- Includes e partials reutilizáveis
- Funções customizadas:
  - `asset()` - URLs para arquivos estáticos
  - `route()` - URLs para rotas
  - `csrf_token()` - Token CSRF automático
  - `version()` - Versão do sistema
- Variáveis globais automáticas:
  - `app_name`, `base_url`
  - `current_user` (id, name, email, role)
  - `is_authenticated`
- Filtros nativos do Twig
- Strict variables para detectar erros

#### Framework CSS - Bootstrap 5.3
- Design responsivo mobile-first
- Componentes modernos:
  - Cards com hover effects
  - Navbar responsiva com dropdown
  - Forms com input groups e ícones
  - Alerts com auto-dismiss
  - Badges e labels
  - Tabelas estilizadas
  - Botões com animações
- Bootstrap Icons integrado (1.11.0)
- Grid system flexível (12 colunas)
- Utilitários CSS prontos
- Tema dark mode ready
- JavaScript Bundle completo
- Validação de formulários
- Tooltips e popovers

#### Envio de Emails - PHPMailer 6.9
- Suporte SMTP completo
- Templates de email com Twig:
  - Email de boas-vindas
  - Recuperação de senha
- Emails pré-configurados:
  - `sendWelcome()` - Boas-vindas
  - `sendPasswordReset()` - Recuperação de senha
  - `sendAdminNotification()` - Notificações admin
  - `sendQuick()` - Envio rápido
- Fluent interface para facilitar uso
- Suporte a anexos
- HTML e texto alternativo
- Múltiplos destinatários (to, cc, bcc)
- Reply-to customizável
- Configuração via constantes
- Suporte a múltiplos provedores SMTP

#### Documentação - PHPDocs
- Todas as classes documentadas
- Todos os métodos públicos documentados
- Exemplos de uso incluídos
- Tags completas:
  - @package - Namespace
  - @author - Autor
  - @version - Versão
  - @since - Versão de introdução
  - @param - Parâmetros
  - @return - Retorno
  - @throws - Exceções
  - @var - Tipo de propriedade
  - @example - Exemplos práticos
- Descrições detalhadas
- Guia completo de PHPDoc

#### Autenticação e Usuários
- Sistema de autenticação completo (login/logout)
- Registro de novos usuários
- Email de boas-vindas automático no registro
- Validação de credenciais
- Sessões seguras
- Regeneração de session_id no login
- Proteção de rotas (requireAuth)
- Verificação de autenticação
- Atualização de último login
- Não retornar senha do banco

#### Dashboard e Interface
- Dashboard administrativo básico
- Cards com estatísticas
- Ícones Bootstrap em toda interface
- Menu responsivo com dropdown
- Página "Sobre" com informações do sistema
- Footer com versão
- Alerts com ícones e auto-dismiss
- Formulários estilizados
- Validação visual de campos

#### Segurança
- Proteção CSRF em todos os formulários
- Sanitização de inputs com `Security::sanitize()`
- Prepared statements (PDO) contra SQL Injection
- Hashing de senhas com bcrypt (cost 12)
- Headers de segurança HTTP no .htaccess:
  - X-XSS-Protection
  - X-Content-Type-Options
  - X-Frame-Options
- Validação de sessões
- Auto-escaping do Twig contra XSS
- Tokens criptograficamente seguros
- Comparação segura contra timing attacks (hash_equals)
- Configurações de sessão seguras:
  - cookie_httponly
  - use_only_cookies
  - cookie_samesite
- Verificação de versão PHP mínima
- Tratamento de erros sem expor informações sensíveis

#### Banco de Dados
- Conexão PDO com MySQL
- Padrão Singleton para conexão única
- Prepared statements em todas as queries
- Model base com CRUD completo:
  - `findAll()` - Buscar todos
  - `findById()` - Buscar por ID
  - `create()` - Criar registro
  - `update()` - Atualizar registro
  - `delete()` - Deletar registro
  - `query()` - Query customizada
- Configurações otimizadas do PDO
- Tratamento de erros PDO
- Schema SQL completo
- Usuário admin padrão

#### Configuração
- Arquivo de configuração centralizado
- Configurações de banco de dados separadas
- Configurações de email separadas
- Constantes para upload de arquivos
- Timezone configurável
- Modo debug configurável
- URLs amigáveis com .htaccess
- Roteamento automático

#### Estrutura de Diretórios
```
/
├── app/
│   ├── controllers/     # Controllers da aplicação
│   ├── models/          # Models (acesso a dados)
│   ├── views/           # Views Twig
│   │   ├── layouts/     # Layouts base
│   │   ├── partials/    # Componentes reutilizáveis
│   │   ├── auth/        # Views de autenticação
│   │   ├── home/        # Views do dashboard
│   │   └── emails/      # Templates de email
│   └── core/            # Classes principais do framework
│       ├── Enums/       # Enumerações
│       └── Attributes/  # Atributos PHP 8
├── config/              # Arquivos de configuração
├── database/            # Scripts SQL
├── public/              # Arquivos públicos (CSS, JS)
├── cache/               # Cache do Twig
├── uploads/             # Arquivos enviados
├── vendor/              # Dependências Composer
├── .htaccess            # Configurações Apache
├── composer.json        # Dependências
├── VERSION              # Versão do sistema
├── version.json         # Metadados da versão
└── index.php            # Front controller
```

#### Documentação
- README.md completo
- CHANGELOG.md detalhado
- TWIG_GUIDE.md - Guia do Twig
- PHPMAILER_GUIDE.md - Guia do PHPMailer
- PHPDOC_GUIDE.md - Guia de documentação
- PHP84_FEATURES.md - Recursos PHP 8.4+
- Exemplos de configuração
- Credenciais padrão documentadas

#### Arquivos de Exemplo
- config/mail.example.php - Exemplo de configuração de email
- Comentários explicativos em todos os arquivos
- Exemplos de uso em PHPDocs

### Segurança
- Implementação de tokens CSRF
- Sanitização de dados com htmlspecialchars
- Prepared statements em todas as queries
- Password hashing com bcrypt (cost 12)
- Validação de sessões
- Headers de segurança (X-XSS-Protection, X-Frame-Options, X-Content-Type-Options)
- Bloqueio de acesso a arquivos sensíveis via .htaccess
- Prevenção de listagem de diretórios
- Proteção contra SQL Injection
- Proteção contra XSS
- Proteção contra CSRF
- Sessões seguras com flags apropriadas

### Configuração
- Arquivo de configuração centralizado (config/config.php)
- Configurações de banco de dados separadas (config/database.php)
- Configurações de email separadas (config/mail.php)
- Constantes para upload de arquivos
- Timezone configurável (America/Sao_Paulo)
- Modo debug configurável
- Verificação de versão PHP mínima (8.4.0)

### Performance
- Cache de templates Twig
- Singleton para conexão de banco
- Autoloader otimizado
- Prepared statements com cache
- Assets via CDN (Bootstrap, Bootstrap Icons)

---

## Tipos de Mudanças

- `Adicionado` para novas funcionalidades
- `Modificado` para mudanças em funcionalidades existentes
- `Descontinuado` para funcionalidades que serão removidas
- `Removido` para funcionalidades removidas
- `Corrigido` para correção de bugs
- `Segurança` para vulnerabilidades corrigidas

---

## Links de Comparação

[Não Lançado]: https://github.com/wblproducoes/mvc07/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/wblproducoes/mvc07/releases/tag/v1.0.0
