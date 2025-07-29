<?php
// aceitar-proposta.php

require_once __DIR__ . '/config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método não permitido.']);
    exit;
}

$proposta_id = isset($_POST['proposta_id']) ? intval($_POST['proposta_id']) : 0;
$servico_id = isset($_POST['servico_id']) ? intval($_POST['servico_id']) : 0;

if (!$proposta_id || !$servico_id) {
    echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
    exit;
}

try {
    $db = (new Database())->getConnection();
    $db->beginTransaction();

    // 1. Atualiza status da proposta para 'aceita'
    $sqlProposta = "UPDATE tb_proposta SET status = 'aceita' WHERE id = :proposta_id";
    $stmt = $db->prepare($sqlProposta);
    $stmt->bindValue(':proposta_id', $proposta_id, PDO::PARAM_INT);
    $stmt->execute();

    // 2. Atualiza status da solicitação para "Serviço em execução" (id 4)
    $sqlServico = "UPDATE tb_solicita_servico SET status_id = 4 WHERE id = :servico_id";
    $stmt = $db->prepare($sqlServico);
    $stmt->bindValue(':servico_id', $servico_id, PDO::PARAM_INT);
    $stmt->execute();

    // 3. Recusa todas as outras propostas desse serviço
    $sqlRecusar = "UPDATE tb_proposta SET status = 'recusada' WHERE solicitacao_id = :servico_id AND id != :proposta_id";
    $stmt = $db->prepare($sqlRecusar);
    $stmt->bindValue(':servico_id', $servico_id, PDO::PARAM_INT);
    $stmt->bindValue(':proposta_id', $proposta_id, PDO::PARAM_INT);
    $stmt->execute();

    $db->commit();

    echo json_encode(['success' => true]);
} catch (Exception $e) {
    if (isset($db) && $db->inTransaction()) $db->rollBack();
    echo json_encode(['success' => false, 'message' => 'Erro ao aceitar proposta.']);
}
