<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cliente_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit();
}

require_once __DIR__ . '/../../models/Servico.class.php';
require_once __DIR__ . '/../../models/Proposta.class.php';
require_once __DIR__ . '/../../models/Notificacao.class.php';

$servico = new Servico();
$proposta = new Proposta();
$notificacao = new Notificacao();

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'cancelar':
            $servico_id = $_POST['servico_id'];
            $motivo = $_POST['motivo'] ?? '';
            $motivo_outro = $_POST['motivo_outro'] ?? '';
            
            // Verificar se o serviço pertence ao cliente
            $detalhes_servico = $servico->getDetalhes($servico_id, $_SESSION['cliente_id']);
            if (!$detalhes_servico) {
                echo json_encode(['success' => false, 'message' => 'Serviço não encontrado.']);
                exit();
            }
            
            // Verificar se o serviço pode ser cancelado (status 1 ou 2)
            if (!in_array($detalhes_servico['status_id'], [1, 2])) {
                echo json_encode(['success' => false, 'message' => 'Este serviço não pode ser cancelado no status atual.']);
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
            
            if ($servico->cancelar($servico_id, $_SESSION['cliente_id'], $motivo_completo)) {
                // Buscar propostas pendentes para notificar prestadores
                $propostas_pendentes = $proposta->getByServico($servico_id, 'pendente');
                
                foreach ($propostas_pendentes as $prop) {
                    $notificacao->servicoCancelado(
                        $prop['prestador_id'], 
                        $servico_id, 
                        $detalhes_servico['titulo']
                    );
                }
                
                echo json_encode([
                    'success' => true, 
                    'message' => 'Serviço cancelado com sucesso. Os prestadores foram notificados.'
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao cancelar serviço.']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
    
} catch (Exception $e) {
    error_log("Erro ao gerenciar serviço: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor.']);
}
?>
