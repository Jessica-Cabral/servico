<?php
require_once __DIR__ . '/../config/database.php';

class Cliente {
    private $conn;
    private $table = 'tb_pessoa';

    public $id;
    public $nome;
    public $email;
    public $senha;
    public $tipo;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getStats($cliente_id) {
        $stats = [];
        
        try {
            // Serviços ativos (aguardando propostas, em análise, proposta aceita, em andamento)
            $query = "SELECT COUNT(*) as total FROM tb_solicita_servico 
                      WHERE cliente_id = :cliente_id AND status_id IN (1, 2, 3, 4)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            $stats['ativos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Serviços concluídos
            $query = "SELECT COUNT(*) as total FROM tb_solicita_servico 
                      WHERE cliente_id = :cliente_id AND status_id = 5";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            $stats['concluidos'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Serviços pendentes (aguardando propostas)
            $query = "SELECT COUNT(*) as total FROM tb_solicita_servico 
                      WHERE cliente_id = :cliente_id AND status_id = 1";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            $stats['pendentes'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total gasto (soma dos valores das propostas aceitas)
            $query = "SELECT SUM(p.valor) as total 
                      FROM tb_proposta p 
                      INNER JOIN tb_solicita_servico s ON s.id = p.solicitacao_id
                      WHERE s.cliente_id = :cliente_id AND p.status = 'aceita'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $stats['total_gasto'] = $result['total'] ?? 0;

        } catch (Exception $e) {
            // Em caso de erro, retornar zeros
            $stats = [
                'ativos' => 0,
                'concluidos' => 0,
                'pendentes' => 0,
                'total_gasto' => 0
            ];
        }

        return $stats;
    }

    public function getById($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM tb_pessoa WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar cliente por ID: " . $e->getMessage());
            return false;
        }
    }

    public function atualizar($id, $dados) {
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

    public function create($dados) {
        try {
            $sql = "INSERT INTO tb_pessoa (nome, email, senha, tipo) VALUES (:nome, :email, :senha, :tipo)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':senha', $dados['senha']);
            $stmt->bindValue(':tipo', $dados['tipo']);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
?>

