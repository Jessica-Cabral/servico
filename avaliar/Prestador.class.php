<?php
// Incluir classe de conexão
include_once 'Conexao.class.php';

// Classe Prestador
class Prestador extends Conexao
{
    // Atributos
    private $id_prestador;
    private $id_usuario;
    private $id_categoria;
    private $avaliacao_media;
    private $imagens_trabalhos;
    private $area_atendimento;

    // Getters e Setters
    public function getIdPrestador()
    {
        return $this->id_prestador;
    }

    public function setIdPrestador($id_prestador)
    {
        $this->id_prestador = $id_prestador;
    }

    public function getIdUsuario()
    {
        return $this->id_usuario;
    }

    public function setIdUsuario($id_usuario)
    {
        $this->id_usuario = $id_usuario;
    }

    public function getIdCategoria()
    {
        return $this->id_categoria;
    }

    public function setIdCategoria($id_categoria)
    {
        $this->id_categoria = $id_categoria;
    }

    public function getAvaliacaoMedia()
    {
        return $this->avaliacao_media;
    }

    public function setAvaliacaoMedia($avaliacao_media)
    {
        $this->avaliacao_media = $avaliacao_media;
    }

    public function getImagensTrabalhos()
    {
        return $this->imagens_trabalhos;
    }

    public function setImagensTrabalhos($imagens_trabalhos)
    {
        $this->imagens_trabalhos = $imagens_trabalhos;
    }

    public function getAreaAtendimento()
    {
        return $this->area_atendimento;
    }

    public function setAreaAtendimento($area_atendimento)
    {
        $this->area_atendimento = $area_atendimento;
    }

    // Método para inserir um prestador
    public function inserirPrestador($id_usuario, $id_categoria, $avaliacao_media, $imagens_trabalhos, $area_atendimento)
    {
        // Setar os atributos
        $this->setIdUsuario($id_usuario);
        $this->setIdCategoria($id_categoria);
        $this->setAvaliacaoMedia($avaliacao_media);
        $this->setImagensTrabalhos($imagens_trabalhos);
        $this->setAreaAtendimento($area_atendimento);

        // Montar query
        $sql = "INSERT INTO tb_prestador (id_prestador, id_usuario, id_categoria, avaliacao_media, imagens_trabalhos, area_atendimento) 
                VALUES (NULL, :id_usuario, :id_categoria, :avaliacao_media, :imagens_trabalhos, :area_atendimento)";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_usuario', $this->getIdUsuario(), PDO::PARAM_INT);
            $query->bindValue(':id_categoria', $this->getIdCategoria(), PDO::PARAM_INT);
            $query->bindValue(':avaliacao_media', $this->getAvaliacaoMedia(), PDO::PARAM_STR);
            $query->bindValue(':imagens_trabalhos', $this->getImagensTrabalhos(), PDO::PARAM_STR);
            $query->bindValue(':area_atendimento', $this->getAreaAtendimento(), PDO::PARAM_STR);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para consultar prestadores
    public function consultarPrestador($id_usuario = null, $id_categoria = null)
    {
        // Montar query
        $sql = "SELECT * FROM tb_prestador WHERE true ";

        if ($id_usuario != null) {
            $sql .= " AND id_usuario = :id_usuario ";
            $this->setIdUsuario($id_usuario);
        }
        if ($id_categoria != null) {
            $sql .= " AND id_categoria = :id_categoria ";
            $this->setIdCategoria($id_categoria);
        }

        $sql .= " ORDER BY id_prestador";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            if ($id_usuario != null) {
                $query->bindValue(':id_usuario', $this->getIdUsuario(), PDO::PARAM_INT);
            }
            if ($id_categoria != null) {
                $query->bindValue(':id_categoria', $this->getIdCategoria(), PDO::PARAM_INT);
            }
            $query->execute();
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para alterar um prestador
    public function alterarPrestador($id_prestador, $id_usuario, $id_categoria, $avaliacao_media, $imagens_trabalhos, $area_atendimento)
    {
        // Setar os atributos
        $this->setIdPrestador($id_prestador);
        $this->setIdUsuario($id_usuario);
        $this->setIdCategoria($id_categoria);
        $this->setAvaliacaoMedia($avaliacao_media);
        $this->setImagensTrabalhos($imagens_trabalhos);
        $this->setAreaAtendimento($area_atendimento);

        // Montar query
        $sql = "UPDATE tb_prestador 
                SET id_usuario = :id_usuario, id_categoria = :id_categoria, 
                    avaliacao_media = :avaliacao_media, imagens_trabalhos = :imagens_trabalhos, 
                    area_atendimento = :area_atendimento 
                WHERE id_prestador = :id_prestador";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_prestador', $this->getIdPrestador(), PDO::PARAM_INT);
            $query->bindValue(':id_usuario', $this->getIdUsuario(), PDO::PARAM_INT);
            $query->bindValue(':id_categoria', $this->getIdCategoria(), PDO::PARAM_INT);
            $query->bindValue(':avaliacao_media', $this->getAvaliacaoMedia(), PDO::PARAM_STR);
            $query->bindValue(':imagens_trabalhos', $this->getImagensTrabalhos(), PDO::PARAM_STR);
            $query->bindValue(':area_atendimento', $this->getAreaAtendimento(), PDO::PARAM_STR);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para excluir um prestador
    public function excluirPrestador($id_prestador)
    {
        // Setar os atributos
        $this->setIdPrestador($id_prestador);

        // Montar query
        $sql = "DELETE FROM tb_prestador WHERE id_prestador = :id_prestador";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_prestador', $this->getIdPrestador(), PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>
