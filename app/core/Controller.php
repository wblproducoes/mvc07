<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * Controller base - PHP 8.4+ com Twig 3.0
 */
abstract class Controller
{
    protected static ?Environment $twig = null;
    
    protected function model(string $model): object
    {
        $modelPath = "app/models/{$model}.php";
        
        if (!file_exists($modelPath)) {
            throw new RuntimeException("Model {$model} não encontrado");
        }
        
        require_once $modelPath;
        return new $model();
    }
    
    protected function view(string $view, array $data = []): void
    {
        $twig = $this->getTwig();
        
        // Adicionar variáveis globais
        $data['app_name'] = APP_NAME;
        $data['base_url'] = BASE_URL;
        $data['current_user'] = [
            'id' => $this->getCurrentUserId(),
            'name' => $this->getCurrentUserName(),
            'email' => $_SESSION['user_email'] ?? null,
            'role' => $_SESSION['user_role'] ?? null,
        ];
        $data['is_authenticated'] = $this->isAuthenticated();
        
        try {
            echo $twig->render("{$view}.twig", $data);
        } catch (\Twig\Error\LoaderError $e) {
            throw new RuntimeException("Template {$view}.twig não encontrado: " . $e->getMessage());
        }
    }
    
    protected function getTwig(): Environment
    {
        if (self::$twig === null) {
            $loader = new FilesystemLoader('app/views');
            
            self::$twig = new Environment($loader, [
                'cache' => APP_DEBUG ? false : 'cache/twig',
                'debug' => APP_DEBUG,
                'auto_reload' => APP_DEBUG,
                'strict_variables' => true,
            ]);
            
            // Adicionar funções customizadas
            self::$twig->addFunction(new TwigFunction('asset', function (string $path): string {
                return BASE_URL . '/public/' . ltrim($path, '/');
            }));
            
            self::$twig->addFunction(new TwigFunction('route', function (string $path): string {
                return BASE_URL . '/' . ltrim($path, '/');
            }));
            
            self::$twig->addFunction(new TwigFunction('csrf_token', function (): string {
                return Security::createCSRFToken();
            }));
            
            self::$twig->addFunction(new TwigFunction('version', function (): string {
                return Version::getFormatted();
            }));
        }
        
        return self::$twig;
    }
    
    protected function redirect(string $url): never
    {
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }
    
    protected function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
    }
    
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }
    }
    
    protected function getCurrentUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    protected function getCurrentUserName(): ?string
    {
        return $_SESSION['user_name'] ?? null;
    }
}
