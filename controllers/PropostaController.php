<?php
// controllers/PropostaController.class.php

// As classes Proposta e Notificacao serão carregadas automaticamente pelo autoloader.

class PropostaController
{
    private $propostaModel;
    private $notificacaoModel;

    public function __construct()
    {
        // Instancia os Models na inicialização do controlador
        $this->propostaModel = new Proposta();
        $this->notificacaoModel = new Notificacao();
    }

    /**
     * Processa a aceitação de uma proposta.
     * Este método é chamado pelo roteador em uma requisição POST.
     * Rota: /servico/proposta/aceitar
     */
    public function aceitar()
    {
        // A autenticação e o ID do cliente já foram verificados pelo roteador.
        $cliente_id = $_SESSION['cliente_id'];
        $propostaId = $_POST['proposta_id'] ?? null;

        if (!$propostaId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID da proposta não fornecido.']);
            return;
        }

        try {
            $result = $this->propostaModel->aceitar($propostaId, $cliente_id);
            if ($result) {
                $detalhes = $this->propostaModel->getDetalhes($propostaId);
                $this->notificacaoModel->propostaAceita($detalhes['prestador_id'], $detalhes['solicitacao_id'], $detalhes['servico_titulo']);
                echo json_encode(['success' => true, 'message' => 'Proposta aceita com sucesso!']);
            } else {
                throw new Exception('Erro ao aceitar a proposta no banco de dados.');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
        }
    }

    /**
     * Processa a recusa de uma proposta.
     * Este método é chamado pelo roteador em uma requisição POST.
     * Rota: /servico/proposta/recusar
     */
    public function recusar()
    {
        $cliente_id = $_SESSION['cliente_id'];
        $propostaId = $_POST['proposta_id'] ?? null;
        $motivo = $_POST['motivo'] ?? '';

        if (!$propostaId) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID da proposta não fornecido.']);
            return;
        }

        try {
            $dados = ['proposta_id' => $propostaId, 'cliente_id' => $cliente_id, 'motivo' => $motivo];
            $result = $this->propostaModel->recusar($dados);

            if ($result) {
                $detalhes = $this->propostaModel->getDetalhes($propostaId);
                $this->notificacaoModel->propostaRecusada($detalhes['prestador_id'], $detalhes['solicitacao_id'], $detalhes['servico_titulo']);
                echo json_encode(['success' => true, 'message' => 'Proposta recusada com sucesso.']);
            } else {
                throw new Exception('Erro ao recusar a proposta no banco de dados.');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
        }
    }

    /**
     * Processa o envio de uma contra-proposta.
     * Este método é chamado pelo roteador em uma requisição POST.
     * Rota: /servico/proposta/contraproposta
     */
    public function contraProposta()
    {
        $cliente_id = $_SESSION['cliente_id'];
        $dados = [
            'proposta_id' => $_POST['proposta_id'] ?? null,
            'cliente_id' => $cliente_id,
            'valor' => $_POST['valor'] ?? null,
            'prazo' => $_POST['prazo'] ?? null,
            'observacoes' => $_POST['observacoes'] ?? ''
        ];

        if (empty($dados['proposta_id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Dados inválidos.']);
            return;
        }

        try {
            $result = $this->propostaModel->criarContraProposta($dados);
            if ($result) {
                $detalhes = $this->propostaModel->getDetalhes($dados['proposta_id']);
                $this->notificacaoModel->contraProposta($detalhes['prestador_id'], $detalhes['solicitacao_id'], $detalhes['servico_titulo']);
                echo json_encode(['success' => true, 'message' => 'Contra-proposta enviada com sucesso.']);
            } else {
                throw new Exception('Erro ao criar contra-proposta.');
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erro: ' . $e->getMessage()]);
        }
    }
}
