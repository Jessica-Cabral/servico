<?php


class Avaliacao
{
    private $conn;
    private $table = 'tb_avaliacao';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Cria uma nova avaliação no banco de dados.
     * @param array $dados Dados da avaliação (servico_id, cliente_id, prestador_id, nota, comentario).
     * @return bool True se a criação for bem-sucedida, false caso contrário.
     */
    public function criar($dados)
    {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (servico_id, cliente_id, prestador_id, nota, comentario) 
                      VALUES (:servico_id, :cliente_id, :prestador_id, :nota, :comentario)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':servico_id', $dados['servico_id']);
            $stmt->bindValue(':cliente_id', $dados['cliente_id']);
            $stmt->bindValue(':prestador_id', $dados['prestador_id']);
            $stmt->bindValue(':nota', $dados['nota']);
            $stmt->bindValue(':comentario', $dados['comentario']);

            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao criar avaliação: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Busca todas as avaliações de um prestador.
     * @param int $prestador_id ID do prestador.
     * @return array Array de avaliações, ou um array vazio em caso de erro.
     */
    public function getByPrestador($prestador_id)
    {
        try {
            $query = "SELECT a.*, c.nome as cliente_nome, s.titulo as servico_titulo
                      FROM " . $this->table . " a
                      INNER JOIN tb_pessoa c ON a.cliente_id = c.id
                      INNER JOIN tb_solicita_servico s ON a.servico_id = s.id
                      WHERE a.prestador_id = :prestador_id
                      ORDER BY a.data_avaliacao DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar avaliações por prestador: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Calcula a média e o total de avaliações de um prestador.
     * @param int $prestador_id ID do prestador.
     * @return array Um array com a média e o total, ou valores padrão em caso de erro.
     */
    public function getMediaPrestador($prestador_id)
    {
        try {
            $query = "SELECT AVG(nota) as media, COUNT(*) as total
                      FROM " . $this->table . "
                      WHERE prestador_id = :prestador_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar média do prestador: " . $e->getMessage());
            return ['media' => 0, 'total' => 0];
        }
    }

    /**
     * Verifica se um cliente já avaliou um serviço.
     * @param int $servico_id ID do serviço.
     * @param int $cliente_id ID do cliente.
     * @return bool True se já avaliou, false caso contrário.
     */
    public function jaAvaliou($servico_id, $cliente_id)
    {
        try {
            $query = "SELECT id FROM " . $this->table . "
                      WHERE servico_id = :servico_id AND cliente_id = :cliente_id";

            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':servico_id', $servico_id);
            $stmt->bindValue(':cliente_id', $cliente_id);
            $stmt->execute();

            return $stmt->rowCount() > 0;
        } catch (Exception $e) {
            error_log("Erro ao verificar avaliação: " . $e->getMessage());
            return false;
        }
    }
}
