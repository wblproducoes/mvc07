<?php

declare(strict_types=1);

/**
 * Front Controller - Ponto de entrada da aplicação
 * PHP 8.4+ com Twig 3.0
 */

session_start();

// Configurações
require_once 'config/config.php';
require_once 'config/database.php';

// Composer autoloader (Twig)
if (file_exists('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
} else {
    die('Execute: composer install');
}

// Autoloader customizado
spl_autoload_register(function (string $class): void {
    $paths = [
        'app/controllers/',
        'app/models/',
        'app/core/',
        'app/core/Enums/',
        'app/core/Attributes/'
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
