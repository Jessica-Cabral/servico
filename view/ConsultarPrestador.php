<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Dados-Prestador </title>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Poppins:400,500,600,700" rel="stylesheet">
 <!-- Estilos -->
 <style>
    /* Reset básico */
    * {
       margin: 0;
       padding: 0;
       box-sizing: border-box;
       font-family: Arial, sans-serif;
   }
   /* Título */
h1, h2 {
text-align: center;
color: #6648EF;
}
body {
   font-family: Arial, sans-serif;
   margin: 0;
   padding: 0;
   background-color: #f8f9fa;
   color: #333;
}
 /* Menu Superior */
 .menu-topo {
   background-color:#6648EF;
   color:#ffffff; /*cor do nome chamaServiço*/
   display: flex;
   justify-content: space-between;
   padding: 10px 50px;
   position: fixed;
   width: 100%;
   top: 0;
   z-index: 1000;
}

.menu-topo a {
   color: #ffffff;
   text-decoration: none;
   margin: 0 10px;
   font-weight: bold;
}

.menu-topo a:hover {
   color:#dcbee7;
   text-decoration: rgb(0, 10, 4);
}

/* Menu Lateral */
.menu-lateral {
   background-color: #f4f4f4;
   width: 250px;
   height: 100vh;
   position: fixed;
   top: 40px; /* abaixo do menu superior */
   left: 3;
   padding: 30px;
   box-shadow: 3px 0 5px rgba(0, 0, 0, 0.1);
}

.menu-lateral h3 {
   margin-bottom: 20px;
   font-size: 18px;
   color:#3c3c5f
}

.menu-lateral a {
   display: block;
   color: #5c2d91;
   text-decoration: none;
   padding: 10px 0;
   font-weight: bold;
}

.menu-lateral a:hover {
   color: #6648EF;
}
h1 {
   text-align: center;
   color: #5c2d91;
   margin-top: 20px;
}
input[type="file"]::file-selector-button {
    background-color: #6a2c7c; /* Cor roxa do layout */
    color: #fff; /* Cor do texto */
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
}

input[type="file"]::file-selector-button:hover {
    background-color: #572264; /* Tom roxo mais escuro ao passar o mouse */
}
/* Estilos do formulário */
form.form-editar {
display: flex;
flex-direction: column;
gap: 15px;
}

.form-group {
display: flex;
flex-direction: column;
}
.form-group label {
    font-weight: 600;
    color: #555;
    margin-bottom: 5px;
}

.container {
   max-width: 1200px;
   margin: 20px auto;
   padding: 20px;
   background-color: #fff;
   border-radius: 8px;
   box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
table {
   width: 100%;
   border-collapse: collapse;
   margin: 20px 0;
}
table th, table td {
   border: 1px solid #ddd;
   padding: 10px;
   text-align: center;
}
table th {
   background-color: #5c2d91;
   color: #fff;
}
table td {
   background-color: #f9f9f9;
}
button {
   padding: 8px 12px;
   border: none;
   border-radius: 4px;
   cursor: pointer;
   color: #fff;
   font-weight: bold;
   background-color: #5c2d91;
}
button:hover {
   background-color: #452176;
}
.exportar-btn {
   display: block;
   margin: 10px auto;
   background-color: #6a2c7c;
}
.adicionar-cartao{
    border-color: #6a2c7c; /* Cor roxa ao focar */
    box-shadow: 0 0 5px rgba(106, 44, 124, 0.2);
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
    transition: border-color 0.3s ease;
    outline: none;
}

.remover{
    background-color: #6a2c7c; /* Cor roxa do layout */
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
}
.btn btn-primary{
    background-color: #6a2c7c; /* Cor roxa do layout */
    color: #fff;
    border: none;
    padding: 8px 12px;
    border-radius: 4px;
    cursor: pointer;
    font-family: 'Poppins', sans-serif;
    font-weight: 500;
}
.form-control {
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
    transition: border-color 0.3s ease;
    outline: none;
}

.form-control:focus {
    border-color: #6a2c7c; /* Cor roxa ao focar */
    box-shadow: 0 0 5px rgba(106, 44, 124, 0.2);
}

/* Rodapé */
.rodape {
   text-align: center;
   padding: 10px;
   background-color:#6648EF;
   color: #fff;
   position: fixed;
   bottom: 0;
   width: 100%;
   object-fit: cover;
}
</style>
</head>
<body>
 <!-- Menu Superior -->
 <div class="menu-topo">
    <div>ChamaServiço</div>
    <div>
        <a href="FormBuscarPrestador.html">Buscar</a>
        <a href="FormBuscarPrestador.html">Serviços</a>
        <a href="PerfilAlteraP.html">Atualizar Dados</a>
    </div>
</div>

<!-- Menu Lateral -->
<div class="menu-lateral">
    <h3>Minha Conta</h3>
    <a href="FormBuscarPrestador.html">Buscar</a>
    <a href="ServicosRealizados.html">Serviços Realizados</a>
    <a href="Faturamento.html">Faturamento</a>
    <a href="PerfilPrestador.html">Meu Perfil</a>
    <a href="Suporte.html">Suporte Técnico</a>
    <a href="Login.html">Sair</a>
</div>

   
        <!-- Conteúdo principal -->
       
        <main class="container">
            <p>*Edite o seu Cadastro</p>
            <div class="perfil">
                
                <img src="assets/img/fotoPerfil.png" alt="Foto do Perfil">
            </div>
            <form action="#" method="post" class="form-editar">
                <div class="form-group">
                    <label for="nomeCompleto">Nome Completo</label>
                    <input type="text" id="nomeCompleto" name="nomeCompleto" class="form-control" value="Erivan Mendes" required>
                </div>
        
                <div class="form-group">
                    <label for="cpf">CPF/CNPJ</label>
                    <input type="text" id="cpf" name="cpf" class="form-control" value="004.569.856-88" disabled >
                </div>
        
                <div class="form-group">
                    <label for="dataNascimento">Data de Nascimento</label>
                    <input type="date" id="dataNascimento" name="dataNascimento" class="form-control" value="1993-12-09" required>
                </div>
        
                <div class="form-group">
                    <label for="telefone">Telefone</label>
                    <input type="tel" id="telefone" name="telefone" class="form-control" value="(61) 99999-9999" required>
                </div>
        
                <div class="form-group">
                    <label for="email">E-mail</label>
                    <input type="email" id="email" name="email" class="form-control" value="contatoerivan.ms@gmail.com" required>
                </div>
        
                <div class="form-group">
                    <label for="endereco">Endereço</label>
                    <input type="text" id="endereco" name="endereco" class="form-control" value="Rua 5 quadra 22 lt 6 Brasilia-DF" required>
                </div>
        
                <div class="form-group">
                    <label for="documentUpload">Upload de Documento (RG ou CNH)</label>
                    <input type="file" id="documentUpload" name="documentUpload" class="form-control" accept=".png, .jpeg, .jpg" required>
                </div>
        
                <a class="form-group text-center mt-3">
                    <a href="PerfilPrestador.html"> <button type="submit" class="tn btn-secondary">Salvar</button> </a>
                </div>
            </form>
        </main>
        
    </div>
    <!-- FIM - CONTAINER PRINCIPAL -->

    <!-- INÍCIO - RODAPÉ 
    <footer>
        <p>Chama Serviço Todos os direitos reservados </p>
    </footer>
     FIM - RODAPÉ -->
</body>
</html>
