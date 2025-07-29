<?php
session_start();

require_once __DIR__ . '/../../models/Prestador.class.php';
require_once __DIR__ . '/../../models/Servico.class.php';
require_once __DIR__ . '/../../models/Proposta.class.php';

// Obter dados dinâmicos
$prestador = new Prestador();
$servico = new Servico();
$proposta = new Proposta();

$prestador_id = $_SESSION['prestador_id'];
$prestador_nome = $_SESSION['prestador_nome'] ?? 'Prestador';

// Buscar dados completos do prestador (tb_pessoa)
$prestador_dados = $prestador->getById($prestador_id);
$prestador_email = $prestador_dados['email'] ?? '';
$prestador_telefone = $prestador_dados['telefone'] ?? '';

$stats = $prestador->getStats($prestador_id);
$servicos_disponiveis = $servico->getDisponiveis(5);
$minhas_propostas = $proposta->getByPrestador($prestador_id, 4);
$grafico_dados = $prestador->getGraficoDados($prestador_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Prestador | Chama Serviço</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
            --success-color: #3498db;
            --warning-color: #f39c12;
            --danger-color: #e74c3c;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .navbar {
            background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
            box-shadow: 0 2px 4px rgba(0,0,0,.1);
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
            background: linear-gradient(135deg, var(--secondary-color), #2ecc71);
            color: white;
        }
        
        .stats-card.primary {
            background: linear-gradient(135deg, var(--success-color), #5dade2);
        }
        
        .stats-card.warning {
            background: linear-gradient(135deg, #e67e22, #f39c12);
        }
        
        .stats-card.danger {
            background: linear-gradient(135deg, var(--danger-color), #c0392b);
        }
        
        .servico-item {
            border-left: 4px solid var(--secondary-color);
            transition: all 0.3s;
        }
        
        .servico-item:hover {
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
        
        .opportunity-card {
            border-left: 4px solid var(--warning-color);
        }
        
        .opportunity-card:hover {
            border-left-color: var(--secondary-color);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-hammer me-2"></i>
                Chama Serviço - Prestador
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" href="#"><i class="fas fa-home me-1"></i> Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="oportunidades.php"><i class="fas fa-search me-1"></i> Oportunidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="minhas-propostas.php"><i class="fas fa-handshake me-1"></i> Minhas Propostas</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="meus-trabalhos.php"><i class="fas fa-briefcase me-1"></i> Meus Trabalhos</a>
                    </li>
               <li class="nav-item">
    <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#perfilModal">
        <i class="fas fa-user me-1"></i> Perfil
    </a>
</li>
                </ul>
                
                <div class="navbar-nav">
                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>
                            <?php echo htmlspecialchars($prestador_nome); ?>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="perfil.php"><i class="fas fa-cog me-2"></i> Configurações</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <!-- Link para desenvolvimento -->
                            <li><a class="dropdown-item text-warning" href="../../switch-user.php?type=cliente"><i class="fas fa-exchange-alt me-2"></i> Trocar para Cliente</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="../../logout.php"><i class="fas fa-sign-out-alt me-2"></i> Sair</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid mt-4">
        <!-- Welcome Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-1">
                            <i class="fas fa-hand-wave text-warning me-2"></i>
                            Bem-vindo, <?php echo htmlspecialchars($prestador_nome); ?>!
                        </h2>
                        <p class="text-muted">Aqui você pode gerenciar suas propostas e encontrar novas oportunidades de trabalho.</p>
                        <a href="oportunidades.php" class="btn btn-success btn-custom">
                            <i class="fas fa-search me-2"></i>
                            Buscar Oportunidades
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $stats['trabalhos_ativos']; ?></h3>
                                <span>Trabalhos Ativos</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-briefcase fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $stats['propostas_enviadas']; ?></h3>
                                <span>Propostas Enviadas</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-paper-plane fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card warning">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0"><?php echo $stats['trabalhos_concluidos']; ?></h3>
                                <span>Concluídos</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-check-circle fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="card stats-card danger">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fw-bold mb-0">R$ <?php echo number_format($stats['total_ganho'], 2, ',', '.'); ?></h3>
                                <span>Total Ganho</span>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts and Opportunities -->
        <div class="row">
            <!-- Chart -->
            <div class="col-xl-8 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line me-2"></i>
                            Performance dos Últimos Meses
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="performanceChart" height="100"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Opportunities -->
            <div class="col-xl-4 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-lightbulb me-2"></i>
                            Novas Oportunidades
                        </h5>
                    </div>
                    <div class="card-body" style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($servicos_disponiveis as $servico_item): ?>
                        <div class="opportunity-card p-3 mb-3 bg-white rounded">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo htmlspecialchars($servico_item['titulo']); ?></h6>
                                    <small class="text-muted d-block mb-1">
                                        <i class="fas fa-tag me-1"></i>
                                        <?php echo htmlspecialchars($servico_item['tipo_servico']); ?>
                                    </small>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        <?php echo date('d/m/Y', strtotime($servico_item['data_solicitacao'])); ?>
                                    </small>
                                    <?php if ($servico_item['orcamento_estimado']): ?>
                                    <div class="mt-1">
                                        <small class="text-success fw-bold">
                                            <i class="fas fa-dollar-sign me-1"></i>
                                            R$ <?php echo number_format($servico_item['orcamento_estimado'], 2, ',', '.'); ?>
                                        </small>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <a href="oportunidades.php?ver=<?php echo $servico_item['id']; ?>" class="btn btn-outline-success btn-sm">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <div class="text-center">
                            <a href="oportunidades.php" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Ver Todas
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Proposals -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-handshake me-2"></i>
                            Minhas Propostas Recentes
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($minhas_propostas)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Nenhuma proposta enviada ainda</h5>
                                <p class="text-muted">Comece enviando propostas para serviços disponíveis!</p>
                                <a href="oportunidades.php" class="btn btn-success">
                                    <i class="fas fa-search me-1"></i>
                                    Buscar Oportunidades
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <?php foreach ($minhas_propostas as $proposta_item): ?>
                                <div class="col-md-6 col-lg-3 mb-3">
                                    <div class="servico-item p-3 bg-white rounded">
                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                            <h6 class="mb-0"><?php echo htmlspecialchars($proposta_item['servico_titulo']); ?></h6>
                                            <span class="status-badge bg-<?php echo $proposta_item['status_class']; ?> text-white">
                                                <?php echo ucfirst($proposta_item['status']); ?>
                                            </span>
                                        </div>
                                        <div class="mb-2">
                                            <strong class="text-success">R$ <?php echo number_format($proposta_item['valor'], 2, ',', '.'); ?></strong>
                                            <small class="text-muted d-block">
                                                <?php echo $proposta_item['prazo_execucao']; ?> dia(s)
                                            </small>
                                        </div>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar me-1"></i>
                                            <?php echo date('d/m/Y', strtotime($proposta_item['data_proposta'])); ?>
                                        </small>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
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
                                    <a href="oportunidades.php" class="btn btn-outline-success btn-lg">
                                        <i class="fas fa-search fa-2x mb-2"></i><br>
                                        Buscar Trabalhos
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-grid">
                                    <a href="minhas-propostas.php" class="btn btn-outline-primary btn-lg">
                                        <i class="fas fa-handshake fa-2x mb-2"></i><br>
                                        Minhas Propostas
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-grid">
                                    <a href="meus-trabalhos.php" class="btn btn-outline-info btn-lg">
                                        <i class="fas fa-briefcase fa-2x mb-2"></i><br>
                                        Meus Trabalhos
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-3 mb-3">
                                <div class="d-grid">
                                    <a href="perfil.php" class="btn btn-outline-warning btn-lg">
                                        <i class="fas fa-user fa-2x mb-2"></i><br>
                                        Meu Perfil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mensagens de Sucesso/Erro -->
    <div class="container mt-3">
      <?php if (!empty($_SESSION['erros_perfil'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <strong>Erro ao atualizar perfil:</strong>
          <ul class="mb-0">
            <?php foreach ($_SESSION['erros_perfil'] as $erro): ?>
              <li><?php echo htmlspecialchars($erro); ?></li>
            <?php endforeach; ?>
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
        <?php unset($_SESSION['erros_perfil']); ?>
      <?php endif; ?>
    </div>

    <!-- Modal de sucesso centralizada -->
    <div class="modal fade" id="sucessoModal" tabindex="-1" aria-labelledby="sucessoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center">
          <div class="modal-header bg-success text-white justify-content-center">
            <h5 class="modal-title w-100" id="sucessoModalLabel">
              <i class="fas fa-check-circle fa-2x me-2"></i>Perfil atualizado com sucesso!
            </h5>
          </div>
          <div class="modal-body">
            <p class="fs-5">Seus dados foram salvos.</p>
            <button type="button" class="btn btn-success" data-bs-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal de Perfil -->
    <div class="modal fade" id="perfilModal" tabindex="-1" aria-labelledby="perfilModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header bg-warning text-dark">
            <h5 class="modal-title" id="perfilModalLabel"><i class="fas fa-user me-2"></i>Meu Perfil</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <form method="post" action="atualizar_perfil.php" enctype="multipart/form-data" id="formPerfil">
            <div class="modal-body">
              <div class="text-center mb-3">
                <?php
                  // Corrige o caminho da foto para funcionar no navegador
                  $foto_perfil = !empty($prestador_dados['foto_perfil']) ? $prestador_dados['foto_perfil'] : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
                  if (!preg_match('/^https?:\/\//', $foto_perfil)) {
                      $foto_perfil = '/' . ltrim($foto_perfil, '/');
                  }
                ?>
                <img id="imgPerfil" src="<?php echo htmlspecialchars($foto_perfil); ?>" alt="Foto de Perfil" class="rounded-circle mb-2" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #ffc107;">
                <div class="mb-2">
                  <input type="file" class="form-control" name="foto_perfil" id="foto_perfil" accept="image/*" disabled>
                </div>
              </div>
              <div class="mb-3">
                <label for="perfil-nome" class="form-label">Nome</label>
                <input type="text" class="form-control" id="perfil-nome" name="nome" value="<?php echo htmlspecialchars($prestador_dados['nome'] ?? $prestador_nome); ?>" required readonly>
              </div>
              <div class="mb-3">
                <label for="perfil-email" class="form-label">Email</label>
                <input type="email" class="form-control" id="perfil-email" name="email" value="<?php echo htmlspecialchars($prestador_email); ?>" required readonly>
              </div>
              <div class="mb-3">
                <label for="perfil-telefone" class="form-label">Telefone</label>
                <input type="text" class="form-control" id="perfil-telefone" name="telefone" value="<?php echo htmlspecialchars($prestador_telefone); ?>" readonly>
              </div>
              <div class="mb-3">
                <label for="perfil-cpf" class="form-label">CPF</label>
                <input type="text" class="form-control" id="perfil-cpf" value="<?php echo htmlspecialchars($prestador_dados['cpf'] ?? ''); ?>" readonly>
              </div>
              <?php if (isset($prestador_dados['data_nascimento'])): ?>
              <div class="mb-3">
                <label for="perfil-data-nascimento" class="form-label">Data de Nascimento</label>
                <input type="text" class="form-control" id="perfil-data-nascimento" value="<?php echo htmlspecialchars($prestador_dados['data_nascimento']); ?>" readonly>
              </div>
              <?php endif; ?>
              <div class="mb-3">
                <label for="perfil-senha" class="form-label">Nova Senha</label>
                <input type="password" class="form-control" id="perfil-senha" name="senha" minlength="6" disabled>
                <small class="form-text text-muted">Preencha apenas se desejar alterar a senha.</small>
              </div>
              <input type="hidden" name="id" value="<?php echo htmlspecialchars($prestador_id); ?>">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
              <button type="button" class="btn btn-warning" id="btnEditarPerfil">Alterar Dados</button>
              <button type="submit" class="btn btn-success" id="btnSalvarPerfil" disabled>Salvar Alterações</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      // Habilita edição dos campos ao clicar em "Alterar Dados"
      document.addEventListener('DOMContentLoaded', function() {
        const btnEditar = document.getElementById('btnEditarPerfil');
        const btnSalvar = document.getElementById('btnSalvarPerfil');
        const form = document.getElementById('formPerfil');
        const camposEditaveis = [
          'perfil-nome', 'perfil-email', 'perfil-telefone', 'foto_perfil', 'perfil-senha'
        ];
        btnEditar.addEventListener('click', function() {
          camposEditaveis.forEach(function(id) {
            const campo = document.getElementById(id);
            if (campo) campo.removeAttribute('readonly');
            if (campo && campo.type === 'file') campo.removeAttribute('disabled');
            if (campo && campo.type === 'password') campo.removeAttribute('disabled');
          });
          btnSalvar.removeAttribute('disabled');
          btnEditar.setAttribute('disabled', 'disabled');
        });

        // Preview da imagem de perfil
        const fotoInput = document.getElementById('foto_perfil');
        if (fotoInput) {
          fotoInput.addEventListener('change', function(e) {
            const [file] = fotoInput.files;
            if (file) {
              document.getElementById('imgPerfil').src = URL.createObjectURL(file);
            }
          });
        }
      });

      // Exibe modal de sucesso se perfil foi atualizado
      document.addEventListener('DOMContentLoaded', function() {
        <?php if (!empty($_GET['sucesso'])): ?>
          var sucessoModal = new bootstrap.Modal(document.getElementById('sucessoModal'));
          sucessoModal.show();
        <?php endif; ?>
      });
    </script>
    <!-- Chart.js Script -->
    <script>
        // Configuração do gráfico
        const ctx = document.getElementById('performanceChart').getContext('2d');
        const performanceChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($grafico_dados['labels']); ?>,
                datasets: [{
                    label: 'Trabalhos Realizados',
                    data: <?php echo json_encode($grafico_dados['dados']); ?>,
                    borderColor: '#27ae60',
                    backgroundColor: 'rgba(39, 174, 96, 0.1)',
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
    </script>
</body>

</html>
</html>
