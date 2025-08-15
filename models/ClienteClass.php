<?php

class Cliente
{
    /**
     * @var PDO Objeto de conexão com o banco de dados.
     */
    private $conn;

    /**
     * @var string Nome da tabela no banco de dados.
     */
    private $table = 'tb_pessoa';

    public function __construct()
    {
        try {
            $database = Database::getInstance();
            $this->conn = $database->getConnection();
            
            // Teste rápido da conexão
            if (!$database->testConnection()) {
                throw new Exception("Falha no teste de conexão com o banco de dados.");
            }
        } catch (Exception $e) {
            error_log("Erro ao conectar no Cliente: " . $e->getMessage());
            throw new Exception("Erro ao inicializar cliente: " . $e->getMessage());
        }
    }

    /**
     * Retorna estatísticas resumidas dos serviços de um cliente.
     * @param int $cliente_id ID do cliente.
     * @return array Array associativo com o total de serviços.
     */
    public function getStatus($cliente_id)
    {
        $status = [
            'ativos' => 0,
            'concluidos' => 0,
            'pendentes' => 0,
            'total_gasto' => 0
        ];

        try {
            // Serviços ativos (aguardando propostas, em análise, proposta aceita, em andamento)
            $query = "SELECT COUNT(*) as total FROM tb_solicita_servico 
                      WHERE cliente_id = :cliente_id AND status_id IN (1, 2, 3, 4)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            $status['ativos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Serviços concluídos
            $query = "SELECT COUNT(*) as total FROM tb_solicita_servico 
                      WHERE cliente_id = :cliente_id AND status_id = 5";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            $status['concluidos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Serviços pendentes (aguardando propostas)
            $query = "SELECT COUNT(*) as total FROM tb_solicita_servico 
                      WHERE cliente_id = :cliente_id AND status_id = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            $status['pendentes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total gasto (soma dos valores das propostas aceitas)
            $query = "SELECT SUM(p.valor) as total 
                      FROM tb_proposta p 
                      INNER JOIN tb_solicita_servico s ON s.id = p.solicitacao_id
                      WHERE s.cliente_id = :cliente_id AND p.status = 'aceita'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $status['total_gasto'] = $result['total'] ?? 0;
        } catch (Exception $e) {
            error_log("Erro em getStatus: " . $e->getMessage());
        }

        return $status;
    }

    /**
     * Busca um cliente por ID.
     * @param int $id ID do cliente.
     * @return array|null Dados do cliente em um array associativo, ou null se não encontrado.
     */
    public function getById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tb_pessoa WHERE id = ? AND tipo IN ('cliente', 'ambos') LIMIT 1");
            $stmt->execute([$id]);
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            return $dados ?: null;
        } catch (Exception $e) {
            error_log("Erro ao buscar cliente por ID: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Busca um cliente por CPF.
     * @param string $cpf CPF do cliente.
     * @return array|null Dados do cliente, ou null se não encontrado.
     */
    public function getByCpf($cpf)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tb_pessoa WHERE cpf = ? AND tipo IN ('cliente', 'ambos') LIMIT 1");
            $stmt->execute([$cpf]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar cliente por CPF: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Busca um cliente por e-mail.
     * @param string $email E-mail do cliente.
     * @return array|null Dados do cliente, ou null se não encontrado.
     */
    public function getByEmail($email)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tb_pessoa WHERE email = ? AND tipo IN ('cliente', 'ambos') LIMIT 1");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar cliente por e-mail: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Atualiza dados do cliente.
     * @param int $id ID do cliente.
     * @param array $dados Array associativo com os dados a serem atualizados.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function atualizar($id, $dados)
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
            if (isset($dados['senha'])) {
                $campos[] = "senha = :senha";
                $params[':senha'] = $dados['senha'];
            }
            if (isset($dados['foto_perfil'])) {
                $campos[] = "foto_perfil = :foto_perfil";
                $params[':foto_perfil'] = $dados['foto_perfil'];
            }
            // Adicionar outros campos que podem ser atualizados

            if (empty($campos)) {
                return false;
            }

            $sql = "UPDATE {$this->table} SET " . implode(', ', $campos) . " WHERE id = :id AND tipo IN ('cliente', 'ambos')";
            $params[':id'] = $id;

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Erro ao atualizar cliente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cria um novo registro de cliente.
     * @param array $dados Array com os dados do cliente.
     * @return bool True se a criação for bem-sucedida, false caso contrário.
     */
    public function create($dados)
    {
        try {
            // No seu banco de dados, a tabela é `tb_pessoa`
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
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao criar cliente: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deleta um cliente por ID.
     * @param int $id ID do cliente a ser deletado.
     * @return bool True se a exclusão for bem-sucedida, false caso contrário.
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM {$this->table} WHERE id = :id AND tipo IN ('cliente', 'ambos')";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao deletar cliente: " . $e->getMessage());
            return false;
        }
    }
}
