<?php

declare(strict_types=1);

/**
 * Configurações de email - PHPMailer
 * PHP 8.4+
 * 
 * Copie este arquivo para mail.php e configure suas credenciais
 */

// Configurações SMTP
define('MAIL_HOST', 'smtp.gmail.com');
define('MAIL_PORT', 587);
define('MAIL_USERNAME', 'seu-email@gmail.com');
define('MAIL_PASSWORD', 'sua-senha-app');
define('MAIL_ENCRYPTION', 'tls'); // tls ou ssl

// Configurações do remetente
define('MAIL_FROM_ADDRESS', 'noreply@seudominio.com');
define('MAIL_FROM_NAME', APP_NAME);

// Configurações gerais
define('MAIL_CHARSET', 'UTF-8');
define('MAIL_DEBUG', 0); // 0 = off, 1 = client, 2 = server, 3 = connection, 4 = lowlevel

/**
 * PROVEDORES SMTP COMUNS:
 * 
 * Gmail:
 * - Host: smtp.gmail.com
 * - Port: 587 (TLS) ou 465 (SSL)
 * - Requer senha de app (https://myaccount.google.com/apppasswords)
 * 
 * Outlook/Hotmail:
 * - Host: smtp-mail.outlook.com
 * - Port: 587
 * - Encryption: tls
 * 
 * Yahoo:
 * - Host: smtp.mail.yahoo.com
 * - Port: 587
 * - Encryption: tls
 * 
 * SendGrid:
 * - Host: smtp.sendgrid.net
 * - Port: 587
 * - Username: apikey
 * - Password: sua-api-key
 * 
 * Mailgun:
 * - Host: smtp.mailgun.org
 * - Port: 587
 * - Encryption: tls
 */
