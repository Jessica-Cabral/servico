<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cliente_id']) && !isset($_SESSION['prestador_id'])) {
    http_response_code(403);
    echo json_encode(['success' => false, 'error' => 'Não autorizado']);
    exit();
}

require_once '../models/Notificacao.php';

$notificacao = new Notificacao();
$user_id = $_SESSION['cliente_id'] ?? $_SESSION['prestador_id'];
$user_type = $_SESSION['user_type'] ?? 'cliente';

try {
    // Log para depuração
    file_put_contents(__DIR__ . '/debug_notifications.txt', date('Y-m-d H:i:s') . " - user_id: $user_id, user_type: $user_type\n", FILE_APPEND);

    // Buscar notificações não lidas
    $notificacoes = $notificacao->getNaoLidas($user_id, $user_type);
    $total = $notificacao->contarNaoLidas($user_id, $user_type);

    // Log resultado
    file_put_contents(__DIR__ . '/debug_notifications.txt', print_r(['notificacoes' => $notificacoes, 'total' => $total], true), FILE_APPEND);

    echo json_encode([
        'success' => true,
        'notificacoes' => $notificacoes,
        'total' => $total
    ]);
    
} catch (Exception $e) {
    error_log("Erro API notificações: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro interno do servidor'
    ]);
}
?>
