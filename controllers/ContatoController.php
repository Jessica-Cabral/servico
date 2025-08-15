<?php
// controllers/ContatoController.php

/**
 * Controller para a página Contato
 */
class ContatoController
{

    /**
     * Exibe a página Contato
     */
    public function index()
    {
        // Incluir a view
        include __DIR__ . '/../view/public/contact.php';
    }
    
    /**
     * Processa o envio do formulário de contato
     */
    public function enviar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?url=Contato');
            exit;
        }
        
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $assunto = trim($_POST['assunto'] ?? '');
        $mensagem = trim($_POST['mensagem'] ?? '');
        
        // Validação simples
        $erros = [];
        if (empty($nome)) $erros[] = 'Nome é obrigatório';
        if (empty($email)) $erros[] = 'Email é obrigatório';
        if (empty($assunto)) $erros[] = 'Assunto é obrigatório';
        if (empty($mensagem)) $erros[] = 'Mensagem é obrigatória';
        
        if (!empty($erros)) {
            $_SESSION['erros_contato'] = $erros;
            $_SESSION['dados_contato'] = $_POST;
            header('Location: index.php?url=Contato');
            exit;
        }
        
        // Aqui você pode implementar o envio do email
        // Por exemplo, usando mail() ou PHPMailer
        
        // Simulando sucesso
        $_SESSION['sucesso_contato'] = 'Mensagem enviada com sucesso! Entraremos em contato em breve.';
        header('Location: index.php?url=Contato');
        exit;
    }
}
