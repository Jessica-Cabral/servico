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

    public function cadastrarPrestador($nome, $email, $senha) {
        $prestador = new Prestador();
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'tipo' => 'prestador'
        ];
        // Implemente um método create no model Prestador se não existir
        return $prestador->create($dados);
    }
}
?>
