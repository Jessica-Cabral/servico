<?php
require_once __DIR__ . '/../../models/Proposta.class.php';

require_once __DIR__ . '/../../models/Servico.class.php';
session_start();

// Verificar se o prestador está logado
if (!isset($_SESSION['prestador_id']) || $_SESSION['user_type'] !== 'prestador') {
    header('Location: ../auth/login.php');
    exit();
}



$proposta = new Proposta();
$servico = new Servico();

$prestador_id = $_SESSION['prestador_id'];
$prestador_nome = $_SESSION['prestador_nome'] ?? 'Prestador';

// Filtros
$filtro_status = $_GET['status'] ?? '';
$minhas_propostas = $proposta->getByPrestadorDetalhado($prestador_id, $filtro_status);

// Estatísticas das propostas
$stats_propostas = $proposta->getStatsPropostas($prestador_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Propostas - Prestador | Chama Serviço</title>
    
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
        
        .proposta-card {
            border-left: 4px solid var(--secondary-color);
        }
        
        .proposta-card.pendente {
            border-left-color: #f39c12;
        }
        
        .proposta-card.aceita {
            border-left-color: #27ae60;
        }
        
        .proposta-card.recusada {
            border-left-color: #e74c3c;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }
        
        .valor-proposta {
            color: var(--secondary-color);
            font-weight: 700;
            font-size: 1.2em;
        }
        
        .stats-mini {
            background: linear-gradient(135deg, #3498db, #5dade2);
            color: white;
            border-radius: 10px;
        }
        
        .negotiation-timeline {
            border-left: 3px solid #dee2e6;
            padding-left: 20px;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 15px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3498db;
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
                <i class="fas fa-handshake me-2"></i>
                Minhas Propostas
            </h2>
            <a href="oportunidades.php" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>
                Enviar Nova Proposta
            </a>
        </div>

        <!-- Stats Cards Mini -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-mini p-3 text-center">
                    <h4 class="mb-1"><?php echo $stats_propostas['total']; ?></h4>
                    <small>Total de Propostas</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-mini p-3 text-center" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <h4 class="mb-1"><?php echo $stats_propostas['pendentes']; ?></h4>
                    <small>Pendentes</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-mini p-3 text-center" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
                    <h4 class="mb-1"><?php echo $stats_propostas['aceitas']; ?></h4>
                    <small>Aceitas</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-mini p-3 text-center" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <h4 class="mb-1"><?php echo $stats_propostas['recusadas']; ?></h4>
                    <small>Recusadas</small>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Filtrar por Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="">Todos os status</option>
                            <option value="pendente" <?php echo $filtro_status == 'pendente' ? 'selected' : ''; ?>>Pendentes</option>
                            <option value="aceita" <?php echo $filtro_status == 'aceita' ? 'selected' : ''; ?>>Aceitas</option>
                            <option value="recusada" <?php echo $filtro_status == 'recusada' ? 'selected' : ''; ?>>Recusadas</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-filter me-1"></i>
                            Filtrar
                        </button>
                        <a href="minhas-propostas.php" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Propostas -->
        <?php if (empty($minhas_propostas)): ?>
            <div class="card text-center">
                <div class="card-body py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Nenhuma proposta encontrada</h4>
                    <p class="text-muted">Você ainda não enviou propostas<?php echo $filtro_status ? " com status '{$filtro_status}'" : ''; ?>.</p>
                    <a href="oportunidades.php" class="btn btn-success">
                        <i class="fas fa-search me-1"></i>
                        Buscar Oportunidades
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($minhas_propostas as $item): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card proposta-card <?php echo $item['status']; ?> h-100">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-briefcase me-2 text-primary"></i>
                                        <?php echo htmlspecialchars($item['servico_titulo']); ?>
                                    </h6>
                                    <span class="status-badge bg-<?php echo $item['status_class']; ?> text-white">
                                        <?php echo ucfirst($item['status']); ?>
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Enviada em <?php echo date('d/m/Y H:i', strtotime($item['data_proposta'])); ?>
                                </small>
                            </div>
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>💰 Sua Proposta:</strong><br>
                                        <span class="valor-proposta">
                                            R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <strong>⏰ Prazo:</strong><br>
                                        <span class="badge bg-info fs-6">
                                            <?php echo $item['prazo_execucao']; ?> dia(s)
                                        </span>
                                    </div>
                                </div>
                                
                                <?php if ($item['descricao']): ?>
                                <div class="mb-3">
                                    <strong>📝 Sua Descrição:</strong><br>
                                    <div class="bg-light p-3 rounded mt-2">
                                        <?php echo nl2br(htmlspecialchars($item['descricao'])); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Histórico de Negociação -->
                                <?php 
                                $historico = $proposta->getHistoricoNegociacao($item['id']);
                                if (!empty($historico)): 
                                ?>
                                <hr>
                                <div class="mb-3">
                                    <strong>🔄 Histórico de Negociação:</strong>
                                    <div class="negotiation-timeline mt-3">
                                        <?php foreach ($historico as $nego): ?>
                                        <div class="timeline-item">
                                            <div class="d-flex justify-content-between">
                                                <strong class="text-<?php echo $nego['tipo'] == 'contra_proposta' ? 'warning' : 'info'; ?>">
                                                    <?php if ($nego['tipo'] == 'contra_proposta'): ?>
                                                        💬 Contra-proposta do Cliente
                                                    <?php else: ?>
                                                        🔄 Sua Resposta
                                                    <?php endif; ?>
                                                </strong>
                                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($nego['data_negociacao'])); ?></small>
                                            </div>
                                            <div>Valor: R$ <?php echo number_format($nego['valor'], 2, ',', '.'); ?></div>
                                            <div>Prazo: <?php echo $nego['prazo']; ?> dia(s)</div>
                                            <?php if ($nego['observacoes']): ?>
                                            <div class="text-muted small">"<?php echo htmlspecialchars($nego['observacoes']); ?>"</div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-outline-info btn-sm" onclick="verDetalhesServico(<?php echo $item['solicitacao_id']; ?>)">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver Serviço
                                    </button>
                                    
                                    <?php if ($item['status'] == 'pendente'): ?>
                                        <button class="btn btn-warning btn-sm" onclick="editarProposta(<?php echo $item['id']; ?>)">
                                            <i class="fas fa-edit me-1"></i>
                                            Editar
                                        </button>
                                        
                                        <button class="btn btn-outline-danger btn-sm" onclick="cancelarProposta(<?php echo $item['id']; ?>)">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar
                                        </button>
                                    <?php elseif ($item['status'] == 'aceita'): ?>
                                        <button class="btn btn-success btn-sm" onclick="iniciarTrabalho(<?php echo $item['id']; ?>)">
                                            <i class="fas fa-play me-1"></i>
                                            Iniciar Trabalho
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        function verDetalhesServico(servicoId) {
            window.open(`../cliente/detalhes-servico.php?id=${servicoId}`, '_blank');
        }

        function editarProposta(propostaId) {
            // Implementar modal de edição
            alert(`Editar proposta ${propostaId} - A implementar`);
        }

        function cancelarProposta(propostaId) {
            if (confirm('Tem certeza que deseja cancelar esta proposta?')) {
                // Implementar cancelamento
                alert(`Cancelar proposta ${propostaId} - A implementar`);
            }
        }

        function iniciarTrabalho(propostaId) {
            window.location.href = `iniciar-trabalho.php?proposta=${propostaId}`;
        }
    </script>
</body>
</html>
