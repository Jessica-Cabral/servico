<?php
//incluir classe conexao
include_once 'Conexao.class.php';

//classe Editora
class Servico extends Conexao
{
    //atributos
    private $id_servico;
    private $descricao_servico;
    private $id_tipo_servico;

    //getters e setters

    public function getIdServico()
    {
        return $this->id_servico;
    }

    public function setIdServico($id_servico)
    {
        $this->id_servico = $id_servico;

    }

    public function getDescricaoServico()
    {
        return $this->descricao_servico;
    }

    public function setDescricaoServico($descricao_servico)
    {
        $this->descricao_servico = $descricao_servico;
    }
    public function getIdTipoServico()
    {
        return $this->id_tipo_servico;
    }

    public function setIdTipoServico($id_tipo_servico)
    {
        $this->id_tipo_servico = $id_tipo_servico;

    }

    //método cadastro de serviço
    public function cadastrarServico($descricao_servico)
    {
        //setar os atributos
        $this->setDescricaoServico($descricao_servico);

        //montar query
        $sql = "INSERT INTO tb_servico (id_servico, descricao_servico) VALUES (NULL, :descricao_servico)";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':descricao_servico', $this->getDescricaoServico(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            //print "Inserido";
            return true;

        } catch (PDOException $e) {
            //print "Erro ao inserir";
            return false;
        }
    }

    // //metodo consultar servico por tipo

    public function consultarServicoPorTipo($id_tipo_servico) 
    {
        $this->setIdTipoServico($id_tipo_servico);

        $sql = "SELECT * FROM tb_servico WHERE id_tipo_servico = :id_tipo_servico";

        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_tipo_servico', $this->getIdTipoServico(), PDO::PARAM_INT);
            $query->execute();
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }
    //metodo consultar servico
    public function consultarServico($descricao_servico)
    {
        //setar os atributos
        $this->setDescricaoServico($descricao_servico);

        //montar query
        $sql = "SELECT * FROM tb_servico where true ";

        //vericar se o nome é nulo
        if ($this->getDescricaoServico() != null) {
            $sql .= " and descricao_servico like :descricao_servico";
        }

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            if ($this->getDescricaoServico() != null) {
                $this->setDescricaoServico("%" . $descricao_servico . "%");
                $query->bindValue(':descricao_servico', $this->getDescricaoServico(), PDO::PARAM_STR);
            }
            //excutar a query
            $query->execute();
            //retorna o resultado
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            return $resultado;

        } catch (PDOException $e) {
            //print "Erro ao consultar";
            return false;
        }

    }

    //método alterar serviço
    public function AlterarServico($id_servico, $descricao_servico)
    {
        //setar os atributos
        $this->setIdServico($id_servico);
        $this->setDescricaoServico($descricao_servico);

        //montar query
        $sql = "UPDATE tb_servico SET descricao_servico = :descricao_servico WHERE id_servico = :id_servico";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':id_servico', $this->getIdServico(), PDO::PARAM_INT);
            $query->bindValue(':descricao_genero', $this->getDescricaoServico(), PDO::PARAM_STR);
            //excutar a query
            $query->execute();
            //retorna o resultado
            //print "Alterado";
            return true;

        } catch (PDOException $e) {
            //print "Erro ao alterar";
            return false;
        }
    }

    //método excluir Genero
    public function excluirServico($id_servico)
    {
        //setar os atributos
        $this->setIdServico($id_servico);

        //montar query
        $sql = "DELETE FROM tb_servico WHERE id_servico = :id_servico";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':id_servico', $this->getIdServico(), PDO::PARAM_INT);
            //excutar a query
            $query->execute();
            //retorna o resultado
            //print "Excluido";
            return true;

        } catch (PDOException $e) {
            // print "Erro ao excluir: " . $e->getMessage();
            return false;
        }
    }

}
