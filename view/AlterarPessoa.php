<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alterar Dados </title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/form.css">
  
</head>
<body>


<?php
 foreach ($_SESSION['dadosPessoa']  as $key => $valor) {
 print' <div class="container-form">
 
    <p class="form-title">*Edite o seu Cadastro</p>
     <div class="mb-3">
     <form action="index.php" method="post" enctype="multipart/form-data">
     <input type="hidden" name="id_pessoa" value='.$valor->id_pessoa.'>
     <img src='.$valor->foto_perfil.' alt="Foto do Perfil" class="perfil-img">
                
                <input class="form-control" type="file" id="imagem" name="foto_perfil">
            </div>

    
      <div class="mb-3">
        <label for="nomeCompleto" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" id="nomeCompleto" name="nome" value='.$valor->nome.' disabled>
      </div>

      <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" value='.$valor->cpf.' disabled>
      </div>

      <div class="mb-3">
        <label for="dataNascimento" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" id="dataNascimento" name="data_nascimento" value= '.$valor->data_nascimento.'  disabled>
      </div>

      <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="telefone" name="telefone" value='.$valor->telefone.' required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" value='.$valor->email.' disabled>
      </div>
      <label for="aviso" class="form-label">*Caso acrescentar outro perfil faça o login do sistema/label>
      <div class="check">
    <input type="checkbox" value="1" name="cliente" id="cliente" style="width: 13px; height: 13px;" 
        '.($valor->cliente == 1 ? 'checked' : '').'>
    <label for="cliente" style="color: #3d3d3d;">Cliente</label>
    
    <input type="checkbox" value="1" name="prestador" id="prestador" style="width: 13px; height: 13px;" 
        '.($valor->prestador == 1 ? 'checked' : '').'>
    <label for="prestador" style="color: #3d3d3d;">Prestador</label>
</div>
       <br><br>

      <div class="text-center">
      <button type="submit" class="btn btn-purple w-100"  name="botao_alterar_pessoa"><i class="bi bi-pencil-square" ></i> Alterar</button>
   
    
      <br><br>
       
      </div>
    </form>';
 }
    ?>
    <?php
        //criar os Modal de alterar
        foreach ($_SESSION['dadosPessoa'] as $key => $valor) {
            $this->botao_alterar_pessoa($valor->id_pessoa,$valor->telefone, $valor->foto_perfil, $valor->cliente,$valor->prestador, );
        }
    ?>
  </div>
  

</body>
</html>
