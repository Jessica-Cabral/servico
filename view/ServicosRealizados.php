<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Serviços Executados</title>
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



    
    <div class="container">
        <h1>Serviços Executados</h1>
        <!-- Tabela de serviços -->
        <table id="tabela-servicos">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Serviço</th>
                    <th>Status</th>
                    <th>Detalhes</th>
                </tr>
            </thead>
            <tbody>
                <!-- Linhas de exemplo -->
                <tr>
                    <td>01/05/2024</td>
                    <td>Instalação de Internet</td>
                    <td>Concluído</td>
                    <td><button onclick="verDetalhes('Instalação de Internet')">Detalhes</button></td>
                </tr>
                <tr>
                    <td>10/05/2024</td>
                    <td>Manutenção Elétrica</td>
                    <td>Concluído</td>
                    <td><button onclick="verDetalhes('Manutenção Elétrica')">Detalhes</button></td>
                </tr>
                <tr>
                    <td>20/05/2024</td>
                    <td>Reparo Hidráulico</td>
                    <td>Concluído</td>
                    <td><button onclick="verDetalhes('Reparo Hidráulico')">Detalhes</button></td>
                </tr>
            </tbody>
        </table>

        <!-- Botão para exportar relatório -->
        <button class="exportar-btn" onclick="exportarRelatorio()">Exportar como Relatório</button>
    </div>

    <script src="assets/js/servicosRealizados.js"></script>
      <!-- Rodapé -->
      <div class="rodape">
        Chama Serviço &copy; Todos os direitos reservados
    </div>
</body>
</html>
