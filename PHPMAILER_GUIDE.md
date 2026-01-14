# Guia PHPMailer 7.0.3

Este documento explica como usar o PHPMailer para envio de emails no sistema.

## Configuração

### 1. Instalar Dependências

```bash
composer install
```

### 2. Configurar SMTP

Edite o arquivo `config/mail.php`:

```php
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'seu-email@gmail.com');
define('MAIL_PASSWORD', 'sua-senha-app');
define('MAIL_ENCRYPTION', 'tls');
```

### 3. Gmail - Senha de App

Para usar Gmail, você precisa criar uma senha de app:

1. Acesse: https://myaccount.google.com/security
2. Ative a verificação em duas etapas
3. Vá em "Senhas de app"
4. Gere uma senha para "Email"
5. Use essa senha no `MAIL_PASSWORD`

## Uso Básico

### Envio Simples

```php
Mailer::sendQuick(
    'destinatario@example.com',
    'Assunto do Email',
    '<h1>Conteúdo HTML</h1>',
    'Nome do Destinatário'
);
```

### Envio com Fluent Interface

```php
$mailer = new Mailer();
$mailer
    ->to('destinatario@example.com', 'Nome')
    ->subject('Assunto')
    ->body('<h1>Conteúdo HTML</h1>')
    ->send();
```

## Métodos Disponíveis

### Destinatários

```php
// Destinatário principal
$mailer->to('email@example.com', 'Nome');

// Cópia (CC)
$mailer->cc('email@example.com', 'Nome');

// Cópia oculta (BCC)
$mailer->bcc('email@example.com', 'Nome');

// Responder para
$mailer->replyTo('email@example.com', 'Nome');
```

### Conteúdo

```php
// Assunto
$mailer->subject('Assunto do Email');

// Corpo HTML
$mailer->body('<h1>HTML</h1>');

// Corpo alternativo (texto puro)
$mailer->altBody('Texto puro para clientes sem HTML');
```

### Anexos

```php
// Anexar arquivo
$mailer->attach('/caminho/arquivo.pdf', 'nome-exibicao.pdf');

// Múltiplos anexos
$mailer
    ->attach('/caminho/arquivo1.pdf')
    ->attach('/caminho/arquivo2.jpg')
    ->attach('/caminho/arquivo3.docx');
```

### Templates Twig

```php
// Usar template
$mailer->template('welcome', [
    'name' => 'João',
    'app_name' => 'Meu Sistema'
]);
```

## Emails Pré-configurados

### Email de Boas-vindas

```php
Mailer::sendWelcome(
    'usuario@example.com',
    'Nome do Usuário'
);
```

### Email de Recuperação de Senha

```php
Mailer::sendPasswordReset(
    'usuario@example.com',
    'Nome do Usuário',
    'token-seguro-123'
);
```

### Notificação ao Admin

```php
Mailer::sendAdminNotification(
    'Novo Usuário Registrado',
    'Um novo usuário se registrou no sistema.'
);
```

## Criar Templates de Email

### 1. Criar arquivo Twig

Crie em `app/views/emails/meu-template.twig`:

```twig
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; }
        .header { background: #0d6efd; color: white; padding: 20px; }
        .content { padding: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ titulo }}</h1>
    </div>
    <div class="content">
        <p>Olá {{ nome }},</p>
        <p>{{ mensagem }}</p>
    </div>
</body>
</html>
```

### 2. Usar o template

```php
$mailer = new Mailer();
$mailer
    ->to('usuario@example.com')
    ->subject('Meu Assunto')
    ->template('meu-template', [
        'titulo' => 'Título do Email',
        'nome' => 'João',
        'mensagem' => 'Sua mensagem aqui'
    ])
    ->send();
```

## Exemplo Completo

```php
<?php

// Criar instância
$mailer = new Mailer();

// Configurar email
$success = $mailer
    ->to('destinatario@example.com', 'João Silva')
    ->cc('copia@example.com')
    ->replyTo('responder@example.com', 'Suporte')
    ->subject('Relatório Mensal')
    ->template('relatorio', [
        'nome' => 'João Silva',
        'mes' => 'Janeiro',
        'dados' => $dadosRelatorio
    ])
    ->attach('/caminho/relatorio.pdf', 'Relatório Janeiro.pdf')
    ->send();

if ($success) {
    echo "Email enviado com sucesso!";
} else {
    echo "Erro: " . $mailer->getError();
}
```

## Tratamento de Erros

```php
try {
    $mailer = new Mailer();
    $success = $mailer
        ->to('email@example.com')
        ->subject('Teste')
        ->body('Conteúdo')
        ->send();
    
    if (!$success) {
        error_log("Erro ao enviar: " . $mailer->getError());
    }
} catch (Exception $e) {
    error_log("Exceção: " . $e->getMessage());
}
```

## Configurações Avançadas

### Debug

No arquivo `config/mail.php`:

```php
define('MAIL_DEBUG', 2); // 0=off, 1=client, 2=server, 3=connection, 4=lowlevel
```

### Diferentes Provedores SMTP

#### Gmail
```php
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls');
```

#### Outlook/Hotmail
```php
define('MAIL_HOST', 'smtp-mail.outlook.com');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls');
```

#### Yahoo
```php
define('MAIL_HOST', 'smtp.mail.yahoo.com');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls');
```

#### SendGrid
```php
define('MAIL_HOST', 'smtp.sendgrid.net');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'apikey');
define('MAIL_PASSWORD', 'sua-api-key');
define('MAIL_ENCRYPTION', 'tls');
```

#### Mailgun
```php
define('MAIL_HOST', 'smtp.mailgun.org');
define('MAIL_PORT', 587);
define('MAIL_ENCRYPTION', 'tls');
```

## Boas Práticas

1. **Sempre use templates** para emails HTML
2. **Forneça texto alternativo** com `altBody()`
3. **Valide emails** antes de enviar
4. **Use filas** para envios em massa
5. **Trate erros** adequadamente
6. **Não exponha credenciais** no código
7. **Use variáveis de ambiente** para senhas
8. **Teste em ambiente de desenvolvimento** primeiro
9. **Monitore logs** de envio
10. **Respeite limites** do provedor SMTP

## Troubleshooting

### Erro: Could not authenticate

- Verifique usuário e senha
- Para Gmail, use senha de app
- Verifique se 2FA está ativo

### Erro: Connection timeout

- Verifique firewall
- Teste porta alternativa (465 para SSL)
- Verifique se SMTP está bloqueado

### Email vai para spam

- Configure SPF, DKIM e DMARC
- Use domínio verificado
- Evite palavras spam no assunto
- Inclua link de descadastro

### Email não chega

- Verifique logs do servidor
- Teste com email diferente
- Verifique caixa de spam
- Valide configurações SMTP

## Recursos Adicionais

- Documentação oficial: https://github.com/PHPMailer/PHPMailer
- Exemplos: https://github.com/PHPMailer/PHPMailer/tree/master/examples
- Wiki: https://github.com/PHPMailer/PHPMailer/wiki
