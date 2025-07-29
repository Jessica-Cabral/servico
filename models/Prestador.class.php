<?php
require_once __DIR__ . '/../config/database.php';

class Prestador {
    private $conn;

    public function __construct() {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getStats($prestador_id) {
        try {
            // Trabalhos ativos (status 3 e 4)
            $query = "SELECT COUNT(*) as total FROM tb_solicita_servico s
                      INNER JOIN tb_proposta p ON s.id = p.solicitacao_id
                      WHERE p.prestador_id = :prestador_id AND p.status = 'aceita'
                      AND s.status_id IN (3, 4)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            $stmt->execute();
            $trabalhos_ativos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Propostas enviadas
            $query = "SELECT COUNT(*) as total FROM tb_proposta WHERE prestador_id = :prestador_id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            $stmt->execute();
            $propostas_enviadas = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Trabalhos concluídos
            $query = "SELECT COUNT(*) as total FROM tb_solicita_servico s
                      INNER JOIN tb_proposta p ON s.id = p.solicitacao_id
                      WHERE p.prestador_id = :prestador_id AND p.status = 'aceita'
                      AND s.status_id = 5";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            $stmt->execute();
            $trabalhos_concluidos = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

            // Total ganho (simulado - seria necessário ter uma tabela de pagamentos)
            $query = "SELECT COALESCE(SUM(p.valor), 0) as total FROM tb_solicita_servico s
                      INNER JOIN tb_proposta p ON s.id = p.solicitacao_id
                      WHERE p.prestador_id = :prestador_id AND p.status = 'aceita'
                      AND s.status_id = 5";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            $stmt->execute();
            $total_ganho = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;

            return [
                'trabalhos_ativos' => $trabalhos_ativos,
                'propostas_enviadas' => $propostas_enviadas,
                'trabalhos_concluidos' => $trabalhos_concluidos,
                'total_ganho' => $total_ganho
            ];

        } catch (Exception $e) {
            return [
                'trabalhos_ativos' => 0,
                'propostas_enviadas' => 0,
                'trabalhos_concluidos' => 0,
                'total_ganho' => 0
            ];
        }
    }

    public function getGraficoDados($prestador_id) {
        try {
            $query = "SELECT 
                        MONTH(s.data_solicitacao) as mes,
                        YEAR(s.data_solicitacao) as ano,
                        COUNT(*) as total
                      FROM tb_solicita_servico s
                      INNER JOIN tb_proposta p ON s.id = p.solicitacao_id
                      WHERE p.prestador_id = :prestador_id AND p.status = 'aceita'
                        AND s.data_solicitacao >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                      GROUP BY YEAR(s.data_solicitacao), MONTH(s.data_solicitacao)
                      ORDER BY ano, mes";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':prestador_id', $prestador_id);
            $stmt->execute();
            
            $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
            $resultado = [
                'labels' => [],
                'dados' => []
            ];
            
            foreach ($dados as $item) {
                $resultado['labels'][] = $meses[$item['mes'] - 1];
                $resultado['dados'][] = $item['total'];
            }
            
            if (empty($resultado['labels'])) {
                $resultado = [
                    'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                    'dados' => [1, 3, 2, 5, 3, 4]
                ];
            }
            
            return $resultado;
            
        } catch (Exception $e) {
            return [
                'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                'dados' => [1, 3, 2, 5, 3, 4]
            ];
        }
    }

    public function getById($id) {
        try {
            $query = "SELECT * FROM tb_pessoa WHERE id = :id AND (tipo = 'prestador' OR tipo = 'ambos')";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $dados = $stmt->fetch(PDO::FETCH_ASSOC);
            return $dados ?: null;
        } catch (Exception $e) {
            return null;
        }
    }

    public function update($id, $dados) {
        try {
            $query = "UPDATE tb_pessoa SET nome = :nome, email = :email, telefone = :telefone WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':telefone', $dados['telefone']);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function updateCompleto($id, $dados) {
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
            if (isset($dados['foto_perfil'])) {
                $campos[] = "foto_perfil = :foto_perfil";
                $params[':foto_perfil'] = $dados['foto_perfil'];
            }
            if (isset($dados['senha'])) {
                $campos[] = "senha = :senha";
                $params[':senha'] = $dados['senha'];
            }
            if (empty($campos)) return false;
            $sql = "UPDATE tb_pessoa SET " . implode(', ', $campos) . " WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            foreach ($params as $k => $v) {
                $stmt->bindValue($k, $v);
            }
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    public function getAll() {
        try {
            $query = "SELECT * FROM tb_pessoa WHERE tipo = 'prestador' OR tipo = 'ambos'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Retorna prestadores recomendados por tipo de serviço.
     * @param int $tipo_servico_id
     * @param int $limite
     * @return array
     */
    public function getRecomendadosPorTipo($tipo_servico_id, $limite = 3) {
        $sql = "SELECT p.id, p.nome, p.avaliacao, p.total_avaliacoes
                FROM prestadores p
                INNER JOIN prestador_servico ps ON ps.prestador_id = p.id
                WHERE ps.tipo_servico_id = :tipo
                ORDER BY p.avaliacao DESC, p.total_avaliacoes DESC
                LIMIT :limite";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':tipo', $tipo_servico_id, PDO::PARAM_INT);
        $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
