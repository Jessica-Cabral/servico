<?php
// controllers/UsuarioController.php

// Carrega o model genérico de Usuário
require_once __DIR__ . '/../models/UsuarioClass.php';

class UsuarioController
{
    private $model;

    public function __construct()
    {
        $this->model = new UsuarioClass();
    }

    // Criação de usuário (AJAX ou formulário)
    public function criar()
    {
        try {
            // Aceita tanto AJAX quanto POST tradicional
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->json(['sucesso' => false, 'mensagem' => 'Método inválido']);
                return;
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

            $result = $this->model->criar($dados);

            // DEBUG: Exibe mensagem detalhada no frontend se houver erro
            if (!$result['sucesso'] && !empty($result['mensagem'])) {
                echo "<div style='color:red;font-weight:bold'>DEBUG: {$result['mensagem']}</div>";
            }

            if ($this->isAjax()) {
                $this->json($result);
            } else {
                if ($result['sucesso']) {
                    // Redireciona para dashboard correto
                    if ($dados['tipo'] === 'cliente') {
                        header('Location: /servico/view/cliente/Dashboard.php');
                    } else {
                        header('Location: /servico/view/prestador/Dashboard.php');
                    }
                } else {
                    error_log("UsuarioController: Erro ao cadastrar: " . $result['mensagem']);
                    header('Location: /servico/cadusuario?erro=' . urlencode($result['mensagem']));
                }
                exit;
            }
        } catch (Exception $e) {
            error_log("UsuarioController: Exception: " . $e->getMessage());
            echo "<div style='color:red;font-weight:bold'>DEBUG: Exception: " . htmlspecialchars($e->getMessage()) . "</div>";
            $this->json(['sucesso' => false, 'mensagem' => 'Erro inesperado: ' . $e->getMessage()]);
        }
    }

    // Listagem de usuários
    public function listar()
    {
        try {
            $usuarios = $this->model->listar();
            if ($this->isAjax()) {
                $this->json(['sucesso' => true, 'usuarios' => $usuarios]);
            } else {
                require __DIR__ . '/../view/public/ListarUsuarios.php';
            }
        } catch (Exception $e) {
            error_log("UsuarioController: Exception listar: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao listar usuários: ' . $e->getMessage()]);
        }
    }

    // Atualização de usuário
    public function atualizar()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                $this->json(['sucesso' => false, 'mensagem' => 'Método inválido']);
                return;
            }
            $id = $_POST['id'] ?? null;
            $dados = [
                'nome' => trim($_POST['nome'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'telefone' => preg_replace('/\D/', '', $_POST['telefone'] ?? ''),
                'dt_nascimento' => $_POST['data_nascimento'] ?? '',
                'tipo' => $_POST['tipo'] ?? ''
            ];
            $ok = $this->model->atualizar($id, $dados);
            $this->json(['sucesso' => $ok]);
        } catch (Exception $e) {
            error_log("UsuarioController: Exception atualizar: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao atualizar usuário: ' . $e->getMessage()]);
        }
    }

    // Exclusão de usuário
    public function excluir()
    {
        try {
            $id = $_GET['id'] ?? null;
            $ok = $this->model->excluir($id);
            $this->json(['sucesso' => $ok]);
        } catch (Exception $e) {
            error_log("UsuarioController: Exception excluir: " . $e->getMessage());
            $this->json(['sucesso' => false, 'mensagem' => 'Erro ao excluir usuário: ' . $e->getMessage()]);
        }
    }

    // Utilitário para resposta JSON
    private function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // Detecta AJAX
    private function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
