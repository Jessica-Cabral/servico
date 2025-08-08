<?php
class StatusSolicitacao {
    private $conn;
    private $table = 'tb_status_solicitacao';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar($page = 1, $perPage = 10, $nome = '') {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $params = [];
        
        if (!empty($nome)) {
            $whereClause = "WHERE s.nome LIKE :nome";
            $params[':nome'] = "%{$nome}%";
        }

        // Contar total de registros
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} s {$whereClause}";
        $countStmt = $this->conn->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Buscar registros com estatísticas
        $sql = "SELECT 
                    s.*,
                    COALESCE(COUNT(sol.id), 0) as total_solicitacoes
                FROM {$this->table} s
                LEFT JOIN tb_solicita_servico sol ON s.id = sol.status_id
                {$whereClause}
                GROUP BY s.id 
                ORDER BY s.id ASC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $status = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'status' => $status,
            'paginacao' => [
                'page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => (int)$total,
                'total_pages' => ceil($total / $perPage)
            ]
        ];
    }

    public function obterEstatisticas() {
        $sql = "SELECT 
                    s.id,
                    s.nome,
                    s.cor,
                    COUNT(sol.id) as total_solicitacoes,
                    COUNT(CASE WHEN DATE(sol.data_solicitacao) = CURDATE() THEN 1 END) as hoje,
                    COUNT(CASE WHEN sol.data_solicitacao >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as esta_semana,
                    COUNT(CASE WHEN MONTH(sol.data_solicitacao) = MONTH(NOW()) AND YEAR(sol.data_solicitacao) = YEAR(NOW()) THEN 1 END) as este_mes
                FROM tb_status_solicitacao s
                LEFT JOIN tb_solicita_servico sol ON s.id = sol.status_id
                GROUP BY s.id, s.nome, s.cor
                ORDER BY total_solicitacoes DESC";
        
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($dados) {
        $sql = "INSERT INTO {$this->table} (nome, descricao, cor) VALUES (:nome, :descricao, :cor)";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':cor', $dados['cor']);
        
        if ($stmt->execute()) {
            return $this->buscarPorId($this->conn->lastInsertId());
        }
        
        throw new Exception("Erro ao criar status");
    }

    public function atualizar($id, $dados) {
        $sql = "UPDATE {$this->table} SET nome = :nome, descricao = :descricao, cor = :cor WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':cor', $dados['cor']);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao atualizar status");
        }
    }

    public function deletar($id) {
        // Verificar se existem solicitações com este status
        $checkSql = "SELECT COUNT(*) as total FROM tb_solicita_servico WHERE status_id = :id";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        $count = $checkStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        if ($count > 0) {
            throw new Exception("Não é possível deletar este status pois existem solicitações associadas");
        }

        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao deletar status");
        }
    }
}
?>
