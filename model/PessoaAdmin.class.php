<?php
// Incluir classe de conexão
include_once 'Conexao.class.php';

class PessoaAdmin extends Conexao
{
    // atributos
    private $id_pessoa;
    private $nome;
    private $cpf;
    private $data_nascimento;
    private $telefone;
    private $email;
    private $status;    
    private $cliente;
    private $prestador;
    private $data_cadastro;
    private $data_inativacao;

    // getters e setters
    public function getIdPessoa()
    {
        return $this->id_pessoa;
    }

    public function setIdPessoa($id_pessoa)
    {
        $this->id_pessoa = $id_pessoa;
    }

    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }

    public function getCpf()
    {
        return $this->cpf;
    }

    public function setCpf($cpf)
    {
        $this->cpf = $cpf;
    }

    public function getDataNascimento()
    {
        return $this->data_nascimento;
    }

    public function setDataNascimento($data_nascimento)
    {
        $this->data_nascimento = $data_nascimento;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function setCliente($cliente)
    {
        $this->cliente = $cliente;
    }

    public function getPrestador()
    {
        return $this->prestador;
    }

    public function setPrestador($prestador)
    {
        $this->prestador = $prestador;
    }

    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
    }

    public function getDataInativacao()
    {
        return $this->data_inativacao;
    }

    public function setDataInativacao($data_inativacao)
    {
        $this->data_inativacao = $data_inativacao;
    }

    // métodos da classe PessoaAdmin
    public function consultarIdPessoaAdmin($email)
    {
        $this->setEmail($email);
        $sql = "SELECT id_pessoa FROM tb_pessoa WHERE email = :email";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->execute();
            $dadosPessoa = $query->fetchAll(PDO::FETCH_OBJ);
            foreach ($dadosPessoa as $key => $valor) {
                $id_pessoa = $valor->id_pessoa;
            }
            return $id_pessoa;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultarPessoaAdmin()
    {
        $sql = "SELECT * FROM tb_pessoa ORDER BY cliente";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->execute();
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            return $resultado;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function alterarPessoaAdmin($id_pessoa,$nome,$telefone,$email,$status, $cliente, $prestador)
    {
        $this->setIdPessoa($id_pessoa);
        $this->setNome($nome);
        $this->setTelefone($telefone);
        $this->setEmail($email);
        $this->setStatus($status);
        $this->setCliente($cliente);
        $this->setPrestador($prestador);

        $sql = "UPDATE tb_pessoa SET nome = :nome, telefone = :telefone, email = :email,status = :status, cliente = :cliente, prestador = :prestador WHERE id_pessoa = :id_pessoa";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
            $query->bindValue(':nome', $this->getNome(), PDO::PARAM_STR);
            $query->bindValue(':telefone', $this->getTelefone(), PDO::PARAM_STR);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
            $query->bindValue(':cliente', $this->getCliente(), PDO::PARAM_INT);
            $query->bindValue(':prestador', $this->getPrestador(), PDO::PARAM_INT);
            $query->execute();
            return true;

        } catch (PDOException $e) {
            print "Erro ao alterar" . $e->getMessage();
            die();
            return false;
        }
    }

    public function excluirPessoaAdmin($id_pessoa)
    {
        $this->setIdPessoa($id_pessoa);
        $this->setStatus(0); // Definir status como inativo
        $sql = "UPDATE tb_pessoa SET data_inativacao = NOW(), status = :status WHERE id_pessoa = :id_pessoa";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
            $query->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function consultarDadosPessoaAdmin($id_pessoa)
    {
        $this->setIdPessoa($id_pessoa);
        $sql = "SELECT * FROM tb_pessoa WHERE id_pessoa = :id_pessoa";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
            $query->execute();
            $dadosPessoa = $query->fetchAll(PDO::FETCH_OBJ);
            return $dadosPessoa;

        } catch (PDOException $e) {
            return false;
        }
    }

    public function cadastrarPessoaAdmin($cpf, $email, $data_nascimento, $telefone, $cliente, $prestador)
    {
        $this->setEmail($email);
        $this->setCpf($cpf);
        $this->setDataNascimento($data_nascimento);
        $this->setTelefone($telefone);
        $this->setCliente($cliente);
        $this->setPrestador($prestador);

        $sql = "INSERT INTO tb_pessoa
            (id_pessoa, email, cpf, data_nascimento, data_cadastro, telefone, cliente, prestador)
            VALUES (NULL, :email, :cpf, :data_nascimento, now(), :telefone, :cliente, :prestador)";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->bindValue(':cpf', $this->getCpf(), PDO::PARAM_STR);
            $query->bindValue(':data_nascimento', $this->getDataNascimento(), PDO::PARAM_STR);
            $query->bindValue(':telefone', $this->getTelefone(), PDO::PARAM_STR);
            $query->bindValue(':cliente', $this->getCliente(), PDO::PARAM_INT);
            $query->bindValue(':prestador', $this->getPrestador(), PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            print "Erro ao inserir: " . $e->getMessage();
            return false;
        }
    }
}
