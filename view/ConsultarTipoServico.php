<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faturamento</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
            display:flex;
            min-hiegtht: 100vh;
        }
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
        
         /* Menu Superior */
         .menu-topo {
            background-color:#6648EF;
            color:#ffffff; /*cor do nome chamaServiço*/
            display: flex;
            justify-content: space-between;
            padding: 10px 50px;
            position: fixed; /* Mantém o menu superior fixo */
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
            position: static;
            left: 0;
            padding: 30px;
            box-shadow: 3px 0 5px rgba(0, 0, 0, 0.1);
            order: -1; /* Coloca o menu lateral antes do conteúdo principal no flex container */
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
            flex-grow: 1;  /* Faz o container principal ocupar o espaço restante */
        }

        /* Formulário de filtro */
        .filtro-form {
            display: flex;
            gap: 10px;
            justify-content: center;
            margin-bottom: 20px;
        }

        .filtro-form input, .filtro-form button {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        .filtro-form button {
            background-color: #5c2d91;
            color: #fff;
            border: none;
            cursor: pointer;
            font-weight: bold;
        }

        .filtro-form button:hover {
            background-color: #452176;
        }

        /* Tabela */
        table {
            width: 100%;
            border-collapse: collapse;
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
            padding: 6px 10px;
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
            margin-top: 20px;
            display: block;
            background-color: #6a2c7c;
            text-align: center;
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
        /* Container para envolver o menu lateral e o conteúdo principal*/
        .layout-principal {
            display: flex;
            margin-top: 30px; /* Ajuste a margem para baixo do menu superior fixo */
            min-height: calc(100vh - 30px - 40px); /* Altura mínima descontando o menu superior e rodapé (se fixo) */
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
    <div class="layout-principal">
        <div class="menu-lateral">
            <h3>Minha Conta</h3>
            <a href="FormBuscarPrestador.html">Buscar</a>
            <a href="ServicosRealizados.html">Serviços Realizados</a>
            <a href="#">Forma de Recebimento</a>
            <a href="Faturamento.html">Faturamento</a>
            <a href="PerfilPrestador.html">Meu Perfil</a>
            <a href="Suporte.html">Suporte Técnico</a>
            <a href="Login.html">Sair</a>
        </div>  
            <div class="container">
                <h1>Tipo de Serviços</h1>
                <!-- Formulário de Filtro -->
                <div class="filtro-form">
                    <input type="text" name="buscar_tipo_servico" id="buscar_tipo_servico" placeholder="Digite o Tipo de serviço...">
                    <input type="submit" name="consultar_tipo_servico" value="Consultar">
                </div>

                <!-- Tabela de tipo de serviço -->
                <table id="tabela-tipo-servico">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tipo serviço</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //mostrar tabela com resultado da consulta
                            foreach ($resultado as $key => $valor) {
                                echo '<tr>';
                                echo '  <th scope="row">' . $valor->id_tipo_servico . '</th>';
                                echo '  <td>' . $valor->descricao_tipo_servico . '</td>';
                                echo '  <td>
                                            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#alterar_tipo_servico' . $valor->id_genero . '"><i class="bi bi-pencil-square"></i> Alterar</button>
                                            <button type="button" class="btn btn-danger"  data-bs-toggle="modal" data-bs-target="#excluir_tipo_servico' . $valor->id_genero . '"><i class="bi bi-x-square-fill"></i> Excluir</button>
                                        </td>';
                                echo '</tr>';
                            }    
                        ?>
                    </tbody>
                </table>

                <!-- Botão para exportar relatório -->
                <button class="exportar-btn" onclick="exportarRelatorio()">Exportar Relatório</button>
            </div>
        <!-- Rodapé -->
        <div class="rodape">
            Chama Serviço &copy; Todos os direitos reservados
        </div>
    </div>    
    <!-- Script JS -->
        <script src="assets/js/servico.js"></script>
        <script src="assets/js/menuLateral.js"></script>
</body>
</html>
