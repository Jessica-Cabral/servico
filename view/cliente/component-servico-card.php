<div class="col-md-6 col-lg-4 mb-4 servico-card"
    data-status="<?php echo htmlspecialchars($item['status_id']); ?>"
    data-tipo="<?php echo htmlspecialchars($item['tipo_servico_id']); ?>"
    data-titulo="<?php echo strtolower(htmlspecialchars($item['titulo'])); ?>"
    data-descricao="<?php echo strtolower(htmlspecialchars($item['descricao'])); ?>"
    data-data="<?php echo htmlspecialchars(strtotime($item['data_solicitacao'])); ?>"
    data-valor="<?php echo htmlspecialchars($item['orcamento_estimado'] ?? 0); ?>">
    <div class="card h-100 template-card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="card-title mb-0"><?php echo htmlspecialchars($item['titulo']); ?></h6>
                <span class="status-badge" style="background-color: <?php echo htmlspecialchars($item['status_cor']); ?>; color: white;">
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
                    onclick="verDetalhes(<?php echo $item['id']; ?>)"
                    aria-label="Ver detalhes do serviço <?php echo htmlspecialchars($item['titulo']); ?>">
                    <i class="fas fa-eye me-1"></i>
                    Detalhes
                </button>
                <?php if ($item['status_id'] == 1): ?>
                    <button class="btn btn-outline-secondary btn-sm btn-action"
                        onclick="editarServico(<?php echo $item['id']; ?>)"
                        aria-label="Editar serviço <?php echo htmlspecialchars($item['titulo']); ?>">
                        <i class="fas fa-edit me-1"></i>
                        Editar
                    </button>
                    <button class="btn btn-outline-danger btn-sm btn-action"
                        onclick="cancelarServico(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['titulo']); ?>')"
                        title="Cancelar solicitação"
                        aria-label="Cancelar serviço <?php echo htmlspecialchars($item['titulo']); ?>">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                <?php elseif ($item['status_id'] == 2): ?>
                    <button class="btn btn-outline-danger btn-sm btn-action"
                        onclick="cancelarServico(<?php echo $item['id']; ?>, '<?php echo htmlspecialchars($item['titulo']); ?>')"
                        title="Cancelar solicitação"
                        aria-label="Cancelar serviço <?php echo htmlspecialchars($item['titulo']); ?>">
                        <i class="fas fa-times me-1"></i>
                        Cancelar
                    </button>
                <?php elseif ($item['status_id'] == 5): ?>
                    <button class="btn btn-outline-warning btn-sm btn-action"
                        onclick="avaliarServico(<?php echo $item['id']; ?>)"
                        title="Avaliar serviço concluído"
                        aria-label="Avaliar serviço <?php echo htmlspecialchars($item['titulo']); ?>">
                        <i class="fas fa-star me-1"></i>
                        Avaliar
                    </button>
                <?php elseif ($item['status_id'] == 3): ?>
                    <small class="text-success"><?php echo htmlspecialchars($item['status_texto']); ?></small>
                <?php elseif ($item['status_id'] == 4): ?>
                    <small class="text-muted"><?php echo htmlspecialchars($item['status_texto']); ?></small>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Template para Vista Lista (oculto inicialmente) -->
    <div class="row-servico template-lista" style="display: none;">
        <div class="col-md-1 d-flex justify-content-center">
            <span class="status-badge" style="background-color: <?php echo $item['status_cor']; ?>; color: white;">
                <?php echo htmlspecialchars($item['status_texto']); ?>
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
                <button class="btn btn-outline-primary btn-sm" onclick="verDetalhes(<?php echo $item['id']; ?>)" title="Ver detalhes" aria-label="Ver detalhes do serviço">
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
