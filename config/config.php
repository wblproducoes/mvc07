<?php
<?php

declare(strict_types=1);

/**
 * Configurações gerais da aplicação
 * PHP 8.4+ - Orientação a Objetos
 */

// Verificar versão mínima do PHP
if (version_compare(PHP_VERSION, '8.4.0', '<')) {
    die('Este sistema requer PHP 8.4.0 ou superior. Versão atual: ' . PHP_VERSION);
}

// Configurações do sistema
define('APP_NAME', 'Sistema Administrativo');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/admin');
define('APP_DEBUG', true); // Desabilitar em produção

// Configurações de segurança
define('HASH_ALGORITHM', 'sha256');
define('SESSION_LIFETIME', 3600); // 1 hora

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Exibição de erros (desabilitar em produção)
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
} else {
    error_reporting(0);
    ini_set('display_errors', '0');
}

// Configurações de sessão seguras
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_secure', '0'); // Mudar para '1' em produção com HTTPS
ini_set('session.cookie_samesite', 'Strict');

// Configurações de upload
define('UPLOAD_MAX_SIZE', 5242880); // 5MB
define('UPLOAD_PATH', 'uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);
