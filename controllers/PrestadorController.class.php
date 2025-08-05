<?php
require_once __DIR__ . '/../models/Prestador.class.php';

class PrestadorController {
    private $prestadorModel;

    public function __construct() {
        $this->prestadorModel = new Prestador();
    }

    // Exibe o perfil do prestador pelo ID
    public function visualizarPerfil($id) {
        return $this->prestadorModel->getById($id);
    }

    // Atualiza dados do prestador (nome, email, telefone)
    public function atualizarPerfil($id, $dados) {
        // Espera $dados = ['nome' => ..., 'email' => ..., 'telefone' => ...]
        return $this->prestadorModel->update($id, $dados);
    }

    // Lista todos os prestadores
    public function listarTodos() {
        return $this->prestadorModel->getAll();
    }

    public function cadastrarPrestador($nome, $email, $senha, $telefone = '') {
        $prestador = new Prestador();
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'tipo' => 'prestador',
            'telefone' => $telefone
        ];
        return $prestador->create($dados);
    }
}

// Cadastro rápido via POST
if (isset($_GET['acao']) && $_GET['acao'] === 'cadastrar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $controller = new PrestadorController();
    if ($controller->cadastrarPrestador($nome, $email, $senha, $telefone)) {
        header('Location: ../Login.php?cadastro=sucesso');
        exit();
    } else {
        header('Location: ../CadUsuario.php?erro=Erro ao cadastrar prestador');
        exit();
    }
}
?>
