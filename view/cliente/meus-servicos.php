<?php
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header('Location: ../auth/login.php');
    exit();
}

// Corrija o nome do arquivo para Servico.class.php
require_once __DIR__ . '/../../models/Servico.class.php';

$servico = new Servico();
$meus_servicos = $servico->getByCliente($_SESSION['cliente_id']);

// Inclua o menu do cliente para manter o padrão visual e navegação
require_once 'menu-cliente.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meus Serviços - Chama Serviço</title>
    
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
            transition: transform 0.2s;
        }
        
        .card:hover {
            transform: translateY(-2px);
        }

        /* Dashboard específico - manter gradiente */
        .card.bg-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
        }

        .bg-gradient .card-body {
            background: transparent !important;
        }
        
        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            white-space: nowrap;
            text-align: center;
            min-width: fit-content;
        }

        .btn-action {
            border-radius: 8px;
            transition: all 0.2s;
        }
        
        .btn-action:hover {
            transform: translateY(-1px);
        }

        .notification-dropdown {
            width: 300px;
        }

        .notification-item {
            cursor: pointer;
        }

        .notification-item:hover {
            background-color: #f1f1f1;
        }

        .opacity-75 {
            opacity: 0.75;
        }

        /* Estilos para diferentes visualizações */
        .vista-lista .servico-card {
            margin-bottom: 15px;
        }
        
        .vista-lista .card {
            border-radius: 8px;
        }
        
        .vista-lista .card-body {
            padding: 15px;
        }
        
        .vista-lista .row-servico {
            display: flex;
            align-items: center;
            padding: 15px;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            margin-bottom: 10px;
            background: white;
            transition: all 0.2s;
        }
        
        .vista-lista .row-servico:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-1px);
        }

        /* Ajustes para status badge na vista lista */
        .vista-lista .status-badge {
            font-size: 0.7em;
            padding: 4px 8px;
            display: inline-block;
            min-width: 80px;
        }

        /* Ajustes para vista timeline */
        .vista-timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .vista-timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #3498db, #2c3e50);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            border: 1px solid #e3e6f0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -37px;
            top: 20px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3498db;
            border: 3px solid white;
            box-shadow: 0 0 0 2px #3498db;
        }
        
        .timeline-date {
            background: linear-gradient(135deg, #3498db, #2c3e50);
            color: white;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
        }

        /* Melhorar responsividade dos templates */
        .template-lista .col-md-1 {
            flex: 0 0 auto;
            width: 120px;
        }
        
        .template-lista .col-md-4 {
            flex: 1;
        }
        
        .template-lista .col-md-3 {
            flex: 0 0 auto;
            width: 120px;
        }
        
        .template-lista .col-md-2 {
            flex: 0 0 auto;
            width: 100px;
            text-align: center;
        }

        #servicosContainer.vista-cards {
            display: flex;
            flex-wrap: wrap;
        }

        #servicosContainer.vista-lista .servico-card,
        #servicosContainer.vista-timeline .servico-card {
            width: 100%;
            max-width: none;
            flex: none;
        }

        /* Ajustes para cards menores em telas pequenas */
        @media (max-width: 768px) {
            .template-lista .row-servico {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .template-lista .col-md-1,
            .template-lista .col-md-2,
            .template-lista .col-md-3,
            .template-lista .col-md-4 {
                width: 100%;
                flex: none;
            }
            
            .status-badge {
                font-size: 0.75em;
                padding: 3px 8px;
            }
        }

        /* Remover estilos duplicados que estavam causando o problema */
        /* Navbar já definida acima */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1030;
        }

        /* Títulos e textos */
        h2 {
            font-size: 1.75rem;
            font-weight: 500;
            color: var(--primary-color);
        }
        
        h5 {
            font-size: 1.25rem;
            font-weight: 500;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .text-success {
            color: #28a745 !important;
        }
        
        .text-danger {
            color: #dc3545 !important;
        }
        
        /* Botões */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #1a2538;
            border-color: #1a2538;
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            color: white;
        }
        
        /* Cards - não sobrescrever o dashboard */
        .card:not(.bg-gradient) {
            border: 1px solid #e3e6f0;
        }
        
        .card-header {
            background-color: #f7f9fc;
            border-bottom: 1px solid #e3e6f0;
        }
        
        .card-title {
            margin-bottom: 0;
        }
        
        /* Outros */
        .alert {
            border-radius: 8px;
        }
        
        .badge {
            border-radius: 12px;
        }
    </style>
</head>
<body>
    <!-- Remova ou comente o bloco de navbar antigo abaixo para evitar duplicidade -->
    <!--
    <nav class="navbar navbar-expand-lg navbar-dark">
        ...navbar antigo...
    </nav>
    -->
    <!-- O menu será exibido pelo require_once acima -->

    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-list me-2"></i>
                Meus Serviços
            </h2>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#novoServicoModal">
                <i class="fas fa-plus me-1"></i>
                Novo Serviço
            </button>
        </div>

        <!-- Dashboard de Estatísticas -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card bg-gradient">
                    <div class="card-body text-white">
                        <div class="row">
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-chart-line fa-2x opacity-75"></i>
                                </div>
                                <h4 class="fw-bold"><?php echo count($meus_servicos); ?></h4>
                                <small>Total de Serviços</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-clock fa-2x opacity-75"></i>
                                </div>
                                <h4 class="fw-bold">
                                    <?php echo count(array_filter($meus_servicos, fn($s) => in_array($s['status_id'], [1,2,3,4]))); ?>
                                </h4>
                                <small>Em Andamento</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-handshake fa-2x opacity-75"></i>
                                </div>
                                <h4 class="fw-bold">15</h4>
                                <small>Propostas Recebidas</small>
                            </div>
                            <div class="col-md-3 text-center">
                                <div class="mb-2">
                                    <i class="fas fa-star fa-2x opacity-75"></i>
                                </div>
                                <h4 class="fw-bold">4.8</h4>
                                <small>Avaliação Média</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros Avançados -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label for="filtroStatus" class="form-label">Status</label>
                        <select class="form-select" id="filtroStatus">
                            <option value="">Todos</option>
                            <option value="1">🟡 Aguardando Propostas</option>
                            <option value="2">🔵 Em Análise</option>
                            <option value="3">🟢 Proposta Aceita</option>
                            <option value="4">🔄 Em Andamento</option>
                            <option value="5">✅ Concluído</option>
                            <option value="6">❌ Cancelado</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filtroTipo" class="form-label">Tipo de Serviço</label>
                        <select class="form-select" id="filtroTipo">
                            <option value="">Todos os tipos</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filtroDataInicio" class="form-label">Data Início</label>
                        <input type="date" class="form-control" id="filtroDataInicio">
                    </div>
                    <div class="col-md-3">
                        <label for="filtroDataFim" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="filtroDataFim">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <input type="text" class="form-control" id="buscarServico" 
                               placeholder="🔍 Buscar por título, descrição ou endereço...">
                    </div>
                    <div class="col-md-6">
                        <div class="btn-group w-100">
                            <button type="button" class="btn btn-outline-primary" onclick="aplicarFiltros()">
                                <i class="fas fa-filter me-1"></i>
                                Filtrar
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="limparFiltros()">
                                <i class="fas fa-times me-1"></i>
                                Limpar
                            </button>
                            <button type="button" class="btn btn-outline-success" onclick="exportarDados()">
                                <i class="fas fa-download me-1"></i>
                                Exportar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vista de Cards Melhorada -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="btn-group btn-group-sm" id="vistaControls">
                <button class="btn btn-primary" id="btn-cards" onclick="mudarVista('cards')">
                    <i class="fas fa-th-large"></i> Cards
                </button>
                <button class="btn btn-outline-primary" id="btn-lista" onclick="mudarVista('lista')">
                    <i class="fas fa-list"></i> Lista
                </button>
                <button class="btn btn-outline-primary" id="btn-timeline" onclick="mudarVista('timeline')">
                    <i class="fas fa-clock"></i> Timeline
                </button>
            </div>
            
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle" data-bs-toggle="dropdown">
                    <i class="fas fa-sort me-1"></i>
                    Ordenar por
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="ordenarServicos('data', 'desc')">Mais Recente</a></li>
                    <li><a class="dropdown-item" href="#" onclick="ordenarServicos('data', 'asc')">Mais Antigo</a></li>
                    <li><a class="dropdown-item" href="#" onclick="ordenarServicos('valor', 'desc')">Maior Valor</a></li>
                    <li><a class="dropdown-item" href="#" onclick="ordenarServicos('status', 'asc')">Por Status</a></li>
                </ul>
            </div>
        </div>

        <?php if (empty($meus_servicos)): ?>
            <div class="card text-center">
                <div class="card-body py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Nenhum serviço encontrado</h4>
                    <p class="text-muted">Você ainda não solicitou nenhum serviço.</p>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#novoServicoModal">
                        <i class="fas fa-plus me-1"></i>
                        Solicitar Primeiro Serviço
                    </button>
                </div>
            </div>
        <?php else: ?>
            <div class="row vista-cards" id="servicosContainer">
                <?php foreach ($meus_servicos as $item): ?>
                    <div class="col-md-6 col-lg-4 mb-4 servico-card" 
                         data-status="<?php echo $item['status_id']; ?>" 
                         data-tipo="<?php echo $item['tipo_servico_id']; ?>"
                         data-titulo="<?php echo strtolower($item['titulo']); ?>"
                         data-descricao="<?php echo strtolower($item['descricao']); ?>"
                         data-data="<?php echo strtotime($item['data_solicitacao']); ?>"
                         data-valor="<?php echo $item['orcamento_estimado'] ?? 0; ?>">
                        
                        <!-- Template para Vista Cards -->
                        <div class="card h-100 template-card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h6 class="card-title mb-0"><?php echo htmlspecialchars($item['titulo']); ?></h6>
                                    <span class="status-badge" style="background-color: <?php echo $item['status_cor']; ?>; color: white;">
                                        <?php echo htmlspecialchars($item['status_texto']); ?>
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
                                
                                <div class="d-flex justify-content-between align-items-center text-muted small">
                                    <span>
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($item['data_solicitacao'])); ?>
                                    </span>
                                    <?php if ($item['orcamento_estimado']): ?>
                                        <span>
                                            <i class="fas fa-dollar-sign me-1"></i>
                                            R$ <?php echo number_format($item['orcamento_estimado'], 2, ',', '.'); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="mt-3">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?php echo htmlspecialchars($item['logradouro'] . ', ' . $item['numero'] . ' - ' . $item['bairro']); ?>
                                    </small>
                                </div>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-grid gap-2 d-md-flex">
                                    <button class="btn btn-outline-primary btn-sm flex-fill" 
                                            onclick="verDetalhes(<?php echo $item['id']; ?>)">
                                        <i class="fas fa-eye me-1"></i>
                                        Detalhes
                                    </button>
                                    
                                    <?php if ($item['status_id'] == 1): ?>
                                        <button class="btn btn-outline-secondary btn-sm btn-action"
                                                onclick="editarServico(<?php echo $item['id']; ?>)">
                                            <i class="fas fa-edit me-1"></i>
                                            Editar
                                        </button>
                                        <button class="btn btn-outline-danger btn-sm btn-action"
                                                onclick="cancelarServico(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['titulo']); ?>')"
                                                title="Cancelar solicitação">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar
                                        </button>
                                    <?php elseif ($item['status_id'] == 2): ?>
                                        <button class="btn btn-outline-danger btn-sm btn-action"
                                                onclick="cancelarServico(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['titulo']); ?>')"
                                                title="Cancelar solicitação">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar
                                        </button>
                                    <?php elseif ($item['status_id'] == 5): ?>
                                        <button class="btn btn-outline-warning btn-sm btn-action"
                                                onclick="avaliarServico(<?php echo $item['id']; ?>)"
                                                title="Avaliar serviço concluído">
                                            <i class="fas fa-star me-1"></i>
                                            Avaliar
                                        </button>
                                    <?php elseif ($item['status_id'] == 3 || $item['status_id'] == 4): ?>
                                        <small class="text-muted">Serviço em execução</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Template para Vista Lista (oculto inicialmente) -->
                        <div class="row-servico template-lista" style="display: none;">
                            <div class="col-md-1 d-flex justify-content-center">
                                <span class="status-badge" style="background-color: <?php echo $item['status_cor']; ?>; color: white;">
                                    <?php 
                                    // Versões curtas para a vista lista
                                    $status_curto = [
                                        'Aguardando Propostas' => 'Aguardando',
                                        'Em Análise' => 'Análise',
                                        'Proposta Aceita' => 'Aceita',
                                        'Em Andamento' => 'Andamento',
                                        'Concluído' => 'Concluído',
                                        'Cancelado' => 'Cancelado'
                                    ];
                                    echo $status_curto[$item['status_texto']] ?? $item['status_texto'];
                                    ?>
                                </span>
                            </div>
                            <div class="col-md-4">
                                <h6 class="mb-1"><?php echo htmlspecialchars($item['titulo']); ?></h6>
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i>
                                    <?php echo htmlspecialchars($item['tipo_servico']); ?>
                                </small>
                            </div>
                            <div class="col-md-3 text-center">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    <?php echo date('d/m/Y', strtotime($item['data_solicitacao'])); ?>
                                </small>
                            </div>
                            <div class="col-md-2 text-center">
                                <?php if ($item['orcamento_estimado']): ?>
                                    <strong class="text-success">
                                        R$ <?php echo number_format($item['orcamento_estimado'], 2, ',', '.'); ?>
                                    </strong>
                                <?php else: ?>
                                    <span class="text-muted">-</span>
                                <?php endif; ?>
                            </div>
                            <div class="col-md-2 text-center">
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary btn-sm" onclick="verDetalhes(<?php echo $item['id']; ?>)" title="Ver detalhes">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if ($item['status_id'] == 1 || $item['status_id'] == 2): ?>
                                        <button class="btn btn-outline-danger btn-sm" 
                                                onclick="cancelarServico(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['titulo']); ?>')"
                                                title="Cancelar">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Template para Vista Timeline (oculto inicialmente) -->
                        <div class="timeline-item template-timeline" style="display: none;">
                            <div class="timeline-date">
                                <?php echo date('d/m/Y H:i', strtotime($item['data_solicitacao'])); ?>
                            </div>
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <h5 class="mb-0 flex-grow-1"><?php echo htmlspecialchars($item['titulo']); ?></h5>
                                <span class="status-badge ms-3" style="background-color: <?php echo $item['status_cor']; ?>; color: white;">
                                    <?php echo htmlspecialchars($item['status_texto']); ?>
                                </span>
                            </div>
                            <p class="text-muted mb-2">
                                <i class="fas fa-tag me-1"></i>
                                <?php echo htmlspecialchars($item['tipo_servico']); ?>
                            </p>
                            <p class="mb-3"><?php echo htmlspecialchars($item['descricao']); ?></p>
                            <div class="row align-items-center">
                                <div class="col-md-8">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?php echo htmlspecialchars($item['logradouro'] . ', ' . $item['numero'] . ' - ' . $item['bairro']); ?>
                                    </small>
                                </div>
                                <div class="col-md-4 text-end">
                                    <?php if ($item['orcamento_estimado']): ?>
                                        <strong class="text-success">
                                            <i class="fas fa-dollar-sign me-1"></i>
                                            R$ <?php echo number_format($item['orcamento_estimado'], 2, ',', '.'); ?>
                                        </strong>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="mt-3 d-flex gap-2">
                                <button class="btn btn-outline-primary btn-sm" onclick="verDetalhes(<?php echo $item['id']; ?>)">
                                    <i class="fas fa-eye me-1"></i>
                                    Ver Detalhes
                                </button>
                                <?php if ($item['status_id'] == 1 || $item['status_id'] == 2): ?>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="cancelarServico(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['titulo']); ?>')">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal Detalhes do Serviço -->
    <div class="modal fade" id="detalhesModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-info-circle me-2"></i>
                        Detalhes do Serviço
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="detalhesContent">
                    <!-- Conteúdo será carregado dinamicamente -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Novo Serviço (incluir o código do novo-servico.php aqui) -->
    <div class="modal fade" id="novoServicoModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>
                        Solicitar Novo Serviço
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="alertContainer"></div>
                    <p class="text-muted">Para solicitar um novo serviço, você será redirecionado para a página específica.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <a href="novo-servico.php" class="btn btn-primary">
                        <i class="fas fa-external-link-alt me-1"></i>
                        Ir para Solicitação
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Confirmar Cancelamento -->
    <div class="modal fade" id="cancelarModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Cancelar Solicitação
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="cancelarForm">
                    <div class="modal-body">
                        <input type="hidden" id="cancelarServicoId" name="servico_id">
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Atenção!</strong> Esta ação não pode ser desfeita.
                        </div>
                        
                        <p>Tem certeza que deseja cancelar a solicitação:</p>
                        <p class="fw-bold text-primary" id="tituloServicoCancelar"></p>
                        
                        <div class="mb-3">
                            <label for="motivoCancelamento" class="form-label">Motivo do cancelamento (opcional)</label>
                            <select class="form-select" id="motivoCancelamento" name="motivo">
                                <option value="">Selecione um motivo</option>
                                <option value="nao_preciso_mais">Não preciso mais do serviço</option>
                                <option value="mudar_detalhes">Quero refazer com detalhes diferentes</option>
                                <option value="muito_caro">Propostas muito caras</option>
                                <option value="demora_propostas">Demora para receber propostas</option>
                                <option value="outro">Outro motivo</option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="motivoOutroContainer" style="display: none;">
                            <label for="motivoOutro" class="form-label">Descreva o motivo</label>
                            <textarea class="form-control" id="motivoOutro" name="motivo_outro" rows="3" 
                                      placeholder="Explique o motivo do cancelamento..."></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>O que acontece ao cancelar:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Todas as propostas recebidas serão automaticamente recusadas</li>
                                <li>Os prestadores serão notificados sobre o cancelamento</li>
                                <li>Você poderá criar uma nova solicitação quando desejar</li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i>
                            Confirmar Cancelamento
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Filtros
            const filtroStatus = document.getElementById('filtroStatus');
            const filtroTipo = document.getElementById('filtroTipo');
            const buscarServico = document.getElementById('buscarServico');
            const servicosCards = document.querySelectorAll('.servico-card');

            // Aplicar filtros
            function aplicarFiltros() {
                const statusSelecionado = filtroStatus.value;
                const tipoSelecionado = filtroTipo.value;
                const termoBusca = buscarServico.value.toLowerCase();

                servicosCards.forEach(card => {
                    let mostrar = true;

                    // Filtro por status
                    if (statusSelecionado && card.dataset.status !== statusSelecionado) {
                        mostrar = false;
                    }

                    // Filtro por tipo de serviço
                    if (tipoSelecionado && card.dataset.tipo !== tipoSelecionado) {
                        mostrar = false;
                    }

                    // Busca por texto no título/descrição
                    if (termoBusca) {
                        const titulo = card.dataset.titulo;
                        const descricao = card.dataset.descricao;
                        if (!titulo.includes(termoBusca) && !descricao.includes(termoBusca)) {
                            mostrar = false;
                        }
                    }

                    card.style.display = mostrar ? 'block' : 'none';
                });
            }

            // Event listeners para filtros
            filtroStatus.addEventListener('change', aplicarFiltros);
            filtroTipo.addEventListener('change', aplicarFiltros);
            buscarServico.addEventListener('input', aplicarFiltros);

            // Carregar tipos de serviço para o filtro
            carregarTiposServico();
        });

        function carregarTiposServico() {
            const tipos = new Set();
            const servicosCards = document.querySelectorAll('.servico-card');
            
            servicosCards.forEach(card => {
                const tipoElement = card.querySelector('.fa-tag');
                if (tipoElement && tipoElement.nextElementSibling) {
                    const tipoTexto = tipoElement.nextElementSibling.textContent.trim();
                    tipos.add(tipoTexto);
                }
            });
            
            const filtroTipo = document.getElementById('filtroTipo');
            tipos.forEach(tipo => {
                const option = document.createElement('option');
                option.value = tipo.toLowerCase();
                option.textContent = tipo;
                filtroTipo.appendChild(option);
            });
        }

        function verDetalhes(servicoId) {
            // Carregar detalhes via AJAX
            fetch(`detalhes-servico.php?id=${servicoId}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('detalhesContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('detalhesModal')).show();
                })
                .catch(error => {
                    console.error('Erro ao carregar detalhes:', error);
                    alert('Erro ao carregar detalhes do serviço.');
                });
        }

        function editarServico(servicoId) {
            // Redirecionar para página de edição ou abrir modal
            window.location.href = `editar-servico.php?id=${servicoId}`;
        }

        function avaliarServico(servicoId) {
            // Redirecionar para página de avaliação
            window.location.href = `avaliar-servico.php?id=${servicoId}`;
        }

        // Sistema de Notificações
        let notificationInterval;

        function carregarNotificacoes() {
            fetch('../../api/notifications.php')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateNotificationBadge(data.total);
                        updateNotificationList(data.notificacoes);
                    }
                })
                .catch(error => console.error('Erro ao carregar notificações:', error));
        }

        function updateNotificationBadge(total) {
            const badge = document.getElementById('notificationBadge');
            if (total > 0) {
                badge.textContent = total > 99 ? '99+' : total;
                badge.style.display = 'block';
            } else {
                badge.style.display = 'none';
            }
        }

        function updateNotificationList(notificacoes) {
            const list = document.getElementById('notificationList');
            
            if (notificacoes.length === 0) {
                list.innerHTML = '<div class="dropdown-item-text text-center text-muted">Nenhuma notificação</div>';
                return;
            }

            let html = '';
            notificacoes.forEach(notif => {
                const timeAgo = moment(notif.data_criacao).fromNow();
                html += `
                    <div class="dropdown-item notification-item ${!notif.lida ? 'bg-light' : ''}" 
                         onclick="marcarComoLida(${notif.id}, '${notif.referencia_id || '#'}')">
                        <div class="d-flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-${getNotificationIcon(notif.tipo_notificacao)} text-primary"></i>
                            </div>
                            <div class="flex-grow-1 ms-2">
                                <div class="fw-bold small">${notif.titulo}</div>
                                <div class="text-muted small">${notif.mensagem}</div>
                                <div class="text-muted" style="font-size: 0.75em;">${timeAgo}</div>
                            </div>
                        </div>
                    </div>
                `;
            });
            list.innerHTML = html;
        }

        function getNotificationIcon(tipo) {
            const icons = {
                'nova_proposta': 'handshake',
                'proposta_aceita': 'check-circle',
                'proposta_recusada': 'times-circle',
                'contra_proposta': 'exchange-alt',
                'servico_concluido': 'flag-checkered'
            };
            return icons[tipo] || 'bell';
        }

        function marcarComoLida(notifId, referencia) {
            fetch('../../api/mark-notification-read.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({notification_id: notifId})
            })
            .then(() => {
                carregarNotificacoes();
                if (referencia && referencia !== '#') {
                    // Redirecionar para a referência se necessário
                    // window.location.href = `detalhes-servico.php?id=${referencia}`;
                }
            });
        }

        function marcarTodasLidas() {
            fetch('../../api/mark-all-notifications-read.php', {
                method: 'POST'
            })
            .then(() => carregarNotificacoes());
        }

        // Inicializar notificações
        document.addEventListener('DOMContentLoaded', function() {
            carregarNotificacoes();
            // Verificar notificações a cada 30 segundos
            notificationInterval = setInterval(carregarNotificacoes, 30000);
        });

        // Limpar interval quando sair da página
        window.addEventListener('beforeunload', function() {
            if (notificationInterval) {
                clearInterval(notificationInterval);
            }
        });

        function exportarDados() {
            const dados = Array.from(document.querySelectorAll('.servico-card:not([style*="none"])'))
                .map(card => ({
                    titulo: card.querySelector('.card-title').textContent,
                    status: card.querySelector('.status-badge').textContent,
                    data: card.querySelector('.fa-calendar').parentNode.textContent,
                    tipo: card.querySelector('.fa-tag').parentNode.textContent
                }));
            
            const csv = 'Título,Status,Data,Tipo\n' + 
                       dados.map(d => `"${d.titulo}","${d.status}","${d.data}","${d.tipo}"`).join('\n');
            
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'meus-servicos.csv';
            a.click();
        }
        
        function mudarVista(tipo) {
            const container = document.getElementById('servicosContainer');
            const cards = document.querySelectorAll('.servico-card');
            const buttons = document.querySelectorAll('#vistaControls .btn');
            
            // Atualizar botões ativos
            buttons.forEach(btn => {
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-outline-primary');
            });
            document.getElementById(`btn-${tipo}`).classList.remove('btn-outline-primary');
            document.getElementById(`btn-${tipo}`).classList.add('btn-primary');
            
            // Remover classes anteriores
            container.classList.remove('vista-cards', 'vista-lista', 'vista-timeline', 'row');
            
            cards.forEach(card => {
                // Ocultar todos os templates
                const templateCard = card.querySelector('.template-card');
                const templateLista = card.querySelector('.template-lista');
                const templateTimeline = card.querySelector('.template-timeline');
                
                templateCard.style.display = 'none';
                templateLista.style.display = 'none';
                templateTimeline.style.display = 'none';
                
                // Resetar classes da coluna
                card.className = 'mb-4 servico-card ' + card.className.split(' ').filter(c => c.startsWith('data-')).join(' ');
            });
            
            // Aplicar nova vista
            switch(tipo) {
                case 'cards':
                    container.classList.add('row', 'vista-cards');
                    cards.forEach(card => {
                        card.classList.add('col-md-6', 'col-lg-4');
                        card.querySelector('.template-card').style.display = 'block';
                    });
                    break;
                    
                case 'lista':
                    container.classList.add('vista-lista');
                    cards.forEach(card => {
                        card.classList.add('col-12');
                        card.querySelector('.template-lista').style.display = 'flex';
                    });
                    break;
                    
                case 'timeline':
                    container.classList.add('vista-timeline');
                    cards.forEach(card => {
                        card.classList.add('col-12');
                        card.querySelector('.template-timeline').style.display = 'block';
                    });
                    break;
            }
        }
        
        function ordenarServicos(criterio, ordem) {
            const container = document.getElementById('servicosContainer');
            const cards = Array.from(container.querySelectorAll('.servico-card'));
            
            cards.sort((a, b) => {
                let valorA, valorB;
                
                switch(criterio) {
                    case 'data':
                        valorA = parseInt(a.dataset.data);
                        valorB = parseInt(b.dataset.data);
                        break;
                    case 'valor':
                        valorA = parseFloat(a.dataset.valor) || 0;
                        valorB = parseFloat(b.dataset.valor) || 0;
                        break;
                    case 'status':
                        valorA = parseInt(a.dataset.status);
                        valorB = parseInt(b.dataset.status);
                        break;
                    default:
                        return 0;
                }
                
                return ordem === 'asc' ? valorA - valorB : valorB - valorA;
            });
            
            // Reordenar no DOM
            cards.forEach(card => container.appendChild(card));
            
            // Mostrar feedback
            const criterioTexto = {
                'data': 'data',
                'valor': 'valor',
                'status': 'status'
            };
            
            const ordemTexto = ordem === 'asc' ? 'crescente' : 'decrescente';
            
            // Toast de feedback
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-success border-0 position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-sort me-2"></i>
                        Serviços ordenados por ${criterioTexto[criterio]} (${ordemTexto})
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            document.body.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Remover toast após ser ocultado
            toast.addEventListener('hidden.bs.toast', () => {
                document.body.removeChild(toast);
            });
        }

        function limparFiltros() {
            document.getElementById('filtroStatus').value = '';
            document.getElementById('filtroTipo').value = '';
            document.getElementById('filtroDataInicio').value = '';
            document.getElementById('filtroDataFim').value = '';
            document.getElementById('buscarServico').value = '';
            
            // Mostrar todos os cards
            document.querySelectorAll('.servico-card').forEach(card => {
                card.style.display = 'block';
            });
            
            // Toast de feedback
            const toast = document.createElement('div');
            toast.className = 'toast align-items-center text-white bg-info border-0 position-fixed top-0 end-0 m-3';
            toast.style.zIndex = '9999';
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        <i class="fas fa-times me-2"></i>
                        Filtros limpos
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            document.body.appendChild(toast);
            
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            toast.addEventListener('hidden.bs.toast', () => {
                document.body.removeChild(toast);
            });
        }

        // Abrir modal de cancelamento com dados do serviço
        function cancelarServico(servicoId, titulo) {
            document.getElementById('cancelarServicoId').value = servicoId;
            document.getElementById('tituloServicoCancelar').textContent = titulo;
            document.getElementById('motivoCancelamento').value = '';
            document.getElementById('motivoOutroContainer').style.display = 'none';
            document.getElementById('motivoOutro').value = '';
            new bootstrap.Modal(document.getElementById('cancelarModal')).show();
        }

        // Mostrar campo "Outro motivo" se selecionado
        document.getElementById('motivoCancelamento').addEventListener('change', function() {
            if (this.value === 'outro') {
                document.getElementById('motivoOutroContainer').style.display = '';
            } else {
                document.getElementById('motivoOutroContainer').style.display = 'none';
                document.getElementById('motivoOutro').value = '';
            }
        });

        // Enviar cancelamento
        document.getElementById('cancelarForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', 'cancelar');

            // Mostrar loading
            const btnSubmit = this.querySelector('button[type="submit"]');
            const originalText = btnSubmit.innerHTML;
            btnSubmit.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Cancelando...';
            btnSubmit.disabled = true;

            fetch('gerenciar-servico.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    bootstrap.Modal.getOrCreateInstance(document.getElementById('cancelarModal')).hide();

                    // Mostrar mensagem de sucesso
                    const alertDiv = document.createElement('div');
                    alertDiv.className = 'alert alert-success alert-dismissible fade show';
                    alertDiv.innerHTML = `
                        <i class="fas fa-check-circle me-2"></i>
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    document.querySelector('.container').insertBefore(alertDiv, document.querySelector('.container').firstChild);

                    // Recarregar página após 2 segundos
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                } else {
                    alert('Erro: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erro:', error);
                alert('Erro de conexão. Tente novamente.');
            })
            .finally(() => {
                // Restaurar botão
                btnSubmit.innerHTML = originalText;
                btnSubmit.disabled = false;
            });
        });
    </script>
    
    <!-- Moment.js para formatação de datas -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/locale/pt-br.min.js"></script>
</body>
<footer class="text-center text-muted py-3 mt-4">
    &copy; <?php echo date('Y'); ?> Chama Serviço. Todos os direitos reservados.
</footer>
</html>
   