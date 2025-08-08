<?php
require_once __DIR__ . '/../config/database.php';

class Usuario
{
    private $conn;
    private $table = 'tb_pessoa';

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getConnection()
    {
        return $this->conn;
    }

    // CREATE - Criar novo usuário
    public function create($dados)
    {
        try {
            $sql = "INSERT INTO {$this->table} (nome, email, senha, tipo, telefone, cpf, data_nascimento) 
                    VALUES (:nome, :email, :senha, :tipo, :telefone, :cpf, :data_nascimento)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':senha', $dados['senha']);
            $stmt->bindValue(':tipo', $dados['tipo']);
            $stmt->bindValue(':telefone', $dados['telefone']);
            $stmt->bindValue(':cpf', $dados['cpf']);
            $stmt->bindValue(':data_nascimento', $dados['data_nascimento']);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return false;
        }
    }

    // READ - Listar todos os usuários com paginação
    public function getAll($page = 1, $per_page = 10, $filtros = [])
    {
        try {
            $offset = ($page - 1) * $per_page;
            $where_conditions = [];
            $params = [];

            // Filtros opcionais
            if (!empty($filtros['tipo'])) {
                $where_conditions[] = "tipo = :tipo";
                $params[':tipo'] = $filtros['tipo'];
            }
            if (!empty($filtros['nome'])) {
                $where_conditions[] = "nome LIKE :nome";
                $params[':nome'] = '%' . $filtros['nome'] . '%';
            }
            if (!empty($filtros['email'])) {
                $where_conditions[] = "email LIKE :email";
                $params[':email'] = '%' . $filtros['email'] . '%';
            }

            $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

            $sql = "SELECT id, nome, email, cpf, telefone, tipo, data_cadastro, ultimo_acesso, ativo 
                    FROM {$this->table} {$where_clause} 
                    ORDER BY data_cadastro DESC 
                    LIMIT :offset, :per_page";
            
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->bindValue(':per_page', $per_page, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao listar usuários: " . $e->getMessage());
            return [];
        }
    }

    // READ - Buscar usuário por ID
    public function getById($id)
    {
        try {
            $sql = "SELECT id, nome, email, cpf, telefone, tipo, data_cadastro, ultimo_acesso, ativo 
                    FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar usuário por ID: " . $e->getMessage());
            return false;
        }
    }

    // READ - Buscar por email
    public function getByEmail($email)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE email = :email";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    // READ - Buscar por CPF
    public function getByCpf($cpf)
    {
        try {
            $sql = "SELECT * FROM {$this->table} WHERE cpf = :cpf";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    // UPDATE - Atualizar usuário
    public function update($id, $dados)
    {
        try {
            $campos = [];
            $params = [];

            if (isset($dados['nome'])) {
                $campos[] = "nome = :nome";
                $params[':nome'] = $dados['nome'];
            }
            if (isset($dados['email'])) {
                $campos[] = "email = :email";
                $params[':email'] = $dados['email'];
            }
            if (isset($dados['telefone'])) {
                $campos[] = "telefone = :telefone";
                $params[':telefone'] = $dados['telefone'];
            }
            if (isset($dados['cpf'])) {
                $campos[] = "cpf = :cpf";
                $params[':cpf'] = $dados['cpf'];
            }
            if (isset($dados['senha'])) {
                $campos[] = "senha = :senha";
                $params[':senha'] = $dados['senha'];
            }
            if (isset($dados['tipo'])) {
                $campos[] = "tipo = :tipo";
                $params[':tipo'] = $dados['tipo'];
            }
            if (isset($dados['foto_perfil'])) {
                $campos[] = "foto_perfil = :foto_perfil";
                $params[':foto_perfil'] = $dados['foto_perfil'];
            }
            if (isset($dados['ativo'])) {
                $campos[] = "ativo = :ativo";
                $params[':ativo'] = $dados['ativo'];
            }

            if (empty($campos)) {
                return false;
            }

            $sql = "UPDATE {$this->table} SET " . implode(', ', $campos) . " WHERE id = :id";
            $params[':id'] = $id;

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }

    // DELETE - Deletar usuário (soft delete)
    public function delete($id)
    {
        try {
            $sql = "UPDATE {$this->table} SET ativo = 0 WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao deletar usuário: " . $e->getMessage());
            return false;
        }
    }

    // DELETE - Deletar permanentemente
    public function deletePermanente($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao deletar usuário permanentemente: " . $e->getMessage());
            return false;
        }
    }

    // Contar total de usuários (para paginação)
    public function count($filtros = [])
    {
        try {
            $where_conditions = [];
            $params = [];

            if (!empty($filtros['tipo'])) {
                $where_conditions[] = "tipo = :tipo";
                $params[':tipo'] = $filtros['tipo'];
            }
            if (!empty($filtros['nome'])) {
                $where_conditions[] = "nome LIKE :nome";
                $params[':nome'] = '%' . $filtros['nome'] . '%';
            }
            if (!empty($filtros['email'])) {
                $where_conditions[] = "email LIKE :email";
                $params[':email'] = '%' . $filtros['email'] . '%';
            }

            $where_clause = !empty($where_conditions) ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

            $sql = "SELECT COUNT(*) as total FROM {$this->table} {$where_clause}";
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
        } catch (Exception $e) {
            return 0;
        }
    }

    // Ativar/Desativar usuário
    // public function toggleStatus($id)
    // {
    //     try {
    //         $sql = "UPDATE {$this->table} SET ativo = NOT ativo WHERE id = :id";
    //         $stmt = $this->conn->prepare($sql);
    //         $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    //         return $stmt->execute();
    //     } catch (Exception $e) {
    //         return false;
    //     }
    // }

    // Validações
    public function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) $d += $cpf[$c] * (($t + 1) - $c);
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }

    public function validarIdade($data_nascimento)
    {
        $nasc = new DateTime($data_nascimento);
        $hoje = new DateTime();
        $idade = $hoje->diff($nasc)->y;
        return $idade >= 18;
    }

    public function validarEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function listar($page = 1, $perPage = 10, $filtros = []) {
        $offset = ($page - 1) * $perPage;
        
        $whereConditions = [];
        $params = [];
        
        if (!empty($filtros['nome'])) {
            $whereConditions[] = "nome LIKE :nome";
            $params[':nome'] = "%{$filtros['nome']}%";
        }
        
        if (!empty($filtros['email'])) {
            $whereConditions[] = "email LIKE :email";
            $params[':email'] = "%{$filtros['email']}%";
        }
        
        if (!empty($filtros['tipo'])) {
            $whereConditions[] = "tipo = :tipo";
            $params[':tipo'] = $filtros['tipo'];
        }

        if (!empty($filtros['status'])) {
            $whereConditions[] = "ativo = :ativo";
            $params[':ativo'] = $filtros['status'] === 'ativo' ? 1 : 0;
        }

        $whereClause = '';
        if (!empty($whereConditions)) {
            $whereClause = "WHERE " . implode(' AND ', $whereConditions);
        }

        // Contar total de registros
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} {$whereClause}";
        $countStmt = $this->conn->prepare($countSql);
        foreach ($params as $key => $value) {
            $countStmt->bindValue($key, $value);
        }
        $countStmt->execute();
        $total = $countStmt->fetch(PDO::FETCH_ASSOC)['total'];

        // Buscar registros com informações adicionais
        $sql = "SELECT 
                    p.*,
                    COUNT(DISTINCT ss.id) as total_solicitacoes,
                    COUNT(DISTINCT pr.id) as total_propostas,
                    AVG(av.nota) as media_avaliacao
                FROM {$this->table} p
                LEFT JOIN tb_solicita_servico ss ON p.id = ss.cliente_id
                LEFT JOIN tb_proposta pr ON p.id = pr.prestador_id
                LEFT JOIN tb_avaliacao av ON p.id = av.avaliado_id
                {$whereClause}
                GROUP BY p.id
                ORDER BY p.data_cadastro DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int)$perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        
        $stmt->execute();
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'usuarios' => $usuarios,
            'paginacao' => [
                'page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => (int)$total,
                'total_pages' => ceil($total / $perPage)
            ]
        ];
    }

    public function buscarPorId($id) {
        $sql = "SELECT id, nome, email, cpf, telefone, tipo, data_cadastro, ultimo_acesso, ativo 
                FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $dados) {
        $campos = [];
        $params = [':id' => $id];
        
        if (!empty($dados['nome'])) {
            $campos[] = "nome = :nome";
            $params[':nome'] = $dados['nome'];
        }
        
        if (!empty($dados['email'])) {
            $campos[] = "email = :email";
            $params[':email'] = $dados['email'];
        }
        
        if (!empty($dados['telefone'])) {
            $campos[] = "telefone = :telefone";
            $params[':telefone'] = $dados['telefone'];
        }
        
        if (!empty($dados['tipo'])) {
            $campos[] = "tipo = :tipo";
            $params[':tipo'] = $dados['tipo'];
        }
        
        if (!empty($dados['senha'])) {
            $campos[] = "senha = :senha";
            $params[':senha'] = password_hash($dados['senha'], PASSWORD_DEFAULT);
        }

        if (empty($campos)) {
            throw new Exception("Nenhum campo para atualizar");
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $campos) . " WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        
        if (!$stmt->execute($params)) {
            throw new Exception("Erro ao atualizar usuário");
        }
    }

    public function toggleStatus($id) {
        $sql = "UPDATE {$this->table} SET ativo = NOT ativo WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        
        if (!$stmt->execute()) {
            throw new Exception("Erro ao alterar status do usuário");
        }
    }

    public function deletar($id) {
        $this->conn->beginTransaction();
        
        try {
            // Deletar registros relacionados primeiro
            $tabelas = ['tb_endereco', 'tb_avaliacao', 'tb_mensagem', 'tb_notificacao'];
            
            foreach ($tabelas as $tabela) {
                $sql = "DELETE FROM {$tabela} WHERE pessoa_id = :id";
                $stmt = $this->conn->prepare($sql);
                $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                $stmt->execute();
            }
            
            // Deletar o usuário
            $sql = "DELETE FROM {$this->table} WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            
            if (!$stmt->execute()) {
                throw new Exception("Erro ao deletar usuário");
            }
            
            $this->conn->commit();
        } catch (Exception $e) {
            $this->conn->rollback();
            throw $e;
        }
    }

    public function obterEstatisticasDetalhadas($id) {
        $sql = "SELECT 
                    p.*,
                    COUNT(DISTINCT ss.id) as total_solicitacoes,
                    COUNT(DISTINCT pr.id) as total_propostas,
                    COUNT(DISTINCT CASE WHEN pr.status = 'aceita' THEN pr.id END) as propostas_aceitas,
                    AVG(av.nota) as media_avaliacao,
                    COUNT(DISTINCT av.id) as total_avaliacoes,
                    COUNT(DISTINCT e.id) as total_enderecos
                FROM tb_pessoa p
                LEFT JOIN tb_solicita_servico ss ON p.id = ss.cliente_id
                LEFT JOIN tb_proposta pr ON p.id = pr.prestador_id
                LEFT JOIN tb_avaliacao av ON p.id = av.avaliado_id
                LEFT JOIN tb_endereco e ON p.id = e.pessoa_id
                WHERE p.id = :id
                GROUP BY p.id";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
