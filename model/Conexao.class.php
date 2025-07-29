<?php
//classe de conexao
class Conexao {
    //atributos
    private $host = "localhost";
    private $db_name = "bd_chamaservico";
    private $user = "root";
    private $pwd = "";
    private $link = null;

    //métodos
    public function conectar(){
        try{
            //se tudo  der certo
            $pdo = new PDO("mysql:host={$this->host};dbname={$this->db_name}","{$this->user}","{$this->pwd}");
            $this->link = $pdo;
            //echo "Conectado";
            return $this->link;

        }
        catch (PDOException $e) {
            //se der algum erro
           // echo "Não conectado". $e->getMessage();
            return false;
        }
        
    }
}

//testar a conexão - não deixa no código - apenas para teste
//$objConexao = new Conexao();
//var_dump("estou aqui");
//$objConexao->conectar();

?>
