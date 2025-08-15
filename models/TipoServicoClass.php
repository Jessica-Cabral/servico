<?php
class TipoServico {
    private $conn;
    private $table = 'tb_tipo_servico';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function listar($page = 1, $perPage = 10, $nome = '') {
        $offset = ($page - 1) * $perPage;
        
        $whereClause = '';
        $params = [];
        
        if (!empty($nome)) {
            $whereClause = "WHERE nome LIKE :nome";
            $params[':nome'] = "%{$nome}%";
        }

        // Contar total de registros
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $countStmt = $this->conn->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Buscar registros com paginação
        $sql = "SELECT * FROM {$this->table} {$whereClause} ORDER BY nome ASC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($sql);
        
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $tipos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'tipos_servico' => $tipos,
            'paginacao' => [
                'page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => (int)$total,
                'total_pages' => ceil($total / $perPage)
            ]
        ];
    }

    public function buscarPorId($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function criar($dados) {
        $sql = "INSERT INTO tb_tipo_servico (nome, descricao, categoria, preco_medio, ativo) VALUES (:nome, :descricao, :categoria, :preco_medio, :ativo)";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':categoria', $dados['categoria']);
        $stmt->bindParam(':preco_medio', $dados['preco_medio']);
        $stmt->bindParam(':ativo', $dados['ativo']);
        
        if ($stmt->execute()) {
            return $this->buscarPorId($this->conn->lastInsertId());
        }
        
        throw new Exception("Erro ao criar tipo de serviço");
    }

    public function atualizar($id, $dados) {
        $sql = "UPDATE tb_tipo_servico SET nome = :nome, descricao = :descricao, categoria = :categoria, preco_medio = :preco_medio, ativo = :ativo WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $dados['nome']);
        $stmt->bindParam(':descricao', $dados['descricao']);
        $stmt->bindParam(':categoria', $dados['categoria']);
        $stmt->bindParam(':preco_medio', $dados['preco_medio']);
        $stmt->bindParam(':ativo', $dados['ativo']);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao atualizar tipo de serviço");
        }
    }

    public function deletar($id) {
        // Verificar se existem solicitações com este tipo
        $checkSql = "SELECT COUNT(*) as total FROM tb_solicita_servico WHERE tipo_servico_id = :id";
        $checkStmt = $this->conn->prepare($checkSql);
        $checkStmt->bindParam(':id', $id, PDO::PARAM_INT);
        $checkStmt->execute();
        $count = $checkStmt->fetch(PDO::FETCH_ASSOC)['total'];
        
        if ($count > 0) {
            throw new Exception("Não é possível deletar este tipo de serviço pois existem solicitações associadas");
        }

        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao deletar tipo de serviço");
        }
    }

    public function buscarTodos() {
        $sql = "SELECT * FROM {$this->table} ORDER BY nome ASC";
        $stmt = $this->conn->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
