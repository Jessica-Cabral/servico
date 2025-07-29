<?php
require_once __DIR__ . '/controllers/ControllerLogin.class.php';
require_once __DIR__ . '/controllers/Controller.class.php';

$url = explode('?', $_SERVER['REQUEST_URI']);
$pagina = isset($url[1]) ? $url[1] : null;

if ($pagina) {
    $objController = new ControllerLogin();
    $objController->redirecionar($pagina);
}

##ROTAS DE AÇÃO
if (isset($_POST['Login'])) {
    $objController = new ControllerLogin();
    $email = htmlspecialchars($_POST['email']);
    $senha = htmlspecialchars($_POST['senha']);
    $objController->validar($email, $senha);
}

// As rotas abaixo usam Controller, mas não há require para ela. Adicione se necessário:
if (isset($_POST['validarAdmin']) ||
    isset($_POST['abrirHomepage']) ||
    isset($_POST['recuperarSenha']) ||
    isset($_POST['abrirformpessoa']) ||
    isset($_POST['cadastrar_pessoa']) ||
    isset($_POST['cadastrar_servico']) ||
    isset($_POST['consultar_pessoa']) ||
    isset($_POST['botao_alterar_pessoa']) ||
    isset($_POST['excluir_pessoa']) ||
    isset($_POST['alterar_pessoa_admin']) ||
    isset($_POST['consultar_pessoa_admin']) ||
    isset($_POST['excluir_pessoa_admin']) ||
    isset($_POST['alterar_prestador_admin']) ||
    isset($_POST['consultar_prestador_admin']) ||
    isset($_POST['excluir_prestador_admin'])
);

// Observações de possíveis erros:
// - O require dos controllers está correto, mas certifique-se que os arquivos existem.
// - O nome dos arquivos e das classes deve ser exatamente igual (case sensitive).
// - O redirecionamento no ControllerLogin deve apontar para o caminho correto do dashboard.
// - Se o login não funcionar, verifique se o método validarPessoa está correto e se o hash da senha bate com o banco.
// - Se o dashboard não abrir, confira se o arquivo existe em view/cliente/clienteDashboard.php ou view/prestador/prestadorDashboard.php.