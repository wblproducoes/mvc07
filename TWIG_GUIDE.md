# Guia Twig 3.0

Este documento explica como usar o Twig 3.0 neste sistema.

## Estrutura de Templates

```
app/views/
├── layouts/
│   └── base.twig          # Layout base
├── partials/
│   ├── navbar.twig        # Menu de navegação
│   └── footer.twig        # Rodapé
├── auth/
│   ├── login.twig         # Página de login
│   └── register.twig      # Página de registro
├── home/
│   └── index.twig         # Dashboard
└── about.twig             # Página sobre
```

## Herança de Templates

### Layout Base (base.twig)

```twig
<!DOCTYPE html>
<html>
<head>
    <title>{% block title %}{{ app_name }}{% endblock %}</title>
    {% block stylesheets %}{% endblock %}
</head>
<body>
    {% include 'partials/navbar.twig' %}
    
    <main>
        {% block content %}{% endblock %}
    </main>
    
    {% include 'partials/footer.twig' %}
    
    {% block javascripts %}{% endblock %}
</body>
</html>
```

### Template Filho

```twig
{% extends 'layouts/base.twig' %}

{% block title %}Minha Página - {{ app_name }}{% endblock %}

{% block content %}
    <h1>Conteúdo da Página</h1>
{% endblock %}
```

## Funções Customizadas

### asset(path)
Gera URL para arquivos estáticos:

```twig
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
<script src="{{ asset('js/main.js') }}"></script>
<img src="{{ asset('images/logo.png') }}">
```

### route(path)
Gera URL para rotas:

```twig
<a href="{{ route('home') }}">Dashboard</a>
<a href="{{ route('auth/login') }}">Login</a>
<form action="{{ route('users/create') }}" method="POST">
```

### csrf_token()
Gera token CSRF:

```twig
<form method="POST">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
</form>
```

### version()
Exibe versão do sistema:

```twig
<footer>
    {{ app_name }} - {{ version() }}
</footer>
```

## Variáveis Globais

Disponíveis em todos os templates:

```twig
{{ app_name }}              {# Nome da aplicação #}
{{ base_url }}              {# URL base #}
{{ is_authenticated }}      {# Boolean: usuário logado? #}
{{ current_user.id }}       {# ID do usuário #}
{{ current_user.name }}     {# Nome do usuário #}
{{ current_user.email }}    {# Email do usuário #}
{{ current_user.role }}     {# Role do usuário #}
```

## Filtros Úteis

### Formatação de Texto

```twig
{{ texto|upper }}           {# MAIÚSCULAS #}
{{ texto|lower }}           {# minúsculas #}
{{ texto|capitalize }}      {# Primeira Letra Maiúscula #}
{{ texto|title }}           {# Cada Palavra Maiúscula #}
```

### Datas

```twig
{{ "now"|date("d/m/Y") }}           {# 14/01/2026 #}
{{ "now"|date("H:i:s") }}           {# 15:30:45 #}
{{ data|date("d/m/Y H:i") }}        {# Data customizada #}
```

### Arrays

```twig
{{ array|length }}          {# Tamanho do array #}
{{ array|first }}           {# Primeiro elemento #}
{{ array|last }}            {# Último elemento #}
{{ array|join(', ') }}      {# Juntar com vírgula #}
```

### Números

```twig
{{ 1234.56|number_format(2, ',', '.') }}  {# 1.234,56 #}
{{ preco|number_format(2) }}              {# Formatação padrão #}
```

## Estruturas de Controle

### Condicionais

```twig
{% if is_authenticated %}
    <p>Bem-vindo, {{ current_user.name }}!</p>
{% else %}
    <p>Faça login</p>
{% endif %}

{% if user.role == 'admin' %}
    <a href="{{ route('admin') }}">Painel Admin</a>
{% elseif user.role == 'user' %}
    <a href="{{ route('dashboard') }}">Dashboard</a>
{% endif %}
```

### Loops

```twig
{% for item in items %}
    <li>{{ item.name }}</li>
{% else %}
    <li>Nenhum item encontrado</li>
{% endfor %}

{# Variáveis especiais no loop #}
{% for user in users %}
    {{ loop.index }}     {# Índice (1, 2, 3...) #}
    {{ loop.index0 }}    {# Índice (0, 1, 2...) #}
    {{ loop.first }}     {# Primeiro item? #}
    {{ loop.last }}      {# Último item? #}
    {{ loop.length }}    {# Total de itens #}
{% endfor %}
```

## Segurança

### Auto-escaping

O Twig escapa automaticamente HTML:

```twig
{{ user_input }}  {# Escapado automaticamente #}
```

Para desabilitar (use com cuidado):

```twig
{{ html_content|raw }}
```

### CSRF Protection

Sempre use em formulários:

```twig
<form method="POST" action="{{ route('users/create') }}">
    <input type="hidden" name="csrf_token" value="{{ csrf_token() }}">
    {# campos do formulário #}
</form>
```

## Includes e Partials

```twig
{# Incluir template #}
{% include 'partials/navbar.twig' %}

{# Incluir com variáveis #}
{% include 'partials/alert.twig' with {'type': 'success', 'message': 'Salvo!'} %}

{# Incluir apenas se existir #}
{% include 'partials/optional.twig' ignore missing %}
```

## Macros (Funções Reutilizáveis)

Criar macro:

```twig
{# macros/forms.twig #}
{% macro input(name, type='text', label='') %}
    <div class="form-group">
        {% if label %}
            <label for="{{ name }}">{{ label }}</label>
        {% endif %}
        <input type="{{ type }}" id="{{ name }}" name="{{ name }}">
    </div>
{% endmacro %}
```

Usar macro:

```twig
{% import 'macros/forms.twig' as forms %}

{{ forms.input('email', 'email', 'E-mail') }}
{{ forms.input('password', 'password', 'Senha') }}
```

## Cache

O cache é configurado automaticamente:

- **Desenvolvimento** (APP_DEBUG=true): Cache desabilitado
- **Produção** (APP_DEBUG=false): Cache em `cache/twig/`

Limpar cache manualmente:

```bash
rm -rf cache/twig/*
```

## Exemplo Completo

```twig
{% extends 'layouts/base.twig' %}

{% block title %}Lista de Usuários - {{ app_name }}{% endblock %}

{% block stylesheets %}
    <link rel="stylesheet" href="{{ asset('css/users.css') }}">
{% endblock %}

{% block content %}
    <div class="container">
        <h1>Usuários</h1>
        
        {% if message is defined %}
            <div class="alert alert-success">{{ message }}</div>
        {% endif %}
        
        {% if users is not empty %}
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Email</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    {% for user in users %}
                        <tr>
                            <td>{{ loop.index }}</td>
                            <td>{{ user.name }}</td>
                            <td>{{ user.email }}</td>
                            <td>
                                <a href="{{ route('users/edit/' ~ user.id) }}">Editar</a>
                            </td>
                        </tr>
                    {% endfor %}
                </tbody>
            </table>
        {% else %}
            <p>Nenhum usuário cadastrado.</p>
        {% endif %}
        
        <a href="{{ route('users/create') }}" class="btn btn-primary">Novo Usuário</a>
    </div>
{% endblock %}

{% block javascripts %}
    <script src="{{ asset('js/users.js') }}"></script>
{% endblock %}
```

## Boas Práticas

1. **Use herança de templates** para evitar duplicação
2. **Crie partials** para componentes reutilizáveis
3. **Nunca use |raw** sem sanitizar dados do usuário
4. **Use macros** para componentes complexos
5. **Aproveite o auto-escaping** para segurança
6. **Organize templates** por módulo/funcionalidade
7. **Use variáveis descritivas** nos templates
8. **Comente código complexo** quando necessário

## Documentação Oficial

Para mais informações: https://twig.symfony.com/doc/3.x/
