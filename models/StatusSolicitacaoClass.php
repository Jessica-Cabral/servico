<?php

// A classe Database será carregada automaticamente pelo autoloader.

class StatusSolicitacao
{
    private $conn;
    private $table = 'tb_status_solicitacao';

    // O construtor é o responsável por obter a conexão, garantindo o encapsulamento.
    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    /**
     * Lista os status de solicitação com paginação e filtro.
     * @param int $page Página atual.
     * @param int $perPage Itens por página.
     * @param string $nome Filtro de nome.
     * @return array Array de status e dados de paginação.
     */
    public function listar($page = 1, $perPage = 10, $nome = '')
    {
        try {
            $offset = ($page - 1) * $perPage;
            $whereClause = '';
            $params = [];

            if (!empty($nome)) {
                $whereClause = "WHERE s.nome LIKE :nome";
                $params[':nome'] = "%{$nome}%";
            }

            $countSql = "SELECT COUNT(*) as total FROM {$this->table} s {$whereClause}";
            $countStmt = $this->conn->prepare($countSql);
            foreach ($params as $key => $value) {
                $countStmt->bindValue($key, $value);
            }
            $countStmt->execute();
            $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

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
        } catch (Exception $e) {
            error_log("Erro em listar status: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Obtém estatísticas de uso dos status de solicitação.
     * @return array Array com as estatísticas.
     */
    public function obterEstatisticas()
    {
        try {
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
        } catch (Exception $e) {
            error_log("Erro em obterEstatisticas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca um status por ID.
     * @param int $id ID do status.
     * @return array|null Dados do status, ou null se não encontrado.
     */
    public function buscarPorId($id)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro em buscarPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Cria um novo status.
     * @param array $dados Dados do novo status.
     * @return array|null Dados do status criado, ou null em caso de erro.
     */
    public function criar($dados)
    {
        try {
            $sql = "INSERT INTO {$this->table} (nome, descricao, cor) VALUES (:nome, :descricao, :cor)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':descricao', $dados['descricao']);
            $stmt->bindParam(':cor', $dados['cor']);

            if ($stmt->execute()) {
                return $this->buscarPorId($this->conn->lastInsertId());
            }
        } catch (Exception $e) {
            error_log("Erro em criar status: " . $e->getMessage());
            throw new Exception("Erro ao criar status");
        }
        return null; // Retorno padrão em caso de falha
    }

    /**
     * Atualiza um status.
     * @param int $id ID do status.
     * @param array $dados Dados a serem atualizados.
     * @return void
     * @throws Exception Em caso de erro na atualização.
     */
    public function atualizar($id, $dados)
    {
        try {
            $sql = "UPDATE {$this->table} SET nome = :nome, descricao = :descricao, cor = :cor WHERE id = :id";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':nome', $dados['nome']);
            $stmt->bindParam(':descricao', $dados['descricao']);
            $stmt->bindParam(':cor', $dados['cor']);

            if (!$stmt->execute()) {
                throw new Exception("Erro ao atualizar status");
            }
        } catch (Exception $e) {
            error_log("Erro em atualizar status: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Deleta um status.
     * @param int $id ID do status.
     * @return void
     * @throws Exception Em caso de erro na exclusão.
     */
    public function deletar($id)
    {
        try {
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
        } catch (Exception $e) {
            error_log("Erro em deletar status: " . $e->getMessage());
            throw $e;
        }
    }
}
