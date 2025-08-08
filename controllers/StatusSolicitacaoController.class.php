<?php
require_once '../config/database.php';
require_once '../models/StatusSolicitacao.class.php';

class StatusSolicitacaoController {
    private $db;
    private $statusSolicitacao;

    public function __construct() {
        session_start();
        $this->verificarAutenticacao();
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->statusSolicitacao = new StatusSolicitacao($this->db);
    }

    public function listar() {
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

    public function buscar() {
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

    public function criar() {
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

    public function atualizar() {
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

    public function deletar() {
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

    public function estatisticas() {
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

    private function verificarAutenticacao() {
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
    $controller = new StatusSolicitacaoController();
    $acao = $_GET['acao'] ?? 'listar';

    switch ($acao) {
        case 'listar':
            $controller->listar();
            break;
        case 'buscar':
            $controller->buscar();
            break;
        case 'criar':
            $controller->criar();
            break;
        case 'atualizar':
            $controller->atualizar();
            break;
        case 'deletar':
            $controller->deletar();
            break;
        case 'estatisticas':
            $controller->estatisticas();
            break;
        default:
            $controller->listar();
    }
}
?>
