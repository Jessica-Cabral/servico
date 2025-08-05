<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../models/Proposta.class.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['sucesso' => false, 'msg' => 'ID não informado']);
    exit;
}

$proposta = new Proposta();
$result = $proposta->cancelar($id);

echo json_encode(['sucesso' => $result]);
echo json_encode(['sucesso' => $result]);
