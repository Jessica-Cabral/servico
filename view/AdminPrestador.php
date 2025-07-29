<!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <title>Consulta</title>
</head>

<body>
    <div class="container-fluid">
        <br>
        <form method="post" action="index.php">
            <div class="row">
                <div class="col-6">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome do Prestador</label>
                        <input type="text" name="nome" class="form-control" id="nome_prestador"
                            placeholder="Digite o nome do Prestador...">
                    </div>
                </div>
                <div>
                    <button type="submit" name="consultar_prestador_admin" class="btn btn-primary"><i
                            class="bi bi-search"></i> Consultar</button>
        </form>
        <br>
    </div>

    <div class="container-fluid">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>CPF</th>
                    <th>Data Nascimento</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                //mostrar os resultados
                foreach ($resultado as $key => $valor) {
                    print '<tr>';
                    print '  <th scope="row">' . $valor->id_pessoa . '</th>';
                    print '  <td>' . $valor->nome . '</td>';
                    print '  <td>' . $valor->cpf . '</td>';
                    print '  <td>' . $valor->data_nascimento . '</td>';
                    print '  <td>' . $valor->telefone . '</td>';
                    print '  <td>' . $valor->email . '</td>';                                               
                    $statusClass = ($valor->status == 'ativo') ? 'text-success' : (($valor->status == 'inativo') ? 'text-danger' : '');
                    print '  <td class="' . $statusClass . '">' . $valor->status . '</td>';
                   

                    print '  <td>
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#alterar_prestador_admin' . $valor->id_pessoa . '"><i class="bi bi-pencil-square"></i> Alterar</button>
                                <button type="button" class="btn btn-danger"  data-bs-toggle="modal" data-bs-target="#excluir_prestador' . $valor->id_pessoa . '"><i class="bi bi-x-square-fill"></i> Excluir</button>
                            </td>';
                    print '</tr>';
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php
        //criar os Modal de excluir
        foreach ($resultado as $key => $valor) {
            $this->modal_excluir_prestador_admin($valor->id_prestador, $valor->nome);
        }
    ?>

    <?php
        //criar os Modal de alterar
        foreach ($resultado as $key => $valor) {
            $this->modal_alterar_prestador_admin(
                $valor->id_pessoa, 
                $valor->nome, 
                $valor->telefone,         // 3º param: telefone
                $valor->email,            // 4º param: email
                $valor->status,           // 5º param: status
                $valor->cliente,          // 6º param: cliente
                $valor->prestador         // 7º param: prestador
            );
        }
    ?>

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>