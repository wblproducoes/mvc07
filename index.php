<?php
/**
 * Front Controller - Ponto de entrada da aplicação
 */

session_start();

// Configurações
require_once 'config/config.php';
require_once 'config/database.php';

// Autoloader
spl_autoload_register(function ($class) {
    $paths = [
        'app/controllers/',
        'app/models/',
        'app/core/'
    ];
    
    foreach ($paths as $path) {
        $file = $path . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Inicializar aplicação
$app = new App();
