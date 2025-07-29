<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Serviços</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- CSS Personalizado -->
    <link rel="stylesheet" href="../assets/css/painel.css">
</head>
<body>
    
    <div class="container mt-5">
        <h1 class="text-center mb-4">Dashboard de Serviços</h1>
        <!-- Adicione esta seção antes dos cards -->
<div class="container mt-4">
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0">Filtros</h5>
        </div>
        <div class="card-body">
            <form id="filtros-form">
                <div class="row">
                    <!-- Filtro por Data -->
                    <div class="col-md-4 mb-3">
                        <label for="data-inicio" class="form-label">Data Início</label>
                        <input type="date" class="form-control" id="data-inicio">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="data-fim" class="form-label">Data Fim</label>
                        <input type="date" class="form-control" id="data-fim">
                    </div>
                    
                    <!-- Filtro por Tipo de Serviço -->
                    <div class="col-md-4 mb-3">
                        <label for="tipo-servico" class="form-label">Tipo de Serviço</label>
                        <select class="form-select" id="tipo-servico">
                            <option value="todos">Todos</option>
                            <option value="manutencao">Manutenção</option>
                            <option value="instalacao">Instalação</option>
                            <option value="consulta">Consulta</option>
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> Filtrar
                </button>
                <button type="button" id="limpar-filtros" class="btn btn-secondary ms-2">
                    <i class="fas fa-broom"></i> Limpar
                </button>
            </form>
        </div>
    </div>
</div>
        
        <div class="row">
            <!-- Card de Serviços Realizados -->
            <div class="col-md-4 mb-4">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-check-circle fa-3x mb-3"></i>
                        <h5 class="card-title">Serviços Realizados</h5>
                        <p class="card-text display-4" id="servicos-realizados">0</p>
                    </div>
                </div>
            </div>
            
            <!-- Card de Serviços Abertos -->
            <div class="col-md-4 mb-4">
                <div class="card bg-warning text-dark">
                    <div class="card-body text-center">
                        <i class="fas fa-hourglass-half fa-3x mb-3"></i>
                        <h5 class="card-title">Serviços Abertos</h5>
                        <p class="card-text display-4" id="servicos-abertos">0</p>
                    </div>
                </div>
            </div>
            
            <!-- Card de Total de Usuários -->
            <div class="col-md-4 mb-4">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <i class="fas fa-users fa-3x mb-3"></i>
                        <h5 class="card-title">Total de Usuários</h5>
                        <p class="card-text display-4" id="total-usuarios">0</p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Gráfico (opcional, usando Chart.js) -->
        <div class="row mt-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Estatísticas Mensais</h5>
                        <canvas id="grafico-servicos" height="100"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS e dependências -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Chart.js para gráficos -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- JS Personalizado -->
    <script src="scripts.js">
        document.addEventListener('DOMContentLoaded', function() {
    // Carrega os dados iniciais
    carregarDados();

    // Quando o formulário de filtros é enviado
    document.getElementById('filtros-form').addEventListener('submit', function(e) {
        e.preventDefault();
        carregarDados();
    });

    // Botão "Limpar Filtros"
    document.getElementById('limpar-filtros').addEventListener('click', function() {
        document.getElementById('data-inicio').value = '';
        document.getElementById('data-fim').value = '';
        document.getElementById('tipo-servico').value = 'todos';
        carregarDados();
    });
});

// Função para carregar dados com filtros
function carregarDados() {
    const dataInicio = document.getElementById('data-inicio').value;
    const dataFim = document.getElementById('data-fim').value;
    const tipoServico = document.getElementById('tipo-servico').value;

    // Dados para enviar ao servidor
    const filtros = {
        data_inicio: dataInicio,
        data_fim: dataFim,
        tipo_servico: tipoServico
    };

    // Faz a requisição AJAX
    fetch('backend/dados.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(filtros)
    })
    .then(response => response.json())
    .then(data => {
        // Atualiza os cards
        document.getElementById('servicos-realizados').textContent = data.servicos_realizados;
        document.getElementById('servicos-abertos').textContent = data.servicos_abertos;
        document.getElementById('total-usuarios').textContent = data.total_usuarios;

        // Atualiza o gráfico (se existir)
        if (window.graficoServicos) {
            window.graficoServicos.data.datasets[0].data = data.grafico_mensal;
            window.graficoServicos.update();
        }
    })
    .catch(error => console.error('Erro ao filtrar dados:', error));
}
    </script>
</body>
</html>