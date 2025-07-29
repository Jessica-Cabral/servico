<?php

require_once __DIR__ . '/../models/Cliente.php';
require_once __DIR__ . '/../models/Servico.php';

class ClienteController {
    private $cliente;
    private $servico;

    public function __construct() {
        $this->cliente = new Cliente();
        $this->servico = new Servico();
    }

    public function dashboard() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['cliente_id'])) {
            header('Location: ../Login.php');
            exit();
        }

        $cliente_id = $_SESSION['cliente_id'];
        $stats = $this->cliente->getStats($cliente_id);
        $servicos_recentes = $this->servico->getRecentes($cliente_id, 5);
        $grafico_dados = $this->servico->getGraficoDados($cliente_id);

        include __DIR__ . '/../views/cliente/dashboard.php';
    }

    public function novoServico() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['cliente_id'])) {
            header('Location: ../Login.php');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dados = [
                'cliente_id' => $_SESSION['cliente_id'],
                'titulo' => trim($_POST['titulo'] ?? ''),
                'descricao' => trim($_POST['descricao'] ?? ''),
                'categoria' => trim($_POST['categoria'] ?? ''),
                'endereco' => trim($_POST['endereco'] ?? ''),
                'data_desejada' => trim($_POST['data_desejada'] ?? '')
            ];

            if ($this->servico->criar($dados)) {
                $_SESSION['sucesso'] = 'Serviço criado com sucesso!';
                header('Location: dashboard.php');
                exit();
            }
        }

        include __DIR__ . '/../views/cliente/novo-servico.php';
    }

    public function meusServicos() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['cliente_id'])) {
            header('Location: ../Login.php');
            exit();
        }

        $cliente_id = $_SESSION['cliente_id'];
        $servicos = $this->servico->getByCliente($cliente_id);

        include __DIR__ . '/../views/cliente/meus-servicos.php';
    }

    public function atualizarPerfil() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['cliente_id'])) {
            header('Location: ../Login.php');
            exit();
        }

        $cliente_id = $_SESSION['cliente_id'];
        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $telefone = trim($_POST['telefone'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $senha_confirmar = $_POST['senha_confirmar'] ?? '';
        $foto_perfil = null;

        // Upload da foto de perfil
        if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $novo_nome = 'perfil_' . $cliente_id . '_' . time() . '.' . $ext;
            $destino = __DIR__ . '/../uploads/' . $novo_nome;
            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $destino)) {
                $foto_perfil = $novo_nome;
            }
        }

        // Validação simples
        if (empty($nome) || empty($email)) {
            $_SESSION['erro'] = 'Nome e e-mail são obrigatórios.';
            header('Location: ../view/cliente/clienteDashboard.php');
            exit();
        }
        if (!empty($senha) && $senha !== $senha_confirmar) {
            $_SESSION['erro'] = 'As senhas não coincidem.';
            header('Location: ../view/cliente/clienteDashboard.php');
            exit();
        }

        // Monta array de dados para atualizar
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone
        ];
        if (!empty($senha)) {
            $dados['senha'] = password_hash($senha, PASSWORD_DEFAULT);
        }
        if ($foto_perfil) {
            $dados['foto_perfil'] = $foto_perfil;
            $_SESSION['cliente_foto'] = 'uploads/' . $foto_perfil;
        }

        // Atualiza no banco
        $atualizado = $this->cliente->atualizar($cliente_id, $dados);

        if ($atualizado) {
            $_SESSION['cliente_nome'] = $nome;
            $_SESSION['cliente_email'] = $email;
            $_SESSION['cliente_telefone'] = $telefone;
            $_SESSION['sucesso'] = 'Perfil atualizado com sucesso!';
        } else {
            $_SESSION['erro'] = 'Erro ao atualizar perfil.';
        }
        header('Location: ../view/cliente/clienteDashboard.php');
        exit();
    }
}
?>
