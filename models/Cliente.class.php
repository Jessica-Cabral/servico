<?php
require_once __DIR__ . '/../config/database.php';

// Model responsável pelos dados do cliente (MVC)
class Cliente
{
    private $conn;
    private $table = 'tb_pessoa';
    public $id;
    public $nome;
    public $email;
    public $senha;
    public $tipo;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    // Tornar a propriedade conn pública para acessar errorInfo
    public function getConnection() {
        return $this->conn;
    }

    public function getStatus($cliente_id)
    {
        // Retorna estatísticas dos serviços do cliente
        $status = [];

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
            // Em caso de erro, retornar zeros
            $status = [
                'ativos' => 0,
                'concluidos' => 0,
                'pendentes' => 0,
                'total_gasto' => 0
            ];
        }

        return $status;
    }

    public function getById($id)
    {
        // Busca dados do cliente por ID
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tb_pessoa WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            // Buscar endereço completo do cliente (caso esteja em outra tabela)
            if ($dados && isset($dados['id'])) {
                // Exemplo: supondo que o endereço está em tb_endereco_cliente
                $stmtEnd = $this->conn->prepare("SELECT cep, endereco, numero, bairro, cidade, uf FROM tb_endereco_cliente WHERE cliente_id = ? LIMIT 1");
                $stmtEnd->execute([$dados['id']]);
                $endereco = $stmtEnd->fetch(PDO::FETCH_ASSOC);
                if ($endereco) {
                    $dados = array_merge($dados, $endereco);
                }
            }

            return $dados ?: null;
        } catch (Exception $e) {
            error_log("Erro ao buscar cliente por ID: " . $e->getMessage());
            return false;
        }
    }

    public function getByCpf($cpf)
    {
        // Busca dados do cliente por CPF
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tb_pessoa WHERE cpf = ? LIMIT 1");
            $stmt->execute([$cpf]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function getByEmail($email)
    {
        // Busca dados do cliente por e-mail
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tb_pessoa WHERE email = ? LIMIT 1");
            $stmt->execute([$email]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return false;
        }
    }

    public function atualizar($id, $dados)
    {
        // Atualiza dados do cliente
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

            if (empty($campos)) {
                return false;
            }

            $sql = "UPDATE {$this->table} SET " . implode(', ', $campos) . " WHERE id = :id";
            $params[':id'] = $id;

            $stmt = $this->conn->prepare($sql);
            return $stmt->execute($params);
        } catch (Exception $e) {
            error_log("Erro ao atualizar cliente: " . $e->getMessage());
            return false;
        }
    }

    public function create($dados)
    {
        // Cria novo cliente
        try {
            $sql = "INSERT INTO tb_pessoa (nome, email, senha, tipo, telefone, cpf, data_nascimento) 
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
            error_log("PDO ERRO: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $sql = "DELETE FROM tb_pessoa WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao deletar cliente: " . $e->getMessage());
            return false;
        }
    }
}
