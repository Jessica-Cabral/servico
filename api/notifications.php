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
    // Buscar notificações não lidas
    $notificacoes = $notificacao->getNaoLidas($user_id, $user_type);
    $total = $notificacao->contarNaoLidas($user_id, $user_type);

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

<script>
// Adicionar ao final dos arquivos de dashboard
setInterval(function() {
    fetch('../../api/notifications.php')
        .then(response => response.json())
        .then(data => {
            if (data.total > 0) {
                // Atualizar interface com notificações
                updateNotificationBadge(data.total);
            }
        })
        .catch(console.error);
}, 30000); // Verificar a cada 30 segundos
</script>
