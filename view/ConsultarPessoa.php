<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Alterar Dados - Prestador</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/form.css">


  
</head>
<body>



  <div class="container-form">
    <p class="form-title">*Edite o seu Cadastro</p>
    <div class="text-center">
      <img src="assets/img/fotoPerfil.png" alt="Foto do Perfil" class="perfil-img">
    </div>

    <form action="salvar_prestador.php" method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <label for="nomeCompleto" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" id="nomeCompleto" name="nomeCompleto" value="$valor->nome" required>
      </div>

      <div class="mb-3">
        <label for="cpf" class="form-label">CPF</label>
        <input type="text" class="form-control" id="cpf" name="cpf" value="004.569.856-88" disabled>
      </div>

      <div class="mb-3">
        <label for="dataNascimento" class="form-label">Data de Nascimento</label>
        <input type="date" class="form-control" id="dataNascimento" name="dataNascimento" value="1993-12-09" required>
      </div>

      <div class="mb-3">
        <label for="telefone" class="form-label">Telefone</label>
        <input type="tel" class="form-control" id="telefone" name="telefone" value="(61) 99999-9999" required>
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">E-mail</label>
        <input type="email" class="form-control" id="email" name="email" value="contatoerivan.ms@gmail.com" required>
      </div>

      <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="senha" class="form-control" id="senha" name="senha" value="xxxxxxxxxxxx" required>
      </div>
      <div class="mb-3">
        <label for="endereco" class="form-label">Endereço</label>
        <input type="text" class="form-control" id="endereco" name="endereco" value="Rua 5 quadra 22 lt 6 Brasilia-DF" required>
      </div>

      <div class="mb-3">
        <label for="documentUpload" class="form-label">Upload de Documento (RG ou CNH)</label>
        <input class="form-control" type="file" id="documentUpload" name="documentUpload" accept=".png, .jpeg, .jpg">
      </div>

      <div class="text-center">
      <button type="button" class="btn btn-purple w-100"  data-bs-target="#alterar_pessoa' . $valor->id_pessoa . '"><i class="bi bi-pencil-square" ></i> Alterar</button>
   
    
      <br><br>
       
      </div>
    </form>
    <?php
        //criar os Modal de excluir
        foreach ($resultado as $key => $valor) {
            $this->modal_excluir_pessoa($valor->id_pessoa, $valor->nome);
            //$this->modal_alterar_pessoa($valor->id_pessoa, $valor->nome);
        }
    ?>
    
    <?php
        //criar os Modal de alterar
        foreach ($resultado as $key => $valor) {
            $this->modal_alterar_pessoa($valor->id_pessoa, $valor->nome, $valor->email, $valor->cpf, $valor->data_nascimento, $valor->telefone, $valor->senha, $valor->foto_perfil, $valor->endereco, $valor->status);
        }
    ?>
  </div>

</body>
</html>
