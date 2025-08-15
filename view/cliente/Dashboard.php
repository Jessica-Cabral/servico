<?php
// view/cliente/clienteDashboard.php - VIEW PURA
// As variáveis globais (ex: $cliente_nome, $stats, $servicos_recentes)
// foram definidas e preparadas pelo ClienteController.php antes de incluir esta view.

// Inicia a sessão para acessar os dados.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// O controlador já incluiu a navegação e os modais, não precisa de require_once aqui.
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cliente | Chama Serviço</title>

    <!-- Favicon: Caminho corrigido para absoluto -->
    <link rel="icon" type="image/png" href="/servico/assets/img/favicon.png">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* Variáveis de cor para o tema */
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #3498db;
            --success-color: #27ae60;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }

        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 4px rgba(0, 0, 0, .1);
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

        .stats-card {
            background: linear-gradient(135deg, var(--secondary-color), #5dade2);
            color: white;
        }

        .stats-card.success {
            background: linear-gradient(135deg, var(--success-color), #2ecc71);
        }

        .stats-card.warning {
            background: linear-gradient(135deg, #e67e22, #f39c12);
        }

        .stats-card.danger {
            background: linear-gradient(135deg, var(--danger-color), #c0392b);
        }

        .recent-services {
            max-height: 400px;
            overflow-y: auto;
        }

        .service-item {
            border-left: 4px solid var(--secondary-color);
            transition: all 0.3s;
        }

        .service-item:hover {
            border-left-color: var(--success-color);
            background-color: #f8f9fa;
        }

        .btn-custom {
            border-radius: 25px;
            padding: 10px 30px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .status-badge {
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }

        /* Sugestões para melhorar a responsividade e experiência do dashboard: */
        @media (max-width: 991.98px) {
            .navbar .dropdown-menu {
                right: 0;
                left: auto;
            }
        }

        .card-body.recent-services {
            overflow-x: auto;
        }

        .container,
        .container-fluid {
            max-width: 1200px;
        }
    </style>
</head>

<body>
    <!-- O menu e os modais serão incluídos pelo controlador -->
    <?php
    // Inclui o menu do cliente
    require_once __DIR__ . '/../components/menu-cliente.php';
    // Inclui a modal de perfil do cliente e a de novo serviço
    require_once __DIR__ . '/modals/modalPerfilCliente.php';
    require_once __DIR__ . '/modals/novoServicoModal.php';
    ?>

    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <!-- Foto do cliente -->
                            <img src="<?php echo htmlspecialchars($cliente_foto ?? 'https://ui-avatars.com/api/?name=' . urlencode($cliente_nome)); ?>" alt="Foto" class="rounded-circle me-3" width="60" height="60" style="object-fit:cover;">
                            <div>
                                <h2 class="card-title mb-1">
                                    <i class="fas fa-hand-wave text-warning me-2"></i>
                                    Bem-vindo, <?php echo htmlspecialchars($cliente_nome ?? 'Cliente'); ?>!
                                </h2>
                                <p class="text-muted mb-0">Aqui você pode gerenciar seus serviços e acompanhar o andamento.</p>
                            </div>
                        </div>
                        <div>
                            <!-- Links corrigidos para o roteador principal -->
                            <a href="/servico/cliente/novoservico" class="btn btn-primary btn-custom me-2" data-bs-toggle="modal" data-bs-target="#novoServicoModal">
                                <i class="fas fa-plus me-2"></i>
                                Novo Serviço
                            </a>

                            <!-- Botão para abrir modal de perfil -->
                            <button class="btn btn-outline-secondary btn-custom" id="btnAbrirPerfil" type="button" data-bs-toggle="modal" data-bs-target="#modalPerfilCliente">
                                <i class="fas fa-user"></i> Perfil
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4 g-3">
            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo htmlspecialchars($stats['ativos'] ?? 0); ?></h3>
                                <span>Serviços Ativos</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-tools fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card stats-card success">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo htmlspecialchars($stats['concluidos'] ?? 0); ?></h3>
                                <span>Concluídos</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card stats-card warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo htmlspecialchars($stats['pendentes'] ?? 0); ?></h3>
                                <span>Pendentes</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-clock fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 col-12 mb-3">
                <div class="card stats-card danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0">R$ <?php echo number_format($stats['total_gasto'] ?? 0, 2, ',', '.'); ?></h3>
                                <span>Total Gasto</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Recent Services -->
        <div class="row">
            <!-- Chart -->
            <div class="col-xl-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Serviços nos Últimos Meses
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="servicesChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Services -->
            <div class="col-xl-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-history me-2"></i>
                            Serviços Recentes
                        </h5>
                    </div>
                    <div class="card-body recent-services">
                        <?php if (empty($servicos_recentes)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-2x text-muted mb-3"></i>
                                <p class="text-muted">Nenhum serviço encontrado</p>
                                <a href="/servico/cliente/novoservico" class="btn btn-primary btn-sm">
                                    Solicitar Primeiro Serviço
                                </a>
                            </div>
                        <?php else: ?>
                            <?php foreach ($servicos_recentes as $servico_item): ?>
                                <div class="service-item p-3 mb-3 bg-white rounded">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($servico_item['titulo']); ?></h6>
                                            <small class="text-muted">
                                                <i class="fas fa-calendar me-1"></i>
                                                <?php echo date('d/m/Y', strtotime($servico_item['created_at'])); ?>
                                            </small>
                                        </div>
                                        <span class="status-badge bg-<?php echo $servico_item['status_class']; ?> text-white">
                                            <?php echo htmlspecialchars($servico_item['status_texto']); ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-bolt me-2"></i>
                            Ações Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <div class="d-grid">
                                    <a href="/servico/cliente/novoservico" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-plus fa-2x mb-2"></i><br>
                                        Novo Serviço
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-grid">
                                    <a href="/servico/cliente/meusservicos" class="btn btn-outline-info btn-lg">
                                        <i class="fas fa-search fa-2x mb-2"></i><br>
                                        Meus Serviços
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-grid">
                                    <a href="/servico/cliente/meusservicos?filtroStatus=5" class="btn btn-outline-success btn-lg">
                                        <i class="fas fa-star fa-2x mb-2"></i><br>
                                        Avaliar Serviço
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-grid">
                                    <a href="https://wa.me/556134032081" target="_blank" class="btn btn-outline-warning btn-lg">
                                        <i class="fas fa-headset fa-2x mb-2"></i><br>
                                        Suporte
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Configuração do gráfico
            const ctx = document.getElementById('servicesChart').getContext('2d');
            const labels = <?php echo json_encode($grafico_dados['labels'] ?? []); ?>;
            const data = <?php echo json_encode($grafico_dados['dados'] ?? []); ?>;

            if (labels.length > 0 && data.length > 0) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Serviços Solicitados',
                            data: data,
                            borderColor: '#3498db',
                            backgroundColor: 'rgba(52, 152, 219, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0,0,0,0.1)'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else {
                document.getElementById('servicesChart').parentElement.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-exclamation-circle fa-2x mb-2"></i>
                        <p>Nenhum dado disponível para exibir no gráfico.</p>
                    </div>`;
            }
        });

        // Ativa tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Máscara para telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });

        // Botão Editar Dados: habilita os campos para edição
        const btnEditar = document.getElementById('btnEditarDados');
        const btnSalvar = document.getElementById('btnSalvarAlteracoes');
        const form = document.getElementById('formEditarPerfil');
        if (btnEditar) {
            btnEditar.addEventListener('click', function() {
                form.querySelectorAll('input').forEach(function(input) {
                    if (input.type !== 'file' && input.name !== 'email' && input.name !== 'uf') input.removeAttribute('readonly');
                    if (input.type === 'file' || input.name === 'senha' || input.name === 'senha_confirmar') input.removeAttribute('disabled');
                });
                btnSalvar.removeAttribute('disabled');
                this.disabled = true;
            });
        }
    </script>
</body>

</html>