<?php
require_once 'controllers/ClienteController.class.php';
require_once 'controllers/PrestadorController.class.php';
require_once 'model/Pessoa.class.php';

$sucesso = false;
$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    $cliente = ($tipo === 'cliente') ? 1 : 0;
    $prestador = ($tipo === 'prestador') ? 1 : 0;

    if ($nome && $email && $senha && $tipo) {
        $pessoa = new Pessoa();
        $ok = $pessoa->cadastrarPessoa(
            $nome,
            '', // cpf
            $email,
            '', // data_nascimento
            '', // telefone
            $senha,
            $cliente,
            $prestador
        );
        if ($ok) {
            header('Location: Login.php?cadastro=sucesso');
            exit();
        } else {
            $erro = "Erro ao cadastrar. Tente novamente.";
        }
    } else {
        $erro = "Preencha todos os campos obrigatórios.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Usuário - Chama Serviço</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,600,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/homepage.css">
    <style>
        body { font-family: 'Montserrat', 'Poppins', Arial, sans-serif; background: #f8fafc; }
        .cadastro-hero {
            background: linear-gradient(90deg, #1a2233 60%, #ffb347 100%);
            color: #fff;
            padding: 60px 0 40px 0;
            border-radius: 0 0 32px 32px;
            margin-bottom: 40px;
        }
        .cadastro-hero .bi {
            font-size: 3rem;
            color: #ffb347;
            background: #fff;
            border-radius: 50%;
            padding: 18px;
            margin-bottom: 16px;
        }
        .cadastro-section {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(30,40,60,0.07);
            padding: 40px 32px;
            max-width: 480px;
            margin: -80px auto 32px auto;
        }
        .form-label {
            font-weight: 600;
            color: #1a2233;
        }
        .form-control, .form-select {
            border-radius: 8px;
            font-size: 1.05rem;
        }
        .btn-cadastro {
            background: #1a2233;
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
            transition: background 0.2s;
        }
        .btn-cadastro:hover {
            background: #ffb347;
            color: #1a2233;
        }
        .btn-back {
            background: #fff;
            color: #1a2233;
            border-radius: 8px;
            font-weight: 600;
            border: 1px solid #1a2233;
        }
        .btn-back:hover {
            background: #ffb347;
            color: #1a2233;
            border-color: #ffb347;
        }
        .form-icon {
            font-size: 1.3rem;
            color: #ffb347;
            margin-right: 8px;
        }
        @media (max-width: 575.98px) {
            .cadastro-section { padding: 24px 8px; }
        }
    </style>
</head>
<body>
<header id="header" class="fixed-top shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #1a2233 60%, #ffb347 100%);">
      <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="HomePage.php" style="gap: 12px;">
          <i class="bi bi-tools" style="font-size: 2rem; margin-right: 8px;"></i>
          <span class="fw-bold">Chama Serviço</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="HomePage.php"><i class="bi bi-house-door me-1"></i> Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="CadUsuario.php"><i class="bi bi-person-plus me-1"></i> Crie sua conta</a>
            </li>
            <li class="nav-item">
              <a class="nav-link d-flex align-items-center" href="Login.php"><i class="bi bi-box-arrow-in-right me-1"></i> Entrar</a>
            </li>
            <li class="nav-item">
              <a class="nav-link active d-flex align-items-center" href="about.html"><i class="bi bi-info-circle me-1"></i> Sobre nós</a>
            </li>
          </ul>
        </div>
      </div>
    </nav>
</header>
<div class="cadastro-hero text-center">
    <div class="container">
        <i class="bi bi-person-plus"  style="margin-top: 5px; display: inline-block;"  ></i>
       
        <h1 class="fw-bold mb-3">Crie sua conta</h1>
        <p class="lead mb-0" style="max-width: 500px; margin: 0 auto;">
            Cadastre-se gratuitamente e tenha acesso a todos os recursos da plataforma Chama Serviço.
        </p>
    </div>
</div>
<div class="cadastro-section">
    <?php if ($erro): ?>
        <div class="alert alert-danger text-center">
            <i class="bi bi-exclamation-triangle-fill"></i> <?php echo htmlspecialchars($erro); ?>
        </div>
    <?php endif; ?>
    <form method="post" action="CadUsuario.php" autocomplete="off">
        <div class="mb-3">
            <label for="nome" class="form-label"><i class="bi bi-person form-icon"></i>Nome completo</label>
            <input type="text" class="form-control" id="nome" name="nome" required maxlength="80" placeholder="Digite seu nome completo">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label"><i class="bi bi-envelope form-icon"></i>E-mail</label>
            <input type="email" class="form-control" id="email" name="email" required maxlength="80" placeholder="Digite seu e-mail">
        </div>
        <div class="mb-3">
            <label for="senha" class="form-label"><i class="bi bi-lock form-icon"></i>Senha</label>
            <input type="password" class="form-control" id="senha" name="senha" required minlength="6" maxlength="32" placeholder="Crie uma senha">
        </div>
        <div class="mb-3">
            <label for="tipo" class="form-label"><i class="bi bi-person-badge form-icon"></i>Tipo de conta</label>
            <select class="form-select" id="tipo" name="tipo" required>
                <option value="">Selecione...</option>
                <option value="cliente">Cliente</option>
                <option value="prestador">Prestador</option>
            </select>
        </div>
        <div class="d-grid gap-2">
            <button type="submit" class="btn btn-cadastro py-2"><i class="bi bi-check-circle me-1"></i>Cadastrar</button>
            <a href="HomePage.php" class="btn btn-back py-2"><i class="bi bi-arrow-left me-1"></i>Voltar</a>
        </div>
    </form>
</div>
<footer class="text-center py-4 mt-5" style="background:#1a2233; color:#fff;">
    &copy; <script>document.write(new Date().getFullYear())</script> Chama Serviço. Todos os direitos reservados.
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
