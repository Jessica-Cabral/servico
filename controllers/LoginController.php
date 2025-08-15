<?php
// controllers/LoginController.php
class LoginController
{
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $erro = $_SESSION['erro_login'] ?? '';
        $sucesso = $_SESSION['sucesso_cadastro'] ?? '';
        unset($_SESSION['erro_login']);
        unset($_SESSION['sucesso_cadastro']);

        ob_start();
        require_once __DIR__ . '/../view/public/Login.php'; // apenas conteúdo principal
        $mainContent = ob_get_clean();
        $pageTitle = 'Login';
        $extraScripts = '<script src="/servico/assets/js/Login.js"></script>';
        require __DIR__ . '/../view/public/main.php'; // layout principal com menu, footer e CSS
    }

    public function autenticar()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /servico/login');
            exit();
        }

        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        $auth = new AuthClass();

        if ($auth->validarPessoa($email, $senha)) {
            $dados = $auth->getDadosPessoa();
            $tipo = $dados['tipo'] ?? '';

            session_regenerate_id(true);
            session_unset();

            if ($tipo === 'cliente' || $tipo === 'ambos') {
                $_SESSION['cliente_id'] = $dados['id'];
                $_SESSION['cliente_nome'] = $dados['nome'] ?? '';
                $_SESSION['user_type'] = 'cliente';
                header('Location: /servico/cliente/dashboard');
                exit();
            } elseif ($tipo === 'prestador') {
                $_SESSION['prestador_id'] = $dados['id'];
                $_SESSION['prestador_nome'] = $dados['nome'] ?? '';
                $_SESSION['user_type'] = 'prestador';
                header('Location: /servico/prestador/dashboard');
                exit();
            } else {
                $_SESSION['erro_login'] = 'Tipo de usuário desconhecido.';
                header('Location: /servico/login');
                exit();
            }
        } else {
            $_SESSION['erro_login'] = 'Email ou senha incorretos.';
            header('Location: /servico/login');
            exit();
        }
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset();
        session_destroy();
        header('Location: /servico/home');
        exit();
    }
}
