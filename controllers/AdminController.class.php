<?php
require_once '../config/database.php';
require_once '../models/TipoServico.class.php';
require_once '../models/StatusSolicitacao.class.php';
require_once '../models/Usuario.class.php';

class AdminController {
    private $db;
    private $tipoServico;
    private $statusSolicitacao;
    private $usuario;

    public function __construct() {
        $database = new Database();
        $this->db = $database->getConnection();
        $this->tipoServico = new TipoServico($this->db);
        $this->statusSolicitacao = new StatusSolicitacao($this->db);
        $this->usuario = new Usuario($this->db);
    }

    public function index() {
        $this->verificarAutenticacao();
        
        // Buscar estatísticas do dashboard
        $stats = $this->getDashboardStats();
        
        header('Content-Type: application/json');
        echo json_encode([
            'sucesso' => true,
            'dados' => $stats
        ]);
    }

    public function listarTiposServico() {
        try {
            $page = $_GET['page'] ?? 1;
            $perPage = $_GET['per_page'] ?? 10;
            $nome = $_GET['nome'] ?? '';

            $resultado = $this->tipoServico->listar($page, $perPage, $nome);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'dados' => $resultado
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function criarTipoServico() {
        try {
            $dados = [
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao'] ?? null,
                'icone' => $_POST['icone'] ?? null
            ];

            $resultado = $this->tipoServico->criar($dados);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Tipo de serviço criado com sucesso!',
                'dados' => $resultado
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function atualizarTipoServico() {
        try {
            $id = $_GET['id'];
            $dados = [
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao'] ?? null,
                'icone' => $_POST['icone'] ?? null
            ];

            $this->tipoServico->atualizar($id, $dados);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Tipo de serviço atualizado com sucesso!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function buscarTipoServico() {
        try {
            $id = $_GET['id'];
            $resultado = $this->tipoServico->buscarPorId($id);
            
            if ($resultado) {
                header('Content-Type: application/json');
                echo json_encode([
                    'sucesso' => true,
                    'dados' => $resultado
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Tipo de serviço não encontrado'
                ]);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function deletarTipoServico() {
        try {
            $id = $_GET['id'];
            $this->tipoServico->deletar($id);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Tipo de serviço deletado com sucesso!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function listarStatusSolicitacao() {
        try {
            $page = $_GET['page'] ?? 1;
            $perPage = $_GET['per_page'] ?? 10;
            $nome = $_GET['nome'] ?? '';

            $resultado = $this->statusSolicitacao->listar($page, $perPage, $nome);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'dados' => $resultado
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function buscarStatusSolicitacao() {
        try {
            $id = $_GET['id'];
            $resultado = $this->statusSolicitacao->buscarPorId($id);
            
            if ($resultado) {
                header('Content-Type: application/json');
                echo json_encode([
                    'sucesso' => true,
                    'dados' => $resultado
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Status não encontrado'
                ]);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function criarStatusSolicitacao() {
        try {
            $dados = [
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao'] ?? null,
                'cor' => $_POST['cor'] ?? '#007bff'
            ];

            $resultado = $this->statusSolicitacao->criar($dados);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Status criado com sucesso!',
                'dados' => $resultado
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function atualizarStatusSolicitacao() {
        try {
            $id = $_GET['id'];
            $dados = [
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao'] ?? null,
                'cor' => $_POST['cor'] ?? '#007bff'
            ];

            $this->statusSolicitacao->atualizar($id, $dados);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Status atualizado com sucesso!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function deletarStatusSolicitacao() {
        try {
            $id = $_GET['id'];
            $this->statusSolicitacao->deletar($id);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Status deletado com sucesso!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function estatisticasStatusSolicitacao() {
        try {
            $stats = $this->statusSolicitacao->obterEstatisticas();
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'dados' => $stats
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function listarUsuarios() {
        try {
            $page = $_GET['page'] ?? 1;
            $perPage = $_GET['per_page'] ?? 10;
            $filtros = [
                'nome' => $_GET['nome'] ?? '',
                'email' => $_GET['email'] ?? '',
                'tipo' => $_GET['tipo'] ?? ''
            ];

            $resultado = $this->usuario->listar($page, $perPage, $filtros);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'dados' => $resultado
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function buscarUsuario() {
        try {
            $id = $_GET['id'];
            $resultado = $this->usuario->buscarPorId($id);
            
            if ($resultado) {
                header('Content-Type: application/json');
                echo json_encode([
                    'sucesso' => true,
                    'dados' => $resultado
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Usuário não encontrado'
                ]);
            }
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function atualizarUsuario() {
        try {
            $id = $_GET['id'];
            $dados = [
                'nome' => $_POST['nome'],
                'email' => $_POST['email'],
                'telefone' => $_POST['telefone'] ?? null,
                'tipo' => $_POST['tipo']
            ];

            if (!empty($_POST['senha'])) {
                $dados['senha'] = $_POST['senha'];
            }

            $this->usuario->atualizar($id, $dados);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Usuário atualizado com sucesso!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function toggleStatusUsuario() {
        try {
            $id = $_GET['id'];
            $this->usuario->toggleStatus($id);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Status do usuário alterado com sucesso!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function deletarUsuario() {
        try {
            $id = $_GET['id'];
            $this->usuario->deletar($id);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Usuário deletado com sucesso!'
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function estatisticasUsuarios() {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    COUNT(*) as total_usuarios,
                    COUNT(CASE WHEN tipo = 'cliente' THEN 1 END) as total_clientes,
                    COUNT(CASE WHEN tipo = 'prestador' THEN 1 END) as total_prestadores,
                    COUNT(CASE WHEN tipo = 'ambos' THEN 1 END) as total_ambos,
                    COUNT(CASE WHEN ativo = 1 THEN 1 END) as usuarios_ativos,
                    COUNT(CASE WHEN ativo = 0 THEN 1 END) as usuarios_inativos,
                    COUNT(CASE WHEN DATE(data_cadastro) = CURDATE() THEN 1 END) as cadastros_hoje,
                    COUNT(CASE WHEN DATE(data_cadastro) >= DATE_SUB(NOW(), INTERVAL 7 DAY) THEN 1 END) as cadastros_semana,
                    COUNT(CASE WHEN DATE(data_cadastro) >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as cadastros_mes
                FROM tb_pessoa
            ");
            $stmt->execute();
            $stats = $stmt->fetch(PDO::FETCH_ASSOC);
            
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'dados' => $stats
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function relatorio() {
        $tipo = $_GET['tipo'] ?? 'geral';
        $data_inicio = $_GET['data_inicio'] ?? null;
        $data_fim = $_GET['data_fim'] ?? null;

        $whereData = '';
        $params = [];

        if ($data_inicio) {
            $whereData .= " AND data_cadastro >= :data_inicio";
            $params[':data_inicio'] = $data_inicio . " 00:00:00";
        }
        if ($data_fim) {
            $whereData .= " AND data_cadastro <= :data_fim";
            $params[':data_fim'] = $data_fim . " 23:59:59";
        }

        $dados = [];

        // Usuários
        if ($tipo == 'usuarios' || $tipo == 'geral') {
            $sql = "SELECT 
                        COUNT(*) as total_usuarios,
                        COUNT(CASE WHEN tipo = 'cliente' THEN 1 END) as total_clientes,
                        COUNT(CASE WHEN tipo = 'prestador' THEN 1 END) as total_prestadores,
                        COUNT(CASE WHEN tipo = 'ambos' THEN 1 END) as total_ambos
                    FROM tb_pessoa
                    WHERE 1=1 $whereData";
            $stmt = $this->db->prepare($sql);
            foreach ($params as $k => $v) $stmt->bindValue($k, $v);
            $stmt->execute();
            $dados = array_merge($dados, $stmt->fetch(PDO::FETCH_ASSOC));
        }

        // Solicitações
        if ($tipo == 'solicitacoes' || $tipo == 'geral') {
            $sql = "SELECT 
                        COUNT(*) as total_solicitacoes,
                        COUNT(CASE WHEN DATE(data_solicitacao) = CURDATE() THEN 1 END) as solicitacoes_hoje,
                        COUNT(CASE WHEN MONTH(data_solicitacao) = MONTH(NOW()) AND YEAR(data_solicitacao) = YEAR(NOW()) THEN 1 END) as solicitacoes_mes
                    FROM tb_solicita_servico
                    WHERE 1=1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $dados = array_merge($dados, $stmt->fetch(PDO::FETCH_ASSOC));
        }

        // Propostas
        if ($tipo == 'propostas' || $tipo == 'geral') {
            $sql = "SELECT 
                        COUNT(*) as total_propostas,
                        COUNT(CASE WHEN status = 'aceita' THEN 1 END) as propostas_aceitas,
                        COUNT(CASE WHEN status = 'pendente' THEN 1 END) as propostas_pendentes
                    FROM tb_proposta";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $dados = array_merge($dados, $stmt->fetch(PDO::FETCH_ASSOC));
        }

        // Avaliações
        if ($tipo == 'avaliacoes' || $tipo == 'geral') {
            $sql = "SELECT 
                        COUNT(*) as total_avaliacoes,
                        AVG(nota) as media_avaliacoes
                    FROM tb_avaliacao";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $dados = array_merge($dados, $stmt->fetch(PDO::FETCH_ASSOC));
        }

        header('Content-Type: application/json');
        echo json_encode([
            'sucesso' => true,
            'dados' => $dados
        ]);
    }

    public function relatorioAvancado() {
        try {
            $tipo = $_GET['tipo'] ?? 'geral';
            $periodo = $_GET['periodo'] ?? 'ultimo_mes';
            $data_inicio = $_GET['data_inicio'] ?? null;
            $data_fim = $_GET['data_fim'] ?? null;
            $status = $_GET['status'] ?? '';

            // Definir datas baseado no período
            if ($periodo !== 'personalizado') {
                $hoje = new DateTime();
                $dataInicio = clone $hoje;
                
                switch($periodo) {
                    case 'hoje':
                        $dataInicio = $hoje;
                        break;
                    case 'ontem':
                        $dataInicio->modify('-1 day');
                        $hoje->modify('-1 day');
                        break;
                    case 'ultima_semana':
                        $dataInicio->modify('-7 days');
                        break;
                    case 'ultimo_mes':
                        $dataInicio->modify('-1 month');
                        break;
                    case 'ultimos_3_meses':
                        $dataInicio->modify('-3 months');
                        break;
                    case 'ultimo_ano':
                        $dataInicio->modify('-1 year');
                        break;
                }
                
                $data_inicio = $dataInicio->format('Y-m-d');
                $data_fim = $hoje->format('Y-m-d');
            }

            $dados = [];
            
            switch($tipo) {
                case 'usuarios':
                    $dados = $this->relatorioUsuarios($data_inicio, $data_fim, $status);
                    break;
                case 'solicitacoes':
                    $dados = $this->relatorioSolicitacoes($data_inicio, $data_fim);
                    break;
                case 'propostas':
                    $dados = $this->relatorioPropostas($data_inicio, $data_fim);
                    break;
                case 'avaliacoes':
                    $dados = $this->relatorioAvaliacoes($data_inicio, $data_fim);
                    break;
                case 'financeiro':
                    $dados = $this->relatorioFinanceiro($data_inicio, $data_fim);
                    break;
                case 'performance':
                    $dados = $this->relatorioPerformance($data_inicio, $data_fim);
                    break;
                default:
                    $dados = $this->relatorioGeral($data_inicio, $data_fim);
            }

            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => true,
                'dados' => $dados
            ]);
        } catch (Exception $e) {
            header('Content-Type: application/json');
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    private function relatorioUsuarios($data_inicio, $data_fim, $status = '') {
        $whereStatus = $status ? "AND ativo = " . ($status === 'ativo' ? '1' : '0') : '';
        
        // Dados principais
        $sql = "SELECT 
                    COUNT(*) as total_usuarios,
                    COUNT(CASE WHEN tipo = 'cliente' THEN 1 END) as total_clientes,
                    COUNT(CASE WHEN tipo = 'prestador' THEN 1 END) as total_prestadores,
                    COUNT(CASE WHEN tipo = 'ambos' THEN 1 END) as total_ambos,
                    COUNT(CASE WHEN ativo = 1 THEN 1 END) as usuarios_ativos,
                    COUNT(CASE WHEN DATE(data_cadastro) BETWEEN :data_inicio AND :data_fim THEN 1 END) as novos_usuarios
                FROM tb_pessoa 
                WHERE 1=1 $whereStatus";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        // Cálculo de crescimento
        $dataInicioAnterior = date('Y-m-d', strtotime($data_inicio . ' -' . (strtotime($data_fim) - strtotime($data_inicio)) . ' seconds'));
        $sql = "SELECT COUNT(*) as usuarios_periodo_anterior FROM tb_pessoa WHERE DATE(data_cadastro) BETWEEN :data_inicio_anterior AND :data_inicio";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio_anterior', $dataInicioAnterior);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->execute();
        $usuariosPeriodoAnterior = $stmt->fetch(PDO::FETCH_ASSOC)['usuarios_periodo_anterior'];
        
        $dados['crescimento_percentual'] = $usuariosPeriodoAnterior > 0 ? 
            round((($dados['novos_usuarios'] - $usuariosPeriodoAnterior) / $usuariosPeriodoAnterior) * 100, 1) : 100;

        // Dados temporais
        $sql = "SELECT 
                    DATE(data_cadastro) as periodo,
                    COUNT(*) as valor
                FROM tb_pessoa 
                WHERE DATE(data_cadastro) BETWEEN :data_inicio AND :data_fim $whereStatus
                GROUP BY DATE(data_cadastro) 
                ORDER BY data_cadastro";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['temporal'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Distribuição por tipo
        $sql = "SELECT 
                    tipo as categoria,
                    COUNT(*) as valor
                FROM tb_pessoa 
                WHERE DATE(data_cadastro) BETWEEN :data_inicio AND :data_fim $whereStatus
                GROUP BY tipo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['distribuicao'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Dados detalhados
        $sql = "SELECT id, nome, email, tipo, data_cadastro, ultimo_acesso, 
                    CASE WHEN ativo = 1 THEN 'Ativo' ELSE 'Inativo' END as status
                FROM tb_pessoa 
                WHERE DATE(data_cadastro) BETWEEN :data_inicio AND :data_fim $whereStatus
                ORDER BY data_cadastro DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['detalhado'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    private function relatorioSolicitacoes($data_inicio, $data_fim) {
        // Dados principais
        $sql = "SELECT 
                    COUNT(*) as total_solicitacoes,
                    COUNT(CASE WHEN DATE(data_solicitacao) BETWEEN :data_inicio AND :data_fim THEN 1 END) as novas_solicitacoes,
                    COUNT(CASE WHEN status_id IN (5, 13) THEN 1 END) as solicitacoes_concluidas,
                    AVG(orcamento_estimado) as valor_medio,
                    AVG(TIMESTAMPDIFF(HOUR, data_solicitacao, (
                        SELECT MIN(data_proposta) 
                        FROM tb_proposta p 
                        WHERE p.solicitacao_id = s.id
                    ))) as tempo_medio_resposta
                FROM tb_solicita_servico s
                WHERE 1=1";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        // Dados temporais
        $sql = "SELECT 
                    DATE(data_solicitacao) as periodo,
                    COUNT(*) as valor
                FROM tb_solicita_servico 
                WHERE DATE(data_solicitacao) BETWEEN :data_inicio AND :data_fim
                GROUP BY DATE(data_solicitacao) 
                ORDER BY data_solicitacao";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['temporal'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Distribuição por status
        $sql = "SELECT 
                    st.nome as categoria,
                    COUNT(*) as valor
                FROM tb_solicita_servico s
                JOIN tb_status_solicitacao st ON s.status_id = st.id
                WHERE DATE(s.data_solicitacao) BETWEEN :data_inicio AND :data_fim
                GROUP BY st.nome";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['distribuicao'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    private function relatorioFinanceiro($data_inicio, $data_fim) {
        $sql = "SELECT 
                    COUNT(CASE WHEN p.status = 'aceita' THEN 1 END) as total_negociacoes,
                    SUM(CASE WHEN p.status = 'aceita' THEN p.valor ELSE 0 END) as volume_total,
                    AVG(CASE WHEN p.status = 'aceita' THEN p.valor ELSE NULL END) as ticket_medio,
                    COUNT(CASE WHEN p.status = 'aceita' AND DATE(p.data_proposta) BETWEEN :data_inicio AND :data_fim THEN 1 END) as negociacoes_periodo
                FROM tb_proposta p";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        // Volume por dia
        $sql = "SELECT 
                    DATE(data_proposta) as periodo,
                    SUM(CASE WHEN status = 'aceita' THEN valor ELSE 0 END) as valor
                FROM tb_proposta 
                WHERE DATE(data_proposta) BETWEEN :data_inicio AND :data_fim
                GROUP BY DATE(data_proposta) 
                ORDER BY data_proposta";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['temporal'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    private function relatorioPerformance($data_inicio, $data_fim) {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM tb_pessoa WHERE ultimo_acesso >= DATE_SUB(NOW(), INTERVAL 7 DAY)) as usuarios_ativos_semana,
                    (SELECT AVG(nota) FROM tb_avaliacao WHERE DATE(data_avaliacao) BETWEEN :data_inicio AND :data_fim) as satisfacao_media,
                    (SELECT COUNT(*) FROM tb_proposta WHERE status = 'aceita' AND DATE(data_proposta) BETWEEN :data_inicio AND :data_fim) as conversao_propostas";
                
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function relatorioGeral($data_inicio, $data_fim) {
        $sql = "SELECT 
                    (SELECT COUNT(*) FROM tb_pessoa WHERE ativo = 1) as total_usuarios,
                    (SELECT COUNT(*) FROM tb_solicita_servico) as total_solicitacoes,
                    (SELECT COUNT(*) FROM tb_proposta) as total_propostas,
                    (SELECT AVG(nota) FROM tb_avaliacao) as media_avaliacoes";
                    
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function relatorioPropostas($data_inicio, $data_fim) {
        $sql = "SELECT 
                    COUNT(*) as total_propostas,
                    COUNT(CASE WHEN status = 'aceita' THEN 1 END) as propostas_aceitas,
                    COUNT(CASE WHEN status = 'pendente' THEN 1 END) as propostas_pendentes
                FROM tb_proposta
                WHERE DATE(data_proposta) BETWEEN :data_inicio AND :data_fim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        // Dados temporais
        $sql = "SELECT 
                    DATE(data_proposta) as periodo,
                    COUNT(*) as valor
                FROM tb_proposta
                WHERE DATE(data_proposta) BETWEEN :data_inicio AND :data_fim
                GROUP BY DATE(data_proposta)
                ORDER BY data_proposta";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['temporal'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Distribuição por status
        $sql = "SELECT 
                    status as categoria,
                    COUNT(*) as valor
                FROM tb_proposta
                WHERE DATE(data_proposta) BETWEEN :data_inicio AND :data_fim
                GROUP BY status";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['distribuicao'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    private function relatorioAvaliacoes($data_inicio, $data_fim) {
        $sql = "SELECT 
                    COUNT(*) as total_avaliacoes,
                    AVG(nota) as media_avaliacoes
                FROM tb_avaliacao
                WHERE DATE(data_avaliacao) BETWEEN :data_inicio AND :data_fim";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados = $stmt->fetch(PDO::FETCH_ASSOC);

        // Dados temporais
        $sql = "SELECT 
                    DATE(data_avaliacao) as periodo,
                    COUNT(*) as valor
                FROM tb_avaliacao
                WHERE DATE(data_avaliacao) BETWEEN :data_inicio AND :data_fim
                GROUP BY DATE(data_avaliacao)
                ORDER BY data_avaliacao";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['temporal'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Distribuição por nota
        $sql = "SELECT 
                    nota as categoria,
                    COUNT(*) as valor
                FROM tb_avaliacao
                WHERE DATE(data_avaliacao) BETWEEN :data_inicio AND :data_fim
                GROUP BY nota";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':data_inicio', $data_inicio);
        $stmt->bindParam(':data_fim', $data_fim);
        $stmt->execute();
        $dados['distribuicao'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $dados;
    }

    public function exportarRelatorio() {
        $formato = $_GET['formato'] ?? 'excel';
        $tipo = $_GET['tipo'] ?? 'geral';
        
        // Gerar dados do relatório
        $dados = $this->relatorioAvancado();
        
        if ($formato === 'excel') {
            header('Content-Type: application/vnd.ms-excel');
            header('Content-Disposition: attachment; filename="relatorio_' . $tipo . '_' . date('Y-m-d') . '.xls"');
            
            echo "<table border='1'>";
            echo "<tr><th>Relatório de " . ucfirst($tipo) . "</th></tr>";
            echo "<tr><th>Data: " . date('d/m/Y H:i') . "</th></tr>";
            echo "</table>";
        }
        // PDF seria implementado com biblioteca como TCPDF
    }

    private function getDashboardStats() {
        $stmt = $this->db->prepare("
            SELECT 
                (SELECT COUNT(*) FROM tb_pessoa WHERE ativo = 1) as total_usuarios,
                (SELECT COUNT(*) FROM tb_pessoa WHERE tipo = 'cliente' AND ativo = 1) as total_clientes,
                (SELECT COUNT(*) FROM tb_pessoa WHERE tipo IN ('prestador', 'ambos') AND ativo = 1) as total_prestadores,
                (SELECT COUNT(*) FROM tb_solicita_servico WHERE DATE(data_solicitacao) = CURDATE()) as solicitacoes_hoje,
                (SELECT COUNT(*) FROM tb_proposta WHERE DATE(data_proposta) = CURDATE()) as propostas_hoje,
                (SELECT COUNT(*) FROM tb_tipo_servico) as total_tipos_servico,
                (SELECT COALESCE(AVG(nota), 0) FROM tb_avaliacao) as media_avaliacoes
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function verificarAutenticacao() {
        session_start();
        if (!isset($_SESSION['admin_id'])) {
            header('HTTP/1.1 401 Unauthorized');
            header('Content-Type: application/json');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Acesso negado']);
            exit;
        }
    }
}

// Processar requisições
if ($_SERVER['REQUEST_METHOD'] === 'GET' || $_SERVER['REQUEST_METHOD'] === 'POST' || $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $controller = new AdminController();
    $acao = $_GET['acao'] ?? 'index';

    switch ($acao) {
        case 'dashboard':
            $controller->index();
            break;
        case 'listar_tipos_servico':
            $controller->listarTiposServico();
            break;
        case 'criar_tipo_servico':
            $controller->criarTipoServico();
            break;
        case 'buscar_tipo_servico':
            $controller->buscarTipoServico();
            break;
        case 'atualizar_tipo_servico':
            $controller->atualizarTipoServico();
            break;
        case 'deletar_tipo_servico':
            $controller->deletarTipoServico();
            break;
        case 'listar_status_solicitacao':
            $controller->listarStatusSolicitacao();
            break;
        case 'buscar_status_solicitacao':
            $controller->buscarStatusSolicitacao();
            break;
        case 'criar_status_solicitacao':
            $controller->criarStatusSolicitacao();
            break;
        case 'atualizar_status_solicitacao':
            $controller->atualizarStatusSolicitacao();
            break;
        case 'deletar_status_solicitacao':
            $controller->deletarStatusSolicitacao();
            break;
        case 'estatisticas_status_solicitacao':
            $controller->estatisticasStatusSolicitacao();
            break;
        case 'listar_usuarios':
            $controller->listarUsuarios();
            break;
        case 'buscar_usuario':
            $controller->buscarUsuario();
            break;
        case 'atualizar_usuario':
            $controller->atualizarUsuario();
            break;
        case 'toggle_status_usuario':
            $controller->toggleStatusUsuario();
            break;
        case 'deletar_usuario':
            $controller->deletarUsuario();
            break;
        case 'estatisticas_usuarios':
            $controller->estatisticasUsuarios();
            break;
        case 'relatorio':
            $controller->relatorio();
            break;
        case 'relatorio_avancado':
            $controller->relatorioAvancado();
            break;
        case 'exportar_relatorio':
            $controller->exportarRelatorio();
            break;
        default:
            $controller->index();
    }
}
?>
