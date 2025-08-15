<?php

// Inclua os Models necessários no topo do arquivo.
// Isso torna as dependências do controller explícitas.
require_once __DIR__ . '/../models/ClienteClass.php';
require_once __DIR__ . '/../models/ServicoClass.php';

/**
 * Controller responsável por todas as ações relacionadas ao cliente.
 * Gerencia o dashboard, serviços, perfil e autenticação.
 */
class ClienteController
{
    /** @var Cliente O modelo para interagir com os dados do cliente. */
    private $cliente;

    /** @var Servico O modelo para interagir com os dados de serviços. */
    private $servico;

    /**
     * O construtor inicializa os models e a sessão.
     * A injeção de dependência pode ser aplicada aqui para facilitar os testes.
     */
    public function __construct()
    {
        // Garante que a sessão seja iniciada.
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Instancia os models que o controller irá utilizar.
        $this->cliente = new Cliente();
        $this->servico = new Servico();
    }

    /**
     * Método privado para verificar se o cliente está autenticado.
     * Centraliza a lógica de verificação para evitar repetição de código.
     * @return int|null Retorna o ID do cliente se estiver logado, ou redireciona e encerra.
     */
    private function verificarAutenticacao()
    {
        if (!isset($_SESSION['cliente_id'])) {
            // Se não estiver logado, redireciona para a página de login.
            header('Location: /servico/login');
            exit();
        }
        return $_SESSION['cliente_id'];
    }

    /**
     * Exibe o dashboard do cliente com dados agregados.
     */
    public function dashboard()
    {
        $cliente_id = $this->verificarAutenticacao();

        try {
            // Prepara todos os dados necessários para a view.
            $dados_view = [
                'dados_cliente' => $this->cliente->getById($cliente_id),
                'stats' => $this->cliente->getStatus($cliente_id),
                'servicos_recentes' => $this->servico->getRecentes($cliente_id, 4),
                'grafico_dados' => $this->servico->getGraficoDados($cliente_id),
                'tipos_servico' => $this->servico->getTiposServico(),
                'enderecos' => $this->servico->getEnderecosPorCliente($cliente_id),
                'cliente_nome' => $_SESSION['cliente_nome'] ?? 'Cliente',
                'cliente_foto' => $_SESSION['cliente_foto'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($_SESSION['cliente_nome'] ?? 'C')
            ];

            // Inclui a view, passando os dados preparados.
            // A função extract pode ser usada dentro da view para transformar as chaves do array em variáveis.
            require_once __DIR__ . '/../view/cliente/dashboard.php';
        } catch (Exception $e) {
            error_log("Erro no dashboard do cliente #{$cliente_id}: " . $e->getMessage());
            $this->exibirPaginaErro("Erro ao Carregar o Dashboard", "Não foi possível carregar as informações do seu painel. Por favor, tente novamente mais tarde.");
        }
    }

    /**
     * Exibe a lista completa de serviços solicitados pelo cliente.
     */
    public function meusServicos()
    {
        $cliente_id = $this->verificarAutenticacao();

        // Paginação
        $page = $_GET['page'] ?? 1;
        $per_page = 12;

        $todos_servicos = $this->servico->getByCliente($cliente_id);
        $total_servicos = is_array($todos_servicos) ? count($todos_servicos) : 0;
        $servicos_paginados = is_array($todos_servicos) ? array_slice($todos_servicos, ($page - 1) * $per_page, $per_page) : [];

        $tipos_servico = $this->servico->getTiposServico();

        // Passa as variáveis para a view
        require_once __DIR__ . '/../view/cliente/MeusServicos.php';
    }

    /**
     * Processa o formulário de cadastro de um novo cliente.
     */
    public function criar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /servico/cadusuario');
            exit();
        }

        // --- VALIDAÇÃO DOS DADOS (ESSENCIAL) ---
        // Aqui deve entrar uma lógica robusta de validação.
        // Ex: verificar se o e-mail é válido, se a senha é forte, se o CPF é válido, etc.
        // Por simplicidade, estamos apenas limpando os dados.

        $dados = [
            'nome' => trim($_POST['nome'] ?? ''),
            'email' => filter_var(trim($_POST['email'] ?? ''), FILTER_VALIDATE_EMAIL),
            'senha' => password_hash($_POST['senha'] ?? '', PASSWORD_DEFAULT),
            'tipo' => 'cliente', // Define o tipo padrão
            'telefone' => preg_replace('/[^0-9]/', '', $_POST['telefone'] ?? ''),
            'cpf' => preg_replace('/[^0-9]/', '', $_POST['cpf'] ?? ''),
            'data_nascimento' => $_POST['data_nascimento'] ?? ''
        ];

        // Verifica se o e-mail era válido
        if ($dados['email'] === false) {
            $_SESSION['erros_cadastro'] = ["O formato do e-mail é inválido."];
            header('Location: /servico/cadusuario');
            exit();
        }

        // Tenta criar o cliente no banco de dados.
        if ($this->cliente->create($dados)) {
            $_SESSION['sucesso_cadastro'] = "Cadastro realizado com sucesso! Faça login para continuar.";
            header('Location: /servico/login');
        } else {
            $_SESSION['erros_cadastro'] = ["Ocorreu um erro ao tentar realizar o cadastro. O e-mail ou CPF já pode estar em uso."];
            header('Location: /servico/cadusuario');
        }
        exit();
    }

    /**
     * Exibe a página/formulário para a criação de um novo serviço.
     */
    public function novoServico()
    {
        $this->verificarAutenticacao();
        // Esta view provavelmente contém o modal que corrigimos anteriormente.
        require_once __DIR__ . '/../view/cliente/NovoServico.php';
    }

    /**
     * Método auxiliar para renderizar uma página de erro padronizada.
     * @param string $titulo O título da página de erro.
     * @param string $mensagem A mensagem a ser exibida para o usuário.
     */
    private function exibirPaginaErro($titulo, $mensagem)
    {
        // Em um projeto real, você teria um arquivo de view para erros.
        http_response_code(500); // Internal Server Error
        echo "<h1>{$titulo}</h1>";
        echo "<p>{$mensagem}</p>";
        echo "<p><a href='/servico/cliente/dashboard'>Voltar ao Início</a></p>";
    }
}
