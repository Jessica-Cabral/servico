<?php
require_once '../config/database.php';
require_once '../models/Usuario.class.php';

class UsuarioController {
    private $db;
    private $usuario;

    public function __construct() {
        session_start();
        $this->verificarAutenticacao();
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->usuario = new Usuario($this->db);
    }

    public function listar() {
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

    public function buscar() {
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

    public function atualizar() {
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

    public function toggleStatus() {
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

    public function deletar() {
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
    $controller = new UsuarioController();
    $acao = $_GET['acao'] ?? 'listar';

    switch ($acao) {
        case 'listar':
            $controller->listar();
            break;
        case 'buscar':
            $controller->buscar();
            break;
        case 'atualizar':
            $controller->atualizar();
            break;
        case 'toggle_status':
            $controller->toggleStatus();
            break;
        case 'deletar':
            $controller->deletar();
            break;
        default:
            $controller->listar();
    }
}
?>
            if ($resultado) {
                return $this->responderJson(true, 'Usuário deletado com sucesso');
            } else {
                return $this->responderJson(false, 'Erro ao deletar usuário');
            }
        }
    }

    // Ativar/Desativar usuário
    public function toggleStatus($id)
    {
        $usuario_existe = $this->usuario->getById($id);
        if (!$usuario_existe) {
            return $this->responderJson(false, 'Usuário não encontrado');
        }

        $resultado = $this->usuario->toggleStatus($id);
        
        if ($resultado) {
            $novo_status = $usuario_existe['ativo'] ? 'desativado' : 'ativado';
            return $this->responderJson(true, "Usuário {$novo_status} com sucesso");
        } else {
            return $this->responderJson(false, 'Erro ao alterar status do usuário');
        }
    }

    // Validações
    private function validarDados($nome, $email, $senha, $cpf, $data_nascimento, $tipo)
    {
        $erros = [];

        if (empty($nome)) $erros[] = 'Nome é obrigatório';
        if (empty($email)) $erros[] = 'E-mail é obrigatório';
        if (empty($senha)) $erros[] = 'Senha é obrigatória';
        if (empty($cpf)) $erros[] = 'CPF é obrigatório';
        if (empty($data_nascimento)) $erros[] = 'Data de nascimento é obrigatória';
        if (empty($tipo)) $erros[] = 'Tipo de usuário é obrigatório';

        if (!empty($email) && !$this->usuario->validarEmail($email)) {
            $erros[] = 'E-mail inválido';
        }

        if (!empty($cpf) && !$this->usuario->validarCPF($cpf)) {
            $erros[] = 'CPF inválido';
        }

        if (!empty($data_nascimento) && !$this->usuario->validarIdade($data_nascimento)) {
            $erros[] = 'Idade mínima de 18 anos';
        }

        if (!empty($senha) && strlen($senha) < 6) {
            $erros[] = 'Senha deve ter no mínimo 6 caracteres';
        }

        if (!in_array($tipo, ['cliente', 'prestador', 'ambos'])) {
            $erros[] = 'Tipo de usuário inválido';
        }

        return $erros;
    }

    // Resposta JSON padronizada
    private function responderJson($sucesso, $mensagem, $dados = null)
    {
        header('Content-Type: application/json');
        $resposta = [
            'sucesso' => $sucesso,
            'mensagem' => $mensagem
        ];
        
        if ($dados !== null) {
            $resposta['dados'] = $dados;
        }
        
        echo json_encode($resposta);
        return $resposta;
    }
}

// Roteamento para API
if (isset($_GET['acao'])) {
    $controller = new UsuarioController();
    
    switch ($_GET['acao']) {
        case 'criar':
            $controller->criar();
            break;
        case 'listar':
            $controller->listar();
            break;
        case 'buscar':
            if (isset($_GET['id'])) {
                $controller->buscarPorId($_GET['id']);
            }
            break;
        case 'atualizar':
            if (isset($_GET['id'])) {
                $controller->atualizar($_GET['id']);
            }
            break;
        case 'deletar':
            if (isset($_GET['id'])) {
                $controller->deletar($_GET['id']);
            }
            break;
        case 'toggle_status':
            if (isset($_GET['id'])) {
                $controller->toggleStatus($_GET['id']);
            }
            break;
        default:
            header('HTTP/1.1 404 Not Found');
            echo json_encode(['sucesso' => false, 'mensagem' => 'Ação não encontrada']);
    }
}
?>
