<?php

declare(strict_types=1);

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFunction;

/**
 * Controller base
 * 
 * Classe abstrata que fornece métodos auxiliares para controllers,
 * incluindo renderização de views com Twig, carregamento de models,
 * redirecionamentos e autenticação.
 * 
 * @package App\Core
 * @author  WBL Produções
 * @version 1.0.0
 * @since   1.0.0
 * 
 * @example
 * ```php
 * class HomeController extends Controller {
 *     public function index(): void {
 *         $this->requireAuth();
 *         $this->view('home/index', ['data' => $data]);
 *     }
 * }
 * ```
 */
abstract class Controller
{
    /**
     * Instância do Twig Environment (Singleton)
     * 
     * @var Environment|null
     */
    protected static ?Environment $twig = null;
    
    /**
     * Carrega e instancia um model
     * 
     * @param string $model Nome do model (sem extensão .php)
     * @return object Instância do model
     * @throws RuntimeException Se o model não for encontrado
     * 
     * @example
     * ```php
     * $userModel = $this->model('User');
     * $users = $userModel->findAll();
     * ```
     */
    protected function model(string $model): object
    {
        $modelPath = "app/models/{$model}.php";
        
        if (!file_exists($modelPath)) {
            throw new RuntimeException("Model {$model} não encontrado");
        }
        
        require_once $modelPath;
        return new $model();
    }
    
    /**
     * Renderiza uma view usando Twig
     * 
     * @param string $view Caminho da view (sem extensão .twig)
     * @param array $data Dados a serem passados para a view
     * @return void
     * @throws RuntimeException Se a view não for encontrada
     * 
     * @example
     * ```php
     * $this->view('home/index', [
     *     'title' => 'Dashboard',
     *     'users' => $users
     * ]);
     * ```
     */
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
    
    /**
     * Redireciona para uma URL
     * 
     * @param string $url URL relativa (sem BASE_URL)
     * @return never Nunca retorna (exit)
     * 
     * @example
     * ```php
     * $this->redirect('home');
     * $this->redirect('auth/login');
     * ```
     */
    protected function redirect(string $url): never
    {
        header('Location: ' . BASE_URL . '/' . ltrim($url, '/'));
        exit;
    }
    
    /**
     * Retorna resposta JSON
     * 
     * @param mixed $data Dados a serem convertidos para JSON
     * @param int $status Código de status HTTP (padrão: 200)
     * @return never Nunca retorna (exit)
     * 
     * @example
     * ```php
     * $this->json(['success' => true, 'data' => $users]);
     * $this->json(['error' => 'Not found'], 404);
     * ```
     */
    protected function json(mixed $data, int $status = 200): never
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    /**
     * Verifica se o usuário está autenticado
     * 
     * @return bool True se autenticado
     * 
     * @example
     * ```php
     * if ($this->isAuthenticated()) {
     *     // Usuário logado
     * }
     * ```
     */
    protected function isAuthenticated(): bool
    {
        return isset($_SESSION['user_id']) && is_int($_SESSION['user_id']);
    }
    
    /**
     * Requer autenticação, redireciona para login se não autenticado
     * 
     * @return void
     * 
     * @example
     * ```php
     * public function dashboard(): void {
     *     $this->requireAuth();
     *     // Código protegido
     * }
     * ```
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            $this->redirect('auth/login');
        }
    }
    
    /**
     * Retorna o ID do usuário autenticado
     * 
     * @return int|null ID do usuário ou null se não autenticado
     * 
     * @example
     * ```php
     * $userId = $this->getCurrentUserId();
     * ```
     */
    protected function getCurrentUserId(): ?int
    {
        return $_SESSION['user_id'] ?? null;
    }
    
    /**
     * Retorna o nome do usuário autenticado
     * 
     * @return string|null Nome do usuário ou null se não autenticado
     * 
     * @example
     * ```php
     * $userName = $this->getCurrentUserName();
     * ```
     */
    protected function getCurrentUserName(): ?string
    {
        return $_SESSION['user_name'] ?? null;
    }
}
