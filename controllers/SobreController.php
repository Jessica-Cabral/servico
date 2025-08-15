<?php
// controllers/SobreController.php

/**
 * Controller para a página Sobre
 */
class SobreController {

    /**
     * Exibe a página Sobre Nós
     */
    public function index() {
        ob_start();
        require __DIR__ . '/../view/public/About.php'; // apenas conteúdo principal

    }
}
