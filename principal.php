<?php
session_start();

// Simulação de usuário cadastrado (substitua por consulta ao banco de dados)
$usuario_demo = [
    'email' => 'admin@chamaservico.com',
    'senha' => password_hash('123456', PASSWORD_DEFAULT)
];

$erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if (!$email) {
        $erro = 'E-mail inválido.';
    } elseif (empty($senha)) {
        $erro = 'Senha é obrigatória.';
    } elseif ($email !== $usuario_demo['email'] || !password_verify($senha, $usuario_demo['senha'])) {
        $erro = 'E-mail ou senha incorretos.';
    } else {
        $_SESSION['usuario'] = $email;
        header('Location: painel.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Login</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; }
        .login-container {
            width: 350px; margin: 80px auto; padding: 30px;
            background: #fff; border-radius: 8px; box-shadow: 0 0 10px #ccc;
        }
        h2 { text-align: center; }
        .erro { color: #d00; margin-bottom: 15px; text-align: center; }
        label { display: block; margin-top: 15px; }
        input[type="email"], input[type="password"] {
            width: 100%; padding: 8px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px;
        }
        button {
            width: 100%; padding: 10px; margin-top: 20px;
            background: #007bff; color: #fff; border: none; border-radius: 4px; font-size: 16px;
            cursor: pointer;
        }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Administrativo</h2>
        <?php if ($erro): ?>
            <div class="erro"><?= htmlspecialchars($erro) ?></div>
        <?php endif; ?>
        <form method="post" autocomplete="off">
            <label for="email">E-mail</label>
            <input type="email" name="email" id="email" required autofocus>

            <label for="senha">Senha</label>
            <input type="password" name="senha" id="senha" required minlength="6">

            <button type="submit">Entrar</button>
        </form>
    </div>
</body>
</html>