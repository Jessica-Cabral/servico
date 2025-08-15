<?php
session_start();

// Verificar se o prestador está logado
if (!isset($_SESSION['prestador_id']) || $_SESSION['user_type'] !== 'prestador') {
    header('Location: ../auth/login.php');
    exit();
}

// Incluir dependências

require_once __DIR__ . '/../../models/ServicoClass.php';
require_once __DIR__ . '/../../models/PropostaClass.php';

$servico = new Servico();
$proposta = new Proposta();

$prestador_id = $_SESSION['prestador_id'];
$prestador_nome = $_SESSION['prestador_nome'] ?? 'Prestador';

// Processar envio de proposta
if ($_POST && isset($_POST['enviar_proposta'])) {
    $dados_proposta = [
        'solicitacao_id' => $_POST['servico_id'],
        'prestador_id' => $prestador_id,
        'valor' => $_POST['valor'],
        'prazo_execucao' => $_POST['prazo_execucao'],
        'descricao' => $_POST['descricao']
    ];

    if ($proposta->criar($dados_proposta)) {
        $sucesso = 'Proposta enviada com sucesso!';
    } else {
        $erro = 'Erro ao enviar proposta. Tente novamente.';
    }
}

// Obter serviços disponíveis
$filtros = [
    'tipo' => $_GET['tipo'] ?? '',
    'orcamento_min' => $_GET['orcamento_min'] ?? '',
    'orcamento_max' => $_GET['orcamento_max'] ?? '',
    'urgencia' => $_GET['urgencia'] ?? ''
];

$servicos_disponiveis = $servico->getDisponiveisComFiltros($filtros);
$tipos_servico = $servico->getTiposServico();

// Verificar se há um serviço específico para visualizar
$servico_detalhado = null;
if (isset($_GET['ver'])) {
    $servico_detalhado = $servico->getDetalhesPublicos($_GET['ver']);
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Oportunidades - Prestador | Chama Serviço</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
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
            transition: transform 0.2s;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .opportunity-card {
            border-left: 4px solid var(--secondary-color);
        }

        .urgencia-badge {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.75em;
            font-weight: 600;
        }

        .urgencia-alta {
            background-color: #e74c3c;
            color: white;
        }

        .urgencia-media {
            background-color: #f39c12;
            color: white;
        }

        .urgencia-baixa {
            background-color: #3498db;
            color: white;
        }

        .valor-destaque {
            color: var(--secondary-color);
            font-weight: 700;
            font-size: 1.1em;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="prestadorDashboard.php">
                <i class="fas fa-hammer me-2"></i>
                Chama Serviço - Prestador
            </a>

            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="prestadorDashboard.php">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-search me-2"></i>
                Oportunidades de Trabalho
            </h2>
            <span class="badge bg-success fs-6"><?php echo count($servicos_disponiveis); ?> oportunidades</span>
        </div>

        <!-- Alertas -->
        <?php if (isset($sucesso)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $sucesso; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (isset($erro)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $erro; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="tipo" class="form-label">Tipo de Serviço</label>
                        <select class="form-select" name="tipo" id="tipo">
                            <option value="">Todos os tipos</option>
                            <?php foreach ($tipos_servico as $tipo): ?>
                                <option value="<?php echo $tipo['id']; ?>"
                                    <?php echo $filtros['tipo'] == $tipo['id'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($tipo['nome']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <label for="orcamento_min" class="form-label">Orçamento Mín.</label>
                        <input type="number" class="form-control" name="orcamento_min"
                            value="<?php echo $filtros['orcamento_min']; ?>" placeholder="R$ 0">
                    </div>

                    <div class="col-md-2">
                        <label for="orcamento_max" class="form-label">Orçamento Máx.</label>
                        <input type="number" class="form-control" name="orcamento_max"
                            value="<?php echo $filtros['orcamento_max']; ?>" placeholder="R$ 9999">
                    </div>

                    <div class="col-md-2">
                        <label for="urgencia" class="form-label">Urgência</label>
                        <select class="form-select" name="urgencia">
                            <option value="">Todas</option>
                            <option value="alta" <?php echo $filtros['urgencia'] == 'alta' ? 'selected' : ''; ?>>Alta</option>
                            <option value="media" <?php echo $filtros['urgencia'] == 'media' ? 'selected' : ''; ?>>Média</option>
                            <option value="baixa" <?php echo $filtros['urgencia'] == 'baixa' ? 'selected' : ''; ?>>Baixa</option>
                        </select>
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-filter me-1"></i>
                            Filtrar
                        </button>
                        <a href="oportunidades.php" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Oportunidades -->
        <?php if (empty($servicos_disponiveis)): ?>
            <div class="card text-center">
                <div class="card-body py-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Nenhuma oportunidade encontrada</h4>
                    <p class="text-muted">Tente ajustar os filtros para encontrar mais oportunidades.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($servicos_disponiveis as $item): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card opportunity-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0"><?php echo htmlspecialchars($item['titulo']); ?></h6>
                                    <span class="urgencia-badge urgencia-<?php echo $item['urgencia']; ?>">
                                        <?php echo ucfirst($item['urgencia']); ?>
                                    </span>
                                </div>

                                <p class="text-muted small mb-2">
                                    <i class="fas fa-tag me-1"></i>
                                    <?php echo htmlspecialchars($item['tipo_servico']); ?>
                                </p>

                                <p class="card-text small">
                                    <?php echo htmlspecialchars(substr($item['descricao'], 0, 100)); ?>
                                    <?php if (strlen($item['descricao']) > 100): ?>...<?php endif; ?>
                                </p>

                                <div class="d-flex justify-content-between align-items-center text-muted small mb-2">
                                    <span>
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($item['data_solicitacao'])); ?>
                                    </span>
                                    <?php if ($item['data_atendimento']): ?>
                                        <span>
                                            <i class="fas fa-clock me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($item['data_atendimento'])); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <?php if ($item['orcamento_estimado']): ?>
                                    <div class="mb-3">
                                        <span class="valor-destaque">
                                            <i class="fas fa-dollar-sign me-1"></i>
                                            R$ <?php echo number_format($item['orcamento_estimado'], 2, ',', '.'); ?>
                                        </span>
                                        <small class="text-muted"> (estimado)</small>
                                    </div>
                                <?php endif; ?>

                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?php echo htmlspecialchars($item['cidade'] . '/' . $item['estado']); ?>
                                    </small>
                                </div>
                            </div>

                            <div class="card-footer bg-transparent">
                                <div class="d-grid gap-2 d-md-flex">
                                    <button class="btn btn-outline-success btn-sm flex-fill"
                                        onclick="verDetalhes(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver Detalhes
                                    </button>
                                    <button class="btn btn-success btn-sm"
                                        onclick="enviarProposta(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-paper-plane me-1"></i>
                                        Enviar Proposta
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Detalhes -->
    <div class="modal fade" id="detalhesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalhes da Oportunidade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detalhesContent">
                    <!-- Conteúdo carregado dinamicamente -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Enviar Proposta -->
    <div class="modal fade" id="propostaModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Enviar Proposta</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="servico_id" id="servicoId">
                        <input type="hidden" name="enviar_proposta" value="1">

                        <div class="mb-3">
                            <label for="valor" class="form-label">Valor da Proposta *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" class="form-control" name="valor" step="0.01" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="prazo_execucao" class="form-label">Prazo de Execução *</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="prazo_execucao" required>
                                <span class="input-group-text">dia(s)</span>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="descricao" class="form-label">Descrição da Proposta</label>
                            <textarea class="form-control" name="descricao" rows="4"
                                placeholder="Descreva como você executará este serviço..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-paper-plane me-1"></i>
                            Enviar Proposta
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function verDetalhes(servicoId) {
            fetch(`detalhes-oportunidade.php?id=${servicoId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('detalhesContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('detalhesModal')).show();
                })
                .catch(error => {
                    console.error('Erro:', error);
                    alert('Erro ao carregar detalhes.');
                });
        }

        function enviarProposta(servicoId) {
            document.getElementById('servicoId').value = servicoId;
            new bootstrap.Modal(document.getElementById('propostaModal')).show();
        }
    </script>
</body>

</html>