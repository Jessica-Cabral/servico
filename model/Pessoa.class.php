<?php
// Incluir classe de conexão
include_once 'Conexao.class.php';

class Pessoa extends Conexao
{
    // atributos
    private $id_pessoa;
    private $nome;
    private $cpf;
    private $email;
    private $data_nascimento;
    private $data_cadastro;
    private $telefone;
    private $senha;
    private $foto_perfil;
    private $cliente;
    private $prestador;
    private $data_inativacao;
    private $status;

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

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getDataNascimento()
    {
        return $this->data_nascimento;
    }

    public function setDataNascimento($data_nascimento)
    {
        $this->data_nascimento = $data_nascimento;
    }

    public function getDataCadastro()
    {
        return $this->data_cadastro;
    }

    public function setDataCadastro($data_cadastro)
    {
        $this->data_cadastro = $data_cadastro;
    }

    public function getTelefone()
    {
        return $this->telefone;
    }

    public function setTelefone($telefone)
    {
        $this->telefone = $telefone;
    }
    public function getSenha()
    {
        return $this->senha;
    }

    public function setSenha($senha)
    {
        $this->senha = $senha;
    }

    public function getFotoPerfil()
    {
        return $this->foto_perfil;
    }

    public function setFotoPerfil($foto_perfil)
    {
        $this->foto_perfil = $foto_perfil;
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

    public function getDataInativacao()
    {
        return $this->data_inativacao;
    }

    public function setDataInativacao($data_inativacao)
    {
        $this->data_inativacao = $data_inativacao;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        $this->status = $status;
    }

    // métodos da classe Pessoa
    //Consultar pessoa pelo email e retornar ID - formulario consulta
    public function consultarIdPessoa($email)
    {
        // Setar os atributos
        $this->setEmail($email);

        // Montar query
        $sql = "SELECT id_pessoa FROM tb_pessoa WHERE email = :email";

        // Executa a query
        try {
            // Conectar com o banco
            $bd = $this->conectar();
            // Preparar o SQL
            $query = $bd->prepare($sql);
            // Blindagem dos dados
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);

            // Executar a query
            $query->execute();
            // Retorna o resultado
            $dadosPessoa = $query->fetchAll(PDO::FETCH_OBJ);
            //verificar o resultado
            foreach ($dadosPessoa as $key => $valor) {
                $id_pessoa = $valor->id_pessoa;
            }
            return $id_pessoa;

        } catch (PDOException $e) {
            // print "Erro ao consultar";
            return false;
        }
    }

       //Consultar pessoa pelo email e retornar ID - formulario consulta
       public function consultarDadosPessoa($id_pessoa)
       {
           // Setar os atributos
           $this->setIdPessoa($id_pessoa);
   
           // Montar query
           $sql = "SELECT * FROM tb_pessoa WHERE id_pessoa = :id_pessoa";
   
           // Executa a query
           try {
               // Conectar com o banco
               $bd = $this->conectar();
               // Preparar o SQL
               $query = $bd->prepare($sql);
               // Blindagem dos dados
               $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
   
               // Executar a query
               $query->execute();
               // Retorna o resultado
               $dadosPessoa = $query->fetchAll(PDO::FETCH_OBJ);
               return $dadosPessoa;
   
           } catch (PDOException $e) {
               // print "Erro ao consultar";
               return false;
           }
       }
       
    public function cadastrarPessoa($nome, $cpf, $email, $data_nascimento, $telefone, $senha, $cliente, $prestador)
    {

        // Setar os atributos
        $this->setNome($nome);
        $this->setEmail($email);
        $this->setCpf($cpf);
        $this->setDataNascimento($data_nascimento);
        $this->setTelefone($telefone);
        $this->setSenha($senha);
        $this->setCliente($cliente);
        $this->setPrestador($prestador);

        // Montar query
        $sql = "INSERT INTO tb_pessoa
            (id_pessoa, nome, email, cpf, data_nascimento, data_cadastro, telefone, foto_perfil, senha, cliente, prestador)
            VALUES (NULL, :nome, :email, :cpf, :data_nascimento, now(), :telefone, '', :senha, :cliente, :prestador)";

        // Executa a query
        try {
            // Conectar com o banco
            $bd = $this->conectar();
            // Preparar o sql
            $query = $bd->prepare($sql);
            // Blindagem dos dados
            $query->bindValue(':nome', $this->getNome(), PDO::PARAM_STR);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->bindValue(':cpf', $this->getCpf(), PDO::PARAM_STR);
            $query->bindValue(':data_nascimento', $this->getDataNascimento(), PDO::PARAM_STR);
            $query->bindValue(':telefone', $this->getTelefone(), PDO::PARAM_STR);
            $query->bindValue(':senha', md5($this->getSenha()), PDO::PARAM_STR);
            $query->bindValue(':cliente', $this->getCliente(), PDO::PARAM_INT);
            $query->bindValue(':prestador', $this->getPrestador(), PDO::PARAM_INT);

            // Executar a query
            $query->execute();
            // Retorna o resultado
            return true;
        } catch (PDOException $e) {
            print "Erro ao inserir: " . $e->getMessage();
            return false;
        }
    }
    public function inativarPessoa($id_pessoa)
    {
        // Example implementation: Update the database to set the status as inactive
        $sql = "UPDATE pessoas SET status = 'inativo' WHERE id_pessoa = :id_pessoa";
        $bd = $this->conectar();
        $stmt = $bd->prepare($sql);
        $stmt->bindParam(':id_pessoa', $id_pessoa, PDO::PARAM_INT);
        return $stmt->execute();

    }
    public function alterarPessoa($id_pessoa,  $telefone, $foto_perfil, $cliente, $prestador)
    {
        //setar os atributos
        $this->setIdPessoa($id_pessoa);
        $this->setTelefone($telefone);
        $this->setFotoPerfil($foto_perfil);
        $this->setCliente($cliente);
        $this->setPrestador($prestador);

        //montar query
        $sql = "UPDATE tb_pessoa SET telefone = :telefone, foto_perfil = :foto_perfil, cliente = :cliente, prestador = :prestador  WHERE id_pessoa = :id_pessoa";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
            $query->bindValue(':telefone', $this->getTelefone(), PDO::PARAM_STR);
            $query->bindValue(':foto_perfil', $this->getFotoPerfil(), PDO::PARAM_STR);
            $query->bindValue(':cliente', $this->getCliente(), PDO::PARAM_INT);
            $query->bindValue(':prestador', $this->getPrestador(), PDO::PARAM_INT);

            //excutar a query
            $query->execute();
            //retorna o resultado
            return true;

        } catch (PDOException $e) {
            print "Erro ao alterar". $e->getMessage();
            die();
            return false;
        }
    }

    
    public function consultarPessoa($nome)
    {
        // Setar os atributos
        $this->setNome($nome);

        // Montar query
        $sql = "SELECT * FROM tb_pessoa WHERE true";

        // Verificar se o nome não é nulo
        if ($this->getNome() != null) {
            $sql .= " AND nome LIKE :nome";
        }

        $sql .= " ORDER BY nome";

        // Executa a query
        try {
            // Conectar com o banco
            $bd = $this->conectar();
            // Preparar o SQL
            $query = $bd->prepare($sql);
            // Blindagem dos dados
            if ($this->getNome() != null) {
                $this->setNome("%" . $nome . "%");
                $query->bindValue(':nome', $this->getNome(), PDO::PARAM_STR);
            }
            // Executar a query
            $query->execute();
            // Retorna o resultado
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            return $resultado;

        } catch (PDOException $e) {
            // print "Erro ao consultar";
            return false;
        }
    }

    public function excluirPessoa($id_pessoa)
    {
        //setar os atributos
        $this->setIdPessoa($id_pessoa);
        $this->setStatus(0); // Definir status como inativo
        //montar query
        $sql = "UPDATE tb_pessoa SET data_inativacao = NOW(), status = :status WHERE id_pessoa = :id_pessoa";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
            $query->bindValue(':status', $this->getStatus(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            return true;
        } catch (PDOException $e) {
            // print "Erro ao excluir: " . $e->getMessage();
            return false;
        }
    }

    //metodo validarlogin
    public function validarPessoa($email, $senha)
    {
        $this->setEmail($email);
        $this->setSenha($senha);

        $sql = "SELECT COUNT(*) AS quantidade FROM tb_pessoa WHERE email = :email AND senha = :senha";

        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->bindValue(':senha', md5($this->getSenha()), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            //verificar o resultado
            foreach ($resultado as $key => $valor) {
                $quantidade = $valor->quantidade;
            }
            //testar quantidade
            if ($quantidade == 1) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            //print "Erro ao consultar";
            return false;
        }
    }

    //metodo validarEmail
    public function validarEmail($email)
    {
        //setar os dados
        $this->setEmail($email);

        //sql
        $sql = "SELECT count(*) as quantidade FROM tb_pessoa WHERE email= :email";

        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            //verificar o resultado
            foreach ($resultado as $key => $valor) {
                $quantidade = $valor->quantidade;
            }
            //testar quantidade
            if ($quantidade == 1) {
                return true;
            } else {
                return false;
            }

        } catch (PDOException $e) {
            //print "Erro ao consultar";
            return false;
        }
    }

// Removed duplicate perfilUsuario method definition to fix syntax error

    public function perfilPessoa($email)
    {
        //setar os dados
        $this->setEmail($email);

        //montar query
        $sql = "SELECT cliente,prestador FROM tb_pessoa WHERE email= :email";

        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            //verificar o resultado
            foreach ($resultado as $key => $valor) {
                $cliente = $valor->cliente;
                $prestador = $valor->prestador;
            }
            if ($cliente == 1 && $prestador == 0) {
                $perfil = "cliente";
            } elseif ($prestador == 1 && $cliente == 0) {
                $perfil = "prestador";
            } elseif ($cliente == 1 && $prestador == 1) {
                $perfil = "clientePrestador";
            } 

            return $perfil;

        } catch (PDOException $e) {
            //print "Erro ao consultar";
            return false;
        }
    }

    public function alterarSenha($email, $senha)
    {
        //setar os dados
        $this->setEmail($email);
        $this->setSenha($senha);

        //montar query
        $sql = "update tb_usuario set senha= :senha where email= :email";

        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->bindValue(':senha', $this->getSenha(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            return true;
        } catch (PDOException $e) {
            //print "Erro ao consultar";
            return false;
        }
    }
}
