<?php
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Se for uma requisição AJAX para criar serviço
if ($_POST && isset($_POST['ajax'])) {
    try {
        error_log("POST data: " . print_r($_POST, true));
        
        require_once __DIR__ . '/../../models/Servico.php';

        $servico = new Servico();
        $endereco_id = null;
        
        // Verificar se é novo endereço ou existente
        if (isset($_POST['endereco_opcao']) && $_POST['endereco_opcao'] === 'novo') {
            // Validar campos obrigatórios do endereço
            $campos_obrigatorios = ['cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado'];
            foreach ($campos_obrigatorios as $campo) {
                if (empty($_POST[$campo])) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => "Campo {$campo} é obrigatório."]);
                    exit();
                }
            }
            
            // Criar novo endereço primeiro
            $endereco_dados = [
                'pessoa_id' => $_SESSION['cliente_id'],
                'cep' => $_POST['cep'],
                'logradouro' => $_POST['logradouro'],
                'numero' => $_POST['numero'],
                'complemento' => $_POST['complemento'] ?? '',
                'bairro' => $_POST['bairro'],
                'cidade' => $_POST['cidade'],
                'estado' => $_POST['estado'],
                'principal' => isset($_POST['endereco_principal']) ? 1 : 0
            ];
            
            $endereco_id = $servico->criarEndereco($endereco_dados);
            
            if (!$endereco_id) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Erro ao cadastrar endereço.']);
                exit();
            }
        } else {
            if (empty($_POST['endereco_id'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Selecione um endereço.']);
                exit();
            }
            $endereco_id = $_POST['endereco_id'];
        }
        
        // Validar campos obrigatórios do serviço
        if (empty($_POST['tipo_servico_id']) || empty($_POST['titulo']) || empty($_POST['descricao'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Preencha todos os campos obrigatórios.']);
            exit();
        }
        
        // Processar data e horário se fornecidos
        $data_atendimento = null;
        if (!empty($_POST['data_desejada']) && !empty($_POST['horario_desejado'])) {
            $data_atendimento = $_POST['data_desejada'] . ' ' . $_POST['horario_desejado'] . ':00';
        }
        
        $dados = [
            'cliente_id' => $_SESSION['cliente_id'],
            'tipo_servico_id' => $_POST['tipo_servico_id'],
            'endereco_id' => $endereco_id,
            'titulo' => $_POST['titulo'],
            'descricao' => $_POST['descricao'],
            'orcamento_estimado' => !empty($_POST['orcamento_estimado']) ? $_POST['orcamento_estimado'] : null,
            'urgencia' => $_POST['urgencia'] ?? 'media',
            'data_atendimento' => $data_atendimento
        ];

        if ($servico->criar($dados)) {
            $servico_id = $servico->getLastInsertId();
            
            // Processar upload de imagens se houver
            if (!empty($_FILES['fotos_servico']['name'][0])) {
                $resultado_upload = $servico->uploadImagensServico($servico_id, $_FILES['fotos_servico']);
                if (!$resultado_upload['success']) {
                    // Se falhou o upload, ainda assim o serviço foi criado
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Serviço criado, mas houve problemas com algumas imagens: ' . $resultado_upload['message']]);
                    exit();
                }
            }
            
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Serviço solicitado com sucesso!']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erro ao criar solicitação de serviço.']);
        }
        
    } catch (Exception $e) {
        error_log("Erro no processamento: " . $e->getMessage());
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Erro interno do servidor.']);
    }
    exit();
}

require_once '../../models/Servico.php';

$servico = new Servico();
$tipos_servico = $servico->getTiposServico();
$enderecos = $servico->getEnderecosPorCliente($_SESSION['cliente_id']);

// Inclua o menu do cliente em todas as páginas para manter o padrão visual e navegação
require_once 'menu-cliente.php';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Serviço - Chama Serviço</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
        }
        
        body {
            background-color: #f8f9fa;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        }
        
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .btn-novo-servico {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            font-size: 24px;
            z-index: 1000;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        .endereco-fields {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
        }

        .loading-cep {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        .cep-group {
            position: relative;
        }
        
        .alert-custom {
            border: none;
            border-radius: 10px;
        }
        
        .btn-solicitar {
            background: linear-gradient(135deg, var(--secondary-color), #5dade2);
            border: none;
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .btn-solicitar:hover {
            background: linear-gradient(135deg, #2980b9, var(--secondary-color));
            transform: translateY(-1px);
        }
        
        .foto-preview {
            position: relative;
            display: inline-block;
            margin: 5px;
        }
        
        .foto-preview img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #dee2e6;
        }
        
        .foto-preview .btn-remover {
            position: absolute;
            top: -5px;
            right: -5px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            padding: 0;
            font-size: 12px;
            line-height: 1;
        }
        
        .upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            background-color: #f8f9fa;
            transition: all 0.2s;
        }
        
        .upload-area:hover {
            border-color: #3498db;
            background-color: #e3f2fd;
        }
        
        .upload-area.dragover {
            border-color: #3498db;
            background-color: #e3f2fd;
        }
    </style>
</head>
<body>
    <?php // Remova ou comente o bloco abaixo para evitar duplicidade do menu ?>
    <!-- Navbar -->
    <!--
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="clienteDashboard.php">
                <i class="fas fa-tools me-2"></i>
                Chama Serviço
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="clienteDashboard.php">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </nav>
    -->
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-list me-2"></i>
                            Área de Solicitações
                        </h4>
                    </div>
                    <div class="card-body text-center py-5">
                        <i class="fas fa-plus-circle fa-4x text-primary mb-3"></i>
                        <h5>Solicite um novo serviço</h5>
                        <p class="text-muted">Clique no botão abaixo para solicitar um novo serviço</p>
                        <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#novoServicoModal">
                            <i class="fas fa-plus me-2"></i>
                            Solicitar Serviço
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botão Flutuante -->
    <button type="button" class="btn btn-primary btn-novo-servico" data-bs-toggle="modal" data-bs-target="#novoServicoModal">
        <i class="fas fa-plus"></i>
    </button>

    <!-- Modal Novo Serviço -->
    <div class="modal fade" id="novoServicoModal" tabindex="-1" aria-labelledby="novoServicoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="novoServicoModalLabel">
                        <i class="fas fa-plus me-2"></i>
                        Solicitar Novo Serviço
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="alertContainer"></div>
                    
                    <form id="novoServicoForm">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="tipo_servico_id" class="form-label">Tipo de Serviço *</label>
                                <select class="form-select" id="tipo_servico_id" name="tipo_servico_id" required>
                                    <option value="">Selecione o tipo de serviço</option>
                                    <?php foreach ($tipos_servico as $tipo): ?>
                                        <option value="<?php echo $tipo['id']; ?>">
                                            <?php echo htmlspecialchars($tipo['nome']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="urgencia" class="form-label">Urgência *</label>
                                <select class="form-select" id="urgencia" name="urgencia" required>
                                    <option value="baixa">Baixa</option>
                                    <option value="media" selected>Média</option>
                                    <option value="alta">Alta</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="titulo" class="form-label">Título do Serviço *</label>
                            <input type="text" class="form-control" id="titulo" name="titulo" 
                                   placeholder="Ex: Instalação de tomadas na sala" required>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição Detalhada *</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="4" 
                                      placeholder="Descreva detalhadamente o serviço que precisa..." required></textarea>
                        </div>

                        <!-- Data e Horário Desejados -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="data_desejada" class="form-label">Data Desejada</label>
                                <input type="date" class="form-control" id="data_desejada" name="data_desejada">
                                <small class="text-muted">Data em que gostaria que o serviço fosse realizado (opcional)</small>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="horario_desejado" class="form-label">Horário Desejado</label>
                                <select class="form-select" id="horario_desejado" name="horario_desejado">
                                    <option value="">Selecione o horário</option>
                                    <option value="08:00">08:00 - Manhã</option>
                                    <option value="09:00">09:00 - Manhã</option>
                                    <option value="10:00">10:00 - Manhã</option>
                                    <option value="11:00">11:00 - Manhã</option>
                                    <option value="13:00">13:00 - Tarde</option>
                                    <option value="14:00">14:00 - Tarde</option>
                                    <option value="15:00">15:00 - Tarde</option>
                                    <option value="16:00">16:00 - Tarde</option>
                                    <option value="17:00">17:00 - Tarde</option>
                                    <option value="18:00">18:00 - Tarde</option>
                                    <option value="19:00">19:00 - Noite</option>
                                    <option value="20:00">20:00 - Noite</option>
                                </select>
                                <small class="text-muted">Horário preferencial (opcional)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="endereco_opcao" class="form-label">Opção de Endereço *</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="endereco_opcao" id="endereco_existente" value="existente" checked>
                                    <label class="form-check-label" for="endereco_existente">
                                        Usar endereço cadastrado
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="endereco_opcao" id="endereco_novo" value="novo">
                                    <label class="form-check-label" for="endereco_novo">
                                        Cadastrar novo endereço
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="orcamento_estimado" class="form-label">Orçamento Estimado</label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" class="form-control" id="orcamento_estimado" 
                                           name="orcamento_estimado" step="0.01" placeholder="0.00">
                                </div>
                                <small class="text-muted">Valor aproximado que você pretende investir (opcional)</small>
                            </div>
                        </div>

                        <!-- Endereços Existentes -->
                        <div id="endereco_existente_group" class="mb-3">
                            <label for="endereco_id" class="form-label">Selecionar Endereço *</label>
                            <select class="form-select" id="endereco_id" name="endereco_id">
                                <option value="">Selecione o endereço</option>
                                <?php foreach ($enderecos as $endereco): ?>
                                    <option value="<?php echo $endereco['id']; ?>">
                                        <?php echo htmlspecialchars($endereco['logradouro'] . ', ' . $endereco['numero'] . ' - ' . $endereco['bairro'] . ' - ' . $endereco['cidade'] . '/' . $endereco['estado']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Novo Endereço -->
                        <div id="endereco_novo_group" class="endereco-fields d-none">
                            <h6 class="mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Novo Endereço
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="cep" class="form-label">CEP *</label>
                                    <div class="cep-group">
                                        <input type="text" class="form-control" id="cep" name="cep" 
                                               placeholder="00000-000" maxlength="9">
                                        <div class="loading-cep d-none">
                                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-8 mb-3">
                                    <label for="logradouro" class="form-label">Logradouro *</label>
                                    <input type="text" class="form-control" id="logradouro" name="logradouro" 
                                           placeholder="Rua, Avenida, etc.">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="numero" class="form-label">Número *</label>
                                    <input type="text" class="form-control" id="numero" name="numero" 
                                           placeholder="123">
                                </div>
                                
                                <div class="col-md-5 mb-3">
                                    <label for="complemento" class="form-label">Complemento</label>
                                    <input type="text" class="form-control" id="complemento" name="complemento" 
                                           placeholder="Apto, Sala, etc.">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="bairro" class="form-label">Bairro *</label>
                                    <input type="text" class="form-control" id="bairro" name="bairro" 
                                           placeholder="Nome do bairro">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label for="cidade" class="form-label">Cidade *</label>
                                    <input type="text" class="form-control" id="cidade" name="cidade" 
                                           placeholder="Nome da cidade">
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="estado" class="form-label">Estado *</label>
                                    <select class="form-select" id="estado" name="estado">
                                        <option value="">Selecione</option>
                                        <option value="AC">Acre</option>
                                        <option value="AL">Alagoas</option>
                                        <option value="AP">Amapá</option>
                                        <option value="AM">Amazonas</option>
                                        <option value="BA">Bahia</option>
                                        <option value="CE">Ceará</option>
                                        <option value="DF">Distrito Federal</option>
                                        <option value="ES">Espírito Santo</option>
                                        <option value="GO">Goiás</option>
                                        <option value="MA">Maranhão</option>
                                        <option value="MT">Mato Grosso</option>
                                        <option value="MS">Mato Grosso do Sul</option>
                                        <option value="MG">Minas Gerais</option>
                                        <option value="PA">Pará</option>
                                        <option value="PB">Paraíba</option>
                                        <option value="PR">Paraná</option>
                                        <option value="PE">Pernambuco</option>
                                        <option value="PI">Piauí</option>
                                        <option value="RJ">Rio de Janeiro</option>
                                        <option value="RN">Rio Grande do Norte</option>
                                        <option value="RS">Rio Grande do Sul</option>
                                        <option value="RO">Rondônia</option>
                                        <option value="RR">Roraima</option>
                                        <option value="SC">Santa Catarina</option>
                                        <option value="SP">São Paulo</option>
                                        <option value="SE">Sergipe</option>
                                        <option value="TO">Tocantins</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="endereco_principal" name="endereco_principal">
                                <label class="form-check-label" for="endereco_principal">
                                    Definir como endereço principal
                                </label>
                            </div>
                        </div>
                        
                        <!-- Upload de Fotos -->
                        <div class="mb-4">
                            <label class="form-label">Fotos do Serviço (opcional)</label>
                            <div class="upload-area" id="uploadArea">
                                <i class="fas fa-camera fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-2">Clique aqui ou arraste fotos para ajudar no orçamento</p>
                                <small class="text-muted">Máximo 5 fotos - JPG, PNG (até 2MB cada)</small>
                                <input type="file" id="fotosServico" name="fotos_servico[]" multiple accept="image/*" class="d-none">
                            </div>
                            <div id="previewContainer" class="mt-3"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <button type="button" class="btn btn-secondary w-100" data-bs-dismiss="modal">
                                    <i class="fas fa-times me-1"></i>
                                    Cancelar
                                </button>
                            </div>
                            <div class="col-md-6 mb-3">
                                <button type="button" class="btn btn-primary btn-solicitar w-100" id="btnSolicitarServico">
                                    <i class="fas fa-paper-plane me-1"></i>
                                    <span class="btn-text">Solicitar Serviço</span>
                                    <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('novoServicoForm');
            const btnSolicitar = document.getElementById('btnSolicitarServico');
            const alertContainer = document.getElementById('alertContainer');
            const modal = new bootstrap.Modal(document.getElementById('novoServicoModal'));

            // Controle de exibição de endereços
            const enderecoExistente = document.getElementById('endereco_existente');
            const enderecoNovo = document.getElementById('endereco_novo');
            const enderecoExistenteGroup = document.getElementById('endereco_existente_group');
            const enderecoNovoGroup = document.getElementById('endereco_novo_group');
            const enderecoSelect = document.getElementById('endereco_id');

            // CEP
            const cepInput = document.getElementById('cep');
            const loadingCep = document.querySelector('.loading-cep');

            function toggleEnderecoGroups() {
                if (enderecoExistente.checked) {
                    enderecoExistenteGroup.classList.remove('d-none');
                    enderecoNovoGroup.classList.add('d-none');
                    enderecoSelect.required = true;
                    clearNovoEnderecoRequired();
                } else {
                    enderecoExistenteGroup.classList.add('d-none');
                    enderecoNovoGroup.classList.remove('d-none');
                    enderecoSelect.required = false;
                    setNovoEnderecoRequired();
                }
            }

            function setNovoEnderecoRequired() {
                ['cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado'].forEach(field => {
                    document.getElementById(field).required = true;
                });
            }

            function clearNovoEnderecoRequired() {
                ['cep', 'logradouro', 'numero', 'bairro', 'cidade', 'estado'].forEach(field => {
                    document.getElementById(field).required = false;
                });
            }

            // Event listeners para opções de endereço
            enderecoExistente.addEventListener('change', toggleEnderecoGroups);
            enderecoNovo.addEventListener('change', toggleEnderecoGroups);

            // Configurar data mínima (hoje)
            const dataDesejada = document.getElementById('data_desejada');
            const hoje = new Date().toISOString().split('T')[0];
            dataDesejada.min = hoje;

            // Habilitar/desabilitar horário baseado na data
            dataDesejada.addEventListener('change', function() {
                const horarioDesejado = document.getElementById('horario_desejado');
                if (this.value) {
                    horarioDesejado.disabled = false;
                } else {
                    horarioDesejado.disabled = true;
                    horarioDesejado.value = '';
                }
            });

            // Máscara para CEP
            cepInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length <= 8) {
                    value = value.replace(/^(\d{5})(\d)/, '$1-$2');
                    e.target.value = value;
                }

                // Buscar CEP quando tiver 8 dígitos
                if (value.replace(/\D/g, '').length === 8) {
                    buscarCep(value.replace(/\D/g, ''));
                }
            });

            function buscarCep(cep) {
                loadingCep.classList.remove('d-none');
                
                fetch(`https://viacep.com.br/ws/${cep}/json/`)
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('logradouro').value = data.logradouro || '';
                            document.getElementById('bairro').value = data.bairro || '';
                            document.getElementById('cidade').value = data.localidade || '';
                            document.getElementById('estado').value = data.uf || '';
                            
                            // Focar no campo número
                            document.getElementById('numero').focus();
                        } else {
                            showAlert('warning', 'CEP não encontrado. Preencha os campos manualmente.');
                        }
                    })
                    .catch(error => {
                        console.error('Erro ao buscar CEP:', error);
                        showAlert('warning', 'Erro ao buscar CEP. Preencha os campos manualmente.');
                    })
                    .finally(() => {
                        loadingCep.classList.add('d-none');
                    });
            }

            // Upload de fotos
            const uploadArea = document.getElementById('uploadArea');
            const fotosInput = document.getElementById('fotosServico');
            const previewContainer = document.getElementById('previewContainer');
            let fotosArray = [];

            // Eventos de drag and drop
            uploadArea.addEventListener('click', () => fotosInput.click());
            uploadArea.addEventListener('dragover', (e) => {
                e.preventDefault();
                uploadArea.classList.add('dragover');
            });
            uploadArea.addEventListener('dragleave', () => {
                uploadArea.classList.remove('dragover');
            });
            uploadArea.addEventListener('drop', (e) => {
                e.preventDefault();
                uploadArea.classList.remove('dragover');
                const files = Array.from(e.dataTransfer.files);
                adicionarFotos(files);
            });

            fotosInput.addEventListener('change', (e) => {
                const files = Array.from(e.target.files);
                adicionarFotos(files);
            });

            function adicionarFotos(files) {
                files.forEach(file => {
                    if (file.type.startsWith('image/') && file.size <= 2 * 1024 * 1024) { // 2MB
                        if (fotosArray.length < 5) {
                            fotosArray.push(file);
                            criarPreview(file, fotosArray.length - 1);
                        } else {
                            showAlert('warning', 'Máximo de 5 fotos permitidas.');
                        }
                    } else {
                        showAlert('warning', `Arquivo ${file.name} inválido ou muito grande.`);
                    }
                });
                atualizarInputFiles();
            }

            function criarPreview(file, index) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const preview = document.createElement('div');
                    preview.className = 'foto-preview';
                    preview.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="btn btn-danger btn-sm btn-remover" onclick="removerFoto(${index})">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    previewContainer.appendChild(preview);
                };
                reader.readAsDataURL(file);
            }

            window.removerFoto = function(index) {
                fotosArray.splice(index, 1);
                atualizarPreviews();
                atualizarInputFiles();
            }

            function atualizarPreviews() {
                previewContainer.innerHTML = '';
                fotosArray.forEach((file, index) => {
                    criarPreview(file, index);
                });
            }

            function atualizarInputFiles() {
                const dt = new DataTransfer();
                fotosArray.forEach(file => dt.items.add(file));
                fotosInput.files = dt.files;
            }

            btnSolicitar.addEventListener('click', function() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                // Mostrar loading
                btnSolicitar.disabled = true;
                btnSolicitar.querySelector('.btn-text').textContent = 'Enviando...';
                btnSolicitar.querySelector('.spinner-border').classList.remove('d-none');

                // Preparar dados com FormData para upload de arquivos
                const formData = new FormData(form);
                formData.append('ajax', '1');

                // Enviar requisição
                fetch('novo-servico.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showAlert('success', data.message);
                        form.reset();
                        fotosArray = [];
                        previewContainer.innerHTML = '';
                        toggleEnderecoGroups();
                        setTimeout(() => {
                            modal.hide();
                            window.location.reload();
                        }, 2000);
                    } else {
                        showAlert('danger', data.message);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    showAlert('danger', 'Erro de conexão. Tente novamente.');
                })
                .finally(() => {
                    btnSolicitar.disabled = false;
                    btnSolicitar.querySelector('.btn-text').textContent = 'Solicitar Serviço';
                    btnSolicitar.querySelector('.spinner-border').classList.add('d-none');
                });
            });

            function showAlert(type, message) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-triangle'} me-2"></i>
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `;
                alertContainer.innerHTML = alertHtml;
            }

            // Limpar alertas quando modal for fechado
            document.getElementById('novoServicoModal').addEventListener('hidden.bs.modal', function () {
                alertContainer.innerHTML = '';
                form.reset();
                fotosArray = [];
                previewContainer.innerHTML = '';
                toggleEnderecoGroups();
            });

            // Inicializar visualização
            toggleEnderecoGroups();
        });
    </script>
</body>
<footer class="text-center text-muted py-3 mt-4">
    &copy; <?php echo date('Y'); ?> Chama Serviço. Todos os direitos reservados.
</footer>
</html>
