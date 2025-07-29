<!DOCTYPE html>
<html lang="pt=br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta </title>

    <!--CSS-->
    <link rel="stylesheet" href="assets/css/styleLogin.css">
    <link rel="stylesheet" href="assets/css/mediaLogin.css">

    <!--JS & jQuery-->
    <script type="text/javascript" src="assets/js/scriptLogin.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>
<body>
<br>
<form method="post" action="index.php" >
    <div id="container">
        <div class="banner">
            <img src="assets/img/login.png" alt="imagem-login">
            <p style="color: #fff;">
                Seja bem vindo, acesse e aproveite todo o conteúdo,
                <br>somos uma equipe de profissionais empenhados em
                <br>trazer o melhor conteúdo direcionado a você usuário. 
            </p>
        </div>

        <div class="box-login">
            <h1>Junte-se a nós,<br>Crie hoje a sua conta!</h1>

            <div class="box-account">
                <h2>informe seus dados</h2>
                <input type="nome" name="nome" id="nome" placeholder="Nome Completo"required>
                <input type="email" name="email" id="email" placeholder="e-mail"required>
                <input type="date" name="data_nascimento" id="dataNascimento" placeholder="Data de Nascimento" maxlength="10" required>
                <span id="erroData" style="color: red; display: none;">Data inválida ou idade inferior a 18 anos!</span>
                <input type="text" name="cpf" id="cpfCnpj" placeholder="CPF" maxlength="18"required>
                <span id="errocpf" style="color: red; display: none;"></span>
                <input type="text" name="telefone" id="telefone" placeholder="Telefone"maxlength="15"required>
                <input type="password" name="senha" id="senha" placeholder="senha"required>
                <div class="check">
                    <input type="checkbox" value="1" name="cliente" id="cliente" style="width: 13px; height: 13px;">
                    <label for="cliente" style="color: #3d3d3d;">Cliente</label>
                    <input type="checkbox" value="1" name="prestador" id="prestador" style="width: 13px; height: 13px;">
                    <label for="termos" style="color: #3d3d3d;">Prestador</label>
                   
                </div>
               
                
                  <!--
                <div class="check">
                    <input type="checkbox" name="termo" id="termo" style="width: 13px; height: 13px;">
                    <label for="termos" style="color: #3d3d3d;">li e aceito os termos</label>
                </div>
                     -->
               
                <button type="submit" name="cadastrar_pessoa" id="criarContaBtn">Criar conta</button>
                
            </div>
        </div>
    </div>

    <a href="HomePage.php">
        <div id="bubble">
            <img src="assets/img/user.png" alt="icone-usuário" title="fazer-login">
        </div>
    </a>

<!--<script type="text/javascript" src="assets/js/ValidarCadUsuario.js" defer></script> -->
</body>
</html>