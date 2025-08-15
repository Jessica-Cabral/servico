<?php


require_once __DIR__ . '/../config/database.php';

class AuthClass
{
    // Atributos da classe
    private $id;
    private $email;
    private $senha;
    private $tipo;

    // Propriedade para armazenar os dados da pessoa após o login
    private $dadosPessoa;

    // Propriedade para a conexão com o banco de dados
    private $conn;

    // Construtor para inicializar a conexão
    public function __construct()
    {
        $database = Database::getInstance();
        $this->conn = $database->getConnection();
    }

    // Getters e setters
    public function getId()
    {
        return $this->id;
    }
    public function setId($id)
    {
        $this->id = $id;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getSenha()
    {
        return $this->senha;
    }
    public function setSenha($senha)
    {
        $this->senha = $senha;
    }
    public function getTipo()
    {
        return $this->tipo;
    }
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
    }

    /**
     * Valida as credenciais de uma pessoa e retorna os dados completos se o login for bem-sucedido.
     */
    public function validarPessoa($email, $senha)
    {
        // Validação adicional para verificar se o email e a senha não estão vazios
        if (empty($email) || empty($senha)) {
            return false;
        }

        $sql = "SELECT id, nome, email, senha, tipo FROM tb_pessoa 
                WHERE email = :email AND ativo = 1 LIMIT 1";

        try {
            $query = $this->conn->prepare($sql);
            $query->bindValue(':email', $email, PDO::PARAM_STR);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_ASSOC);

            // Verifica se o resultado existe e se a senha está correta
            if ($resultado && password_verify($senha, $resultado['senha'])) {
                // Armazena os dados do usuário na propriedade da classe
                $this->dadosPessoa = $resultado;

                // Atualiza o timestamp do último acesso
                $this->atualizarUltimoAcesso($resultado['id']);

                return true;
            }
        } catch (PDOException $e) {
            error_log("Erro na validação do login: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Retorna os dados completos da pessoa logada.
     */
    public function getDadosPessoa()
    {
        return $this->dadosPessoa;
    }

    /**
     * Atualiza o timestamp do último acesso do usuário.
     */
    private function atualizarUltimoAcesso($id)
    {
        try {
            $query = "UPDATE tb_pessoa SET ultimo_acesso = NOW() WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
        } catch (Exception $e) {
            error_log("Erro ao atualizar último acesso: " . $e->getMessage());
        }
    }

    // Os métodos consultarIdPessoa e perfilPessoa são redundantes, pois os dados já estão em $this->dadosPessoa.
    // O método validarEmail já existe em Usuario.php e pode ser chamado a partir de lá.
    // O método alterarSenha precisa de uma lógica para garantir que seja para a tabela correta (tb_pessoa).
    // O método logout é responsabilidade do Controller, não do Model.
}
