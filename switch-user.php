<?php
session_start();

$type = $_GET['type'] ?? '';

if ($type === 'cliente') {
    // Troca para cliente se houver dados na sessão
    if (isset($_SESSION['cliente_id'])) {
        $_SESSION['user_type'] = 'cliente';
        header('Location: view/cliente/clienteDashboard.php');
        exit();
    } else {
        // Se não houver cliente logado, redireciona para login
        header('Location: login.php');
        exit();
    }
} elseif ($type === 'prestador') {
    // Troca para prestador se houver dados na sessão
    if (isset($_SESSION['prestador_id'])) {
        $_SESSION['user_type'] = 'prestador';
        header('Location: view/prestador/prestadorDashboard.php');
        exit();
    } else {
        header('Location: login.php');
        exit();
    }
} else {
    // Tipo inválido, volta para home
    header('Location: HomePage.php');
    exit();
}
