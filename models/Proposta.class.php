<?php
require_once __DIR__ . '/../config/database.php';

class Proposta {
    private $conn;
    private $table = 'tb_proposta';

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getByServico($servico_id) {
        try {
            $query = "SELECT p.*, ps.nome as prestador_nome
                      FROM " . $this->table . " p
                      INNER JOIN tb_pessoa ps ON p.prestador_id = ps.id
                      WHERE p.solicitacao_id = :servico_id AND p.status != 'recusada'
                      ORDER BY p.data_proposta DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':servico_id', $servico_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }

    public function criar($dados) {
        try {
            $this->conn->beginTransaction();
            
            // Lock para evitar propostas simultâneas
            $query_lock = "SELECT id FROM " . $this->table . " 
                          WHERE solicitacao_id = :solicitacao_id AND prestador_id = :prestador_id 
                          FOR UPDATE";
            $stmt_lock = $this->conn->prepare($query_lock);
            $stmt_lock->bindValue(':solicitacao_id', $dados['solicitacao_id']);
            $stmt_lock->bindValue(':prestador_id', $dados['prestador_id']);
            $stmt_lock->execute();
            
            if ($stmt_lock->rowCount() > 0) {
                $this->conn->rollback();
                return false; // Já existe proposta
            }
            
            $query = "INSERT INTO " . $this->table . " 
                      (solicitacao_id, prestador_id, valor, prazo_execucao, descricao, status) 
                      VALUES (:solicitacao_id, :prestador_id, :valor, :prazo_execucao, :descricao, 'pendente')";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':solicitacao_id', $dados['solicitacao_id']);
            $stmt->bindValue(':prestador_id', $dados['prestador_id']);
            $stmt->bindValue(':valor', $dados['valor']);
            $stmt->bindValue(':prazo_execucao', $dados['prazo_execucao']);
            $stmt->bindValue(':descricao', $dados['descricao']);
            
            $result = $stmt->execute();
            $this->conn->commit();
            
            return $result;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Erro ao criar proposta: " . $e->getMessage());
            return false;
        }
    }

    public function getDetalhes($proposta_id) {
        try {
            $query = "SELECT p.*, s.titulo as servico_titulo, s.cliente_id
                      FROM " . $this->table . " p
                      INNER JOIN tb_solicita_servico s ON p.solicitacao_id = s.id
                      WHERE p.id = :proposta_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':proposta_id', $proposta_id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar detalhes da proposta: " . $e->getMessage());
            return false;
        }
    }

    public function aceitar($proposta_id, $cliente_id) {
        try {
            $this->conn->beginTransaction();
            
            // Verificar se a proposta pertence ao cliente
            $query_check = "SELECT p.id, p.solicitacao_id FROM " . $this->table . " p
                           INNER JOIN tb_solicita_servico s ON p.solicitacao_id = s.id
                           WHERE p.id = :proposta_id AND s.cliente_id = :cliente_id AND p.status = 'pendente'";
            
            $stmt_check = $this->conn->prepare($query_check);
            $stmt_check->bindValue(':proposta_id', $proposta_id);
            $stmt_check->bindValue(':cliente_id', $cliente_id);
            $stmt_check->execute();
            
            $proposta_info = $stmt_check->fetch(PDO::FETCH_ASSOC);
            if (!$proposta_info) {
                $this->conn->rollback();
                return false;
            }
            
            // Aceitar a proposta
            $query = "UPDATE " . $this->table . " SET status = 'aceita', data_aceite = NOW() WHERE id = :proposta_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':proposta_id', $proposta_id);
            $stmt->execute();
            
            // Recusar outras propostas do mesmo serviço
            $query_recusar = "UPDATE " . $this->table . " SET status = 'recusada' 
                             WHERE solicitacao_id = :solicitacao_id 
                             AND id != :proposta_id AND status = 'pendente'";
            $stmt_recusar = $this->conn->prepare($query_recusar);
            $stmt_recusar->bindValue(':solicitacao_id', $proposta_info['solicitacao_id']);
            $stmt_recusar->bindValue(':proposta_id', $proposta_id);
            $stmt_recusar->execute();
            
            // Atualizar status do serviço para "Proposta Aceita" (status_id = 3)
            $query_servico = "UPDATE tb_solicita_servico SET status_id = 3 WHERE id = :solicitacao_id";
            $stmt_servico = $this->conn->prepare($query_servico);
            $stmt_servico->bindValue(':solicitacao_id', $proposta_info['solicitacao_id']);
            $stmt_servico->execute();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Erro ao aceitar proposta: " . $e->getMessage());
            return false;
        }
    }

    public function recusar($dados) {
        try {
            $this->conn->beginTransaction();
            
            // Verificar se a proposta pertence ao cliente
            $query_check = "SELECT p.id FROM " . $this->table . " p
                           INNER JOIN tb_solicita_servico s ON p.solicitacao_id = s.id
                           WHERE p.id = :proposta_id AND s.cliente_id = :cliente_id AND p.status = 'pendente'";
            
            $stmt_check = $this->conn->prepare($query_check);
            $stmt_check->bindValue(':proposta_id', $dados['proposta_id']);
            $stmt_check->bindValue(':cliente_id', $dados['cliente_id']);
            $stmt_check->execute();
            
            if ($stmt_check->rowCount() === 0) {
                $this->conn->rollback();
                return false;
            }
            
            // Recusar a proposta
            $query = "UPDATE " . $this->table . " SET status = 'recusada', data_recusa = NOW() WHERE id = :proposta_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':proposta_id', $dados['proposta_id']);
            $stmt->execute();
            
            // Registrar motivo se fornecido
            if (!empty($dados['motivo'])) {
                $query_motivo = "INSERT INTO tb_negociacao_proposta 
                                (proposta_id, tipo, observacoes) 
                                VALUES (:proposta_id, 'recusa', :motivo)";
                
                $stmt_motivo = $this->conn->prepare($query_motivo);
                $stmt_motivo->bindValue(':proposta_id', $dados['proposta_id']);
                $stmt_motivo->bindValue(':motivo', $dados['motivo']);
                $stmt_motivo->execute();
            }
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Erro ao recusar proposta: " . $e->getMessage());
            return false;
        }
    }

    public function criarContraProposta($dados) {
        try {
            $this->conn->beginTransaction();
            
            // Verificar se a proposta pertence ao cliente
            $query_check = "SELECT p.id FROM " . $this->table . " p
                           INNER JOIN tb_solicita_servico s ON p.solicitacao_id = s.id
                           WHERE p.id = :proposta_id AND s.cliente_id = :cliente_id AND p.status = 'pendente'";
            
            $stmt_check = $this->conn->prepare($query_check);
            $stmt_check->bindValue(':proposta_id', $dados['proposta_id']);
            $stmt_check->bindValue(':cliente_id', $dados['cliente_id']);
            $stmt_check->execute();
            
            if ($stmt_check->rowCount() === 0) {
                $this->conn->rollback();
                return false;
            }
            
            // Inserir na tabela de negociação
            $query = "INSERT INTO tb_negociacao_proposta 
                      (proposta_id, tipo, valor, prazo, observacoes) 
                      VALUES (:proposta_id, 'contra_proposta', :valor, :prazo, :observacoes)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':proposta_id', $dados['proposta_id']);
            $stmt->bindValue(':valor', $dados['valor']);
            $stmt->bindValue(':prazo', $dados['prazo']);
            $stmt->bindValue(':observacoes', $dados['observacoes']);
            
            $result = $stmt->execute();
            $this->conn->commit();
            
            return $result;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Erro ao criar contra-proposta: " . $e->getMessage());
            return false;
        }
    }

    public function getHistoricoNegociacao($proposta_id) {
        try {
            $query = "SELECT * FROM tb_negociacao_proposta 
                      WHERE proposta_id = :proposta_id 
                      ORDER BY data_negociacao ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':proposta_id', $proposta_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar histórico: " . $e->getMessage());
            return [];
        }
    }

    public function getByPrestador($prestador_id, $limit = null) {
        try {
            $query = "SELECT p.*, s.titulo as servico_titulo,
                             CASE 
                                WHEN p.status = 'pendente' THEN 'warning'
                                WHEN p.status = 'aceita' THEN 'success'
                                WHEN p.status = 'recusada' THEN 'danger'
                                ELSE 'secondary'
                             END as status_class
                      FROM " . $this->table . " p
                      INNER JOIN tb_solicita_servico s ON p.solicitacao_id = s.id
                      WHERE p.prestador_id = :prestador_id 
                      ORDER BY p.data_proposta DESC";
            
            if ($limit) {
                $query .= " LIMIT :limit";
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            if ($limit) {
                $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            }
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar propostas do prestador: " . $e->getMessage());
            return [];
        }
    }

    public function getByPrestadorDetalhado($prestador_id, $filtro_status = '') {
        try {
            $where_conditions = ["p.prestador_id = :prestador_id"];
            $params = [':prestador_id' => $prestador_id];
            
            if (!empty($filtro_status)) {
                $where_conditions[] = "p.status = :status";
                $params[':status'] = $filtro_status;
            }
            
            $where_clause = implode(' AND ', $where_conditions);
            
            $query = "SELECT p.*, s.titulo as servico_titulo, s.descricao as servico_descricao,
                             s.orcamento_estimado, s.urgencia,
                             CASE 
                                WHEN p.status = 'pendente' THEN 'warning'
                                WHEN p.status = 'aceita' THEN 'success'
                                WHEN p.status = 'recusada' THEN 'danger'
                                ELSE 'secondary'
                             END as status_class
                      FROM " . $this->table . " p
                      INNER JOIN tb_solicita_servico s ON p.solicitacao_id = s.id
                      WHERE {$where_clause}
                      ORDER BY p.data_proposta DESC";
            
            $stmt = $this->conn->prepare($query);
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar propostas detalhadas: " . $e->getMessage());
            return [];
        }
    }

    public function getStatsPropostas($prestador_id) {
        try {
            $query = "SELECT 
                        COUNT(*) as total,
                        SUM(CASE WHEN status = 'pendente' THEN 1 ELSE 0 END) as pendentes,
                        SUM(CASE WHEN status = 'aceita' THEN 1 ELSE 0 END) as aceitas,
                        SUM(CASE WHEN status = 'recusada' THEN 1 ELSE 0 END) as recusadas
                      FROM " . $this->table . " 
                      WHERE prestador_id = :prestador_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return [
                'total' => $result['total'] ?? 0,
                'pendentes' => $result['pendentes'] ?? 0,
                'aceitas' => $result['aceitas'] ?? 0,
                'recusadas' => $result['recusadas'] ?? 0
            ];
            
        } catch (Exception $e) {
            return [
                'total' => 0,
                'pendentes' => 0,
                'aceitas' => 0,
                'recusadas' => 0
            ];
        }
    }

    public function cancelar($id, $motivo = '') {
        try {
            $query = "UPDATE tb_proposta SET status = 'cancelada' WHERE id = :id AND status = 'pendente'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT * FROM tb_proposta WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return false;
        }
    }

    public function atualizar($id, $valor, $prazo, $descricao) {
        try {
            $query = "UPDATE tb_proposta 
                      SET valor = :valor, prazo_execucao = :prazo, descricao = :descricao 
                      WHERE id = :id AND status = 'pendente'";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':valor', $valor);
            $stmt->bindValue(':prazo', $prazo);
            $stmt->bindValue(':descricao', $descricao);
            $stmt->bindValue(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao atualizar proposta: " . $e->getMessage());
            return false;
        }
    }
}
?>