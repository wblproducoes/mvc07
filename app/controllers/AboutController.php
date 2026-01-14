<?php

declare(strict_types=1);

/**
 * Controller de informações sobre o sistema - PHP 8.4+
 */
final class AboutController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();
        
        $data = [
            'title' => 'Sobre o Sistema',
            'info' => Version::getFullInfo()
        ];
        
        $this->view('about', $data);
    }
}
