<?php
require_once __DIR__ . '/../../models/Proposta.class.php';
header('Content-Type: application/json');

$id = $_POST['id'] ?? null;
$valor = $_POST['valor'] ?? null;
$prazo = $_POST['prazo'] ?? null;
$descricao = $_POST['descricao'] ?? '';

if (!$id || !$valor || !$prazo) {
    echo json_encode(['sucesso' => false, 'erro' => 'Dados obrigatórios não informados.']);
    exit;
}

$proposta = new Proposta();
$ok = $proposta->atualizar($id, $valor, $prazo, $descricao);

if ($ok) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['sucesso' => false, 'erro' => 'Erro ao atualizar proposta.']);
}
