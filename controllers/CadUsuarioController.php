<?php
require_once __DIR__ . '/../models/PessoaClass.php';
require_once __DIR__ . '/PrestadorController.php';
require_once __DIR__ . '/ClienteController.php';

class CadUsuarioController
{
    public function index()
    {
        require __DIR__ . '/../view/public/CadUsuario.php';
    }

    public function criar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /servico/cadusuario');
            exit();
        }

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'senha' => $_POST['senha'] ?? '',
            'cpf' => preg_replace('/\D/', '', $_POST['cpf'] ?? ''),
            'telefone' => preg_replace('/\D/', '', $_POST['telefone'] ?? ''),
            'dt_nascimento' => $_POST['data_nascimento'] ?? '',
            'tipo' => $_POST['tipo'] ?? ''
        ];

        $pessoaModel = new Pessoa();

        // Verifica se já existe email/cpf
        if ($pessoaModel->getByEmail($dados['email'])) {
            header('Location: /servico/cadusuario?erro=E-mail já cadastrado.');
            exit();
        }

        $id = $pessoaModel->create($dados);
        if ($id) {
            $_SESSION['usuario_id'] = $id;
            $_SESSION['usuario_nome'] = $dados['nome'];
            $_SESSION['usuario_tipo'] = $dados['tipo'];
            // Redireciona para dashboard correto
            if ($dados['tipo'] === 'cliente') {
                header('Location: /servico/cliente/dashboard');
            } else {
                header('Location: /servico/prestador/dashboard');
            }
            exit();
        } else {
            header('Location: /servico/cadusuario?erro=Erro ao cadastrar usuário.');
            exit();
        }
    }

    public function editar()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /servico/cadusuario');
            exit();
        }
        $pessoaModel = new Pessoa();
        $usuario = $pessoaModel->getById($id);
        require __DIR__ . '/../view/public/EditarUsuario.php';
    }

    public function atualizar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /servico/cadusuario');
            exit();
        }
        $id = $_POST['id'] ?? null;
        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'telefone' => preg_replace('/\D/', '', $_POST['telefone'] ?? ''),
            'dt_nascimento' => $_POST['data_nascimento'] ?? '',
            'tipo' => $_POST['tipo'] ?? ''
        ];
        $pessoaModel = new Pessoa();
        if ($pessoaModel->update($id, $dados)) {
            header('Location: /servico/cadusuario?sucesso=Atualizado com sucesso!');
        } else {
            header('Location: /servico/cadusuario?erro=Erro ao atualizar usuário.');
        }
        exit();
    }

    public function excluir()
    {
        $id = $_GET['id'] ?? null;
        $pessoaModel = new Pessoa();
        if ($id && $pessoaModel->delete($id)) {
            header('Location: /servico/cadusuario?sucesso=Usuário excluído!');
        } else {
            header('Location: /servico/cadusuario?erro=Erro ao excluir usuário.');
        }
        exit();
    }

    public function listar()
    {
        $pessoaModel = new Pessoa();
        $usuarios = $pessoaModel->getAll();
        require __DIR__ . '/../view/public/ListarUsuarios.php';
    }
}
