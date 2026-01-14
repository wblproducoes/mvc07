<?php
/**
 * Configurações gerais da aplicação
 */

// Configurações do sistema
define('APP_NAME', 'Sistema Administrativo');
define('APP_VERSION', '1.0.0');
define('BASE_URL', 'http://localhost/admin');

// Configurações de segurança
define('HASH_ALGORITHM', 'sha256');
define('SESSION_LIFETIME', 3600); // 1 hora

// Timezone
date_default_timezone_set('America/Sao_Paulo');

// Exibição de erros (desabilitar em produção)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configurações de upload
define('UPLOAD_MAX_SIZE', 5242880); // 5MB
define('UPLOAD_PATH', 'uploads/');
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);
