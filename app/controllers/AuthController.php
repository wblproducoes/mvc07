<?php

declare(strict_types=1);

/**
 * Controller de autenticação - PHP 8.4+
 */
final class AuthController extends Controller
{
    public function login(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirect('home');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleLogin();
        } else {
            $this->showLoginForm();
        }
    }
    
    private function handleLogin(): void
    {
        $email = Security::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (!Security::validateCSRF($_POST['csrf_token'] ?? null)) {
            $this->view('auth/login', [
                'error' => 'Token CSRF inválido'
            ]);
            return;
        }
        
        if (empty($email) || empty($password)) {
            $this->view('auth/login', [
                'error' => 'Preencha todos os campos'
            ]);
            return;
        }
        
        /** @var User $userModel */
        $userModel = $this->model('User');
        $user = $userModel->authenticate($email, $password);
        
        if ($user) {
            session_regenerate_id(true);
            
            $_SESSION['user_id'] = (int)$user['id'];
            $_SESSION['user_name'] = (string)$user['name'];
            $_SESSION['user_email'] = (string)$user['email'];
            $_SESSION['user_role'] = (string)($user['role'] ?? 'user');
            
            $userModel->updateLastLogin($user['id']);
            
            $this->redirect('home');
        } else {
            $this->view('auth/login', [
                'error' => 'Credenciais inválidas'
            ]);
        }
    }
    
    private function showLoginForm(): void
    {
        $this->view('auth/login');
    }
    
    public function logout(): void
    {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
        $this->redirect('auth/login');
    }
    
    public function register(): void
    {
        if ($this->isAuthenticated()) {
            $this->redirect('home');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handleRegister();
        } else {
            $this->showRegisterForm();
        }
    }
    
    private function handleRegister(): void
    {
        if (!Security::validateCSRF($_POST['csrf_token'] ?? null)) {
            $this->view('auth/register', [
                'error' => 'Token CSRF inválido'
            ]);
            return;
        }
        
        $name = Security::sanitize($_POST['name'] ?? '');
        $email = Security::sanitize($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($name) || empty($email) || empty($password)) {
            $this->view('auth/register', [
                'error' => 'Preencha todos os campos'
            ]);
            return;
        }
        
        if (strlen($password) < 6) {
            $this->view('auth/register', [
                'error' => 'A senha deve ter no mínimo 6 caracteres'
            ]);
            return;
        }
        
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->view('auth/register', [
                'error' => 'E-mail inválido'
            ]);
            return;
        }
        
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $password
        ];
        
        /** @var User $userModel */
        $userModel = $this->model('User');
        
        if ($userModel->register($data)) {
            $this->redirect('auth/login');
        } else {
            $this->view('auth/register', [
                'error' => 'E-mail já cadastrado ou erro ao registrar'
            ]);
        }
    }
    
    private function showRegisterForm(): void
    {
        $this->view('auth/register');
    }
}
