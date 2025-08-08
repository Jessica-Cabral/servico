<?php
require_once __DIR__ . '/../../models/Proposta.class.php';

require_once __DIR__ . '/../../models/Servico.class.php';
session_start();

// Verificar se o prestador está logado
if (!isset($_SESSION['prestador_id']) || $_SESSION['user_type'] !== 'prestador') {
    header('Location: ../auth/login.php');
    exit();
}



$proposta = new Proposta();
$servico = new Servico();

$prestador_id = $_SESSION['prestador_id'];
$prestador_nome = $_SESSION['prestador_nome'] ?? 'Prestador';

// Filtros
$filtro_status = $_GET['status'] ?? '';
$minhas_propostas = $proposta->getByPrestadorDetalhado($prestador_id, $filtro_status);

// Estatísticas das propostas
$stats_propostas = $proposta->getStatsPropostas($prestador_id);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas Propostas - Prestador | Chama Serviço</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #2c3e50;
            --secondary-color: #27ae60;
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
        
        .proposta-card {
            border-left: 4px solid var(--secondary-color);
        }
        
        .proposta-card.pendente {
            border-left-color: #f39c12;
        }
        
        .proposta-card.aceita {
            border-left-color: #27ae60;
        }
        
        .proposta-card.recusada {
            border-left-color: #e74c3c;
        }
        
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: 600;
        }
        
        .valor-proposta {
            color: var(--secondary-color);
            font-weight: 700;
            font-size: 1.2em;
        }
        
        .stats-mini {
            background: linear-gradient(135deg, #3498db, #5dade2);
            color: white;
            border-radius: 10px;
        }
        
        .negotiation-timeline {
            border-left: 3px solid #dee2e6;
            padding-left: 20px;
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 15px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -26px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #3498db;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="prestadorDashboard.php">
                <i class="fas fa-hammer me-2"></i>
                Chama Serviço - Prestador
            </a>
            
            <div class="navbar-nav ms-auto">
                <a class="nav-link" href="prestadorDashboard.php">
                    <i class="fas fa-arrow-left me-1"></i>
                    Voltar ao Dashboard
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>
                <i class="fas fa-handshake me-2"></i>
                Minhas Propostas
            </h2>
            <a href="oportunidades.php" class="btn btn-success">
                <i class="fas fa-plus me-1"></i>
                Enviar Nova Proposta
            </a>
        </div>

        <!-- Stats Cards Mini -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="stats-mini p-3 text-center">
                    <h4 class="mb-1"><?php echo $stats_propostas['total']; ?></h4>
                    <small>Total de Propostas</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-mini p-3 text-center" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <h4 class="mb-1"><?php echo $stats_propostas['pendentes']; ?></h4>
                    <small>Pendentes</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-mini p-3 text-center" style="background: linear-gradient(135deg, #27ae60, #2ecc71);">
                    <h4 class="mb-1"><?php echo $stats_propostas['aceitas']; ?></h4>
                    <small>Aceitas</small>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="stats-mini p-3 text-center" style="background: linear-gradient(135deg, #e74c3c, #c0392b);">
                    <h4 class="mb-1"><?php echo $stats_propostas['recusadas']; ?></h4>
                    <small>Recusadas</small>
                </div>
            </div>
        </div>

        <!-- Filtros -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="status" class="form-label">Filtrar por Status</label>
                        <select class="form-select" name="status" id="status">
                            <option value="">Todos os status</option>
                            <option value="pendente" <?php echo $filtro_status == 'pendente' ? 'selected' : ''; ?>>Pendentes</option>
                            <option value="aceita" <?php echo $filtro_status == 'aceita' ? 'selected' : ''; ?>>Aceitas</option>
                            <option value="recusada" <?php echo $filtro_status == 'recusada' ? 'selected' : ''; ?>>Recusadas</option>
                        </select>
                    </div>
                    
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-success me-2">
                            <i class="fas fa-filter me-1"></i>
                            Filtrar
                        </button>
                        <a href="minhas-propostas.php" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>
                            Limpar
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Lista de Propostas -->
        <?php if (empty($minhas_propostas)): ?>
            <div class="card text-center">
                <div class="card-body py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">Nenhuma proposta encontrada</h4>
                    <p class="text-muted">Você ainda não enviou propostas<?php echo $filtro_status ? " com status '{$filtro_status}'" : ''; ?>.</p>
                    <a href="oportunidades.php" class="btn btn-success">
                        <i class="fas fa-search me-1"></i>
                        Buscar Oportunidades
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($minhas_propostas as $item): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card proposta-card <?php echo $item['status']; ?> h-100">
                            <div class="card-header bg-light">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0">
                                        <i class="fas fa-briefcase me-2 text-primary"></i>
                                        <?php echo htmlspecialchars($item['servico_titulo']); ?>
                                    </h6>
                                    <span class="status-badge bg-<?php echo $item['status_class']; ?> text-white">
                                        <?php echo ucfirst($item['status']); ?>
                                    </span>
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>
                                    Enviada em <?php echo date('d/m/Y H:i', strtotime($item['data_proposta'])); ?>
                                </small>
                            </div>
                            
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <strong>💰 Sua Proposta:</strong><br>
                                        <span class="valor-proposta">
                                            R$ <?php echo number_format($item['valor'], 2, ',', '.'); ?>
                                        </span>
                                    </div>
                                    
                                    <div class="col-md-6 mb-3">
                                        <strong>⏰ Prazo:</strong><br>
                                        <span class="badge bg-info fs-6">
                                            <?php echo $item['prazo_execucao']; ?> dia(s)
                                        </span>
                                    </div>
                                </div>
                                
                                <?php if ($item['descricao']): ?>
                                <div class="mb-3">
                                    <strong>📝 Sua Descrição:</strong><br>
                                    <div class="bg-light p-3 rounded mt-2">
                                        <?php echo nl2br(htmlspecialchars($item['descricao'])); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <!-- Histórico de Negociação -->
                                <?php 
                                $historico = $proposta->getHistoricoNegociacao($item['id']);
                                if (!empty($historico)): 
                                ?>
                                <hr>
                                <div class="mb-3">
                                    <strong>🔄 Histórico de Negociação:</strong>
                                    <div class="negotiation-timeline mt-3">
                                        <?php foreach ($historico as $nego): ?>
                                        <div class="timeline-item">
                                            <div class="d-flex justify-content-between">
                                                <strong class="text-<?php echo $nego['tipo'] == 'contra_proposta' ? 'warning' : 'info'; ?>">
                                                    <?php if ($nego['tipo'] == 'contra_proposta'): ?>
                                                        💬 Contra-proposta do Cliente
                                                    <?php else: ?>
                                                        🔄 Sua Resposta
                                                    <?php endif; ?>
                                                </strong>
                                                <small class="text-muted"><?php echo date('d/m/Y H:i', strtotime($nego['data_negociacao'])); ?></small>
                                            </div>
                                            <div>Valor: R$ <?php echo number_format($nego['valor'], 2, ',', '.'); ?></div>
                                            <div>Prazo: <?php echo $nego['prazo']; ?> dia(s)</div>
                                            <?php if ($nego['observacoes']): ?>
                                            <div class="text-muted small">"<?php echo htmlspecialchars($nego['observacoes']); ?>"</div>
                                            <?php endif; ?>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="card-footer bg-transparent">
                                <div class="d-flex flex-wrap gap-2">
                                    <button class="btn btn-outline-info btn-sm" onclick="abrirModal('ver', <?php echo $item['solicitacao_id']; ?>)">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver Serviço
                                    </button>
                                    
                                    <?php if ($item['status'] == 'pendente'): ?>
                                        <button class="btn btn-outline-danger btn-sm" onclick="abrirModal('cancelar', <?php echo $item['id']; ?>)">
                                            <i class="fas fa-times me-1"></i>
                                            Cancelar
                                        </button>
                                    <?php elseif ($item['status'] == 'aceita'): ?>
                                        <?php
                                            // Buscar status do serviço para condicionar o botão "Iniciar Trabalho"
                                            $servicoDetalhes = $servico->getDetalhes($item['solicitacao_id'], $item['cliente_id'] ?? null);
                                            $statusServicoId = $servicoDetalhes['status_id'] ?? null;
                                        ?>
                                        <?php if ($statusServicoId == 3 || $statusServicoId == 4): ?>
                                            <button class="btn btn-success btn-sm" onclick="iniciarTrabalho(<?php echo $item['id']; ?>)">
                                                <i class="fas fa-play me-1"></i>
                                                Iniciar Trabalho
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-success btn-sm" disabled data-bs-toggle="tooltip" data-bs-placement="top" title="O serviço ainda não está pronto para iniciar.">
                                                <i class="fas fa-play me-1"></i>
                                                Iniciar Trabalho
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Modal genérica para ações -->
    <div class="modal fade" id="acaoModal" tabindex="-1" aria-labelledby="acaoModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="acaoModalLabel">Ação</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
          </div>
          <div class="modal-body" id="acaoModalConteudo">
            <!-- Conteúdo dinâmico -->
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Inicializa tooltips do Bootstrap
        document.addEventListener('DOMContentLoaded', function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.forEach(function (tooltipTriggerEl) {
                new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });

        function abrirModal(tipo, id) {
            let url = '';
            let titulo = '';
            let spinner = `<div class="d-flex justify-content-center align-items-center" style="height:120px;">
                <div class="spinner-border text-info" role="status"><span class="visually-hidden">Carregando...</span></div>
            </div>`;
            document.getElementById('acaoModalConteudo').innerHTML = spinner;

            if (tipo === 'ver') {
                url = 'detalhes-oportunidade.php?id=' + id;
                titulo = 'Detalhes do Serviço';
            } else if (tipo === 'editar') {
                url = 'editar-proposta.php?id=' + id;
                titulo = 'Editar Proposta';
            } else if (tipo === 'cancelar') {
                url = 'cancelar-proposta.php?id=' + id;
                titulo = 'Cancelar Proposta';
            }
            document.getElementById('acaoModalLabel').innerText = titulo;

            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(text || response.statusText); });
                    }
                    return response.text();
                })
                .then(html => {
                    document.getElementById('acaoModalConteudo').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('acaoModal')).show();
                })
                .catch((err) => {
                    document.getElementById('acaoModalConteudo').innerHTML = '<div class="alert alert-danger">Erro ao carregar conteúdo.<br>' + (err.message || '') + '</div>';
                    new bootstrap.Modal(document.getElementById('acaoModal')).show();
                    console.error('Erro ao carregar conteúdo:', err); // <-- Adicione esta linha para logar o erro no console
                });
        }

        function iniciarTrabalho(propostaId) {
            window.location.href = `iniciar-trabalho.php?proposta=${propostaId}`;
        }

        // Delegação de evento para botão "Confirmar Cancelamento" dentro do modal
        document.addEventListener('click', function(e) {
            if (e.target && e.target.id === 'btnConfirmarCancelamento') {
                const btn = e.target;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Cancelando...';
                const id = btn.getAttribute('data-id');
                // Captura o motivo do cancelamento do campo do modal
                const motivoInput = document.getElementById('motivoCancelamento');
                const motivo = motivoInput ? motivoInput.value : '';
                fetch('cancelar-proposta-acao.php?id=' + id, {
                    method: 'POST',
                    body: new URLSearchParams({ motivo: motivo })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.sucesso) {
                        document.getElementById('cancelarStatus').innerHTML = '<div class="alert alert-success">Proposta cancelada com sucesso!</div>';
                        setTimeout(() => { location.reload(); }, 1200);
                    } else {
                        document.getElementById('cancelarStatus').innerHTML = '<div class="alert alert-danger">Erro ao cancelar proposta.</div>';
                        btn.disabled = false;
                        btn.innerHTML = 'Confirmar Cancelamento';
                    }
                })
                .catch(() => {
                    document.getElementById('cancelarStatus').innerHTML = '<div class="alert alert-danger">Erro de comunicação.</div>';
                    btn.disabled = false;
                    btn.innerHTML = 'Confirmar Cancelamento';
                });
            }
        });
    </script>
</body>
</html>

<!-- Sugestões de melhoria:

1. Validação e feedback mais claros na edição/cancelamento de propostas
   - Exibir mensagens de erro/sucesso mais detalhadas na modal.
   - Validar campos obrigatórios antes de enviar o formulário de edição.

2. Paginação para lista de propostas
   - Se houver muitas propostas, implemente paginação para evitar carregamento lento.

3. Filtro por período/data
   - Permitir filtrar propostas por data de envio ou período.

4. Atualização automática (AJAX polling)
   - Atualizar a lista de propostas automaticamente sem recarregar a página.

5. Histórico de status da proposta
   - Exibir uma linha do tempo com todas as mudanças de status da proposta.

6. Acessibilidade
   - Adicionar atributos ARIA e melhorar a navegação por teclado nas modais.

7. Confirmação antes de cancelar
   - Exibir um modal de confirmação antes de cancelar uma proposta.

8. Exportação de propostas
   - Permitir exportar a lista de propostas em PDF ou Excel.

9. Notificações em tempo real
   - Integrar WebSocket ou AJAX para avisar o prestador sobre mudanças de status.

10. Melhorias de segurança
    - Validar permissões no backend para garantir que só o dono da proposta possa editar/cancelar.
    - Sanitizar todas as entradas e saídas para evitar XSS/SQL Injection.

11. Melhorias visuais
    - Adicionar loading spinners nos botões durante requisições.
    - Exibir ícones diferentes para cada status de proposta.

12. Logs e auditoria
    - Registrar todas as ações de edição/cancelamento para auditoria.

13. Documentação
    - Adicionar comentários/documentação no código para facilitar manutenção.

14. Testes automatizados
    - Criar testes automatizados para os principais fluxos de proposta.

Essas melhorias podem ser implementadas gradualmente, conforme a prioridade do projeto. -->
