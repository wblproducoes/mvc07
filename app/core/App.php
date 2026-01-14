<?php

declare(strict_types=1);

/**
 * Classe principal da aplicação - Roteamento - PHP 8.4+
 */
final class App
{
    private string $controller = 'HomeController';
    private string $method = 'index';
    private array $params = [];

    public function __construct()
    {
        $url = $this->parseUrl();
        
        // Verificar controller
        if (isset($url[0]) && $url[0] !== '') {
            $controllerName = ucfirst($url[0]) . 'Controller';
            $controllerPath = "app/controllers/{$controllerName}.php";
            
            if (file_exists($controllerPath)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }
        
        $controllerPath = "app/controllers/{$this->controller}.php";
        
        if (!file_exists($controllerPath)) {
            $this->notFound();
        }
        
        require_once $controllerPath;
        $this->controller = new $this->controller();
        
        // Verificar método
        if (isset($url[1]) && method_exists($this->controller, $url[1])) {
            $this->method = $url[1];
            unset($url[1]);
        }
        
        // Parâmetros
        $this->params = $url ? array_values($url) : [];
        
        // Executar
        try {
            call_user_func_array([$this->controller, $this->method], $this->params);
        } catch (Throwable $e) {
            $this->handleError($e);
        }
    }
    
    private function parseUrl(): array
    {
        if (isset($_GET['url'])) {
            $url = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            return $url !== '' ? explode('/', $url) : [];
        }
        return [];
    }
    
    private function notFound(): never
    {
        http_response_code(404);
        echo "Página não encontrada";
        exit;
    }
    
    private function handleError(Throwable $e): never
    {
        error_log($e->getMessage());
        http_response_code(500);
        
        if (defined('APP_DEBUG') && APP_DEBUG) {
            echo "<pre>Erro: " . $e->getMessage() . "\n\n" . $e->getTraceAsString() . "</pre>";
        } else {
            echo "Erro interno do servidor";
        }
        exit;
    }
}
