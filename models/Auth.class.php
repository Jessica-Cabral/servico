<?php
// Incluir classe de conexão

require_once __DIR__ . '/../config/database.php';

class Auth
{
    // atributos
    private $id;
    private $email;
    private $senha;
    private $tipo;

    // Adicione este método para garantir que conectar() funcione
    protected $dbInstance = null;

    public function conectar()
    {
        if ($this->dbInstance === null) {
            $database = new Database();
            $this->dbInstance = $database->getConnection();
        }
        return $this->dbInstance;
    }

    // getters e setters
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

 
    //Consultar pessoa pelo email e retornar ID - formulario consulta
    public function consultarIdPessoa($email)
    {
        $this->setEmail($email);

        // Montar query
        $sql = "SELECT id FROM tb_pessoa WHERE email = :email";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->execute();
            $dadosPessoa = $query->fetchAll(PDO::FETCH_OBJ);

            // Corrija para acessar o campo correto (id)
            if (!empty($dadosPessoa)) {
                return $dadosPessoa[0]->id;
            }
            return false;
        } catch (PDOException $e) {
            return false;
        }
    }

       //Consultar pessoa pelo email e retornar ID - formulario consulta
       public function consultarDadosPessoa($id)
       {
           // Setar os atributos
           $this->setId($id);

           // Montar query
           $sql = "SELECT * FROM tb_pessoa WHERE id = :id";

           // Executa a query
           try {
               // Conectar com o banco
               $bd = $this->conectar();
               // Preparar o SQL
               $query = $bd->prepare($sql);
               // Blindagem dos dados
               $query->bindValue(':id', $this->getId(), PDO::PARAM_INT);
   
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
       
    
    
    

    //metodo validarlogin
    public function validarPessoa($email, $senha)
    {
        $this->setEmail($email);
        $this->setSenha($senha);

        // Agora valida usando password_verify (para hash $2y$...)
        $sql = "SELECT senha FROM tb_pessoa WHERE email = :email";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_OBJ);

            if ($resultado && password_verify($this->getSenha(), $resultado->senha)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
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
        $this->setEmail($email);

        // Use o campo 'tipo' para identificar o perfil
        $sql = "SELECT tipo FROM tb_pessoa WHERE email= :email";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->execute();
            $resultado = $query->fetch(PDO::FETCH_OBJ);

            if ($resultado) {
                // Retorna o valor do campo tipo: 'cliente', 'prestador' ou 'ambos'
                return $resultado->tipo;
            }
            return false;
        } catch (PDOException $e) {
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
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':email', $this->getEmail(), PDO::PARAM_STR);
            $query->bindValue(':senha', $this->getSenha(), PDO::PARAM_STR);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}


   
?>






