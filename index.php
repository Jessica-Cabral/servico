<?php
// Configuração de exibição de erros
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclui o autoloader
include_once 'autoload.php';

// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Limpa qualquer saída antes de redirecionar (solução para "headers already sent")
ob_start();

// Inclui o roteador
include_once 'router.php';

// Redirecionamento após login bem-sucedido
if (isset($_SESSION['user_type'])) {
    if ($_SESSION['user_type'] === 'cliente' && isset($_SESSION['cliente_id'])) {
        header('Location: view/cliente/clienteDashboard.php');
        exit();
    } elseif ($_SESSION['user_type'] === 'prestador' && isset($_SESSION['prestador_id'])) {
        header('Location: view/prestador/prestadorDashboard.php');
        exit();
    } elseif ($_SESSION['user_type'] === 'admin' && isset($_SESSION['admin_id'])) {
        header('Location: view/admin/adminDashboard.php');
        exit();
    }
}

// Se não estiver logado, redireciona para login
header('Location: Login.php');
exit();