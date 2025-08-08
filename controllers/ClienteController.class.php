<?php
// Controller responsável pelas ações do cliente (MVC)

require_once __DIR__ . '/../models/Cliente.class.php';
require_once __DIR__ . '/../models/Servico.class.php';

// ADICIONE O BLOCO PROCEDURAL PARA PROCESSAR O CADASTRO
if (isset($_GET['acao']) && $_GET['acao'] === 'cadastrar' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST recebido em ClienteController: " . print_r($_POST, true));
    
    // Extrair dados do POST
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $data_nascimento = $_POST['data_nascimento'] ?? '';
    $cpf = $_POST['cpf'] ?? '';
    $tipo = $_POST['tipo'] ?? 'cliente';

    // Validação backend
    function validarCPF($cpf) {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) return false;
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) $d += $cpf[$c] * (($t + 1) - $c);
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) return false;
        }
        return true;
    }
    
    function idadeMinima($data) {
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
    $controller = new ClienteController();
    $cliente = new Cliente();
    
    // Verificar se já existe email ou cpf cadastrado
    $jaEmail = $cliente->getByEmail($email);
    if ($jaEmail) {
        header('Location: ../CadUsuario.php?erro=E-mail já cadastrado.');
        exit();
    }
    $jaCpf = $cliente->getByCpf($cpf);
    if ($jaCpf) {
        header('Location: ../CadUsuario.php?erro=CPF já cadastrado.');
        exit();
    }

    // Tentar cadastrar
    if ($controller->cadastrarCliente($nome, $email, $senha, $telefone, $cpf, $data_nascimento, $tipo)) {
        header('Location: ../Login.php?cadastro=sucesso');
        exit();
    } else {
        header('Location: ../CadUsuario.php?erro=Erro ao inserir dados no banco.');
        exit();
    }
}

class ClienteController
{
    private $cliente;
    private $servico;

    public function __construct()
    {
        $this->cliente = new Cliente();

        $this->servico = new Servico();
    }

    public function dashboard()
    {
        // Exibe o dashboard do cliente
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['cliente_id'])) {
            header('Location: ../Login.php');
            exit();
        }

        $cliente_id = $_SESSION['cliente_id'];
        $cliente_nome = $_SESSION['cliente_nome'] ?? 'Cliente';
        $cliente_foto = $_SESSION['cliente_foto'] ?? null;

        $status = $this->cliente->getStatus($cliente_id);
        $servicos_recentes = $this->servico->getRecentes($cliente_id, 4);
        $grafico_dados = $this->servico->getGraficoDados($cliente_id);
        $dados_cliente = $this->cliente->getById($cliente_id);

        // Adicionais para evitar warning na view
        $cliente_email = $dados_cliente['email'] ?? '';
        $cliente_telefone = $dados_cliente['telefone'] ?? '';

        // Inclui a view, as variáveis acima estarão disponíveis nela
        include __DIR__ . '/../view/cliente/clienteDashboard.php';
    }

    public function novoServico()
    {
        // Exibe formulário para novo serviço e processa criação
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

    public function meusServicos()
    {
        // Lista serviços do cliente
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['cliente_id'])) {
            header('Location: ../Login.php');
            exit();
        }

        $cliente_id = $_SESSION['cliente_id'];
        $status_map = [
            1 => ['texto' => 'Aguardando Propostas', 'cor' => '#ffc107', 'curto' => 'Aguardando'],
            2 => ['texto' => 'Em Andamento', 'cor' => '#007bff', 'curto' => 'Andamento'],
            3 => ['texto' => 'Concluído', 'cor' => '#28a745', 'curto' => 'Concluído'],
            4 => ['texto' => 'Cancelado', 'cor' => '#dc3545', 'curto' => 'Cancelado']
        ];

        // Paginação e filtros
        $page = $_GET['page'] ?? 1;
        $per_page = 12;
        $servicos = $this->servico->getByCliente($cliente_id);

        include __DIR__ . '/../view/cliente/meus-servicos.php';
    }

    public function atualizarPerfil()
    {
        // Atualiza dados do perfil do cliente
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

    public function cadastrarCliente($nome, $email, $senha, $telefone = '', $cpf = '', $data_nascimento = '', $tipo = 'cliente')
    {
        // Cadastra novo cliente
        $cliente = new Cliente();
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'tipo' => $tipo,
            'telefone' => $telefone,
            'cpf' => $cpf,
            'data_nascimento' => $data_nascimento
        ];
        error_log("Dados a serem inseridos: " . print_r($dados, true));
        $result = $cliente->create($dados);
        if (!$result) {
            error_log("Erro ao cadastrar cliente (ClienteController): " . print_r($dados, true));
            // Usar o método público getConnection() ao invés de acessar $conn diretamente
            $conn = $cliente->getConnection();
            error_log("Erro PDO: " . print_r($conn->errorInfo(), true));
        }
        return $result;
    }

    public function avaliarServico($servico_id)
    {
        // Avalia um serviço prestado
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (empty($_SESSION['cliente_id'])) {
            header('Location: ../Login.php');
            exit();
        }

        require_once __DIR__ . '/../models/Avaliacao.class.php';

        $cliente_id = $_SESSION['cliente_id'];
        $avaliacao = new Avaliacao();

        // Verifica se já foi avaliado
        if ($avaliacao->jaAvaliou($servico_id, $cliente_id)) {
            echo '<div class="alert alert-info">Você já avaliou este serviço.</div>';
            exit();
        }

        // Processa avaliação
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nota = $_POST['nota'] ?? null;
            $comentario = $_POST['comentario'] ?? '';
            $dados = [
                'servico_id' => $servico_id,
                'cliente_id' => $cliente_id,
                'prestador_id' => $dados_servico['prestador_id'],
                'nota' => $nota,
                'comentario' => $comentario
            ];
            if ($avaliacao->criar($dados)) {
                echo '<div class="alert alert-success">Avaliação enviada com sucesso!</div>';
                exit();
            } else {
                echo '<div class="alert alert-danger">Erro ao enviar avaliação.</div>';
            }
        }

        // Inclui a view do formulário
        include __DIR__ . '/../view/cliente/avaliar-servico.php';
    }
	
	public function editarCliente($id, $nome, $email, $telefone) {
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone
        ];
        return $this->cliente->atualizar($id, $dados);
    }

    public function excluirCliente($id) {
        return $this->cliente->delete($id);
    }
}
?>
    if ($cliente->create($dados)) {
        header('Location: ../Login.php?cadastro=sucesso');
        exit();
    } else {
        header('Location: ../CadUsuario.php?erro=Erro ao inserir dados no banco.');
        exit();
    }
}
*/
?>
