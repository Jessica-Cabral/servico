<?php
// Endpoint AJAX para gerenciar propostas (MVC)

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['cliente_id'])) {
    echo json_encode(['success' => false, 'message' => 'Não autorizado']);
    exit();
}

require_once __DIR__ . '/../../models/PropostaClass.php';
require_once __DIR__ . '/../../models/ServicoClass.php';
require_once __DIR__ . '/../../models/NotificacaoClass.php';

$proposta = new Proposta();
$servico = new Servico();
$notificacao = new Notificacao();

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? $_POST['action'] ?? '';

// Adicione log para depuração
file_put_contents(__DIR__ . '/debug_gerenciar_proposta.txt', print_r([
    'POST' => $_POST,
    'INPUT' => file_get_contents('php://input')
], true), FILE_APPEND);

try {
    // Processa ações: aceitar, contra-proposta, recusar
    switch ($action) {
        case 'aceitar':
            try {
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
                
                // 1. Verificar se a proposta pertence ao cliente logado
                $stmt = $conn->prepare("
                    SELECT s.id as solicitacao_id, s.cliente_id 
                    FROM tb_proposta p 
                    INNER JOIN tb_solicita_servico s ON s.id = p.solicitacao_id
                    WHERE p.id = :proposta_id AND p.status = 'pendente'
                ");
                $stmt->bindParam(':proposta_id', $proposta_id);
                $stmt->execute();
                $proposta = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$proposta || $proposta['cliente_id'] != $cliente_id) {
                    echo json_encode(['success' => false, 'message' => 'Proposta não encontrada ou você não tem permissão.']);
                    exit;
                }

                $solicitacao_id = $proposta['solicitacao_id'];

                // 2. Iniciar transação para garantir consistência
                $conn->beginTransaction();

                // 3. Atualizar status da proposta aceita
                $stmt = $conn->prepare("UPDATE tb_proposta SET status = 'aceita' WHERE id = :proposta_id");
                $stmt->bindParam(':proposta_id', $proposta_id);
                $stmt->execute();

                // 4. Recusar todas as outras propostas para o mesmo serviço
                $stmt = $conn->prepare("UPDATE tb_proposta SET status = 'recusada' WHERE solicitacao_id = :solicitacao_id AND id != :proposta_id");
                $stmt->bindParam(':solicitacao_id', $solicitacao_id);
                $stmt->bindParam(':proposta_id', $proposta_id);
                $stmt->execute();

                // 5. Atualizar status da solicitação de serviço para "Proposta Aceita"
                $stmt = $conn->prepare("UPDATE tb_solicita_servico SET status_id = 3 WHERE id = :solicitacao_id");
                $stmt->bindParam(':solicitacao_id', $solicitacao_id);
                $stmt->execute();

                // 6. Criar notificação para o prestador
                $stmt = $conn->prepare("SELECT prestador_id FROM tb_proposta WHERE id = :proposta_id");
                $stmt->bindParam(':proposta_id', $proposta_id);
                $stmt->execute();
                $prestador = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($prestador) {
                    $stmt = $conn->prepare("
                        INSERT INTO tb_notificacao (pessoa_id, titulo, mensagem, tipo, referencia_id) 
                        VALUES (:prestador_id, 'Proposta Aceita!', 'Sua proposta foi aceita pelo cliente.', 'proposta_aceita', :proposta_id)
                    ");
                    $stmt->bindParam(':prestador_id', $prestador['prestador_id']);
                    $stmt->bindParam(':proposta_id', $proposta_id);
                    $stmt->execute();
                }

                // 7. Commit da transação
                $conn->commit();

                echo json_encode(['success' => true, 'message' => 'Proposta aceita com sucesso!']);

            } catch (Exception $e) {
                // Em caso de erro, fazer rollback
                if ($conn->inTransaction()) {
                    $conn->rollBack();
                }
                echo json_encode(['success' => false, 'message' => 'Erro ao aceitar proposta: ' . $e->getMessage()]);
            }
            break;
        
        case 'recusar':
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
            
            $proposta_id = $_POST['proposta_id'] ?? 0;

            if ($proposta_id <= 0) {
                echo json_encode(['success' => false, 'message' => 'ID da proposta inválido.']);
                exit;
            }

            try {
                // Verificar se a proposta pertence ao cliente logado
                $stmt = $conn->prepare("
                    SELECT s.cliente_id 
                    FROM tb_proposta p 
                    INNER JOIN tb_solicita_servico s ON s.id = p.solicitacao_id
                    WHERE p.id = :proposta_id
                ");
                $stmt->bindParam(':proposta_id', $proposta_id);
                $stmt->execute();
                $proposta = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$proposta || $proposta['cliente_id'] != $cliente_id) {
                    echo json_encode(['success' => false, 'message' => 'Proposta não encontrada ou você não tem permissão.']);
                    exit;
                }

                // Atualizar status da proposta para recusada
                $stmt = $conn->prepare("UPDATE tb_proposta SET status = 'recusada' WHERE id = :proposta_id");
                $stmt->bindParam(':proposta_id', $proposta_id);
                $stmt->execute();

                echo json_encode(['success' => true, 'message' => 'Proposta recusada com sucesso.']);

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Erro ao recusar proposta: ' . $e->getMessage()]);
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
            
            $proposta_id = $_POST['proposta_id'] ?? 0;
            $valor = $_POST['valor'] ?? 0;
            $prazo = $_POST['prazo'] ?? 0;
            $observacoes = $_POST['observacoes'] ?? '';

            if ($proposta_id <= 0 || $valor <= 0 || $prazo <= 0) {
                echo json_encode(['success' => false, 'message' => 'Dados inválidos para contra-proposta.']);
                exit;
            }

            try {
                // Verificar se a proposta pertence ao cliente logado
                $stmt = $conn->prepare("
                    SELECT s.cliente_id 
                    FROM tb_proposta p 
                    INNER JOIN tb_solicita_servico s ON s.id = p.solicitacao_id
                    WHERE p.id = :proposta_id
                ");
                $stmt->bindParam(':proposta_id', $proposta_id);
                $stmt->execute();
                $proposta = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$proposta || $proposta['cliente_id'] != $cliente_id) {
                    echo json_encode(['success' => false, 'message' => 'Proposta não encontrada ou você não tem permissão.']);
                    exit;
                }

                // Atualizar dados da proposta
                $stmt = $conn->prepare("UPDATE tb_proposta SET valor = :valor, prazo_execucao = :prazo, descricao = :observacoes WHERE id = :proposta_id");
                $stmt->bindParam(':proposta_id', $proposta_id);
                $stmt->bindParam(':valor', $valor);
                $stmt->bindParam(':prazo', $prazo);
                $stmt->bindParam(':observacoes', $observacoes);
                $stmt->execute();

                echo json_encode(['success' => true, 'message' => 'Contra-proposta enviada com sucesso.']);

            } catch (Exception $e) {
                echo json_encode(['success' => false, 'message' => 'Erro ao enviar contra-proposta: ' . $e->getMessage()]);
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
