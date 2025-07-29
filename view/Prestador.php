

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Prestador</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Estilos personalizados -->
    <link rel="stylesheet" href="assets/css/style.css">
    
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Área do Prestador</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#faturamentoModal">Faturamento</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#alterarSenhaModal">Alterar Senha</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#excluirContaModal">Excluir Conta</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cliente.php">Mudar para Cliente</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="index.html">Sair</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Conteúdo Principal -->
    <div class="container mt-4">
        <div class="row">
            <!-- Dados Pessoais -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">Dados Pessoais</div>
                    <div class="card-body text-center">
                        <img src="<?php echo $prestador['foto_perfil']; ?>" alt="Foto de Perfil" class="profile-img mb-3">
                        <h5 class="card-title"><?php echo $prestador['nome']; ?></h5>
                        <p class="card-text">CPF: <?php echo $prestador['cpf']; ?></p>
                        <p class="card-text">Data de Nascimento: <?php echo $prestador['data_nascimento']; ?></p>
                        <p class="card-text">Telefone: <?php echo $prestador['telefone']; ?></p>
                        <p class="card-text">E-mail: <?php echo $prestador['email']; ?></p>
                        <p class="card-text">Endereço: <?php echo $prestador['endereco']; ?></p>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#editarDadosModal">Editar Dados</button>
                    </div>
                </div>
            </div>

            <!-- Notificações de Orçamentos -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header">Notificações de Orçamentos</div>
                    <div class="card-body" id="notificacoesOrcamentos">
                        <!-- Exemplo de notificação -->
                        <div class="card mb-2">
                            <div class="card-body">
                                <h6 class="card-title">Solicitação #001 - Encanamento</h6>
                                <p class="card-text">Descrição: Reparo de vazamento na cozinha.</p>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detalhesOrcamentoModal">Visualizar Detalhes</button>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-body">
                                <h6 class="card-title">Solicitação #002 - Elétrica</h6>
                                <p class="card-text">Descrição: Troca de fiação na sala.</p>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detalhesOrcamentoModal">Visualizar Detalhes</button>
                            </div>
                        </div>
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
                            <input type="text" class="form-control" id="nome" value="<?php echo $prestador['nome']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" class="form-control" id="cpf" value="<?php echo $prestador['cpf']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="dataNascimento" class="form-label">Data de Nascimento</label>
                            <input type="date" class="form-control" id="dataNascimento" value="<?php echo $prestador['data_nascimento']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" value="<?php echo $prestador['telefone']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" class="form-control" id="email" value="<?php echo $prestador['email']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco" value="<?php echo $prestador['endereco']; ?>" required>
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

    <!-- Modal para Faturamento -->
    <div class="modal fade" id="faturamentoModal" tabindex="-1" aria-labelledby="faturamentoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="faturamentoModalLabel">Faturamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Faturamento Total: R$ 5.000,00</p>
                    <p>Último Mês: R$ 1.200,00</p>
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

    <!-- Modal para Visualizar Detalhes do Orçamento -->
    <div class="modal fade" id="detalhesOrcamentoModal" tabindex="-1" aria-labelledby="detalhesOrcamentoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalhesOrcamentoModalLabel">Detalhes do Orçamento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Solicitação:</strong> Reparo de vazamento na cozinha</p>
                    <p><strong>Descrição:</strong> Vazamento na pia da cozinha, precisa de reparo urgente.</p>
                    <p><strong>Data de Abertura:</strong> 01/04/2025</p>
                    <div class="mb-3">
                        <label for="preco" class="form-label">Preço do Serviço (R$)</label>
                        <input type="number" class="form-control" id="preco" placeholder="Digite o preço do serviço">
                    </div>
                    <button class="btn btn-primary" onclick="enviarPreco()">Enviar Preço</button>
                    <button class="btn btn-success mt-2" onclick="fecharServico()">Fechar Serviço</button>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para Exibir Contato do Solicitante (após fechar o serviço) -->
    <div class="modal fade" id="contatoSolicitanteModal" tabindex="-1" aria-labelledby="contatoSolicitanteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contatoSolicitanteModalLabel">Contato do Solicitante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Nome:</strong> João Silva</p>
                    <p><strong>Telefone:</strong> (11) 98765-4321</p>
                    <p><strong>Endereço:</strong> Rua das Flores, 123, São Paulo, SP</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JavaScript personalizado -->
    <script src="assets/js/prestador.js"></script>
</body>
</html>