<?php
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: meus-servicos.php');
    exit();
}

require_once __DIR__ . '/../../models/Servico.php';

$servico = new Servico();
$servico_id = $_GET['id'];
$dados_servico = $servico->getDetalhes($servico_id, $_SESSION['cliente_id']);

if (!$dados_servico || $dados_servico['status_id'] != 1) {
    $_SESSION['erro'] = 'Serviço não pode ser editado';
    header('Location: meus-servicos.php');
    exit();
}

$tipos_servico = $servico->getTiposServico();
$enderecos = $servico->getEnderecosPorCliente($_SESSION['cliente_id']);

$sucesso = '';
$erro = '';

if ($_POST) {
    // Processar data e horário se fornecidos
    $data_atendimento = null;
    if (!empty($_POST['data_desejada']) && !empty($_POST['horario_desejado'])) {
        $data_atendimento = $_POST['data_desejada'] . ' ' . $_POST['horario_desejado'] . ':00';
    }
    
    $dados_update = [
        'id' => $servico_id,
        'tipo_servico_id' => $_POST['tipo_servico_id'],
        'endereco_id' => $_POST['endereco_id'],
        'titulo' => $_POST['titulo'],
        'descricao' => $_POST['descricao'],
        'orcamento_estimado' => !empty($_POST['orcamento_estimado']) ? $_POST['orcamento_estimado'] : null,
        'urgencia' => $_POST['urgencia'],
        'data_atendimento' => $data_atendimento
    ];

    if ($servico->atualizar($dados_update)) {
        $sucesso = 'Serviço atualizado com sucesso!';
        // Recarregar dados atualizados
        $dados_servico = $servico->getDetalhes($servico_id, $_SESSION['cliente_id']);
    } else {
        $erro = 'Erro ao atualizar serviço. Tente novamente.';
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Serviço - Chama Serviço</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
            --edit-color: #3498db;
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
        
        .card-header.bg-edit {
            background: linear-gradient(135deg, var(--edit-color), #5dade2) !important;
            color: white !important;
        }
        
        .btn-edit {
            background: linear-gradient(135deg, var(--edit-color), #5dade2);
            border: none;
            color: white;
        }
        
        .btn-edit:hover {
            background: linear-gradient(135deg, #2980b9, var(--edit-color));
            color: white;
            transform: translateY(-1px);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="clienteDashboard.php">
                <i class="fas fa-tools me-2"></i>
                Chama Serviço
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="meus-servicos.php">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar aos Serviços
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-edit">
                        <h4 class="mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Editar Serviço
                        </h4>
                    </div>
                    <div class="card-body">
                        <?php if ($sucesso): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $sucesso; ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($erro): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $erro; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tipo_servico_id" class="form-label">Tipo de Serviço *</label>
                                    <select class="form-select" id="tipo_servico_id" name="tipo_servico_id" required>
                                        <option value="">Selecione o tipo de serviço</option>
                                        <?php foreach ($tipos_servico as $tipo): ?>
                                            <option value="<?php echo $tipo['id']; ?>" 
                                                    <?php echo $tipo['id'] == $dados_servico['tipo_servico_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($tipo['nome']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="urgencia" class="form-label">Urgência *</label>
                                    <select class="form-select" id="urgencia" name="urgencia" required>
                                        <option value="baixa" <?php echo $dados_servico['urgencia'] == 'baixa' ? 'selected' : ''; ?>>Baixa</option>
                                        <option value="media" <?php echo $dados_servico['urgencia'] == 'media' ? 'selected' : ''; ?>>Média</option>
                                        <option value="alta" <?php echo $dados_servico['urgencia'] == 'alta' ? 'selected' : ''; ?>>Alta</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="titulo" class="form-label">Título do Serviço *</label>
                                <input type="text" class="form-control" id="titulo" name="titulo" 
                                       value="<?php echo htmlspecialchars($dados_servico['titulo']); ?>" required>
                            </div>

                            <div class="mb-3">
                                <label for="descricao" class="form-label">Descrição Detalhada *</label>
                                <textarea class="form-control" id="descricao" name="descricao" rows="4" required><?php echo htmlspecialchars($dados_servico['descricao']); ?></textarea>
                            </div>

                            <!-- Data e Horário Desejados -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="data_desejada" class="form-label">Data Desejada</label>
                                    <input type="date" class="form-control" id="data_desejada" name="data_desejada"
                                           value="<?php echo $dados_servico['data_atendimento'] ? date('Y-m-d', strtotime($dados_servico['data_atendimento'])) : ''; ?>">
                                    <small class="text-muted">Data em que gostaria que o serviço fosse realizado (opcional)</small>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="horario_desejado" class="form-label">Horário Desejado</label>
                                    <select class="form-select" id="horario_desejado" name="horario_desejado">
                                        <option value="">Selecione o horário</option>
                                        <?php 
                                        $horario_atual = $dados_servico['data_atendimento'] ? date('H:i', strtotime($dados_servico['data_atendimento'])) : '';
                                        $horarios = ['08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00', '17:00', '18:00', '19:00', '20:00'];
                                        foreach ($horarios as $horario): 
                                        ?>
                                            <option value="<?php echo $horario; ?>" <?php echo $horario == $horario_atual ? 'selected' : ''; ?>>
                                                <?php echo $horario . ' - ' . ($horario < '12:00' ? 'Manhã' : ($horario < '18:00' ? 'Tarde' : 'Noite')); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <small class="text-muted">Horário preferencial (opcional)</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="endereco_id" class="form-label">Endereço *</label>
                                    <select class="form-select" id="endereco_id" name="endereco_id" required>
                                        <option value="">Selecione o endereço</option>
                                        <?php foreach ($enderecos as $endereco): ?>
                                            <option value="<?php echo $endereco['id']; ?>"
                                                    <?php echo $endereco['id'] == $dados_servico['endereco_id'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($endereco['logradouro'] . ', ' . $endereco['numero'] . ' - ' . $endereco['bairro']); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="orcamento_estimado" class="form-label">Orçamento Estimado</label>
                                    <div class="input-group">
                                        <span class="input-group-text">R$</span>
                                        <input type="number" class="form-control" id="orcamento_estimado" 
                                               name="orcamento_estimado" step="0.01" 
                                               value="<?php echo $dados_servico['orcamento_estimado']; ?>">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="meus-servicos.php" class="btn btn-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-edit">
                                    <i class="fas fa-save me-1"></i>
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
        });
    </script>
</body>
</html>
