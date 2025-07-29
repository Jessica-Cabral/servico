<?php
require_once __DIR__ . '/../config/database.php';

class Avaliacao {
    private $conn;
    private $table = 'tb_avaliacao';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function criar($dados) {
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

    public function getByPrestador($prestador_id) {
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
            return [];
        }
    }

    public function getMediaPrestador($prestador_id) {
        try {
            $query = "SELECT AVG(nota) as media, COUNT(*) as total
                      FROM " . $this->table . "
                      WHERE prestador_id = :prestador_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return ['media' => 0, 'total' => 0];
        }
    }

    public function jaAvaliou($servico_id, $cliente_id) {
        try {
            $query = "SELECT id FROM " . $this->table . "
                      WHERE servico_id = :servico_id AND cliente_id = :cliente_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':servico_id', $servico_id);
            $stmt->bindValue(':cliente_id', $cliente_id);
            $stmt->execute();
            
            return $stmt->rowCount() > 0;
            
        } catch (Exception $e) {
            return false;
        }
    }
}
?>
