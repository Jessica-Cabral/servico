<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Cliente</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
    <!-- Navbar -->


    <!-- Conteúdo Principal -->
    <?php
    foreach ($resultado as $key => $value){
    print '<div class="container mt-4">';
    print '    <div class="row">';
    print '        <!-- Dados Pessoais -->';
    print '        <div class="col-md-4">';
    print '            <div class="card">';
    print '                <div class="card-header">Dados Pessoais</div>';
    print '                <div class="card-body text-center">';
    print '                    <img src="assets/img/' . $valor->foto_perfil . '" alt="Foto de Perfil" class="profile-img mb-3">';
    print '                    <h5 class="card-title">' . htmlspecialchars($valor->nome_completo) . '</h5>';
    print '                    <p class="card-text">CPF: ' . htmlspecialchars($valor->cpf) . '</p>';
    print '                    <p class="card-text">Data de Nascimento: ' . htmlspecialchars($valor->data_nascimento) . '</p>';
    print '                    <p class="card-text">Telefone: ' . htmlspecialchars($valor->telefone) . '</p>';
    print '                    <p class="card-text">E-mail: ' . htmlspecialchars($valor->email) . '</p>';
    print '                    <p class="card-text">Endereço: ' . htmlspecialchars($valor->endereco) . '</p>';
    print '                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarDadosModal">Editar Dados</button>';
    print '                </div>';
    print '            </div>';
    print '        </div>';
        }  ?>
            <!-- Filtros de Busca -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Buscar Profissionais</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="regiao" class="form-label">Região</label>
                                <input type="text" class="form-control" id="regiao" placeholder="Digite sua região">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="avaliacao" class="form-label">Avaliação Mínima</label>
                                <select class="form-select" id="avaliacao">
                                    <option value="0">Todas</option>
                                    <option value="4">4+ estrelas</option>
                                    <option value="4.5">4.5+ estrelas</option>
                                    <option value="5">5 estrelas</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-primary" onclick="buscarProfissionais()">Buscar</button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Resultados da Busca -->
                <div class="card mb-4">
                    <div class="card-header">Profissionais Encontrados</div>
                    <div class="card-body" id="resultadosBusca">
                        <!-- Resultados serão preenchidos via JavaScript -->
                        <p>Nenhum profissional encontrado. Use os filtros acima para buscar.</p>
                    </div>
                </div>

                <!-- Solicitar Orçamento -->
                <div class="card">
                    <div class="card-header">Solicitar Orçamento</div>
                    <div class="card-body">
                        <form id="formOrcamento">
                            <div class="mb-3">
                                <label for="servico" class="form-label">Serviço</label>
                                <select class="form-select" id="servico" required>
                                    <option value="">Selecione um serviço</option>
                                    <option value="1">Encanamento</option>
                                    <option value="2">Elétrica</option>
                                    <option value="3">Pintura</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição</label>
                                <textarea class="form-control" id="descricao" rows="3" placeholder="Descreva o serviço necessário" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">Enviar Solicitação</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Editar Dados -->
    <div class="modal fade" id="editarDadosModal" tabindex="-1" aria-labelledby="editarDadosModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editarDadosModalLabel">Editar Dados Pessoais</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formEditarDados">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome Completo</label>
                            <input type="text" class="form-control" id="nome" value="Nome Completo" required>
                        </div>
                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" value="123.456.789-00" required>
                        </div>
                        <div class="mb-3">
                            <label for="dataNascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="dataNascimento" value="1990-05-15" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" value="(11) 98765-4321" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" value="cliente@email.com" required>
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco" value="Rua Exemplo, 123, São Paulo, SP" required>
                        </div>
                        <div class="mb-3">
                            <label for="fotoPerfil" class="form-label">Foto de Perfil</label>
                            <input type="file" class="form-control" id="fotoPerfil" accept="image/*">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="salvarDados()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Histórico -->
    <div class="modal fade" id="historicoModal" tabindex="-1" aria-labelledby="historicoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="historicoModalLabel">Histórico de Solicitações</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Solicitação #1 - Encanamento - 01/04/2025 - Concluído</p>
                    <p>Solicitação #2 - Elétrica - 15/03/2025 - Pendente</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Alterar Senha -->
    <div class="modal fade" id="alterarSenhaModal" tabindex="-1" aria-labelledby="alterarSenhaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="alterarSenhaModalLabel">Alterar Senha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formAlterarSenha">
                        <div class="mb-3">
                            <label for="senhaAtual" class="form-label">Senha Atual</label>
                            <input type="password" class="form-control" id="senhaAtual" required>
                        </div>
                        <div class="mb-3">
                            <label for="novaSenha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="novaSenha" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmarSenha" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="confirmarSenha" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" onclick="alterarSenha()">Salvar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Excluir Conta -->
    <div class="modal fade" id="excluirContaModal" tabindex="-1" aria-labelledby="excluirContaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="excluirContaModalLabel">Excluir Conta</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Tem certeza que deseja excluir sua conta? Esta ação não pode ser desfeita.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger" onclick="excluirConta()">Excluir</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript personalizado -->
    <script src="assets/js/cliente.js"></script>
</body>
</html>