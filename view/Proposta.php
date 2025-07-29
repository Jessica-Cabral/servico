<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Serviços</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        .btn-custom {
            background-color: #5c2d91;
            color: #fff;
            border: none;
        }
        .btn-custom:hover {
            background-color: #482173;
        }
        .service-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .service-header {
            background-color: #5c2d91;
            color: #fff;
            padding: 15px;
            border-radius: 8px 8px 0 0;
        }
        .service-body {
            padding: 15px;
        }
        
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
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
          /* Rodapé */
          .rodape {
            text-align: center;
            padding: 10px;
            background-color: #5c2d91;
            color: #fff;
            position: fixed;
            bottom: 0;
            width: 100%;
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


    <div class="container my-5">
        <h1>Lista de Serviços</h1>
        
        <!-- Serviço 1 -->
        <div class="service-card">
            <div class="service-header">
                <h5 class="mb-0">Serviço: Pintura Residencial</h5>
            </div>
            <div class="service-body">
                <p><strong>Endereço:</strong> Rua das Flores, 123 - São Paulo, SP</p>
                <p><strong>Descrição:</strong> Pintura completa de uma casa de 2 andares.</p>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-custom me-2">Mais Detalhes</button>
                    <button class="btn btn-success me-2">Aceitar</button>
                    <button class="btn btn-danger">Excluir</button>
                </div>
            </div>
        </div>

        <!-- Serviço 2 -->
        <div class="service-card">
            <div class="service-header">
                <h5 class="mb-0">Serviço: Limpeza Pós-Obra</h5>
            </div>
            <div class="service-body">
                <p><strong>Endereço:</strong> Avenida Central, 456 - Rio de Janeiro, RJ</p>
                <p><strong>Descrição:</strong> Limpeza geral após término de reforma em apartamento.</p>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-custom me-2">Mais Detalhes</button>
                    <button class="btn btn-success me-2">Aceitar</button>
                    <button class="btn btn-danger">Excluir</button>
                </div>
            </div>
        </div>

        <!-- Serviço 3 -->
        <div class="service-card">
            <div class="service-header">
                <h5 class="mb-0">Serviço: Conserto de Encanamento</h5>
            </div>
            <div class="service-body">
                <p><strong>Endereço:</strong> Rua dos Pinheiros, 789 - Belo Horizonte, MG</p>
                <p><strong>Descrição:</strong> Reparação de vazamento em encanamento interno.</p>
                <div class="d-flex justify-content-end">
                    <button class="btn btn-custom me-2">Mais Detalhes</button>
                    <button class="btn btn-success me-2">Aceitar</button>
                    <button class="btn btn-danger">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Rodapé -->
        <div class="rodape">
            Chama Serviço &copy; Todos os direitos reservados
        </div>
</body>
</html>
