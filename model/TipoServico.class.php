<?php
//incluir classe conexao
include_once 'Conexao.class.php';

//classe Editora
class TipoServico extends Conexao
{
    //atributos
    private $id_tipo_servico;
    private $descricao_tipo_servico;

    //getters e setters

    public function getIdTipoServico()
    {
        return $this->id_tipo_servico;
    }

    public function setIdTipoServico($id_tipo_servico)
    {
        $this->id_tipo_servico = $id_tipo_servico;

    }

    public function getDescricaoTipoServico()
    {
        return $this->descricao_tipo_servico;
    }

    public function setDescricaoTipoServico($descricao_tipo_servico)
    {
        $this->descricao_tipo_servico = $descricao_tipo_servico;

    }

    //método cadastro tipo serviço
    public function cadastrarTipoServico($descricao_tipo_servico)
    {
        //setar os atributos
        $this->setDescricaoTipoServico($descricao_tipo_servico);

        //montar query
        $sql = "INSERT INTO tb_tipo_servico (id_tipo_servico, descricao_tipo_servico) VALUES (NULL, :descricao_tipo_servico)";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':descricao_tipo_servico', $this->getDescricaoTipoServico(), PDO::PARAM_STR);
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

    //metodo consultar Genero
    public function consultarTipoServico($descricao_tipo_servico)
    {
        //setar os atributos
        $this->setDescricaoTipoServico($descricao_tipo_servico);

        //montar query
        $sql = "SELECT * FROM tb_tipo_servico where true ";

        //vericar se o nome é nulo
        if ($this->getDescricaoTipoServico() != null) {
            $sql .= " and descricao_tipo_servico like :descricao_tipo_servico";
        }

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            if ($this->getDescricaoTipoServico() != null) {
                $this->setDescricaoTipoServico("%" . $descricao_tipo_servico . "%");
                $query->bindValue(':descricao_tipo_servico', $this->getDescricaoTipoServico(), PDO::PARAM_STR);
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

    //método alterar tipo de serviço
    public function AlterarTipoServico($id_tipo_servico, $descricao_tipo_servico)
    {
        //setar os atributos
        $this->setIdTipoServico($id_tipo_servico);
        $this->setDescricaoTipoServico($descricao_tipo_servico);

        //montar query
        $sql = "UPDATE tb_tipo_servico SET descricao_tipo_servico = :descricao_tipo_servico WHERE id_tipo_servico = :id_tipo_servico";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':id_tipo_servico', $this->getIdTipoServico(), PDO::PARAM_INT);
            $query->bindValue(':descricao_genero', $this->getDescricaoTipoServico(), PDO::PARAM_STR);
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
    public function excluirTipoServico($id_tipo_servico)
    {
        //setar os atributos
        $this->setIdTipoServico($id_tipo_servico);

        //montar query
        $sql = "DELETE FROM tb_tipo_servico WHERE id_tipo_servico = :id_tipo_servico";

        //executa a query
        try {
            //conectar com o banco
            $bd = $this->conectar();
            //preparar o sql
            $query = $bd->prepare($sql);
            //blidagem dos dados
            $query->bindValue(':id_tipo_servico', $this->getIdTipoServico(), PDO::PARAM_INT);
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