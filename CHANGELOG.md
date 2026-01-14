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
- Sistema de notificações
- Upload de avatar de usuário
- Tema escuro

---

## [1.0.0] - 2026-01-14

### Adicionado
- PHP 8.4+ com Orientação a Objetos (compatível com PHP 8.4 e 8.5)
- Tipagem forte (strict_types) em todos os arquivos
- Propriedades readonly para imutabilidade
- Union types (array|false, mixed)
- Enums para UserRole e UserStatus
- Atributos PHP 8 para rotas
- Arrow functions para código mais limpo
- Named arguments suportados
- Classes final para prevenir herança indevida
- Tratamento de erros com Throwable
- Arquitetura MVC completa e organizada
- Sistema de autenticação (login/logout)
- Registro de novos usuários
- Dashboard administrativo básico
- Proteção CSRF em todos os formulários
- Sanitização automática de inputs
- Hashing seguro de senhas com bcrypt
- Conexão com banco de dados usando PDO (Singleton)
- Prepared statements para prevenir SQL Injection
- Roteamento com URLs amigáveis
- Model base com operações CRUD
- Controller base com métodos auxiliares
- Sistema de views com layouts reutilizáveis
- Interface responsiva e moderna
- Validação de formulários no frontend
- Headers de segurança HTTP
- Proteção contra XSS
- Proteção contra listagem de diretórios
- Schema SQL completo do banco de dados
- Documentação completa no README
- Arquivo .htaccess configurado
- Arquivo .gitignore
- Estrutura de diretórios organizada

### Segurança
- Implementação de tokens CSRF
- Sanitização de dados com htmlspecialchars
- Prepared statements em todas as queries
- Password hashing com bcrypt (cost 12)
- Validação de sessões
- Headers de segurança (X-XSS-Protection, X-Frame-Options, X-Content-Type-Options)
- Bloqueio de acesso a arquivos sensíveis via .htaccess

### Configuração
- Arquivo de configuração centralizado
- Configurações de banco de dados separadas
- Constantes para upload de arquivos
- Timezone configurável
- Modo de debug configurável

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
