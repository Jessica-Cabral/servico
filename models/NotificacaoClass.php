<?php

// A classe Database será carregada automaticamente pelo autoloader.

class Notificacao
{
    private $conn;
    private $table = 'tb_notificacao';

    public function __construct()
    {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    /**
     * Cria uma nova notificação no banco de dados.
     * @param array $dados Array com os dados da notificação.
     * @return bool True se a criação for bem-sucedida, false caso contrário.
     */
    public function criar($dados)
    {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (usuario_id, tipo_usuario, titulo, mensagem, tipo_notificacao, referencia_id) 
                      VALUES (:usuario_id, :tipo_usuario, :titulo, :mensagem, :tipo_notificacao, :referencia_id)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':usuario_id', $dados['usuario_id']);
            $stmt->bindValue(':tipo_usuario', $dados['tipo_usuario']);
            $stmt->bindValue(':titulo', $dados['titulo']);
            $stmt->bindValue(':mensagem', $dados['mensagem']);
            $stmt->bindValue(':tipo_notificacao', $dados['tipo_notificacao']);
            $stmt->bindValue(':referencia_id', $dados['referencia_id'] ?? null);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao criar notificação: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retorna as notificações não lidas para um usuário específico.
     * @param int $usuario_id ID do usuário.
     * @param string $tipo_usuario Tipo do usuário ('cliente' ou 'prestador').
     * @return array Array de notificações não lidas.
     */
    public function getNaoLidas($usuario_id, $tipo_usuario)
    {
        try {
            $query = "SELECT * FROM " . $this->table . " 
                      WHERE usuario_id = :usuario_id AND tipo_usuario = :tipo_usuario AND lida = 0
                      ORDER BY data_criacao DESC 
                      LIMIT 10";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':usuario_id', $usuario_id);
            $stmt->bindValue(':tipo_usuario', $tipo_usuario);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar notificações não lidas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Marca uma notificação como lida.
     * @param int $notificacao_id ID da notificação.
     * @param int $usuario_id ID do usuário.
     * @return bool True se a notificação foi marcada como lida, false caso contrário.
     */
    public function marcarComoLida($notificacao_id, $usuario_id)
    {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET lida = 1, data_leitura = NOW() 
                      WHERE id = :id AND usuario_id = :usuario_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $notificacao_id);
            $stmt->bindValue(':usuario_id', $usuario_id);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao marcar notificação como lida: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Conta o número de notificações não lidas para um usuário.
     * @param int $usuario_id ID do usuário.
     * @param string $tipo_usuario Tipo do usuário.
     * @return int O número total de notificações não lidas.
     */
    public function contarNaoLidas($usuario_id, $tipo_usuario)
    {
        try {
            $query = "SELECT COUNT(*) as total FROM " . $this->table . " 
                      WHERE usuario_id = :usuario_id AND tipo_usuario = :tipo_usuario AND lida = 0";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':usuario_id', $usuario_id);
            $stmt->bindValue(':tipo_usuario', $tipo_usuario);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($result['total'] ?? 0);
        } catch (Exception $e) {
            error_log("Erro ao contar notificações não lidas: " . $e->getMessage());
            return 0;
        }
    }

    // Métodos específicos para diferentes tipos de notificação
    // A lógica de criação está centralizada no método 'criar' para reutilização.
    public function novaProposta($cliente_id, $servico_id, $prestador_nome)
    {
        return $this->criar([
            'usuario_id' => $cliente_id,
            'tipo_usuario' => 'cliente',
            'titulo' => 'Nova Proposta Recebida',
            'mensagem' => "O prestador {$prestador_nome} enviou uma proposta para seu serviço.",
            'tipo_notificacao' => 'nova_proposta',
            'referencia_id' => $servico_id
        ]);
    }

    public function propostaAceita($prestador_id, $servico_id, $servico_titulo)
    {
        return $this->criar([
            'usuario_id' => $prestador_id,
            'tipo_usuario' => 'prestador',
            'titulo' => 'Proposta Aceita!',
            'mensagem' => "Sua proposta para '{$servico_titulo}' foi aceita pelo cliente.",
            'tipo_notificacao' => 'proposta_aceita',
            'referencia_id' => $servico_id
        ]);
    }

    public function propostaRecusada($prestador_id, $servico_id, $servico_titulo)
    {
        return $this->criar([
            'usuario_id' => $prestador_id,
            'tipo_usuario' => 'prestador',
            'titulo' => 'Proposta Recusada',
            'mensagem' => "Sua proposta para '{$servico_titulo}' foi recusada pelo cliente.",
            'tipo_notificacao' => 'proposta_recusada',
            'referencia_id' => $servico_id
        ]);
    }

    public function contraProposta($prestador_id, $servico_id, $servico_titulo)
    {
        return $this->criar([
            'usuario_id' => $prestador_id,
            'tipo_usuario' => 'prestador',
            'titulo' => 'Contra-Proposta Recebida',
            'mensagem' => "O cliente fez uma contra-proposta para '{$servico_titulo}'.",
            'tipo_notificacao' => 'contra_proposta',
            'referencia_id' => $servico_id
        ]);
    }

    public function servicoCancelado($prestador_id, $servico_id, $servico_titulo)
    {
        return $this->criar([
            'usuario_id' => $prestador_id,
            'tipo_usuario' => 'prestador',
            'titulo' => 'Serviço Cancelado',
            'mensagem' => "O serviço '{$servico_titulo}' foi cancelado pelo cliente.",
            'tipo_notificacao' => 'servico_cancelado',
            'referencia_id' => $servico_id
        ]);
    }
}
