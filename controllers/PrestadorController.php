<?php
require_once __DIR__ . '/../models/PrestadorClass.php';

// ADICIONE O BLOCO PROCEDURAL PARA PROCESSAR O CADASTRO
if (isset($_GET['acao']) && $_GET['acao'] === 'cadastrar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST recebido em PrestadorController: " . print_r($_POST, true));

    // Extrair dados do POST
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $tipo = $_POST['tipo'] ?? 'prestador';

    // Validação backend (igual ao cliente)
    function validarCPF($cpf)
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) $d += $cpf[$c] * (($t + 1) - $c);
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }

    function idadeMinima($data)
    {
        $nasc = new DateTime($data);
        $hoje = new DateTime();
        $idade = $hoje->diff($nasc)->y;
        return $idade >= 18;
    }

    // Validações
    if (!$nome || !$email || !$senha || !$cpf || !$data_nascimento || !$tipo) {
        header('Location: ../CadUsuario.php?erro=Preencha todos os campos obrigatórios.');
        exit();
    }
    if (!validarCPF($cpf)) {
        header('Location: ../CadUsuario.php?erro=CPF inválido.');
        exit();
    }
    if (!idadeMinima($data_nascimento)) {
        header('Location: ../CadUsuario.php?erro=Você deve ter pelo menos 18 anos.');
        exit();
    }

    // Instanciar controller e processar cadastro
    $controller = new PrestadorController();
    $prestador = new Prestador();

    // Verificar se já existe email cadastrado
    $jaEmail = $prestador->getByEmail($email);
    if ($jaEmail) {
        header('Location: ../CadUsuario.php?erro=E-mail já cadastrado.');
        exit();
    }

    // Tentar cadastrar
    if ($controller->cadastrarPrestador($nome, $email, $senha, $telefone, $cpf, $data_nascimento, $tipo)) {
        header('Location: ../Login.php?cadastro=sucesso');
        exit();
    } else {
        header('Location: ../CadUsuario.php?erro=Erro ao cadastrar prestador.');
        exit();
    }
}

class PrestadorController
{
    private $prestadorModel;

    public function __construct()
    {
        $this->prestadorModel = new Prestador();
    }

    // Exibe o perfil do prestador pelo ID
    public function visualizarPerfil($id)
    {
        return $this->prestadorModel->getById($id);
    }

    // Atualiza dados do prestador (nome, email, telefone)
    public function atualizarPerfil($id, $dados)
    {
        // Espera $dados = ['nome' => ..., 'email' => ..., 'telefone' => ...]
        return $this->prestadorModel->update($id, $dados);
    }

    // Lista todos os prestadores
    public function listarTodos()
    {
        return $this->prestadorModel->getAll();
    }

    public function cadastrarPrestador($nome, $email, $senha, $telefone = '', $cpf = '', $data_nascimento = '', $tipo = 'prestador')
    {
        $prestador = new Prestador();
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'tipo' => $tipo,
            'telefone' => $telefone,
            'cpf' => $cpf,
            'data_nascimento' => $data_nascimento
        ];
        return $prestador->create($dados);
    }
}
