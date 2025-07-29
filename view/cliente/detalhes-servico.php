<?php
session_start();

if (!isset($_SESSION['cliente_id'])) {
    http_response_code(403);
    echo '<div class="alert alert-danger">Acesso não autorizado</div>';
    exit();
}

if (!isset($_GET['id'])) {
    echo '<div class="alert alert-danger">ID do serviço não informado</div>';
    exit();
}

require_once __DIR__ . '/../../models/Servico.class.php';
require_once __DIR__ . '/../../models/Proposta.class.php';
require_once __DIR__ . '/../../models/Prestador.class.php';

$servico = new Servico();
$proposta = new Proposta();
$prestador = new Prestador();

$servico_id = $_GET['id'];
$detalhes = $servico->getDetalhes($servico_id, $_SESSION['cliente_id']);

if (!$detalhes) {
    echo '<div class="alert alert-danger">Serviço não encontrado</div>';
    exit();
}

$propostas = $proposta->getByServico($servico_id);
$imagens = $servico->getImagensServico($servico_id);
?>

<style>
.detalhes-container {
    padding: 0;
}

.info-section {
    background: #ffffff;
    border-radius: 12px;
    padding: 25px;
    margin-bottom: 20px;
    border: 1px solid #e3e6f0;
}

.status-card {
    background: linear-gradient(135deg, #3498db, #5dade2);
    border-radius: 12px;
    border: none;
    height: fit-content;
}

.titulo-servico {
    color: #3498db;
    font-weight: 600;
    font-size: 1.2em;
    margin-top: 5px;
}

.campo-info {
    margin-bottom: 20px;
}

.label-campo {
    font-weight: 600;
    color: #2c3e50;
    margin-bottom: 5px;
    font-size: 0.95em;
}

.valor-campo {
    margin-top: 5px;
}

.urgencia-badge {
    padding: 6px 16px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85em;
    display: inline-block;
}

.urgencia-alta {
    background-color: #dc3545;
    color: white;
}

.urgencia-media {
    background-color: #ffc107;
    color: #212529;
}

.urgencia-baixa {
    background-color: #17a2b8;
    color: white;
}

.endereco-info {
    background: #f8f9fa;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 15px;
    margin-top: 8px;
}

.data-highlight {
    color: #3498db;
    font-weight: 600;
}

.valor-destaque {
    color: #27ae60;
    font-weight: 700;
    font-size: 1.1em;
}

.tipo-badge {
    background-color: #6c757d;
    color: white;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 0.85em;
    font-weight: 500;
}

.row-info {
    margin-top: 10px;
}

.col-info {
    padding-right: 25px;
}

.status-content {
    text-align: center;
    color: white;
    padding: 20px;
}

.status-badge-main {
    background-color: rgba(255,255,255,0.2);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-weight: 600;
    margin: 10px 0;
    display: inline-block;
}

.status-description {
    font-size: 0.9em;
    opacity: 0.9;
    margin-top: 10px;
}

.galeria-fotos {
    margin-top: 15px;
}

.foto-servico {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
    border: 1px solid #dee2e6;
    margin: 5px;
    cursor: pointer;
    transition: transform 0.2s;
}

.foto-servico:hover {
    transform: scale(1.05);
}

.proposta-card {
    border: 1px solid #e9ecef;
    border-radius: 12px;
    margin-bottom: 20px;
    transition: all 0.3s;
}

.proposta-card:hover {
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
    transform: translateY(-2px);
}

.proposta-header {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 12px 12px 0 0;
    padding: 15px;
}

.prestador-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3498db, #5dade2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}

.valor-destaque {
    color: #27ae60;
    font-size: 1.4em;
    font-weight: 700;
}

.btn-action-group {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
}

.btn-action-group .btn {
    border-radius: 8px;
    font-weight: 500;
    padding: 8px 16px;
}

.rating-stars {
    color: #ffc107;
}

.proposta-status {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.8em;
    font-weight: 600;
}

.status-pendente { background-color: #fff3cd; color: #856404; }
.status-aceita { background-color: #d1edff; color: #0c5460; }
.status-recusada { background-color: #f8d7da; color: #721c24; }

/* Novos estilos para melhorias */
.acao-rapida {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 15px;
    border-left: 4px solid #3498db;
}

.comparador-propostas {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin: 20px 0;
}

.mapa-container {
    height: 200px;
    border-radius: 8px;
    overflow: hidden;
}

.timeline-atividade {
    border-left: 3px solid #3498db;
    padding-left: 20px;
    margin-bottom: 15px;
    position: relative;
}

.timeline-atividade::before {
    content: '';
    position: absolute;
    left: -7px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #3498db;
}

.estatisticas-servico {
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
}

.progress-servico {
    height: 25px;
    border-radius: 15px;
    margin: 10px 0;
}

.documentos-anexos {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 20px;
    text-align: center;
    margin: 15px 0;
}
</style>

<div class="detalhes-container">
    <!-- Ações Rápidas -->
    <div class="acao-rapida">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h6 class="mb-1"><i class="fas fa-bolt text-warning me-2"></i>Ações Rápidas</h6>
                <small class="text-muted">Gerencie seu serviço de forma eficiente</small>
            </div>
            <div class="col-md-4">
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-primary btn-sm" onclick="compartilharServico()">
                        <i class="fas fa-share-alt me-1"></i>
                        Compartilhar
                    </button>
                    <button class="btn btn-outline-success btn-sm" onclick="duplicarServico()">
                        <i class="fas fa-copy me-1"></i>
                        Duplicar
                    </button>
                    <button class="btn btn-outline-info btn-sm" onclick="exportarPDF()">
                        <i class="fas fa-file-pdf me-1"></i>
                        PDF
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Progresso do Serviço -->
    <?php if ($detalhes['status_id'] >= 3): ?>
    <div class="estatisticas-servico">
        <h6><i class="fas fa-chart-line me-2"></i>Progresso do Serviço</h6>
        <div class="progress progress-servico">
            <div class="progress-bar" style="width: <?php echo min(($detalhes['status_id'] / 5) * 100, 100); ?>%">
                <?php echo min(($detalhes['status_id'] / 5) * 100, 100); ?>%
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-4 text-center">
                <div class="fw-bold"><?php echo count($propostas); ?></div>
                <small>Propostas</small>
            </div>
            <div class="col-4 text-center">
                <div class="fw-bold"><?php echo count($imagens); ?></div>
                <small>Fotos</small>
            </div>
            <div class="col-4 text-center">
                <div class="fw-bold">
                    <?php 
                    $dias_desde_criacao = ceil((time() - strtotime($detalhes['data_solicitacao'])) / (60 * 60 * 24));
                    echo $dias_desde_criacao;
                    ?>
                </div>
                <small>Dias</small>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-8">
            <!-- Tabs de Informações -->
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs" id="infoTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="detalhes-tab" data-bs-toggle="tab" data-bs-target="#detalhes-content">
                                <i class="fas fa-info-circle me-1"></i>Detalhes
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="localizacao-tab" data-bs-toggle="tab" data-bs-target="#localizacao-content">
                                <i class="fas fa-map-marker-alt me-1"></i>Localização
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="timeline-tab" data-bs-toggle="tab" data-bs-target="#timeline-content">
                                <i class="fas fa-history me-1"></i>Timeline
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="documentos-tab" data-bs-toggle="tab" data-bs-target="#documentos-content">
                                <i class="fas fa-paperclip me-1"></i>Anexos
                            </button>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="tab-content" id="infoTabsContent">
                        <!-- Aba Detalhes -->
                        <div class="tab-pane fade show active" id="detalhes-content">
                            <div class="info-section">
                                <h6 class="fw-bold text-primary mb-4">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informações do Serviço
                                </h6>
                                
                                <div class="campo-info">
                                    <div class="label-campo">Título:</div>
                                    <div class="valor-campo titulo-servico"><?php echo htmlspecialchars($detalhes['titulo']); ?></div>
                                </div>
                                
                                <div class="campo-info">
                                    <div class="label-campo">Tipo de Serviço:</div>
                                    <div class="valor-campo">
                                        <span class="tipo-badge"><?php echo htmlspecialchars($detalhes['tipo_servico']); ?></span>
                                    </div>
                                </div>
                                
                                <div class="campo-info">
                                    <div class="label-campo">Descrição:</div>
                                    <div class="valor-campo text-muted"><?php echo nl2br(htmlspecialchars($detalhes['descricao'])); ?></div>
                                </div>
                                
                                <div class="campo-info">
                                    <div class="label-campo">Endereço:</div>
                                    <div class="endereco-info">
                                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                                        <?php echo htmlspecialchars($detalhes['endereco_completo']); ?>
                                    </div>
                                </div>
                                
                                <div class="row row-info">
                                    <div class="col-md-6 col-info">
                                        <div class="campo-info">
                                            <div class="label-campo">Data da Solicitação:</div>
                                            <div class="valor-campo data-highlight">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo date('d/m/Y H:i', strtotime($detalhes['data_solicitacao'])); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="campo-info">
                                            <div class="label-campo">Urgência:</div>
                                            <div class="valor-campo">
                                                <span class="urgencia-badge urgencia-<?php echo $detalhes['urgencia']; ?>">
                                                    <?php echo ucfirst($detalhes['urgencia']); ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <?php if ($detalhes['data_atendimento']): ?>
                                <div class="campo-info">
                                    <div class="label-campo">Data/Horário Desejados:</div>
                                    <div class="valor-campo data-highlight">
                                        <i class="fas fa-clock me-1"></i>
                                        <?php echo date('d/m/Y \à\s H:i', strtotime($detalhes['data_atendimento'])); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($detalhes['orcamento_estimado']): ?>
                                <div class="campo-info">
                                    <div class="label-campo">Orçamento Estimado:</div>
                                    <div class="valor-campo valor-destaque">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        R$ <?php echo number_format($detalhes['orcamento_estimado'], 2, ',', '.'); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Aba Localização -->
                        <div class="tab-pane fade" id="localizacao-content">
                            <div class="mb-3">
                                <h6><i class="fas fa-map-marker-alt me-2 text-danger"></i>Endereço Completo</h6>
                                <div class="bg-light p-3 rounded">
                                    <?php echo htmlspecialchars($detalhes['endereco_completo']); ?>
                                </div>
                            </div>
                            
                            <div class="mapa-container bg-light d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-2"></i>
                                    <p class="text-muted">Mapa integrado<br>Recurso em desenvolvimento</p>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <button class="btn btn-outline-primary btn-sm me-2" onclick="copiarEndereco()">
                                    <i class="fas fa-copy me-1"></i>Copiar Endereço
                                </button>
                                <button class="btn btn-outline-success btn-sm" onclick="abrirMaps()">
                                    <i class="fas fa-route me-1"></i>Ver no Google Maps
                                </button>
                            </div>
                        </div>

                        <!-- Aba Timeline -->
                        <div class="tab-pane fade" id="timeline-content">
                            <h6><i class="fas fa-history me-2 text-primary"></i>Histórico de Atividades</h6>
                            
                            <!-- Timeline baseado nos dados existentes -->
                            <div class="timeline-atividade">
                                <div class="d-flex justify-content-between">
                                    <strong>Serviço Solicitado</strong>
                                    <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($detalhes['data_solicitacao'])); ?></small>
                                </div>
                                <div class="text-muted">Solicitação criada e publicada para prestadores</div>
                            </div>
                            
                            <?php if (!empty($propostas)): ?>
                            <div class="timeline-atividade">
                                <div class="d-flex justify-content-between">
                                    <strong>Propostas Recebidas</strong>
                                    <small class="text-muted"><?php echo date('d/m/Y', strtotime($propostas[0]['data_proposta'])); ?></small>
                                </div>
                                <div class="text-muted"><?php echo count($propostas); ?> proposta(s) foram enviadas por prestadores</div>
                            </div>
                            <?php endif; ?>
                            
                            <?php 
                            $proposta_aceita = array_filter($propostas, fn($p) => $p['status'] === 'aceita');
                            if (!empty($proposta_aceita)): 
                                $aceita = reset($proposta_aceita);
                            ?>
                            <div class="timeline-atividade">
                                <div class="d-flex justify-content-between">
                                    <strong>Proposta Aceita</strong>
                                    <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($aceita['data_proposta'])); ?></small>
                                </div>
                                <div class="text-muted">Proposta de <?php echo htmlspecialchars($aceita['prestador_nome']); ?> foi aceita</div>
                            </div>
                            <?php endif; ?>
                        </div>

                        <!-- Aba Documentos -->
                        <div class="tab-pane fade" id="documentos-content">
                            <h6><i class="fas fa-paperclip me-2 text-primary"></i>Documentos e Anexos</h6>
                            
                            <?php if (!empty($imagens)): ?>
                            <div class="mb-4">
                                <h6>Fotos do Serviço</h6>
                                <div class="galeria-fotos">
                                    <?php foreach ($imagens as $imagem): ?>
                                        <img src="../../uploads/servicos/<?php echo htmlspecialchars($imagem['caminho_imagem']); ?>" 
                                             class="foto-servico" 
                                             alt="Foto do serviço"
                                             onclick="abrirModalImagem(this.src)">
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="documentos-anexos">
                                <i class="fas fa-upload fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-2">Adicione documentos complementares</p>
                                <button class="btn btn-outline-primary" onclick="adicionarDocumento()">
                                    <i class="fas fa-plus me-1"></i>Adicionar Documento
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card status-card">
                <div class="card-body status-content">
                    <h6 class="fw-bold mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        Status do Serviço
                    </h6>
                    <div class="status-badge-main">
                        <?php echo htmlspecialchars($detalhes['status_texto']); ?>
                    </div>
                    
                    <div class="status-description">
                        <?php if ($detalhes['status_id'] == 1): ?>
                            <i class="fas fa-clock me-1"></i>
                            Aguardando propostas de prestadores
                        <?php elseif ($detalhes['status_id'] == 2): ?>
                            <i class="fas fa-search me-1"></i>
                            Analisando propostas recebidas
                        <?php elseif ($detalhes['status_id'] == 3): ?>
                            <i class="fas fa-handshake me-1"></i>
                            Proposta aceita, aguardando início
                        <?php elseif ($detalhes['status_id'] == 4): ?>
                            <i class="fas fa-tools me-1"></i>
                            Serviço em execução
                        <?php elseif ($detalhes['status_id'] == 5): ?>
                            <i class="fas fa-check-circle me-1"></i>
                            Serviço concluído com sucesso
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Chat Rápido -->
            <?php if ($detalhes['status_id'] >= 3): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-comments me-2"></i>Comunicação</h6>
                </div>
                <div class="card-body">
                    <div class="chat-preview">
                        <div class="text-center text-muted">
                            <i class="fas fa-comment-dots fa-2x mb-2"></i>
                            <p class="small">Inicie uma conversa com seu prestador</p>
                        </div>
                    </div>
                    <div class="mt-2">
                        <div class="input-group">
                            <input type="text" class="form-control form-control-sm" placeholder="Digite sua mensagem...">
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-paper-plane"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Prestadores Recomendados -->
            <?php if ($detalhes['status_id'] == 1 && empty($propostas)): ?>
            <div class="card mt-3">
                <div class="card-header">
                    <h6 class="mb-0"><i class="fas fa-user-plus me-2"></i>Prestadores Recomendados</h6>
                </div>
                <div class="card-body">
                    <?php 
                    // Troque $prestador_model por $prestador
                    $prestadores_recomendados = $prestador->getRecomendadosPorTipo($detalhes['tipo_servico_id'], 3);
                    foreach ($prestadores_recomendados as $prestador_rec): 
                    ?>
                    <div class="prestador-card-mini">
                        <div class="d-flex align-items-center">
                            <div class="prestador-avatar me-2" style="width: 30px; height: 30px; font-size: 0.8em;">
                                <?php echo strtoupper(substr($prestador_rec['nome'], 0, 2)); ?>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-bold small"><?php echo htmlspecialchars($prestador_rec['nome']); ?></div>
                                <div class="text-muted" style="font-size: 0.75em;">
                                    <i class="fas fa-star text-warning"></i> <?php echo $prestador_rec['avaliacao'] ?? '5.0'; ?>
                                    (<?php echo $prestador_rec['total_avaliacoes'] ?? '0'; ?>)
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" onclick="convidarPrestador(<?php echo $prestador_rec['id']; ?>)">
                                Convidar
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Comparador de Propostas -->
<?php if (count($propostas) > 1): ?>
<div class="comparador-propostas">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0"><i class="fas fa-balance-scale me-2"></i>Comparador de Propostas</h6>
        <button class="btn btn-outline-primary btn-sm" onclick="toggleComparador()">
            <i class="fas fa-eye me-1"></i>Comparar
        </button>
    </div>
    
    <div id="comparadorContent" style="display: none;">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Prestador</th>
                        <th>Valor</th>
                        <th>Prazo</th>
                        <th>Avaliação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($propostas as $prop): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($prop['prestador_nome']); ?></td>
                        <td class="fw-bold text-success">R$ <?php echo number_format($prop['valor'], 2, ',', '.'); ?></td>
                        <td><?php echo $prop['prazo_execucao']; ?> dias</td>
                        <td><i class="fas fa-star text-warning"></i> 4.8</td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="aceitarProposta(<?php echo $prop['id']; ?>)">
                                Aceitar
                            </button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (!empty($propostas)): ?>
<hr class="my-4">
<div class="d-flex justify-content-between align-items-center mb-3">
    <h6 class="fw-bold text-primary mb-0">
        <i class="fas fa-handshake me-2"></i>
        Propostas Recebidas (<?php echo count($propostas); ?>)
    </h6>
    <div class="dropdown">
        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
            <i class="fas fa-sort me-1"></i>
            Ordenar
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#" onclick="ordenarPropostas('valor', 'desc')">Maior Valor</a></li>
            <li><a class="dropdown-item" href="#" onclick="ordenarPropostas('valor', 'asc')">Menor Valor</a></li>
            <li><a class="dropdown-item" href="#" onclick="ordenarPropostas('prazo', 'asc')">Menor Prazo</a></li>
            <li><a class="dropdown-item" href="#" onclick="ordenarPropostas('data', 'desc')">Mais Recente</a></li>
        </ul>
    </div>
</div>

<div class="row" id="propostas-container">
    <?php foreach ($propostas as $prop): ?>
    <div class="col-md-12 mb-3 proposta-item" 
         data-valor="<?php echo $prop['valor']; ?>" 
         data-prazo="<?php echo $prop['prazo_execucao']; ?>"
         data-data="<?php echo strtotime($prop['data_proposta']); ?>">
        <div class="proposta-card">
            <div class="proposta-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="prestador-avatar me-3">
                            <?php echo strtoupper(substr($prop['prestador_nome'], 0, 2)); ?>
                        </div>
                        <div>
                            <h6 class="mb-1">
                                <?php echo htmlspecialchars($prop['prestador_nome']); ?>
                                <i class="fas fa-star rating-stars ms-2"></i>
                                <span class="text-muted small">4.8 (12 avaliações)</span>
                            </h6>
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                Enviada em <?php echo date('d/m/Y H:i', strtotime($prop['data_proposta'])); ?>
                                <span class="ms-3">
                                    <i class="fas fa-clock me-1"></i>
                                    Prestador desde 2022
                                </span>
                            </small>
                        </div>
                    </div>
                    <span class="proposta-status status-<?php echo $prop['status']; ?>">
                        <?php echo ucfirst($prop['status']); ?>
                    </span>
                </div>
            </div>
            
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="mb-2">
                                <i class="fas fa-dollar-sign text-success fa-2x"></i>
                            </div>
                            <div class="valor-destaque">
                                R$ <?php echo number_format($prop['valor'], 2, ',', '.'); ?>
                            </div>
                            <small class="text-muted">Valor Proposto</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="mb-2">
                                <i class="fas fa-clock text-info fa-2x"></i>
                            </div>
                            <div class="fs-5 fw-bold">
                                <?php echo $prop['prazo_execucao']; ?> dia(s)
                            </div>
                            <small class="text-muted">Prazo de Execução</small>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="text-center p-3 bg-light rounded">
                            <div class="mb-2">
                                <i class="fas fa-tools text-warning fa-2x"></i>
                            </div>
                            <div class="fs-6 fw-bold">
                                15+ Serviços
                            </div>
                            <small class="text-muted">Concluídos</small>
                        </div>
                    </div>
                </div>
                
                <?php if ($prop['descricao']): ?>
                <div class="mt-3">
                    <strong class="text-primary">💬 Proposta Detalhada:</strong>
                    <div class="bg-light p-3 rounded mt-2">
                        <?php echo nl2br(htmlspecialchars($prop['descricao'])); ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <!-- Histórico de Negociação -->
                <?php 
                $historico = $proposta->getHistoricoNegociacao($prop['id']);
                if (!empty($historico)): 
                ?>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong class="text-info">📋 Histórico de Negociação</strong>
                        <button class="btn btn-link btn-sm p-0" onclick="toggleHistorico(<?php echo $prop['id']; ?>)">
                            <i class="fas fa-chevron-down" id="toggle-<?php echo $prop['id']; ?>"></i>
                        </button>
                    </div>
                    <div class="collapse" id="historico-<?php echo $prop['id']; ?>">
                        <div class="mt-2">
                            <?php foreach ($historico as $nego): ?>
                            <div class="border-start border-3 border-<?php echo $nego['tipo'] == 'contra_proposta' ? 'warning' : 'info'; ?> ps-3 mb-2">
                                <div class="d-flex justify-content-between">
                                    <strong>
                                        <?php if ($nego['tipo'] == 'contra_proposta'): ?>
                                            🔄 Sua Contra-proposta
                                        <?php else: ?>
                                            💬 Resposta do Prestador
                                        <?php endif; ?>
                                    </strong>
                                    <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($nego['data_negociacao'])); ?></small>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">Valor: R$ <?php echo number_format($nego['valor'], 2, ',', '.'); ?></div>
                                    <div class="col-md-6">Prazo: <?php echo $nego['prazo']; ?> dia(s)</div>
                                </div>
                                <?php if ($nego['observacoes']): ?>
                                <div class="text-muted small mt-1">"<?php echo htmlspecialchars($nego['observacoes']); ?>"</div>
                                <?php endif; ?>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            
            <?php if ($prop['status'] == 'pendente' && $detalhes['status_id'] <= 2): ?>
            <div class="card-footer bg-transparent border-0">
                <div class="btn-action-group">
                    <button class="btn btn-success" onclick="aceitarProposta(<?php echo $prop['id']; ?>)">
                        <i class="fas fa-check me-1"></i>
                        Aceitar Proposta
                    </button>
                    
                    <button class="btn btn-warning" onclick="abrirContraProposta(<?php echo $prop['id']; ?>, <?php echo $prop['valor']; ?>, <?php echo $prop['prazo_execucao']; ?>)">
                        <i class="fas fa-exchange-alt me-1"></i>
                        Contra-Proposta
                    </button>
                    
                    <button class="btn btn-outline-info" onclick="abrirChat(<?php echo $prop['prestador_id']; ?>)">
                        <i class="fas fa-comments me-1"></i>
                        Conversar
                    </button>
                    
                    <button class="btn btn-outline-primary" onclick="verPerfilPrestador(<?php echo $prop['prestador_id']; ?>)">
                        <i class="fas fa-user me-1"></i>
                        Ver Perfil
                    </button>
                    
                    <button class="btn btn-outline-danger" onclick="recusarProposta(<?php echo $prop['id']; ?>)">
                        <i class="fas fa-times me-1"></i>
                        Recusar
                    </button>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php else: ?>
<hr class="my-4">
<div class="text-center py-5">
    <div class="mb-3">
        <i class="fas fa-inbox fa-3x text-muted"></i>
    </div>
    <h5 class="text-muted">Nenhuma proposta recebida ainda</h5>
    <p class="text-muted">Aguarde! Os prestadores interessados enviarão suas propostas em breve.</p>
</div>
<?php endif; ?>

<!-- Modal para visualizar imagem -->
<div class="modal fade" id="modalImagem" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Foto do Serviço</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img id="imagemModal" src="" class="img-fluid" alt="Foto do serviço">
            </div>
        </div>
    </div>
</div>

<!-- Modal Contra-Proposta -->
<div class="modal fade" id="contraPropostaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exchange-alt me-2"></i>
                    Fazer Contra-Proposta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="contraPropostaForm">
                <div class="modal-body">
                    <input type="hidden" id="propostaId" name="proposta_id">
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Proposta Original:</strong><br>
                        Valor: R$ <span id="valorOriginal"></span> | Prazo: <span id="prazoOriginal"></span> dia(s)
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="novoValor" class="form-label">Novo Valor *</label>
                            <div class="input-group">
                                <span class="input-group-text">R$</span>
                                <input type="number" class="form-control" id="novoValor" name="valor" step="0.01" required>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="novoPrazo" class="form-label">Novo Prazo *</label>
                            <div class="input-group">
                                <input type="number" class="form-control" id="novoPrazo" name="prazo" required>
                                <span class="input-group-text">dia(s)</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="observacoes" class="form-label">Observações</label>
                        <textarea class="form-control" id="observacoes" name="observacoes" rows="3" 
                                  placeholder="Explique o motivo da contra-proposta ou adicione observações..."></textarea>
                    </div>
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção:</strong> O prestador será notificado da sua contra-proposta e poderá aceitar, recusar ou fazer uma nova proposta.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-1"></i>
                        Enviar Contra-Proposta
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Confirmar Recusa -->
<div class="modal fade" id="recusaModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-times me-2"></i>
                    Recusar Proposta
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="recusaForm">
                <div class="modal-body">
                    <input type="hidden" id="recusaPropostaId" name="proposta_id">
                    
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        Tem certeza que deseja recusar esta proposta? Esta ação não pode ser desfeita.
                    </div>
                    
                    <div class="mb-3">
                        <label for="motivoRecusa" class="form-label">Motivo da Recusa (opcional)</label>
                        <textarea class="form-control" id="motivoRecusa" name="motivo" rows="3" 
                                  placeholder="Explique o motivo da recusa para o prestador..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times me-1"></i>
                        Confirmar Recusa
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function aceitarProposta(propostaId) {
    if (confirm('Tem certeza que deseja aceitar esta proposta?\n\nAo aceitar, todas as outras propostas serão automaticamente recusadas.')) {
        fetch('gerenciar-proposta.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({action: 'aceitar', proposta_id: propostaId})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Proposta aceita com sucesso!');
                window.location.reload();
            } else {
                alert('Erro: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erro:', error);
            alert('Erro de conexão. Tente novamente.');
        });
    }
}

function abrirContraProposta(propostaId, valorOriginal, prazoOriginal) {
    document.getElementById('propostaId').value = propostaId;
    document.getElementById('valorOriginal').textContent = new Intl.NumberFormat('pt-BR', {minimumFractionDigits: 2}).format(valorOriginal);
    document.getElementById('prazoOriginal').textContent = prazoOriginal;
    
    document.getElementById('novoValor').value = valorOriginal;
    document.getElementById('novoPrazo').value = prazoOriginal;
    
    new bootstrap.Modal(document.getElementById('contraPropostaModal')).show();
}

function recusarProposta(propostaId) {
    document.getElementById('recusaPropostaId').value = propostaId;
    new bootstrap.Modal(document.getElementById('recusaModal')).show();
}

function verPerfilPrestador(prestadorId) {
    alert(`Ver perfil do prestador ${prestadorId} - A implementar`);
}

// Event Listeners para formulários
document.getElementById('contraPropostaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const novoValor = parseFloat(document.getElementById('novoValor').value);
    const novoPrazo = parseInt(document.getElementById('novoPrazo').value);
    
    if (novoValor <= 0) {
        alert('O valor deve ser maior que zero.');
        return;
    }
    
    if (novoPrazo <= 0) {
        alert('O prazo deve ser maior que zero.');
        return;
    }
    
    const formData = new FormData(this);
    formData.append('action', 'contra_proposta');
    
    fetch('gerenciar-proposta.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('contraPropostaModal')).hide();
            alert('Contra-proposta enviada com sucesso!');
            window.location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    });
});

document.getElementById('recusaForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'recusar');
    
    fetch('gerenciar-proposta.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            bootstrap.Modal.getInstance(document.getElementById('recusaModal')).hide();
            alert('Proposta recusada.');
            window.location.reload();
        } else {
            alert('Erro: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Erro de conexão. Tente novamente.');
    });
});

// Funções de utilidade
function abrirModalImagem(src) {
    document.getElementById('imagemModal').src = src;
    new bootstrap.Modal(document.getElementById('modalImagem')).show();
}

function ordenarPropostas(criterio, ordem) {
    const container = document.getElementById('propostas-container');
    const propostas = Array.from(container.querySelectorAll('.proposta-item'));
    
    propostas.sort((a, b) => {
        let valorA, valorB;
        
        switch(criterio) {
            case 'valor':
                valorA = parseFloat(a.dataset.valor);
                valorB = parseFloat(b.dataset.valor);
                break;
            case 'prazo':
                valorA = parseInt(a.dataset.prazo);
                valorB = parseInt(b.dataset.prazo);
                break;
            case 'data':
                valorA = parseInt(a.dataset.data);
                valorB = parseInt(b.dataset.data);
                break;
        }
        
        return ordem === 'asc' ? valorA - valorB : valorB - valorA;
    });
    
    propostas.forEach(proposta => container.appendChild(proposta));
}

function toggleHistorico(propostaId) {
    const historico = document.getElementById(`historico-${propostaId}`);
    const toggle = document.getElementById(`toggle-${propostaId}`);
    
    if (historico.classList.contains('show')) {
        historico.classList.remove('show');
        toggle.classList.remove('fa-chevron-up');
        toggle.classList.add('fa-chevron-down');
    } else {
        historico.classList.add('show');
        toggle.classList.remove('fa-chevron-down');
        toggle.classList.add('fa-chevron-up');
    }
}

function abrirChat(prestadorId) {
    alert(`Chat com prestador ${prestadorId} - A implementar`);
}

// Funções das melhorias implementadas
function compartilharServico() {
    if (navigator.share) {
        navigator.share({
            title: 'Serviço: <?php echo htmlspecialchars($detalhes['titulo']); ?>',
            text: 'Confira este serviço na plataforma Chama Serviço',
            url: window.location.href
        });
    } else {
        navigator.clipboard.writeText(window.location.href);
        alert('Link copiado para área de transferência!');
    }
}

function duplicarServico() {
    if (confirm('Deseja criar um novo serviço baseado neste?')) {
        window.location.href = `novo-servico.php?duplicar=<?php echo $servico_id; ?>`;
    }
}

function exportarPDF() {
    window.print();
}

function copiarEndereco() {
    const endereco = '<?php echo htmlspecialchars($detalhes['endereco_completo']); ?>';
    navigator.clipboard.writeText(endereco);
    alert('Endereço copiado!');
}

function abrirMaps() {
    const endereco = encodeURIComponent('<?php echo htmlspecialchars($detalhes['endereco_completo']); ?>');
    window.open(`https://www.google.com/maps/search/${endereco}`, '_blank');
}

function toggleComparador() {
    const content = document.getElementById('comparadorContent');
    content.style.display = content.style.display === 'none' ? 'block' : 'none';
}

function convidarPrestador(prestadorId) {
    if (confirm('Deseja enviar um convite para este prestador?')) {
        fetch('enviar-convite.php', {
            method: 'POST',
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify({
                prestador_id: prestadorId,
                servico_id: <?php echo $servico_id; ?>
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Convite enviado com sucesso!');
            } else {
                alert('Erro: ' + data.message);
            }
        });
    }
}

function adicionarDocumento() {
    alert('Funcionalidade de upload de documentos - A implementar');
}

