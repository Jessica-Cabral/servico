<?php
require_once __DIR__ . '/../config/database.php';

class Servico {
    private $conn;
    private $table = 'tb_solicita_servico';

    public function __construct() {
        $this->conn = (new Database())->getConnection();
        if (!$this->conn) {
            throw new Exception("Erro de conexão com o banco de dados.");
        }
    }

    public function getRecentes($cliente_id, $limite = 4) {
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com banco não estabelecida");
            }
            
            $stmt = $this->conn->prepare("
                SELECT 
                    s.id,
                    s.titulo,
                    s.data_solicitacao as created_at,
                    COALESCE(ss.nome, 'Pendente') as status_texto,
                    CASE 
                        WHEN s.status_id = 1 THEN 'warning'
                        WHEN s.status_id = 2 THEN 'info'
                        WHEN s.status_id = 3 THEN 'success'
                        WHEN s.status_id = 4 THEN 'primary'
                        WHEN s.status_id = 5 THEN 'success'
                        ELSE 'secondary'
                    END as status_class
                FROM tb_solicita_servico s
                LEFT JOIN tb_status_solicitacao ss ON s.status_id = ss.id
                WHERE s.cliente_id = ?
                ORDER BY s.data_solicitacao DESC
                LIMIT ?
            ");
            $stmt->execute([$cliente_id, $limite]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar serviços recentes: " . $e->getMessage());
            return [];
        }
    }

    public function getGraficoDados($cliente_id) {
        try {
            if (!$this->conn) {
                throw new Exception("Conexão com banco não estabelecida");
            }
            
            $stmt = $this->conn->prepare("
                SELECT 
                    DATE_FORMAT(data_solicitacao, '%Y-%m') as mes,
                    COUNT(*) as total
                FROM tb_solicita_servico 
                WHERE cliente_id = ? 
                    AND data_solicitacao >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY DATE_FORMAT(data_solicitacao, '%Y-%m')
                ORDER BY mes ASC
            ");
            $stmt->execute([$cliente_id]);
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Preparar dados para o gráfico
            $labels = [];
            $dados = [];
            
            // Últimos 6 meses
            for ($i = 5; $i >= 0; $i--) {
                $mes = date('Y-m', strtotime("-$i months"));
                $labels[] = date('M/Y', strtotime("-$i months"));
                
                $encontrado = false;
                foreach ($resultados as $resultado) {
                    if ($resultado['mes'] === $mes) {
                        $dados[] = (int) $resultado['total'];
                        $encontrado = true;
                        break;
                    }
                }
                
                if (!$encontrado) {
                    $dados[] = 0;
                }
            }
            
            return [
                'labels' => $labels,
                'dados' => $dados
            ];
        } catch (Exception $e) {
            error_log("Erro ao buscar dados do gráfico: " . $e->getMessage());
            return [
                'labels' => ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun'],
                'dados' => [0, 0, 0, 0, 0, 0]
            ];
        }
    }

    public function criar($dados) {
        try {
            $query = "INSERT INTO " . $this->table . " 
                      (cliente_id, tipo_servico_id, endereco_id, titulo, descricao, 
                       orcamento_estimado, status_id, urgencia, data_atendimento) 
                      VALUES (:cliente_id, :tipo_servico_id, :endereco_id, :titulo, 
                              :descricao, :orcamento_estimado, 1, :urgencia, :data_atendimento)";
            
            $stmt = $this->conn->prepare($query);
            
            // Usar bindValue ao invés de bindParam
            $stmt->bindValue(':cliente_id', $dados['cliente_id']);
            $stmt->bindValue(':tipo_servico_id', $dados['tipo_servico_id']);
            $stmt->bindValue(':endereco_id', $dados['endereco_id']);
            $stmt->bindValue(':titulo', $dados['titulo']);
            $stmt->bindValue(':descricao', $dados['descricao']);
            $stmt->bindValue(':orcamento_estimado', $dados['orcamento_estimado']);
            $stmt->bindValue(':urgencia', $dados['urgencia']);
            $stmt->bindValue(':data_atendimento', $dados['data_atendimento']);
            
            return $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Erro ao criar serviço: " . $e->getMessage());
            return false;
        }
    }

    public function getByCliente($cliente_id) {
        try {
            $query = "SELECT s.*, ts.nome as tipo_servico, st.nome as status_texto,
                             st.cor as status_cor, e.logradouro, e.numero, e.bairro
                      FROM " . $this->table . " s
                      INNER JOIN tb_tipo_servico ts ON s.tipo_servico_id = ts.id
                      INNER JOIN tb_status_solicitacao st ON s.status_id = st.id
                      INNER JOIN tb_endereco e ON s.endereco_id = e.id
                      WHERE s.cliente_id = :cliente_id 
                      ORDER BY s.data_solicitacao DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }

    public function getTiposServico() {
        try {
            $query = "SELECT * FROM tb_tipo_servico ORDER BY nome";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }

  

    public function getEnderecosPorCliente($cliente_id) {
        try {
            $query = "SELECT * FROM tb_endereco 
                      WHERE pessoa_id = :cliente_id 
                      ORDER BY principal DESC, id ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }

    public function criarEndereco($dados) {
        try {
            $query = "INSERT INTO tb_endereco 
                      (pessoa_id, cep, logradouro, numero, complemento, bairro, cidade, estado, principal) 
                      VALUES (:pessoa_id, :cep, :logradouro, :numero, :complemento, :bairro, :cidade, :estado, :principal)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':pessoa_id', $dados['pessoa_id']);
            $stmt->bindParam(':cep', $dados['cep']);
            $stmt->bindParam(':logradouro', $dados['logradouro']);
            $stmt->bindParam(':numero', $dados['numero']);
            $stmt->bindParam(':complemento', $dados['complemento']);
            $stmt->bindParam(':bairro', $dados['bairro']);
            $stmt->bindParam(':cidade', $dados['cidade']);
            $stmt->bindParam(':estado', $dados['estado']);
            $stmt->bindParam(':principal', $dados['principal']);
            
            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
            
        } catch (Exception $e) {
            error_log("Erro ao criar endereço: " . $e->getMessage());
            return false;
        }
    }

    public function getDetalhes($servico_id, $cliente_id) {
        try {
            $query = "SELECT s.*, ts.nome as tipo_servico, st.nome as status_texto, st.cor as status_cor,
                             CONCAT(e.logradouro, ', ', e.numero, ' - ', e.bairro, ' - ', e.cidade, '/', e.estado) as endereco_completo,
                             p.prestador_id
                      FROM " . $this->table . " s
                      INNER JOIN tb_tipo_servico ts ON s.tipo_servico_id = ts.id
                      INNER JOIN tb_status_solicitacao st ON s.status_id = st.id
                      INNER JOIN tb_endereco e ON s.endereco_id = e.id
                      LEFT JOIN tb_proposta p ON s.id = p.solicitacao_id AND p.status = 'aceita'
                      WHERE s.id = :servico_id AND s.cliente_id = :cliente_id";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':servico_id', $servico_id);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return false;
        }
    }

    public function atualizar($dados) {
        try {
            $query = "UPDATE " . $this->table . " 
                      SET tipo_servico_id = :tipo_servico_id,
                          endereco_id = :endereco_id,
                          titulo = :titulo,
                          descricao = :descricao,
                          orcamento_estimado = :orcamento_estimado,
                          urgencia = :urgencia,
                          data_atendimento = :data_atendimento
                      WHERE id = :id";
            
            $stmt = $this->conn->prepare($query);
            
            // Usar bindValue ao invés de bindParam para valores que podem ser null
            $stmt->bindValue(':id', $dados['id']);
            $stmt->bindValue(':tipo_servico_id', $dados['tipo_servico_id']);
            $stmt->bindValue(':endereco_id', $dados['endereco_id']);
            $stmt->bindValue(':titulo', $dados['titulo']);
            $stmt->bindValue(':descricao', $dados['descricao']);
            $stmt->bindValue(':orcamento_estimado', $dados['orcamento_estimado']);
            $stmt->bindValue(':urgencia', $dados['urgencia']);
            $stmt->bindValue(':data_atendimento', $dados['data_atendimento']);
            
            return $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Erro ao atualizar serviço: " . $e->getMessage());
            return false;
        }
    }

    public function atualizarStatus($servico_id, $novo_status) {
        try {
            $query = "UPDATE " . $this->table . " SET status_id = :status WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':status', $novo_status);
            $stmt->bindValue(':id', $servico_id);
            
            return $stmt->execute();
            
        } catch (Exception $e) {
            error_log("Erro ao atualizar status: " . $e->getMessage());
            return false;
        }
    }

    public function getLastInsertId() {
        return $this->conn->lastInsertId();
    }

    public function uploadImagensServico($servico_id, $files) {
        try {
            $upload_dir = __DIR__ . '/../uploads/servicos/';
            
            // Criar diretório se não existir
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $uploaded_files = [];
            $errors = [];
            
            for ($i = 0; $i < count($files['name']); $i++) {
                if ($files['error'][$i] === UPLOAD_ERR_OK) {
                    $temp_name = $files['tmp_name'][$i];
                    $original_name = $files['name'][$i];
                    
                    // Validar tipo de arquivo
                    $file_info = getimagesize($temp_name);
                    if ($file_info === false) {
                        $errors[] = "Arquivo {$original_name} não é uma imagem válida.";
                        continue;
                    }
                    
                    // Gerar nome único
                    $extension = pathinfo($original_name, PATHINFO_EXTENSION);
                    $new_name = 'servico_' . $servico_id . '_' . time() . '_' . $i . '.' . $extension;
                    $file_path = $upload_dir . $new_name;
                    
                    // Mover arquivo
                    if (move_uploaded_file($temp_name, $file_path)) {
                        // Salvar no banco
                        $query = "INSERT INTO tb_imagem_solicitacao (solicitacao_id, caminho_imagem) 
                                  VALUES (:servico_id, :caminho)";
                        $stmt = $this->conn->prepare($query);
                        $stmt->bindParam(':servico_id', $servico_id);
                        $stmt->bindParam(':caminho', $new_name);
                        
                        if ($stmt->execute()) {
                            $uploaded_files[] = $new_name;
                        } else {
                            $errors[] = "Erro ao salvar {$original_name} no banco.";
                            unlink($file_path); // Remove arquivo se falhou no banco
                        }
                    } else {
                        $errors[] = "Erro ao mover arquivo {$original_name}.";
                    }
                } else {
                    $errors[] = "Erro no upload de {$files['name'][$i]}.";
                }
            }
            
            return [
                'success' => empty($errors),
                'uploaded' => $uploaded_files,
                'message' => empty($errors) ? 'Imagens enviadas com sucesso!' : implode(' ', $errors)
            ];
            
        } catch (Exception $e) {
            error_log("Erro no upload de imagens: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Erro interno no upload de imagens.'
            ];
        }
    }

    public function getImagensServico($servico_id) {
        try {
            $query = "SELECT * FROM tb_imagem_solicitacao 
                      WHERE solicitacao_id = :servico_id 
                      ORDER BY data_upload ASC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':servico_id', $servico_id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }

    public function getDisponiveis($limit = 10) {
        try {
            $query = "SELECT s.*, ts.nome as tipo_servico
                      FROM " . $this->table . " s
                      INNER JOIN tb_tipo_servico ts ON s.tipo_servico_id = ts.id
                      WHERE s.status_id = 1
                      ORDER BY s.data_solicitacao DESC 
                      LIMIT :limit";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            return [];
        }
    }

    public function getDisponiveisComFiltros($filtros) {
        try {
            $where_conditions = ["s.status_id = 1"];
            $params = [];
            
            // Filtro por tipo
            if (!empty($filtros['tipo'])) {
                $where_conditions[] = "s.tipo_servico_id = :tipo";
                $params[':tipo'] = $filtros['tipo'];
            }
            
            // Filtro por orçamento mínimo
            if (!empty($filtros['orcamento_min'])) {
                $where_conditions[] = "s.orcamento_estimado >= :orcamento_min";
                $params[':orcamento_min'] = $filtros['orcamento_min'];
            }
            
            // Filtro por orçamento máximo
            if (!empty($filtros['orcamento_max'])) {
                $where_conditions[] = "s.orcamento_estimado <= :orcamento_max";
                $params[':orcamento_max'] = $filtros['orcamento_max'];
            }
            
            // Filtro por urgência
            if (!empty($filtros['urgencia'])) {
                $where_conditions[] = "s.urgencia = :urgencia";
                $params[':urgencia'] = $filtros['urgencia'];
            }
            
            $where_clause = implode(' AND ', $where_conditions);
            
            $query = "SELECT s.*, ts.nome as tipo_servico, e.cidade, e.estado
                      FROM " . $this->table . " s
                      INNER JOIN tb_tipo_servico ts ON s.tipo_servico_id = ts.id
                      INNER JOIN tb_endereco e ON s.endereco_id = e.id
                      WHERE {$where_clause}
                      ORDER BY s.data_solicitacao DESC";
            
            $stmt = $this->conn->prepare($query);
            foreach ($params as $param => $value) {
                $stmt->bindValue($param, $value);
            }
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar serviços com filtros: " . $e->getMessage());
            return [];
        }
    }

    public function getDetalhesPublicos($servico_id) {
        try {
            $query = "SELECT s.*, ts.nome as tipo_servico, st.nome as status_texto, st.cor as status_cor,
                             CONCAT(e.logradouro, ', ', e.numero, ' - ', e.bairro, ' - ', e.cidade, '/', e.estado) as endereco_completo,
                             e.cidade, e.estado, e.bairro
                      FROM " . $this->table . " s
                      INNER JOIN tb_tipo_servico ts ON s.tipo_servico_id = ts.id
                      INNER JOIN tb_status_solicitacao st ON s.status_id = st.id
                      INNER JOIN tb_endereco e ON s.endereco_id = e.id
                      WHERE s.id = :servico_id AND s.status_id = 1";
            
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':servico_id', $servico_id);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
            
        } catch (Exception $e) {
            error_log("Erro ao buscar detalhes públicos: " . $e->getMessage());
            return false;
        }
    }

    public function cancelar($servico_id, $cliente_id, $motivo = '') {
        try {
            $this->conn->beginTransaction();
            
            // Verificar se o serviço pertence ao cliente e pode ser cancelado
            $query_check = "SELECT status_id FROM " . $this->table . " 
                           WHERE id = :servico_id AND cliente_id = :cliente_id 
                           AND status_id IN (1, 2)";
            
            $stmt_check = $this->conn->prepare($query_check);
            $stmt_check->bindValue(':servico_id', $servico_id);
            $stmt_check->bindValue(':cliente_id', $cliente_id);
            $stmt_check->execute();
            
            if ($stmt_check->rowCount() === 0) {
                $this->conn->rollback();
                return false;
            }
            
            // Atualizar status do serviço para cancelado (status_id = 6)
            $query_cancelar = "UPDATE " . $this->table . " 
                              SET status_id = 6, data_cancelamento = NOW(), motivo_cancelamento = :motivo 
                              WHERE id = :servico_id";
            
            $stmt_cancelar = $this->conn->prepare($query_cancelar);
            $stmt_cancelar->bindValue(':servico_id', $servico_id);
            $stmt_cancelar->bindValue(':motivo', $motivo);
            $stmt_cancelar->execute();
            
            // Recusar todas as propostas pendentes
            $query_recusar_propostas = "UPDATE tb_proposta 
                                       SET status = 'recusada', data_recusa = NOW() 
                                       WHERE solicitacao_id = :servico_id AND status = 'pendente'";
            
            $stmt_recusar = $this->conn->prepare($query_recusar_propostas);
            $stmt_recusar->bindValue(':servico_id', $servico_id);
            $stmt_recusar->execute();
            
            $this->conn->commit();
            return true;
            
        } catch (Exception $e) {
            $this->conn->rollback();
            error_log("Erro ao cancelar serviço: " . $e->getMessage());
            return false;
        }
    }
}
?>
