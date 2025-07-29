<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login-Administrativo</title>

    <!-- CSS -->
    <link rel="stylesheet" href="assets/css/styleLogin.css">
    <link rel="stylesheet" href="assets/css/mediaLogin.css">

    <!-- Estilo para a mensagem de erro -->
    <style>
        .error-message {
            color: red;
            font-size: 14px;
            text-align: center;
            margin-top: 10px;
            display: none; /* Inicialmente escondido */
        }
    </style>
</head>
<body>
    <div id="container">
        <div class="banner">
            <img src="assets/img/login.png" alt="imagem-login">
          
        </div>

        <div class="box-login">
            <form action="index.php" method="post" id="loginForm">
                <h1>
                    Olá!<br>
                    Painel Administrativo 
                </h1>

                <div class="box">
                    <h2>faça o seu login agora</h2>
                    
                    <input type="email" name="email" id="email" placeholder="E-mail" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                    <input type="password" name="senha" id="senha" placeholder="Senha">
                    
                    <!-- Espaço para a mensagem de erro -->
                    <?php
                    // Exibir mensagem de erro, se existir
                    if (isset($errorMessage)) {
                        echo '<p class="error-message" style="display: block;">' . htmlspecialchars($errorMessage) . '</p>';
                    } else {
                        echo '<p class="error-message" style="display: none;"></p>';
                    }
                    ?>
                    
                   
                    </a>
                    <button name="validarAdmin" class="btn btn-primary btn-lg" type="submit"><i class="bi bi-box-arrow-in-right"> Acessar </i></button>
                   
                    
                   <!-- <a name="abrirformpessoa"  href="index.php">
                        <p>Criar uma conta</p>
                    </a>-->
                </div>
            </form>
        </div>
    </div>
    <a href="HomePage.php">
        <div id="bubble">
            <img src="assets/img/user.png" alt="icone-usuário" title="fazer-login">
        </div>
    </a>
</body>
</html>