<?php
header('Content-Type: application/json');

// Recebe os filtros via POST (ou GET)
$dataInicio = $_POST['data_inicio'] ?? null;
$dataFim = $_POST['data_fim'] ?? null;
$tipoServico = $_POST['tipo_servico'] ?? 'todos';

// Simulação de consulta ao banco de dados (substitua pelo seu código real)
$servicosRealizados = 150;
$servicosAbertos = 25;
$totalUsuarios = 320;

// Aplicando filtros (simulação)
if ($dataInicio && $dataFim) {
    $servicosRealizados = rand(50, 100); // Simula filtro por data
    $servicosAbertos = rand(5, 20);
}

if ($tipoServico !== 'todos') {
    $servicosRealizados = rand(30, 70); // Simula filtro por tipo
    $servicosAbertos = rand(3, 15);
}

// Retorna os dados filtrados
echo json_encode([
    'servicos_realizados' => $servicosRealizados,
    'servicos_abertos' => $servicosAbertos,
    'total_usuarios' => $totalUsuarios,
    'grafico_mensal' => [30, 45, 60, 50, 70, 90, 100] // Dados do gráfico
]);
?>