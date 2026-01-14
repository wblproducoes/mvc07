<?php
/**
 * Controller da página inicial
 */

class HomeController extends Controller {
    
    public function index() {
        $this->requireAuth();
        
        $data = [
            'title' => 'Dashboard',
            'user' => $_SESSION['user_name'] ?? 'Usuário'
        ];
        
        $this->view('home/index', $data);
    }
}
