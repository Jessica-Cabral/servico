<?php
session_start();
echo '<pre>';
print_r($_SESSION);
echo '</pre>';

// Verificar se o usuário está logado através dos dadosPessoa
if (!isset($_SESSION['dadosPessoa']) || empty($_SESSION['dadosPessoa'])) {
    $_SESSION['mensagem'] = [
        'tipo' => 'danger',
        'texto' => 'Você precisa estar logado para acessar esta página.'
    ];
    header('Location: index.php?pagina=login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Solicitar Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .required:after {
            content: " *";
            color: red;
        }
        .card {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .loading {
            display: none;
            color: rgb(123, 14, 133);
            margin-left: 10px;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Exibição de mensagens de feedback -->
                <?php if (isset($_SESSION['mensagem'])) : ?>
                    <div class="alert alert-<?= $_SESSION['mensagem']['tipo'] ?> alert-dismissible fade show">
                        <?= $_SESSION['mensagem']['texto'] ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <?php unset($_SESSION['mensagem']); ?>
                <?php endif; ?>

                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-tools me-2"></i>Solicitar Orçamento/Serviço</h4>
                    </div>
                    <div class="card-body">
                        <form action="index.php" method="POST" enctype="multipart/form-data" id="form-solicitacao">

                            <div class="mb-4">
                                <h5 class="mb-3 border-bottom pb-2"><i class="fas fa-user me-2"></i>Dados do Solicitante</h5>
                                <input type="hidden" name="id_pessoa" value="<?= $_SESSION['dadosPessoa'][0]->id_pessoa ?? '' ?>">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="nome" class="form-label required">Nome Completo</label>
                                        <input type="text" class="form-control" id="nome" name="nome" 
                                               value="<?= $_SESSION['dadosPessoa'][0]->nome ?? '' ?>" readonly>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="telefone" class="form-label required">Telefone</label>
                                        <input type="tel" class="form-control" id="telefone" name="telefone" 
                                               value="<?= $_SESSION['dadosPessoa'][0]->telefone ?? '' ?>" required>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">E-mail</label>
                                    <input type="email" class="form-control" id="email" name="email" 
                                           value="<?= $_SESSION['dadosPessoa'][0]->email ?? '' ?>">
                                </div>
                            </div>

                            <div class="mb-4">
                                <h5 class="mb-3 border-bottom pb-2"><i class="fas fa-clipboard-list me-2"></i>Dados do Serviço</h5>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="tipo_servico" class="form-label required">Tipo de Serviço</label>
                                        <select name="tipo_servico" id="tipo_servico" class="form-select" required>
                                            <option value="">Selecione um Tipo</option>
                                            <?php
                                            $objTipoServico = new TipoServico();
                                            $tipos = $objTipoServico->consultarTipoServico(null);
                                            if ($tipos) {
                                                foreach ($tipos as $tipo) {
                                                    echo "<option value='" . $tipo->id_tipo_servico . "'>" . $tipo->descricao_tipo_servico . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="servico" class="form-label required">Serviço</label>
                                        <select name="servico" id="servico" class="form-select" required disabled>
                                            <option value="">Selecione um Serviço</option>
                                        </select>
                                    </div>
                                </div>

                                <h6 class="mb-3 border-bottom pb-2"><i class="fas fa-map-marker-alt me-2"></i>Endereço</h6>

                                <div class="row mb-3">
                                    <div class="col-md-3">
                                        <label for="cep" class="form-label required">CEP</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="cep" name="cep" required>
                                            <button class="btn btn-outline-secondary" type="button" id="buscar-cep">
                                                <i class="fas fa-search"></i>
                                            </button>
                                        </div>
                                        <span id="loading-cep" class="loading">
                                            <i class="fas fa-spinner fa-spin"></i> Buscando...
                                        </span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="logradouro" class="form-label required">Logradouro</label>
                                        <input type="text" class="form-control" id="logradouro" name="logradouro" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="numero" class="form-label required">Número</label>
                                        <input type="text" class="form-control" id="numero" name="numero" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="complemento" class="form-label">Complemento</label>
                                        <input type="text" class="form-control" id="complemento" name="complemento">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="bairro" class="form-label required">Bairro</label>
                                        <input type="text" class="form-control" id="bairro" name="bairro" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="cidade" class="form-label required">Cidade</label>
                                        <input type="text" class="form-control" id="cidade" name="cidade" required>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="uf" class="form-label required">Estado</label>
                                        <input type="text" class="form-control" id="uf" name="uf" required maxlength="2">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="descricao" class="form-label required">Descrição Detalhada</label>
                                    <textarea class="form-control" id="descricao" name="descricao_solicitacao" rows="4" required></textarea>
                                </div>

                                <div class="mb-3">
                                    <label for="img_solicitacao" class="form-label">Fotos (Fotos/Arquivos)</label>
                                    <input type="file" class="form-control" id="img_solicitacao" name="img_solicitacao[]" multiple
                                           accept="image/*,.pdf">
                                    <small class="text-muted">Formatos aceitos: JPG, PNG, GIF, PDF (Máx. 5MB cada)</small>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="reset" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-eraser me-1"></i> Limpar
                                </button>
                                <button type="submit" name="cadastrar_solicitacao" class="btn btn-primary">
                                    <i class="fas fa-paper-plane me-1"></i> Enviar Solicitação
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Máscaras e validações
        document.addEventListener('DOMContentLoaded', function() {
            // Máscara para CEP
            document.getElementById('cep').addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '')
                    .replace(/^(\d{5})(\d)/, '$1-$2')
                    .substring(0, 9);
            });

            // Máscara para telefone
            document.getElementById('telefone').addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '')
                    .replace(/^(\d{2})(\d)/, '($1) $2')
                    .replace(/(\d{5})(\d)/, '$1-$2')
                    .substring(0, 15);
            });

            // Buscar CEP via API
            document.getElementById('buscar-cep').addEventListener('click', buscarCEP);
            document.getElementById('cep').addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    buscarCEP();
                }
            });

            // Carregar serviços dinamicamente
            document.getElementById('tipo_servico').addEventListener('change', function() {
                const tipoServicoId = this.value;
                const selectServico = document.getElementById('servico');
                
                if (tipoServicoId) {
                    selectServico.disabled = false;
                    fetch(`buscar_servicos.php?tipo_servico_id=${tipoServicoId}`)
                        .then(response => response.text())
                        .then(data => {
                            selectServico.innerHTML = data;
                        });
                } else {
                    selectServico.disabled = true;
                    selectServico.innerHTML = '<option value="">Selecione um Serviço</option>';
                }
            });

            // Validação do formulário
            document.getElementById('form-solicitacao').addEventListener('submit', function(e) {
                let isValid = true;
                document.querySelectorAll('[required]').forEach(function(campo) {
                    if (!campo.value.trim()) {
                        campo.classList.add('is-invalid');
                        isValid = false;
                    } else {
                        campo.classList.remove('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor, preencha todos os campos obrigatórios!');
                }
            });
        });

        function buscarCEP() {
            const cep = document.getElementById('cep').value.replace(/\D/g, '');
            const loading = document.getElementById('loading-cep');

            if (cep.length !== 8) {
                alert('CEP deve conter 8 dígitos');
                return;
            }

            loading.style.display = 'inline-block';

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        throw new Error('CEP não encontrado');
                    }

                    document.getElementById('logradouro').value = data.logradouro || '';
                    document.getElementById('bairro').value = data.bairro || '';
                    document.getElementById('cidade').value = data.localidade || '';
                    document.getElementById('uf').value = data.uf || '';
                    document.getElementById('numero').focus();
                })
                .catch(error => {
                    console.error('Erro ao buscar CEP:', error);
                    alert('CEP não encontrado. Preencha manualmente.');
                    document.getElementById('logradouro').focus();
                })
                .finally(() => {
                    loading.style.display = 'none';
                });
        }
    </script>
</body>

</html>