<?php
session_start();

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: Login.php');
    exit();
}

// Se já estiver logado, redirecionar para o dashboard correto
if (isset($_SESSION['cliente_id'])) {
    header('Location: view/cliente/clienteDashboard.php');
    exit();
} elseif (isset($_SESSION['prestador_id'])) {
    header('Location: view/prestador/prestadorDashboard.php');
    exit();
}

$erro = '';

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    require_once __DIR__ . '/models/Auth.class.php';
    $auth = new Auth();

    // Verifique se o campo senha está salvo como md5 no banco!
    // Se não estiver, troque para:
    // $query->bindValue(':senha', $this->getSenha(), PDO::PARAM_STR);
    // no método validarPessoa do Auth.class.php

    $loginValido = $auth->validarPessoa($email, $senha);

    if ($loginValido) {
        $id = $auth->consultarIdPessoa($email);
        $dados = $auth->consultarDadosPessoa($id);
        $tipo = $auth->perfilPessoa($email);

        session_regenerate_id(true);
        unset($_SESSION['cliente_id'], $_SESSION['cliente_nome'], $_SESSION['user_type']);
        unset($_SESSION['prestador_id'], $_SESSION['prestador_nome']);

        if ($tipo == 'cliente' || $tipo == 'clientePrestador') {
            $_SESSION['cliente_id'] = $id;
            $_SESSION['cliente_nome'] = $dados[0]->nome ?? '';
            $_SESSION['user_type'] = 'cliente';
            header('Location: view/cliente/clienteDashboard.php');
            exit();
        } elseif ($tipo == 'prestador') {
            $_SESSION['prestador_id'] = $id;
            $_SESSION['prestador_nome'] = $dados[0]->nome ?? '';
            $_SESSION['user_type'] = 'prestador';
            header('Location: view/prestador/prestadorDashboard.php');
            exit();
        } else {
            $erro = 'Tipo de usuário inválido.';
        }
    } else {
        $erro = 'Email ou senha incorretos.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Chama Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <link rel="stylesheet" href="assets/css/Login.css">
    <style>
        /* Pequeno ajuste para centralizar melhor em telas grandes */
        body { min-height: 100vh; display: flex; align-items: center; justify-content: center; background: none; }
    </style>
</head>
<body>
    <div class="container min-vh-100 d-flex flex-column justify-content-center align-items-center">
        <div class="row w-100 justify-content-center">
            <div class="col-md-6 col-lg-4 login-container">
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <div class="text-center mb-3">
                            <i class="bi bi-tools fa-2x text-primary mb-2"></i>
                            <h3 class="fw-bold mb-1" style="font-size:1.6rem; color:#1a2233;">Chama Serviço</h3>
                            <p class="text-muted mb-0" style="font-size:1.05rem;">Acesse sua conta para usar o sistema</p>
                        </div>
                        <form action="Login.php" method="post" id="loginForm" autocomplete="off">
                            <fieldset class="border rounded-3 p-3 mb-3">
                                <legend class="float-none w-auto px-2 fs-6 text-primary mb-3">
                                    <i class="bi bi-person-circle me-1"></i> Login
                                </legend>
                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-envelope"></i>
                                        </span>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>"
                                            placeholder="Digite seu e-mail" required autofocus>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="senha" class="form-label">Senha</label>
                                    <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="bi bi-lock"></i>
                                        </span>
                                        <input type="password" class="form-control" id="senha" name="senha"
                                            placeholder="Digite sua senha" required>
                                        <button class="btn btn-outline-secondary" type="button" id="toggleSenha" tabindex="-1">
                                            <i class="bi bi-eye" id="iconSenha"></i>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <a href="index.php?Recuperar" class="small text-decoration-none text-primary">
                                        <i class="bi bi-key me-1"></i> Esqueceu a senha?
                                    </a>
                                </div>
                                <div class="btn-group-login d-flex flex-column gap-2 mt-3">
                                    <button name="Login" class="btn btn-login btn-sm w-100" type="submit">
                                        <i class="bi bi-box-arrow-in-right"></i> Entrar
                                    </button>
                                    <a href="CadUsuario.php" class="btn btn-outline-secondary btn-sm w-100">
                                        <i class="bi bi-person-plus"></i> Criar uma conta
                                    </a>
                                </div>
                            </fieldset>
                        </form>
                        <?php if ($erro): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($erro); ?></div>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="HomePage.php" class="text-decoration-none text-secondary small">
                        <i class="bi bi-arrow-left-circle me-1"></i> Voltar para a Home
                    </a>
                </div>
            </div>
        </div>
        <a href="HomePage.php">
        <div id="bubble">
            <img src="assets/img/user.png" alt="icone-usuário" title="fazer-login">
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Visualizador de senha
        document.addEventListener('DOMContentLoaded', function() {
            const senhaInput = document.getElementById('senha');
            const toggleSenha = document.getElementById('toggleSenha');
            const iconSenha = document.getElementById('iconSenha');
            if (toggleSenha) {
                toggleSenha.addEventListener('click', function() {
                    if (senhaInput.type === 'password') {
                        senhaInput.type = 'text';
                        iconSenha.classList.remove('bi-eye');
                        iconSenha.classList.add('bi-eye-slash');
                    } else {
                        senhaInput.type = 'password';
                        iconSenha.classList.remove('bi-eye-slash');
                        iconSenha.classList.add('bi-eye');
                    }
                });
            }
        });
    </script>
</body>
</html>