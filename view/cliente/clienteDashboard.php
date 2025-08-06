<?php
// View do dashboard do cliente (MVC)

// Inicia a sessão se ainda não estiver iniciada
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se o cliente está autenticado, senão redireciona para o login
if (empty($_SESSION['cliente_id'])) {
    header('Location: ../../Login.php');
    exit();
}

// Inclui as classes necessárias
require_once __DIR__ . '/../../models/Cliente.class.php';
require_once __DIR__ . '/../../models/Servico.class.php';

// Recupera dados do cliente da sessão
$cliente = new Cliente();
$servico = new Servico();
$cliente_id = $_SESSION['cliente_id'];
$cliente_nome = $_SESSION['cliente_nome'] ?? 'Cliente';

// Obtém estatísticas do cliente de forma segura
try {
    $stats = $cliente->getStats($cliente_id);
} catch (Exception $e) {
    $stats = [
        'ativos' => 0,
        'concluidos' => 0,
        'pendentes' => 0,
        'total_gasto' => 0
    ];
}

// Obtém serviços recentes do cliente
try {
    $servicos_recentes = $servico->getRecentes($cliente_id, 4);
} catch (Exception $e) {
    $servicos_recentes = [];
}

// Obtém dados para o gráfico de serviços
try {
    $grafico_dados = $servico->getGraficoDados($cliente_id);
} catch (Exception $e) {
    $grafico_dados = [
        'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
        'dados' => [0, 0, 0, 0, 0, 0]
    ];
}

// Inclui o menu do cliente
require_once 'menu-cliente.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Cliente | Chama Serviço</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="../../assets/img/favicon.png">

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
    <!-- Toast de feedback -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1055">
        <div id="toastFeedback" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Alterações salvas com sucesso!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Fechar"></button>
            </div>
        </div>
    </div>
    <!-- Main Content -->
    <div class="container mt-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body d-flex flex-column flex-md-row align-items-center justify-content-between">
                        <div class="d-flex align-items-center mb-3 mb-md-0">
                            <!-- Foto do cliente -->
                            <img src="<?php echo isset($_SESSION['cliente_foto']) ? htmlspecialchars($_SESSION['cliente_foto']) : 'https://ui-avatars.com/api/?name=' . urlencode($cliente_nome); ?>" alt="Foto" class="rounded-circle me-3" width="60" height="60" style="object-fit:cover;">
                            <div>
                                <h2 class="card-title mb-1">
                                    <i class="fas fa-hand-wave text-warning me-2"></i>
                                    Bem-vindo, <?php echo htmlspecialchars($cliente_nome); ?>!
                                </h2>
                                <p class="text-muted mb-0">Aqui você pode gerenciar seus serviços e acompanhar o andamento.</p>
                            </div>
                        </div>
                        <div>
                            <a href="novo-servico.php" class="btn btn-primary btn-custom me-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Solicite um novo serviço">
                                <i class="fas fa-plus me-2"></i>
                                Novo Serviço
                            </a>
                            <!-- Botão para abrir modal perfil -->
                            <button class="btn btn-outline-secondary btn-custom" id="btnAbrirPerfil" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="perfilModal">
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
                                <h3 class="fw-bold mb-0"><?php echo $stats['ativos']; ?></h3>
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
                                <h3 class="fw-bold mb-0"><?php echo $stats['concluidos']; ?></h3>
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
                                <h3 class="fw-bold mb-0"><?php echo $stats['pendentes']; ?></h3>
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
                                <h3 class="fw-bold mb-0">R$ <?php echo number_format($stats['total_gasto'], 2, ',', '.'); ?></h3>
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
                                <a href="novo-servico.php" class="btn btn-primary btn-sm">
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
                                    <a href="novo-servico.php" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-plus fa-2x mb-2"></i><br>
                                        Novo Serviço
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-grid">
                                    <a href="meus-servicos.php" class="btn btn-outline-info btn-lg">
                                        <i class="fas fa-search fa-2x mb-2"></i><br>
                                        Meus Serviços
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-grid">
                                    <button class="btn btn-outline-success btn-lg"
                                        onclick="window.location.href='meus-servicos.php?filtroStatus=5'">
                                        <i class="fas fa-star fa-2x mb-2"></i><br>
                                        Avaliar Serviço
                                    </button>
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

    <!-- Modal de Perfil -->
    <?php
    // Sempre busca a foto atualizada do banco
    $foto_perfil = !empty($prestador_dados['foto_perfil']) ? $prestador_dados['foto_perfil'] : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
    // Corrige o caminho apenas se não for URL absoluta e não começa com "view/"
    if (!preg_match('/^https?:\/\//', $foto_perfil)) {
        // Se já começa com "view/", não adiciona nada
        if (strpos($foto_perfil, 'view/') === 0) {
            $foto_perfil = $foto_perfil;
        } else {
            // Se começa com "uploads/", adiciona o caminho relativo correto
            $foto_perfil = 'view/prestador/' . ltrim($foto_perfil, '/');
        }
    }
    ?>
    <div class="modal fade" id="modalPerfilCliente" tabindex="-1" aria-labelledby="modalPerfilClienteLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="formEditarPerfil" method="post" action="atualizar-perfil.php" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalPerfilClienteLabel"><i class="fas fa-user-circle me-2"></i>Meu Perfil</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 text-center">
                            <img src="<?php echo isset($_SESSION['cliente_foto']) ? htmlspecialchars($_SESSION['cliente_foto']) : 'https://ui-avatars.com/api/?name=' . urlencode($cliente_nome); ?>" alt="Foto de Perfil" id="previewFotoPerfil" class="rounded-circle mb-2" width="100" height="100" style="object-fit:cover;">
                            <input type="file" class="form-control mt-2" id="imagem" name="imagem" accept="image/*" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($dados_cliente['nome'] ?? $cliente_nome); ?>" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="perfil-email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="perfil-email" name="email" value="<?php echo htmlspecialchars($dados_cliente['email'] ?? $cliente_email); ?>" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($dados_cliente['telefone'] ?? $cliente_telefone); ?>" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="cep" class="form-label">CEP</label>
                            <input type="text" class="form-control" id="cep" name="cep" value="<?php echo htmlspecialchars($dados_cliente['cep'] ?? ''); ?>" maxlength="9" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" class="form-control" id="endereco" name="endereco" value="<?php echo htmlspecialchars($dados_cliente['logradouro'] ?? $dados_cliente['endereco'] ?? ''); ?>" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="numero" class="form-label">Número</label>
                            <input type="text" class="form-control" id="numero" name="numero" value="<?php echo htmlspecialchars($dados_cliente['numero'] ?? ''); ?>" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="bairro" class="form-label">Bairro</label>
                            <input type="text" class="form-control" id="bairro" name="bairro" value="<?php echo htmlspecialchars($dados_cliente['bairro'] ?? ''); ?>" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="cidade" class="form-label">Cidade</label>
                            <input type="text" class="form-control" id="cidade" name="cidade" value="<?php echo htmlspecialchars($dados_cliente['cidade'] ?? ''); ?>" required readonly>
                        </div>
                        <div class="mb-3">
                            <label for="uf" class="form-label">UF</label>
                            <input type="text" class="form-control" id="uf" name="uf" value="<?php echo htmlspecialchars($dados_cliente['estado'] ?? $dados_cliente['uf'] ?? ''); ?>" maxlength="2" required readonly>
                        </div>
                        <hr>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" placeholder="Deixe em branco para não alterar" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="senha_confirmar" class="form-label">Confirmar Nova Senha</label>
                            <input type="password" class="form-control" id="senha_confirmar" name="senha_confirmar" placeholder="Repita a nova senha" disabled>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-warning" id="btnEditarDados">Editar Dados</button>
                        <button type="submit" class="btn btn-primary" id="btnSalvarAlteracoes" disabled>Salvar Alterações</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Garante que a modal será aberta ao clicar no botão
        document.getElementById('btnAbrirPerfil').addEventListener('click', function(e) {
            e.preventDefault();
            var perfilModal = new bootstrap.Modal(document.getElementById('modalPerfilCliente'));
            perfilModal.show();
        });

        // Configuração do gráfico
        const ctx = document.getElementById('servicesChart').getContext('2d');
        const servicesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($grafico_dados['labels']); ?>,
                datasets: [{
                    label: 'Serviços Solicitados',
                    data: <?php echo json_encode($grafico_dados['dados']); ?>,
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

        // Ativa tooltips Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Preview da foto de perfil
        document.getElementById('imagem').addEventListener('change', function(e) {
            const [file] = e.target.files;
            if (file) {
                document.getElementById('previewFotoPerfil').src = URL.createObjectURL(file);
            }
        });

        // Máscara para telefone
        document.getElementById('telefone').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/(\d{2})(\d)/, '($1) $2');
            value = value.replace(/(\d{5})(\d)/, '$1-$2');
            e.target.value = value;
        });

        // Botão Editar Dados: habilita os campos para edição
        document.getElementById('btnEditarDados').addEventListener('click', function() {
            const form = document.getElementById('formEditarPerfil');
            form.querySelectorAll('input').forEach(function(input) {
                if (input.type !== 'file' && input.name !== 'email') input.removeAttribute('readonly');
                if (input.type === 'file' || input.name === 'senha' || input.name === 'senha_confirmar') input.removeAttribute('disabled');
            });
            document.getElementById('btnSalvarAlteracoes').disabled = false;
            this.disabled = true;
            document.getElementById('cep').focus();
        });

        // Auto-preenchimento de endereço via ViaCEP ao digitar o CEP
        document.getElementById('cep').addEventListener('blur', function() {
            const cep = this.value.replace(/\D/g, '');
            if (cep.length === 8) {
                fetch('https://viacep.com.br/ws/' + cep + '/json/')
                    .then(response => response.json())
                    .then(data => {
                        if (!data.erro) {
                            document.getElementById('endereco').value = data.logradouro || '';
                            document.getElementById('bairro').value = data.bairro || '';
                            document.getElementById('cidade').value = data.localidade || '';
                            document.getElementById('uf').value = data.uf || '';
                            // Habilita campos se ainda estiverem readonly
                            ['endereco', 'bairro', 'cidade', 'uf'].forEach(id => {
                                document.getElementById(id).removeAttribute('readonly');
                            });
                        } else {
                            alert('CEP não encontrado.');
                        }
                    })
                    .catch(() => alert('Erro ao buscar o CEP.'));
            }
        });

        // Validação rápida do formulário (opcional)
        document.getElementById('formEditarPerfil').addEventListener('submit', function(e) {
            const tel = document.getElementById('telefone').value;
            const senha = document.getElementById('senha').value;
            const senha2 = document.getElementById('senha_confirmar').value;
            if (tel.length < 14) {
                e.preventDefault();
                alert('Informe um telefone válido.');
            }
            if (senha && senha !== senha2) {
                e.preventDefault();
                alert('As senhas não coincidem.');
            }
            // Exemplo de toast de sucesso (remova se for usar backend real)
            if (!e.defaultPrevented) {
                var toast = new bootstrap.Toast(document.getElementById('toastFeedback'));
                toast.show();
                e.preventDefault(); // Remova esta linha se for usar backend real
            }
        });
    </script>



    <footer class="text-center text-muted py-3 mt-4">
        &copy; <?php echo date('Y'); ?> Chama Serviço. Todos os direitos reservados.
    </footer>

</body>

</html>