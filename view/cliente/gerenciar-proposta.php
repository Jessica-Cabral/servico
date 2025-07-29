<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cliente_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit();
}

require_once __DIR__ . '/../../models/Proposta.php';
require_once __DIR__ . '/../../models/Servico.php';
require_once __DIR__ . '/../../models/Notificacao.php';

$proposta = new Proposta();
$servico = new Servico();
$notificacao = new Notificacao();

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'aceitar':
            $proposta_id = $input['proposta_id'] ?? $_POST['proposta_id'];
            
            // Buscar detalhes da proposta
            $detalhes_proposta = $proposta->getDetalhes($proposta_id);
            if (!$detalhes_proposta) {
                echo json_encode(['success' => false, 'message' => 'Proposta não encontrada.']);
                exit();
            }
            
            // Verificar se o serviço pertence ao cliente
            $servico_detalhes = $servico->getDetalhes($detalhes_proposta['solicitacao_id'], $_SESSION['cliente_id']);
            if (!$servico_detalhes) {
                echo json_encode(['success' => false, 'message' => 'Serviço não encontrado.']);
                exit();
            }
            
            if ($proposta->aceitar($proposta_id, $_SESSION['cliente_id'])) {
                // Enviar notificação para o prestador
                $notificacao->propostaAceita(
                    $detalhes_proposta['prestador_id'], 
                    $detalhes_proposta['solicitacao_id'], 
                    $servico_detalhes['titulo']
                );
                
                echo json_encode(['success' => true, 'message' => 'Proposta aceita com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao aceitar proposta.']);
            }
            break;
            
        case 'contra_proposta':
            $dados = [
                'proposta_id' => $_POST['proposta_id'],
                'cliente_id' => $_SESSION['cliente_id'],
                'valor' => $_POST['valor'],
                'prazo' => $_POST['prazo'],
                'observacoes' => $_POST['observacoes'] ?? ''
            ];
            
            // Buscar detalhes da proposta
            $detalhes_proposta = $proposta->getDetalhes($dados['proposta_id']);
            if (!$detalhes_proposta) {
                echo json_encode(['success' => false, 'message' => 'Proposta não encontrada.']);
                exit();
            }
            
            // Verificar se o serviço pertence ao cliente
            $servico_detalhes = $servico->getDetalhes($detalhes_proposta['solicitacao_id'], $_SESSION['cliente_id']);
            if (!$servico_detalhes) {
                echo json_encode(['success' => false, 'message' => 'Serviço não encontrado.']);
                exit();
            }
            
            if ($proposta->criarContraProposta($dados)) {
                // Enviar notificação para o prestador
                $notificacao->contraProposta(
                    $detalhes_proposta['prestador_id'], 
                    $detalhes_proposta['solicitacao_id'], 
                    $servico_detalhes['titulo']
                );
                
                echo json_encode(['success' => true, 'message' => 'Contra-proposta enviada com sucesso!']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao enviar contra-proposta.']);
            }
            break;
            
        case 'recusar':
            $dados = [
                'proposta_id' => $_POST['proposta_id'],
                'cliente_id' => $_SESSION['cliente_id'],
                'motivo' => $_POST['motivo'] ?? ''
            ];
            
            // Buscar detalhes da proposta
            $detalhes_proposta = $proposta->getDetalhes($dados['proposta_id']);
            if (!$detalhes_proposta) {
                echo json_encode(['success' => false, 'message' => 'Proposta não encontrada.']);
                exit();
            }
            
            // Verificar se o serviço pertence ao cliente
            $servico_detalhes = $servico->getDetalhes($detalhes_proposta['solicitacao_id'], $_SESSION['cliente_id']);
            if (!$servico_detalhes) {
                echo json_encode(['success' => false, 'message' => 'Serviço não encontrado.']);
                exit();
            }
            
            if ($proposta->recusar($dados)) {
                // Enviar notificação para o prestador
                $notificacao->propostaRecusada(
                    $detalhes_proposta['prestador_id'], 
                    $detalhes_proposta['solicitacao_id'], 
                    $servico_detalhes['titulo']
                );
                
                echo json_encode(['success' => true, 'message' => 'Proposta recusada.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erro ao recusar proposta.']);
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'message' => 'Ação inválida.']);
    }
    
} catch (Exception $e) {
    error_log("Erro ao gerenciar proposta: " . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Erro interno do servidor.']);
}
?>
