<?php
// Endpoint AJAX para gerenciar serviços (MVC)
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cliente_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit();
}

require_once __DIR__ . '/../../models/ServicoClass.php';
require_once __DIR__ . '/../../models/PropostaClass.php';
require_once __DIR__ . '/../../models/NotificacaoClass.php';
require_once '../../config/database.php';

$servico = new Servico();
$proposta = new Proposta();
$notificacao = new Notificacao();

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        // Processa ação de cancelamento de serviço
        case 'cancelar':
            $servico_id = $_POST['servico_id'];
            $motivo = $_POST['motivo'] ?? '';
            $motivo_outro = $_POST['motivo_outro'] ?? '';

            $cliente_id = $_SESSION['cliente_id'];

            // Verificar se o serviço pertence ao cliente
            $detalhes_servico = $servico->getDetalhes($servico_id, $cliente_id);
            if (!$detalhes_servico) {
                echo json_encode(['success' => false, 'message' => 'Serviço não encontrado.']);
                exit();
            }

            // Verificar se o serviço pode ser cancelado (status 1 ou 2)
            if (!in_array($detalhes_servico['status_id'], [1, 2])) {
                echo json_encode(['success' => false, 'message' => 'Este serviço não pode ser cancelado no status atual.']);
                exit();
            }

            // Validar dados de entrada
            if (empty($servico_id)) {
                echo json_encode(['success' => false, 'message' => 'ID do serviço não fornecido.']);
                exit();
            }

            if ($motivo === 'outro' && empty($motivo_outro)) {
                echo json_encode(['success' => false, 'message' => 'Descreva o motivo do cancelamento.']);
                exit();
            }

            // Montar motivo completo
            $motivo_completo = '';
            if ($motivo) {
                $motivos_texto = [
                    'nao_preciso_mais' => 'Não preciso mais do serviço',
                    'mudar_detalhes' => 'Quero refazer com detalhes diferentes',
                    'muito_caro' => 'Propostas muito caras',
                    'demora_propostas' => 'Demora para receber propostas',
                    'outro' => $motivo_outro
                ];
                $motivo_completo = $motivos_texto[$motivo] ?? $motivo;
            }

            // Conectar ao banco
            $database = new Database();
            $conn = $database->getConnection();

            // Iniciar transação
            $conn->beginTransaction();

            // Atualizar status do serviço para cancelado (status_id = 6)
            $stmt = $conn->prepare("UPDATE tb_solicita_servico SET status_id = 6 WHERE id = :servico_id");
            $stmt->bindParam(':servico_id', $servico_id);
            $stmt->execute();

            // Recusar todas as propostas pendentes
            $stmt = $conn->prepare("UPDATE tb_proposta SET status = 'recusada' WHERE solicitacao_id = :servico_id AND status = 'pendente'");
            $stmt->bindParam(':servico_id', $servico_id);
            $stmt->execute();

            // Registrar o motivo do cancelamento (se houver)
            if ($motivo || $motivo_outro) {
                $motivo_final = $motivo === 'outro' ? $motivo_outro : $motivo;
                $stmt = $conn->prepare("
                    INSERT INTO tb_cancelamento (servico_id, cliente_id, motivo, data_cancelamento) 
                    VALUES (:servico_id, :cliente_id, :motivo, NOW())
                ");
                $stmt->bindParam(':servico_id', $servico_id);
                $stmt->bindParam(':cliente_id', $cliente_id);
                $stmt->bindParam(':motivo', $motivo_final);
                $stmt->execute();
            }

            // Notificar prestadores que enviaram propostas
            $stmt = $conn->prepare("
                SELECT DISTINCT prestador_id 
                FROM tb_proposta 
                WHERE solicitacao_id = :servico_id
            ");
            $stmt->bindParam(':servico_id', $servico_id);
            $stmt->execute();
            $prestadores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($prestadores as $prestador) {
                $stmt = $conn->prepare("
                    INSERT INTO tb_notificacao (pessoa_id, titulo, mensagem, tipo, referencia_id) 
                    VALUES (:prestador_id, 'Serviço Cancelado', 'O cliente cancelou a solicitação de serviço.', 'servico_cancelado', :servico_id)
                ");
                $stmt->bindParam(':prestador_id', $prestador['prestador_id']);
                $stmt->bindParam(':servico_id', $servico_id);
                $stmt->execute();
            }

            $conn->commit();
            echo json_encode(['success' => true, 'message' => 'Serviço cancelado com sucesso.']);

            break;

        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
} catch (Exception $e) {
    error_log("Erro ao gerenciar serviço: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor.']);
}
?>
