<?php

declare(strict_types=1);

/**
 * Controller da página inicial - PHP 8.4+
 */
final class HomeController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        
        $data = [
            'title' => 'Dashboard',
            'user' => $this->getCurrentUserName() ?? 'Usuário'
        ];
        
        $this->view('home/index', $data);
    }
}
