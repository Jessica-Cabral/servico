<?php
// controllers/HomeController.php

// O nome da classe DEVE ser exatamente este: HomeController
class HomeController
{

    /**
     * Exibe a página inicial.
     * Rota: /servico/
     */
    public function index()
    {
        require __DIR__ . '/../view/public/HomePage.php';
    }
}
        $mainContent = ob_get_clean();
        $pageTitle = 'Página Inicial';
        $extraScripts = '<script src="/servico/assets/js/homepage.js"></script>';
        require __DIR__ . '/../view/public/HomePage.php'; // layout principal com menu, footer, CSS, etc.
