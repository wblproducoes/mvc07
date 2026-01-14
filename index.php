<?php

declare(strict_types=1);

/**
 * Front Controller - Ponto de entrada da aplicação
 * PHP 8.4+ - Orientação a Objetos
 */

session_start();

// Configurações
require_once 'config/config.php';
require_once 'config/database.php';

// Autoloader
spl_autoload_register(function (string $class): void {
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
new App();
