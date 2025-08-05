<?php
require_once __DIR__ . '/controllers/ControllerLogin.class.php';
require_once __DIR__ . '/controllers/ClienteController.class.php';

$url = explode('?', $_SERVER['REQUEST_URI']);
$pagina = isset($url[1]) ? $url[1] : null;

if ($pagina) {
    $objController = new ControllerLogin();
    $objController->redirecionar($pagina);
}

// ROTAS DE AÇÃO
if (isset($_POST['Login'])) {
    $objController = new ControllerLogin();
    $email = htmlspecialchars($_POST['email']);
    $senha = htmlspecialchars($_POST['senha']);
    $objController->validar($email, $senha);
}

if (isset($_GET['pagina']) && $_GET['pagina'] === 'dashboard') {
    $controller = new ClienteController();
    $controller->dashboard();
    exit();
}

if (isset($_GET['pagina']) && $_GET['pagina'] === 'avaliar-servico' && isset($_GET['id'])) {
    $controller = new ClienteController();
    $controller->avaliarServico($_GET['id']);
    exit();
}

isset($_POST['excluir_pessoa']) ||
    isset($_POST['alterar_pessoa_admin']) ||
    isset($_POST['consultar_pessoa_admin']) ||
    isset($_POST['excluir_pessoa_admin']) ||
    isset($_POST['alterar_prestador_admin']) ||
    isset($_POST['consultar_prestador_admin']) ||
    isset($_POST['excluir_prestador_admin']);

if (isset($_GET['pagina']) && $_GET['pagina'] === 'dashboard') {
    $controller = new ClienteController();
    $controller->dashboard();
    exit();
}

if (isset($_GET['pagina']) && $_GET['pagina'] === 'avaliar-servico' && isset($_GET['id'])) {
    $controller = new ClienteController();
    $controller->avaliarServico($_GET['id']);
    exit();
}
