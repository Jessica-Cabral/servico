<?php
session_start();

if (!isset($_SESSION['cliente_id'])) {
    header('Location:Login.php');
    exit();
}

if (!isset($_GET['id'])) {
    header('Location: meus-servicos.php');
    exit();
}

// Corrija o caminho dos Models
require_once __DIR__ . '/../../models/Servico.php';
require_once __DIR__ . '/../../models/Avaliacao.php';

$servico = new Servico();
$avaliacao = new Avaliacao();
$servico_id = $_GET['id'];

// Verificar se o serviço existe e foi concluído
$detalhes = $servico->getDetalhes($servico_id, $_SESSION['cliente_id']);
if (!$detalhes || $detalhes['status_id'] != 5) {
    $_SESSION['erro'] = 'Serviço não pode ser avaliado. Apenas serviços concluídos podem ser avaliados.';
    header('Location: meus-servicos.php');
    exit();
}

// Verificar se existe prestador associado
if (!$detalhes['prestador_id']) {
    $_SESSION['erro'] = 'Não é possível avaliar: nenhum prestador foi designado para este serviço.';
    header('Location: meus-servicos.php');
    exit();
}

// Verificar se já foi avaliado
if ($avaliacao->jaAvaliou($servico_id, $_SESSION['cliente_id'])) {
    $_SESSION['erro'] = 'Este serviço já foi avaliado por você.';
    header('Location: meus-servicos.php');
    exit();
}

$sucesso = '';
$erro = '';

if ($_POST) {
    // Validar dados
    if (empty($_POST['nota']) || $_POST['nota'] < 1 || $_POST['nota'] > 5) {
        $erro = 'Por favor, selecione uma nota de 1 a 5 estrelas.';
    } else {
        $dados_avaliacao = [
            'servico_id' => $servico_id,
            'cliente_id' => $_SESSION['cliente_id'],
            'prestador_id' => $detalhes['prestador_id'],
            'nota' => $_POST['nota'],
            'comentario' => trim($_POST['comentario'] ?? '')
        ];

        if ($avaliacao->criar($dados_avaliacao)) {
            $sucesso = 'Avaliação enviada com sucesso! Obrigado pelo seu feedback.';
        } else {
            $erro = 'Erro ao enviar avaliação. Tente novamente.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Avaliar Serviço - Chama Serviço</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
        }
        
        .star-rating {
            font-size: 2rem;
            color: #ddd;
            cursor: pointer;
        }
        
        .star-rating .star.active {
            color: #ffc107;
        }
        
        .star-rating .star:hover {
            color: #ffc107;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));">
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
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            <i class="fas fa-star me-2"></i>
                            Avaliar Serviço
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="mb-4 p-3 bg-light rounded">
                            <h5 class="text-primary"><?php echo htmlspecialchars($detalhes['titulo']); ?></h5>
                            <p class="text-muted mb-1">
                                <i class="fas fa-tag me-1"></i>
                                <?php echo htmlspecialchars($detalhes['tipo_servico']); ?>
                            </p>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar me-1"></i>
                                Concluído em <?php echo date('d/m/Y', strtotime($detalhes['data_solicitacao'])); ?>
                            </p>
                        </div>

                        <?php if ($sucesso): ?>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle me-2"></i>
                                <?php echo $sucesso; ?>
                                <div class="mt-2">
                                    <a href="meus-servicos.php" class="btn btn-success btn-sm">
                                        <i class="fas fa-list me-1"></i>
                                        Ver Meus Serviços
                                    </a>
                                </div>
                            </div>
                        <?php else: ?>

                        <?php if ($erro): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo $erro; ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" id="avaliacaoForm">
                            <div class="mb-4">
                                <label class="form-label fw-bold">Como você avalia este serviço? *</label>
                                <div class="star-rating text-center py-3" id="starRating">
                                    <span class="star" data-rating="1"><i class="fas fa-star"></i></span>
                                    <span class="star" data-rating="2"><i class="fas fa-star"></i></span>
                                    <span class="star" data-rating="3"><i class="fas fa-star"></i></span>
                                    <span class="star" data-rating="4"><i class="fas fa-star"></i></span>
                                    <span class="star" data-rating="5"><i class="fas fa-star"></i></span>
                                </div>
                                <input type="hidden" id="nota" name="nota" required>
                                <div id="ratingText" class="mt-2 text-center">
                                    <small class="text-muted">Clique nas estrelas para avaliar</small>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label for="comentario" class="form-label fw-bold">Comentário sobre o serviço</label>
                                <textarea class="form-control" id="comentario" name="comentario" rows="4" 
                                          placeholder="Conte como foi sua experiência com este prestador. Seu feedback ajuda outros clientes!"
                                          maxlength="500"></textarea>
                                <div class="form-text">Máximo 500 caracteres</div>
                            </div>

                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <a href="meus-servicos.php" class="btn btn-outline-secondary me-md-2">
                                    <i class="fas fa-times me-1"></i>
                                    Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary" id="btnEnviar" disabled>
                                    <i class="fas fa-star me-1"></i>
                                    Enviar Avaliação
                                </button>
                            </div>
                        </form>

                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5><i class="fas fa-star me-2"></i>Avaliar Serviço</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="nota" class="form-label">Nota (1 a 5)</label>
                        <select class="form-select" id="nota" name="nota" required>
                            <option value="">Selecione</option>
                            <?php for ($i=1; $i<=5; $i++): ?>
                                <option value="<?php echo $i; ?>"><?php echo $i; ?> estrela(s)</option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="comentario" class="form-label">Comentário</label>
                        <textarea class="form-control" id="comentario" name="comentario" rows="4" placeholder="Descreva sua experiência..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-paper-plane me-1"></i>Enviar Avaliação
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const stars = document.querySelectorAll('.star');
            const notaInput = document.getElementById('nota');
            const ratingText = document.getElementById('ratingText');
            const btnEnviar = document.getElementById('btnEnviar');
            
            const textos = {
                1: '⭐ Muito Ruim - Serviço não atendeu expectativas',
                2: '⭐⭐ Ruim - Serviço com problemas significativos',
                3: '⭐⭐⭐ Regular - Serviço adequado, mas pode melhorar',
                4: '⭐⭐⭐⭐ Bom - Serviço de qualidade, recomendo',
                5: '⭐⭐⭐⭐⭐ Excelente - Serviço excepcional!'
            };

            let selectedRating = 0;

            stars.forEach((star, index) => {
                star.addEventListener('mouseover', function() {
                    const rating = this.dataset.rating;
                    highlightStars(rating);
                    if (selectedRating === 0) {
                        ratingText.innerHTML = `<span class="text-warning">${textos[rating]}</span>`;
                    }
                });

                star.addEventListener('mouseout', function() {
                    if (selectedRating > 0) {
                        highlightStars(selectedRating);
                        ratingText.innerHTML = `<span class="text-warning fw-bold">${textos[selectedRating]}</span>`;
                    } else {
                        clearStars();
                        ratingText.innerHTML = '<small class="text-muted">Clique nas estrelas para avaliar</small>';
                    }
                });

                star.addEventListener('click', function() {
                    const rating = this.dataset.rating;
                    selectedRating = rating;
                    notaInput.value = rating;
                    highlightStars(rating);
                    ratingText.innerHTML = `<span class="text-warning fw-bold">${textos[rating]}</span>`;
                    btnEnviar.disabled = false;
                    btnEnviar.classList.remove('btn-primary');
                    btnEnviar.classList.add('btn-success');
                });
            });

            function highlightStars(rating) {
                stars.forEach((star, index) => {
                    if (index < rating) {
                        star.classList.add('active');
                    } else {
                        star.classList.remove('active');
                    }
                });
            }

            function clearStars() {
                stars.forEach(star => {
                    star.classList.remove('active');
                });
            }

            // Validação do formulário
            document.getElementById('avaliacaoForm').addEventListener('submit', function(e) {
                if (!notaInput.value) {
                    e.preventDefault();
                    alert('Por favor, selecione uma nota clicando nas estrelas.');
                    return false;
                }
            });
        });
    </script>
</body>
</html>
