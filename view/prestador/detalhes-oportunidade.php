<?php
session_start();

if (!isset($_SESSION['prestador_id']) || !isset($_GET['id'])) {
    echo '<div class="alert alert-danger">Acesso não autorizado ou ID inválido</div>';
    exit();
}

// Corrija o caminho do require_once para Servico.class.php
require_once __DIR__ . '/../../models/Servico.class.php';

$servico = new Servico();
$servico_id = $_GET['id'];
$detalhes = $servico->getDetalhesPublicos($servico_id);
$imagens = $servico->getImagensServico($servico_id);

if (!$detalhes) {
    echo '<div class="alert alert-danger">Serviço não encontrado ou não disponível</div>';
    exit();
}
?>

<style>
.info-section {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 20px;
    border-left: 4px solid #27ae60;
}

.urgencia-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 0.85em;
}

.urgencia-alta { background-color: #e74c3c; color: white; }
.urgencia-media { background-color: #f39c12; color: white; }
.urgencia-baixa { background-color: #3498db; color: white; }

.valor-destaque {
    color: #27ae60;
    font-weight: 700;
    font-size: 1.3em;
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
</style>

<div class="row">
    <div class="col-md-8">
        <div class="info-section">
            <h5 class="text-success mb-3">
                <i class="fas fa-info-circle me-2"></i>
                Detalhes da Oportunidade
            </h5>
            
            <div class="mb-3">
                <strong>Título:</strong><br>
                <span class="fs-5 text-primary"><?php echo htmlspecialchars($detalhes['titulo']); ?></span>
            </div>
            
            <div class="mb-3">
                <strong>Tipo de Serviço:</strong><br>
                <span class="badge bg-secondary fs-6 mt-1"><?php echo htmlspecialchars($detalhes['tipo_servico']); ?></span>
            </div>
            
            <div class="mb-3">
                <strong>Descrição:</strong><br>
                <p class="text-muted mt-2"><?php echo nl2br(htmlspecialchars($detalhes['descricao'])); ?></p>
            </div>
            
            <div class="mb-3">
                <strong>Endereço do Serviço:</strong><br>
                <div class="mt-2 p-2 bg-white border rounded">
                    <i class="fas fa-map-marker-alt text-danger me-2"></i>
                    <?php echo htmlspecialchars($detalhes['endereco_completo']); ?>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Data da Solicitação:</strong><br>
                    <span class="text-primary">
                        <i class="fas fa-calendar me-1"></i>
                        <?php echo date('d/m/Y H:i', strtotime($detalhes['data_solicitacao'])); ?>
                    </span>
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Urgência:</strong><br>
                    <span class="urgencia-badge urgencia-<?php echo $detalhes['urgencia']; ?>">
                        <?php echo ucfirst($detalhes['urgencia']); ?>
                    </span>
                </div>
            </div>
            
            <?php if ($detalhes['data_atendimento']): ?>
            <div class="mb-3">
                <strong>Data/Horário Desejados:</strong><br>
                <span class="text-success">
                    <i class="fas fa-clock me-1"></i>
                    <?php echo date('d/m/Y \à\s H:i', strtotime($detalhes['data_atendimento'])); ?>
                </span>
            </div>
            <?php endif; ?>
            
            <?php if ($detalhes['orcamento_estimado']): ?>
            <div class="mb-3">
                <strong>Orçamento Estimado pelo Cliente:</strong><br>
                <span class="valor-destaque">
                    <i class="fas fa-dollar-sign me-1"></i>
                    R$ <?php echo number_format($detalhes['orcamento_estimado'], 2, ',', '.'); ?>
                </span>
                <small class="text-muted d-block">Este é apenas um valor de referência</small>
            </div>
            <?php endif; ?>
            
            <?php if (!empty($imagens)): ?>
            <div class="mb-3">
                <strong>Fotos do Local/Serviço:</strong><br>
                <div class="mt-2">
                    <?php foreach ($imagens as $imagem): ?>
                        <img src="../../uploads/servicos/<?php echo htmlspecialchars($imagem['caminho_imagem']); ?>" 
                             class="foto-servico" 
                             alt="Foto do serviço"
                             onclick="abrirModalImagem(this.src)">
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-success">
            <div class="card-header bg-success text-white text-center">
                <h6 class="mb-0">
                    <i class="fas fa-handshake me-2"></i>
                    Interessado?
                </h6>
            </div>
            <div class="card-body text-center">
                <p class="mb-3">Envie sua proposta para este serviço!</p>
                
                <button class="btn btn-success btn-lg w-100 mb-2" 
                        onclick="enviarProposta(<?php echo $detalhes['id']; ?>)">
                    <i class="fas fa-paper-plane me-2"></i>
                    Enviar Proposta
                </button>
                
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    Você pode incluir valor, prazo e detalhes na sua proposta
                </small>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-body">
                <h6 class="card-title">
                    <i class="fas fa-lightbulb me-2 text-warning"></i>
                    Dicas para uma boa proposta
                </h6>
                <ul class="small mb-0">
                    <li>Seja específico sobre como executará o trabalho</li>
                    <li>Inclua materiais no orçamento se necessário</li>
                    <li>Seja realista com prazos</li>
                    <li>Demonstre experiência no tipo de serviço</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
function enviarProposta(servicoId) {
    // Fechar modal atual e abrir modal de proposta
    const detalhesModal = bootstrap.Modal.getInstance(document.getElementById('detalhesModal'));
    detalhesModal.hide();
    
    setTimeout(() => {
        document.getElementById('servicoId').value = servicoId;
        new bootstrap.Modal(document.getElementById('propostaModal')).show();
    }, 300);
}

function abrirModalImagem(src) {
    // Criar modal para imagem (implementar se necessário)
    window.open(src, '_blank');
}
</script>
