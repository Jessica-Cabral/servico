<?php
// View: Detalhes do serviço para prestadores
// Variáveis esperadas: $detalhes (array|null), $imagens (array), $erro_titulo, $erro_mensagem

// Detecta AJAX
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH'])
    && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

// Se não for AJAX, inicia buffer para renderizar via layout
$renderViaLayout = !$isAjax && !defined('IN_MAIN_LAYOUT');
if ($renderViaLayout) {
    ob_start();
}
?>
<style>
.info-section{background:#fff;border-radius:10px;padding:20px;margin-bottom:20px;border-left:4px solid #27ae60;}
.urgencia-badge{padding:6px 14px;border-radius:20px;font-weight:600;font-size:.85em;}
.urgencia-alta{background:#e74c3c;color:#fff}
.urgencia-media{background:#f39c12;color:#fff}
.urgencia-baixa{background:#3498db;color:#fff}
.valor-destaque{color:#27ae60;font-weight:700;font-size:1.1em}
.foto-servico{width:80px;height:80px;object-fit:cover;border-radius:8px;border:1px solid #dee2e6;margin:4px;cursor:pointer}
</style>

<?php if (!empty($erro_titulo) || !$detalhes): ?>
    <div class="alert alert-danger">
        <strong><?= htmlspecialchars($erro_titulo ?? 'Erro'); ?></strong><br>
        <?= htmlspecialchars($erro_mensagem ?? 'Detalhes indisponíveis.'); ?>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <div class="info-section">
                <h5 class="text-success mb-3"><i class="fas fa-info-circle me-2"></i> Detalhes da Oportunidade</h5>
                <!-- Título -->
                <div class="mb-3">
                    <strong>Título:</strong><br>
                    <span class="fs-5 text-primary"><?= htmlspecialchars($detalhes['titulo'] ?? '-'); ?></span>
                </div>
                <!-- Tipo -->
                <div class="mb-3">
                    <strong>Tipo de Serviço:</strong><br>
                    <span class="badge bg-secondary fs-6 mt-1"><?= htmlspecialchars($detalhes['tipo_servico'] ?? '-'); ?></span>
                </div>
                <!-- Descrição -->
                <div class="mb-3">
                    <strong>Descrição:</strong><br>
                    <p class="text-muted mt-2"><?= nl2br(htmlspecialchars($detalhes['descricao'] ?? '')); ?></p>
                </div>
                <!-- Endereço -->
                <div class="mb-3">
                    <strong>Endereço:</strong><br>
                    <div class="mt-2 p-2 bg-white border rounded">
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        <?= htmlspecialchars($detalhes['endereco_completo'] ?? '-'); ?>
                    </div>
                </div>
                <!-- Datas e urgência -->
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Solicitado em:</strong><br>
                        <span class="text-primary">
                            <i class="fas fa-calendar me-1"></i>
                            <?= !empty($detalhes['data_solicitacao'])
                               ? date('d/m/Y H:i', strtotime($detalhes['data_solicitacao']))
                               : '-'; ?>
                        </span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Urgência:</strong><br>
                        <?php $urg = $detalhes['urgencia'] ?? 'baixa'; ?>
                        <span class="urgencia-badge urgencia-<?= htmlspecialchars($urg); ?>">
                            <?= ucfirst(htmlspecialchars($urg)); ?>
                        </span>
                    </div>
                </div>
                <!-- Orçamento estimado -->
                <?php if (!empty($detalhes['orcamento_estimado'])): ?>
                <div class="mb-3">
                    <strong>Orçamento Estimado:</strong><br>
                    <span class="valor-destaque">R$ <?= number_format($detalhes['orcamento_estimado'],2,',','.'); ?></span>
                </div>
                <?php endif; ?>
                <!-- Imagens -->
                <?php if ($imagens): ?>
                <div class="mb-3">
                    <strong>Fotos:</strong><br>
                    <div class="mt-2 d-flex flex-wrap">
                        <?php foreach ($imagens as $img): 
                            $src = htmlspecialchars($img['caminho_imagem'] ?? $img['url'] ?? '');
                            if ($src && !preg_match('#^https?://#',$src)) {
                                $src = '/servico/uploads/servicos/'.ltrim($src,'/'); 
                            }
                        ?>
                            <img src="<?= $src; ?>" alt="Foto do serviço" class="foto-servico" loading="lazy" tabindex="0">
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <!-- Seção de envio de proposta -->
        <div class="col-md-4">
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white text-center">
                    <h6 class="mb-0"><i class="fas fa-paper-plane me-2"></i> Enviar Proposta</h6>
                </div>
                <div class="card-body text-center">
                    <button class="btn btn-success btn-lg w-100 mb-2"
                            type="button"
                            onclick="enviarPropostaLocal(<?= (int)($detalhes['id'] ?? 0); ?>)">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Proposta
                    </button>
                    <small class="text-muted">Você pode incluir valor, prazo e descrição.</small>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
function enviarPropostaLocal(servicoId) {
    const fld = document.getElementById('servicoId');
    if (fld) fld.value = servicoId;
    const m = document.getElementById('propostaModal');
    if (m && typeof bootstrap !== 'undefined') {
        new bootstrap.Modal(m).show();
    }
}
</script>

<?php
// Se não for AJAX, injeta no layout principal
if ($renderViaLayout) {
    $mainContent = ob_get_clean();
    $pageTitle   = 'Detalhes da Oportunidade';
    require_once __DIR__ . '/main.php';
    exit;
}
?>
                </div>small>
            </div>div>
            </div>
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">
                        <i class="fas fa-lightbulb me-2 text-warning"></i>
                        Dicas para uma boa propostame-2 text-warning"></i>
                    </h6>icas para uma boa proposta
                    <ul class="small mb-0">
                        <li>Seja específico sobre como executará o trabalho</li>
                        <li>Inclua materiais no orçamento se necessário</li>/li>
                        <li>Seja realista com prazos</li> se necessário</li>
                        <li>Demonstre experiência no tipo de serviço</li>
                    </ul>li>Demonstre experiência no tipo de serviço</li>
                </div>ul>
            </div>div>
        </div>div>
    </div>div>
<?php endif; ?>
<?php endif; ?>
<script>
function enviarPropostaAqui(servicoId) {
    // tenta usar função global primeiro
    if (window.app && typeof window.app.enviarProposta === 'function') {
        window.app.enviarProposta(servicoId);rProposta === 'function') {
        return;app.enviarProposta(servicoId);
    }   return;
    if (typeof window.enviarProposta === 'function') {
        window.enviarProposta(servicoId);'function') {
        return;enviarProposta(servicoId);
    }   return;
    }
    // fallback local
    var servicoInput = document.getElementById('servicoId');
    if (servicoInput) servicoInput.value = servicoId;coId');
    var propostaModal = document.getElementById('propostaModal');
    if (propostaModal && typeof bootstrap !== 'undefined') {al');
        new bootstrap.Modal(propostaModal).show();efined') {
    } else {bootstrap.Modal(propostaModal).show();
        alert('Modal de proposta não disponível.');
    }   alert('Modal de proposta não disponível.');
}   }
</script>
<script>
    /* função local segura para abrir modal de proposta */
    function enviarPropostaLocal(servicoId) {
        // lógica da função
    }
</script>

<?php
// Se foi iniciado buffer para acesso direto, injeta no layout
if (!empty($renderViaLayout) && $renderViaLayout) {
    $mainContent = ob_get_clean();
    $pageTitle   = 'Detalhes da Oportunidade';
    require_once __DIR__ . '/main.php';
    exit;
}
?>
}
?>
