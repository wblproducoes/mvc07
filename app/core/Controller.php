<?php

declare(strict_types=1);

/**
 * Controller base - PHP 8.4+
 */
abstract class Controller
{
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
        extract($data);
        
        $viewPath = "app/views/{$view}.php";
        
        if (!file_exists($viewPath)) {
            throw new RuntimeException("View {$view} não encontrada");
        }
        
        require_once $viewPath;
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
