<?php
// models/Servico.class.php
// A classe Database será carregada automaticamente pelo autoloader.

/**
 * Model responsável pela gestão dos dados de serviços (solicitações).
 * Segue o padrão MVC, encapsulando toda a lógica de acesso ao banco de dados.
 */
class Servico
{
    /**
     * @var PDO Objeto de conexão com o banco de dados.
     */
    private $conn;

    /**
     * @var string Nome da tabela no banco de dados.
     */
    private $table = 'tb_solicita_servico';

    /**
     * O construtor da classe estabelece a conexão com o banco de dados.
     */
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
            error_log("Erro ao conectar no Servico: " . $e->getMessage());
            throw new Exception("Erro ao inicializar serviço: " . $e->getMessage());
        }
    }

    /**
     * Retorna serviços recentes de um cliente para exibição em dashboards.
     * @param int $cliente_id ID do cliente.
     * @param int $limite Limite de resultados.
     * @return array Array de serviços recentes.
     */
    public function getRecentes($cliente_id, $limite = 4)
    {
        try {
            $query = "
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
                FROM {$this->table} s
                LEFT JOIN tb_status_solicitacao ss ON s.status_id = ss.id
                WHERE s.cliente_id = :cliente_id
                ORDER BY s.data_solicitacao DESC
                LIMIT :limite
            ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->bindParam(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar serviços recentes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retorna dados agregados para o gráfico de serviços mensais do cliente.
     * @param int $cliente_id ID do cliente.
     * @return array Array com labels e dados para o gráfico.
     */
    public function getGraficoDados($cliente_id)
    {
        try {
            $query = "
                SELECT 
                    DATE_FORMAT(data_solicitacao, '%Y-%m') as mes,
                    COUNT(*) as total
                FROM {$this->table}
                WHERE cliente_id = :cliente_id 
                  AND data_solicitacao >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
                GROUP BY mes
                ORDER BY mes ASC
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt->execute();
            $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Mapeia os resultados para uma busca mais eficiente.
            $meses_map = [];
            foreach ($resultados as $resultado) {
                $meses_map[$resultado['mes']] = (int) $resultado['total'];
            }

            $labels = [];
            $dados = [];
            // Gera os labels para os últimos 6 meses, preenchendo com 0 onde não há dados.
            for ($i = 5; $i >= 0; $i--) {
                $mes_chave = date('Y-m', strtotime("-$i months"));
                $mes_label = date('M/Y', strtotime("-$i months"));

                $labels[] = $mes_label;
                $dados[] = $meses_map[$mes_chave] ?? 0;
            }

            return ['labels' => $labels, 'dados' => $dados];
        } catch (Exception $e) {
            error_log("Erro ao buscar dados do gráfico: " . $e->getMessage());
            // Retorna uma estrutura vazia em caso de erro para não quebrar o front-end.
            $labels = array_map(fn($i) => date('M/Y', strtotime("-$i months")), range(5, 0));
            return ['labels' => $labels, 'dados' => array_fill(0, 6, 0)];
        }
    }

    /**
     * Retorna todos os tipos de serviço disponíveis.
     * @return array Array de tipos de serviço.
     */
    public function getTiposServico()
    {
        try {
            $query = "SELECT id, nome FROM tb_tipo_servico ORDER BY nome";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar tipos de serviço: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retorna os endereços de um cliente.
     * @param int $cliente_id ID do cliente.
     * @return array Array de endereços.
     */
    public function getEnderecosPorCliente($cliente_id)
    {
        try {
            $query = "SELECT * FROM tb_endereco 
                      WHERE pessoa_id = :cliente_id 
                      ORDER BY principal DESC, id ASC";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar endereços do cliente: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Busca todos os serviços de um cliente.
     * @param int $cliente_id ID do cliente.
     * @return array Array de serviços do cliente.
     */
    public function getByCliente($cliente_id)
    {
        try {
            $query = "
                SELECT s.*, ts.nome as tipo_servico, st.nome as status_texto, st.cor as status_cor,
                       e.logradouro, e.numero, e.complemento, e.bairro, e.cidade, e.estado, e.cep,
                       CONCAT(e.logradouro, ', ', e.numero, 
                              CASE WHEN e.complemento IS NOT NULL THEN CONCAT(', ', e.complemento) ELSE '' END,
                              ', ', e.bairro, ', ', e.cidade, ' - ', e.estado) as endereco_completo
                FROM {$this->table} s
                LEFT JOIN tb_tipo_servico ts ON ts.id = s.tipo_servico_id
                LEFT JOIN tb_status_solicitacao st ON st.id = s.status_id
                LEFT JOIN tb_endereco e ON e.id = s.endereco_id
                WHERE s.cliente_id = :cliente_id
                ORDER BY s.data_solicitacao DESC
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();

            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar serviços: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retorna os detalhes de um serviço para um cliente específico.
     * @param int $servico_id ID do serviço.
     * @param int $cliente_id ID do cliente.
     * @return array|bool Detalhes do serviço ou false em caso de erro.
     */
    public function getDetalhes($servico_id, $cliente_id)
    {
        try {
            $query = "
                SELECT s.*, ts.nome as tipo_servico, st.nome as status_texto, st.cor as status_cor,
                       e.logradouro, e.numero, e.complemento, e.bairro, e.cidade, e.estado, e.cep,
                       CONCAT(e.logradouro, ', ', e.numero, 
                              CASE WHEN e.complemento IS NOT NULL THEN CONCAT(', ', e.complemento) ELSE '' END,
                              ', ', e.bairro, ', ', e.cidade, ' - ', e.estado, ', CEP: ', e.cep) as endereco_completo
                FROM {$this->table} s
                LEFT JOIN tb_tipo_servico ts ON ts.id = s.tipo_servico_id
                LEFT JOIN tb_status_solicitacao st ON st.id = s.status_id
                LEFT JOIN tb_endereco e ON e.id = s.endereco_id
                WHERE s.id = :servico_id AND s.cliente_id = :cliente_id
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':servico_id', $servico_id);
            $stmt->bindParam(':cliente_id', $cliente_id);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar detalhes do serviço: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Cria um novo serviço no banco de dados.
     * @param array $dados Dados do serviço (ex: vindo de um formulário $_POST).
     * @return int|bool Retorna o ID do novo serviço ou false em caso de erro.
     */
    public function criar($dados)
    {
        try {
            $query = "INSERT INTO {$this->table} 
                        (cliente_id, tipo_servico_id, endereco_id, titulo, descricao, 
                         orcamento_estimado, status_id, urgencia, data_atendimento) 
                      VALUES (:cliente_id, :tipo_servico_id, :endereco_id, :titulo, 
                              :descricao, :orcamento_estimado, 1, :urgencia, :data_atendimento)";

            $stmt = $this->conn->prepare($query);

            // Sanitiza e associa os parâmetros
            $stmt->bindValue(':cliente_id', $dados['cliente_id'], PDO::PARAM_INT);
            $stmt->bindValue(':tipo_servico_id', $dados['tipo_servico_id'], PDO::PARAM_INT);
            $stmt->bindValue(':endereco_id', $dados['endereco_id'], PDO::PARAM_INT);
            $stmt->bindValue(':titulo', htmlspecialchars(strip_tags($dados['titulo'])));
            $stmt->bindValue(':descricao', htmlspecialchars(strip_tags($dados['descricao'])));
            $stmt->bindValue(':orcamento_estimado', $dados['orcamento_estimado']);
            $stmt->bindValue(':urgencia', $dados['urgencia']);
            $stmt->bindValue(':data_atendimento', $dados['data_atendimento']);

            if ($stmt->execute()) {
                return $this->conn->lastInsertId();
            }
            return false;
        } catch (Exception $e) {
            error_log("Erro ao criar serviço: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualiza um serviço.
     * @param array $dados Dados a serem atualizados.
     * @return bool True se a atualização for bem-sucedida, false caso contrário.
     */
    public function atualizar($dados)
    {
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

    /**
     * Cancela um serviço e recusa as propostas pendentes relacionadas.
     * Apenas o cliente dono do serviço pode cancelar, e somente se o serviço
     * estiver em um status que permita o cancelamento (ex: Pendente, status_id = 1).
     *
     * @param int $servico_id ID do serviço a ser cancelado.
     * @param int $cliente_id ID do cliente que está tentando cancelar (para segurança).
     * @param string $motivo O motivo do cancelamento.
     * @return bool Retorna true se o cancelamento for bem-sucedido, false caso contrário.
     */
    public function cancelar($servico_id, $cliente_id, $motivo)
    {
        // A transação garante que ambas as operações (cancelar e recusar)
        // ocorram com sucesso, ou nenhuma delas ocorre.
        try {
            $this->conn->beginTransaction();

            // 1. Atualiza o serviço para "Cancelado pelo Cliente" (assumindo status_id = 6)
            // VERIFICAÇÃO CRUCIAL: só cancela se o ID do cliente bater e o status for 'Pendente' (status_id = 1)
            $query_cancelar = "UPDATE {$this->table}
                               SET status_id = 6, 
                                   data_cancelamento = NOW(), 
                                   motivo_cancelamento = :motivo 
                               WHERE id = :servico_id 
                                 AND cliente_id = :cliente_id 
                                 AND status_id = 1";

            $stmt_cancelar = $this->conn->prepare($query_cancelar);
            $stmt_cancelar->bindValue(':servico_id', $servico_id, PDO::PARAM_INT);
            $stmt_cancelar->bindValue(':cliente_id', $cliente_id, PDO::PARAM_INT);
            $stmt_cancelar->bindValue(':motivo', htmlspecialchars(strip_tags($motivo)), PDO::PARAM_STR);
            $stmt_cancelar->execute();

            // Se nenhuma linha foi afetada, o serviço não pertencia ao cliente
            // ou não estava em um status cancelável. A transação é revertida.
            if ($stmt_cancelar->rowCount() === 0) {
                $this->conn->rollback();
                error_log("Tentativa de cancelamento falhou para o serviço #{$servico_id}. Motivo: Não pertence ao cliente #{$cliente_id} ou não está com status 'Pendente'.");
                return false;
            }

            // 2. Recusa todas as propostas que estavam pendentes para este serviço
            $query_recusar_propostas = "UPDATE tb_proposta 
                                        SET status = 'recusada', data_recusa = NOW() 
                                        WHERE solicitacao_id = :servico_id AND status = 'pendente'";

            $stmt_recusar = $this->conn->prepare($query_recusar_propostas);
            $stmt_recusar->bindValue(':servico_id', $servico_id, PDO::PARAM_INT);
            $stmt_recusar->execute();

            // Se tudo deu certo, confirma as alterações no banco de dados.
            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            // Se qualquer erro de banco de dados ocorrer, desfaz todas as alterações.
            if ($this->conn->inTransaction()) {
                $this->conn->rollback();
            }
            error_log("Erro na transação de cancelamento do serviço #{$servico_id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retorna serviços disponíveis para prestadores (status pendente ou em análise).
     * @param int $limite Limite de resultados.
     * @return array
     */
    public function getDisponiveis($limite = 5)
    {
        try {
            $query = "
                SELECT s.*, ts.nome as tipo_servico, st.nome as status_texto, st.cor as status_cor,
                       e.logradouro, e.numero, e.complemento, e.bairro, e.cidade, e.estado, e.cep,
                       CONCAT(e.logradouro, ', ', e.numero, 
                              CASE WHEN e.complemento IS NOT NULL THEN CONCAT(', ', e.complemento) ELSE '' END,
                              ', ', e.bairro, ', ', e.cidade, ' - ', e.estado) as endereco_completo
                FROM {$this->table} s
                LEFT JOIN tb_tipo_servico ts ON ts.id = s.tipo_servico_id
                LEFT JOIN tb_status_solicitacao st ON st.id = s.status_id
                LEFT JOIN tb_endereco e ON e.id = s.endereco_id
                WHERE s.status_id IN (1,2)
                ORDER BY s.data_solicitacao DESC
                LIMIT :limite
            ";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar serviços disponíveis: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retorna serviços disponíveis para prestadores com filtros (status pendente ou em análise).
     * @param array $filtros Array de filtros a serem aplicados na busca.
     * @return array
     */
    public function getDisponiveisComFiltros($filtros = [])
    {
        try {
            $query = "SELECT s.*, ts.nome as tipo_servico, st.nome as status_texto, st.cor as status_cor,
                       e.logradouro, e.numero, e.complemento, e.bairro, e.cidade, e.estado, e.cep,
                       CONCAT(e.logradouro, ', ', e.numero, 
                              CASE WHEN e.complemento IS NOT NULL THEN CONCAT(', ', e.complemento) ELSE '' END,
                              ', ', e.bairro, ', ', e.cidade, ' - ', e.estado) as endereco_completo
                      FROM {$this->table} s
                      LEFT JOIN tb_tipo_servico ts ON ts.id = s.tipo_servico_id
                      LEFT JOIN tb_status_solicitacao st ON st.id = s.status_id
                      LEFT JOIN tb_endereco e ON e.id = s.endereco_id
                      WHERE s.status_id IN (1,2)";

            // Adiciona filtros à query, se houver
            if (!empty($filtros)) {
                foreach ($filtros as $key => $value) {
                    // Exemplo de filtro: apenas por status_id
                    if ($key === 'status_id' && is_array($value)) {
                        $query .= " AND s.status_id IN (" . implode(',', array_map('intval', $value)) . ")";
                    }
                    // Adicione mais condições de filtro conforme necessário
                }
            }

            $query .= " ORDER BY s.data_solicitacao DESC LIMIT 10"; // Limite padrão de 10 resultados
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar serviços disponíveis com filtros: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Retorna os detalhes públicos de um serviço (usado pelo prestador).
     * @param int $servico_id ID do serviço.
     * @return array|bool Detalhes do serviço ou false em caso de erro.
     */
    public function getDetalhesPublicos($servico_id)
    {
        try {
            $query = "
                SELECT s.*, ts.nome as tipo_servico, st.nome as status_texto, st.cor as status_cor,
                       e.logradouro, e.numero, e.complemento, e.bairro, e.cidade, e.estado, e.cep,
                       CONCAT(e.logradouro, ', ', e.numero, 
                              CASE WHEN e.complemento IS NOT NULL THEN CONCAT(', ', e.complemento) ELSE '' END,
                              ', ', e.bairro, ', ', e.cidade, ' - ', e.estado, ', CEP: ', e.cep) as endereco_completo
                FROM {$this->table} s
                LEFT JOIN tb_tipo_servico ts ON ts.id = s.tipo_servico_id
                LEFT JOIN tb_status_solicitacao st ON st.id = s.status_id
                LEFT JOIN tb_endereco e ON e.id = s.endereco_id
                WHERE s.id = :servico_id
                LIMIT 1
            ";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':servico_id', $servico_id, PDO::PARAM_INT);
            $stmt->execute();

            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("Erro ao buscar detalhes públicos do serviço: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Retorna as imagens relacionadas a um serviço.
     * @param int $servico_id
     * @return array Array com chaves (id, caminho_imagem) ou array vazio
     */
    public function getImagensServico($servico_id)
    {
        try {
            // Tenta ler de uma tabela comum para imagens; se a tabela não existir,
            // captura exceção e retorna array vazio.
            $query = "SELECT id, caminho_imagem FROM tb_imagens_servico WHERE servico_id = :servico_id ORDER BY id ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':servico_id', $servico_id, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Caso a estrutura de colunas seja diferente, normalize o retorno.
            if (!$rows) return [];
            return $rows;
        } catch (Exception $e) {
            // loga e retorna array vazio para não quebrar a view/modal
            error_log("Erro ao buscar imagens do serviço #{$servico_id}: " . $e->getMessage());
            return [];
        }
    }
}
