<?php
// Dados serão passados pelo controlador
$erro_titulo = $erro_titulo ?? '';
$erro_mensagem = $erro_mensagem ?? '';
$detalhes = $detalhes ?? [];
$imagens = $imagens ?? [];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes da Oportunidade | Chama Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/servico/assets/css/prestador.css">
    <style>
        .urgencia-badge {
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 0.85em;
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
            color: #27ae60;
            font-weight: 600;
        }

        .foto-servico {
            max-width: 150px;
            margin: 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
</head>

<body>
    <?php include __DIR__ . '/../components/menu-prestador.php'; ?>

    <main class="container mt-4">
        <?php if ($erro_mensagem): ?>
            <div class="alert alert-warning text-center shadow-sm p-4">
                <i class="fas fa-exclamation-triangle fa-3x mb-3 text-warning"></i>
                <h4 class="alert-heading"><?= htmlspecialchars($erro_titulo); ?></h4>
                <p><?= htmlspecialchars($erro_mensagem); ?></p>
                <hr>
                <a href="/servico/prestador/dashboard" class="btn btn-primary">Voltar para o Dashboard</a>
            </div>
        <?php else: ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white py-3">
                            <h4 class="text-primary mb-0 d-flex align-items-center">
                                <i class="fas fa-search-plus me-2"></i>
                                Detalhes da Oportunidade
                            </h4>
                        </div>
                        <div class="card-body p-4">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Título:</label>
                                <h5 class="mt-1"><?= htmlspecialchars($detalhes['titulo'] ?? ''); ?></h5>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Tipo de Serviço:</label><br>
                                <span class="badge bg-secondary fs-6 mt-1"><?= htmlspecialchars($detalhes['tipo_servico'] ?? ''); ?></span>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Descrição Completa:</label>
                                <div class="text-muted mt-2 bg-light p-3 border rounded"><?= nl2br(htmlspecialchars($detalhes['descricao'] ?? '')); ?></div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">Endereço do Serviço:</label>
                                <div class="mt-2 p-3 bg-light border rounded d-flex align-items-center">
                                    <i class="fas fa-map-marker-alt text-danger me-2 fa-lg"></i>
                                    <span><?= htmlspecialchars($detalhes['endereco_completo'] ?? ''); ?></span>
                                </div>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label fw-bold">Data da Solicitação:</label><br>
                                    <span class="text-dark"><i class="fas fa-calendar-alt me-1"></i> <?= date('d/m/Y \à\s H:i', strtotime($detalhes['data_solicitacao'] ?? '')); ?></span>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-bold">Urgência:</label><br>
                                    <?php
                                    $urgencia = strtolower($detalhes['urgencia'] ?? 'normal');
                                    ?>
                                    <span class="urgencia-badge urgencia-<?= htmlspecialchars($urgencia); ?>"><?= ucfirst(htmlspecialchars($urgencia)); ?></span>
                                </div>
                            </div>

                            <?php if (!empty($detalhes['data_atendimento'])): ?>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Data/Horário Preferencial do Cliente:</label><br>
                                    <span class="text-success fw-bold"><i class="fas fa-clock me-1"></i> <?= date('d/m/Y \à\s H:i', strtotime($detalhes['data_atendimento'])); ?></span>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($detalhes['orcamento_estimado']) && $detalhes['orcamento_estimado'] > 0): ?>
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Orçamento de Referência do Cliente:</label>
                                    <div class="valor-destaque fs-5 text-success">
                                        <i class="fas fa-dollar-sign me-1"></i>
                                        R$ <?= number_format($detalhes['orcamento_estimado'], 2, ',', '.'); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($imagens)): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Fotos Enviadas pelo Cliente:</label>
                                    <div class="mt-2 image-gallery">
                                        <?php foreach ($imagens as $imagem): ?>
                                            <img src="/servico/uploads/servicos/<?= htmlspecialchars($imagem['caminho_imagem']); ?>"
                                                class="foto-servico"
                                                alt="Foto do serviço"
                                                onclick="abrirModalImagem(this.src)"
                                                style="cursor: pointer;">
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card border-success shadow-sm sticky-top" style="top: 20px;">
                        <div class="card-header bg-success text-white text-center">
                            <h5 class="mb-0"><i class="fas fa-handshake me-2"></i> Faça sua Proposta</h5>
                        </div>
                        <div class="card-body text-center p-4">
                            <p class="mb-3">Mostre seu interesse e envie uma proposta detalhada para o cliente!</p>
                            <button class="btn btn-success btn-lg w-100 mb-2" data-bs-toggle="modal" data-bs-target="#propostaModal">
                                <i class="fas fa-paper-plane me-2"></i> Enviar Proposta
                            </button>
                            <small class="text-muted"><i class="fas fa-info-circle me-1"></i> Inclua valor, prazo e detalhes para aumentar suas chances.</small>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Visualizar Imagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body p-0 text-center">
                    <img id="modalImage" src="" class="img-fluid" alt="Imagem Ampliada">
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function abrirModalImagem(src) {
            document.getElementById('modalImage').src = src;
            const imageModal = new bootstrap.Modal(document.getElementById('imageModal'));
            imageModal.show();
        }
    </script>
</body>

</html>