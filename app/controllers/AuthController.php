<?php
/**
 * Controller de autenticação
 */

class AuthController extends Controller {
    
    public function login() {
        if ($this->isAuthenticated()) {
            $this->redirect('home');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = Security::sanitize($_POST['email']);
            $password = $_POST['password'];
            
            if (!Security::validateCSRF($_POST['csrf_token'] ?? '')) {
                $data['error'] = 'Token CSRF inválido';
                $this->view('auth/login', $data);
                return;
            }
            
            $userModel = $this->model('User');
            $user = $userModel->authenticate($email, $password);
            
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                
                $this->redirect('home');
            } else {
                $data['error'] = 'Credenciais inválidas';
                $this->view('auth/login', $data);
            }
        } else {
            $data['csrf_token'] = Security::createCSRFToken();
            $this->view('auth/login', $data);
        }
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('auth/login');
    }
    
    public function register() {
        if ($this->isAuthenticated()) {
            $this->redirect('home');
        }
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!Security::validateCSRF($_POST['csrf_token'] ?? '')) {
                $data['error'] = 'Token CSRF inválido';
                $this->view('auth/register', $data);
                return;
            }
            
            $data = [
                'name' => Security::sanitize($_POST['name']),
                'email' => Security::sanitize($_POST['email']),
                'password' => $_POST['password']
            ];
            
            $userModel = $this->model('User');
            
            if ($userModel->register($data)) {
                $this->redirect('auth/login');
            } else {
                $error['error'] = 'Erro ao registrar usuário';
                $this->view('auth/register', $error);
            }
        } else {
            $data['csrf_token'] = Security::createCSRFToken();
            $this->view('auth/register', $data);
        }
    }
}
