<?php
// controllers/StatusSolicitacaoController.class.php
// Este controlador lida com as requisições da área de administração para gerenciar status.

// O autoload.php já deve ter sido incluído pelo roteador principal (index.php)
// e a sessão já deve estar ativa.

// Padroniza o cabeçalho para todas as respostas da API
header('Content-Type: application/json');

class StatusSolicitacaoController
{
    private $db;
    private $statusSolicitacao;

    public function __construct()
    {
        // A lógica de sessão e autenticação é do roteador principal.
        // Se a requisição chegou aqui, o usuário já está autenticado como admin.

        // A classe Database e StatusSolicitacao serão carregadas pelo autoloader.
        $database = new Database();
        $this->db = $database->getConnection();
        $this->statusSolicitacao = new StatusSolicitacao($this->db);
    }

    public function listar()
    {
        try {
            $page = $_GET['page'] ?? 1;
            $perPage = $_GET['per_page'] ?? 10;
            $nome = $_GET['nome'] ?? '';

            $resultado = $this->statusSolicitacao->listar($page, $perPage, $nome);

            echo json_encode([
                'sucesso' => true,
                'dados' => $resultado
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function buscar()
    {
        try {
            $id = $_GET['id'];
            $resultado = $this->statusSolicitacao->buscarPorId($id);

            if ($resultado) {
                echo json_encode([
                    'sucesso' => true,
                    'dados' => $resultado
                ]);
            } else {
                http_response_code(404);
                echo json_encode([
                    'sucesso' => false,
                    'mensagem' => 'Status não encontrado'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function criar()
    {
        try {
            $dados = [
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao'] ?? null,
                'cor' => $_POST['cor'] ?? '#007bff'
            ];

            $resultado = $this->statusSolicitacao->criar($dados);

            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Status criado com sucesso!',
                'dados' => $resultado
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function atualizar()
    {
        try {
            $id = $_GET['id'];
            $dados = [
                'nome' => $_POST['nome'],
                'descricao' => $_POST['descricao'] ?? null,
                'cor' => $_POST['cor'] ?? '#007bff'
            ];

            $this->statusSolicitacao->atualizar($id, $dados);

            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Status atualizado com sucesso!'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function deletar()
    {
        try {
            $id = $_GET['id'];
            $this->statusSolicitacao->deletar($id);

            echo json_encode([
                'sucesso' => true,
                'mensagem' => 'Status deletado com sucesso!'
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }

    public function estatisticas()
    {
        try {
            $stats = $this->statusSolicitacao->obterEstatisticas();

            echo json_encode([
                'sucesso' => true,
                'dados' => $stats
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'sucesso' => false,
                'mensagem' => $e->getMessage()
            ]);
        }
    }
}

