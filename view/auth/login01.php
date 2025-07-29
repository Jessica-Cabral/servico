<?php
session_start();

// Se já estiver logado, redirecionar para o dashboard correto
if (isset($_SESSION['cliente_id'])) {
    header('Location: ../cliente/clienteDashboard.php');
    exit();
} elseif (isset($_SESSION['prestador_id'])) {
    header('Location: ../prestador/prestadorDashboard.php');
    exit();
}

$erro = '';

// Processar login
if ($_POST) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    require_once __DIR__ . '/../../models/Auth.class.php';
    $auth = new Auth();

    $loginValido = $auth->validarPessoa($email, $senha);

    if ($loginValido) {
        // Buscar dados do usuário para sessão
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
            header('Location: ../cliente/clienteDashboard.php');
            exit();
        } elseif ($tipo == 'prestador') {
            $_SESSION['prestador_id'] = $id;
            $_SESSION['prestador_nome'] = $dados[0]->nome ?? '';
            $_SESSION['user_type'] = 'prestador';
            header('Location: ../prestador/prestadorDashboard.php');
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
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        
        .login-container {
            max-width: 400px;
            width: 100%;
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            border-radius: 25px;
            padding: 10px;
            font-weight: 600;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 login-container">
                <div class="card">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <i class="fas fa-tools fa-3x text-primary mb-3"></i>
                            <h2 class="fw-bold">Chama Serviço</h2>
                            <p class="text-muted">Faça login para acessar o dashboard</p>
                        </div>
                        
                        <?php if ($erro): ?>
                            <div class="alert alert-danger" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($erro); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-envelope"></i>
                                    </span>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           placeholder="Digite seu email" required>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="senha" class="form-label">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <input type="password" class="form-control" id="senha" name="senha" 
                                           placeholder="Digite sua senha" required>
                                </div>
                            </div>
                            
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-login">
                                    <i class="fas fa-sign-in-alt me-2"></i>
                                    Entrar
                                </button>
                            </div>
                        </form>
                        
                       
                    </div>
                </div>
            </div>
        </div>
        
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

