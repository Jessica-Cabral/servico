<?php
// Incluir classe de conexão
include_once 'Conexao.class.php';
include_once 'TipoServico.class.php';
include_once 'ServicoClass.php'
// Classe pessoaServico
class Pessoa_servico extends Conexao
{
    // Atributos
    private $id_pessoa;
    private $id_servico;
    private $portfolio;
    private $avaliacao_media;
    private $area_atendimento;

    // Getters e Setters
    // Método para inserir um prestador
    public function inserirPessoaServico($id_pessoa, $id_servico, $portfolio, $avaliacao_media, $area_atendimento)
    {
        // Setar os atributos
        $this->setIdPessoa($id_pessoa);
        $this->setIdServico($id_servico);
        $this->setPortfolio($portfolio);
        $this->setAvaliacaoMedia($avaliacao_media);
        $this->setAreaAtendimento($area_atendimento);

        // Montar query
        $sql = "INSERT INTO tb_pessoa_servivo (id_pessoa, id_servico, portfolio,avaliaçao_media, area_atendimento) 
                VALUES (NULL, :id_usuario, :id_servico, :portfolio, :avaliacao_media, :area_atendimento)";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
            $query->bindValue(':id_servico', $this->getIdServico(), PDO::PARAM_INT);
            $query->bindValue(':portfolio', $this->getPortfolio(), PDO::PARAM_STR);
            $query->bindValue(':avaliacao_media', $this->getAvaliacaoMedia(), PDO::PARAM_STR);
            $query->bindValue(':area_atendimento', $this->getAreaAtendimento(), PDO::PARAM_STR);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para consultar pessoa servico (prestadores)
    public function consultarPessoaServico($id_pessoa = null, $id_servico = null)
    {
        // Montar query
        $sql = "SELECT * FROM tb_pessoa_servico WHERE true ";

        if ($id_pessoa != null) {
            $sql .= " AND id_pessoa = :id_pessoa ";
            $this->setIdPessoa($id_pessoa);
        }
        if ($id_servico != null) {
            $sql .= " AND id_servico = :id_servico ";
            $this->setIdServico($id_Servico);
        }

        $sql .= " ORDER BY id_pessoa_servico";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            if ($id_pessoa != null) {
                $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
            }
            if ($id_servico != null) {
                $query->bindValue(':id_servico', $this->getIdServico(), PDO::PARAM_INT);
            }
            $query->execute();
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para alterar pessoa servico (prestador)
    public function alterarPessoaServico($id_pessoa, $id_servico, $portfolio,$avaliacao_media, $area_atendimento)
    {
        // Setar os atributos
        $this->setIdPessoaServico($id_pessoa);
        $this->setIdServico($id_Servico);
        $this->setIdPortfolio($id_portfolio);
        $this->setAvaliacaoMedia($avaliacao_medi);
        $this->setAreaAtendimento($area_atendimento);

        // Montar query
        $sql = "UPDATE tb_pessoa_servico 
                SET id_pessoa = :id_pessoa, 
                    id_servico = :id_servico,
                    portfolio = :porfolio,
                    avaliacao_media = :avaliacao_media, 
                    area_atendimento = :area_atendimento 
                WHERE id_pessoa_servico = :id_pessoa_servico";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
            $query->bindValue(':id_servico', $this->getIdServico(), PDO::PARAM_INT);
            $query->bindValue(':portfolio', $this->getPortfolio(), PDO::PARAM_STR);
            $query->bindValue(':avaliacao_media', $this->getAvaliacaoMedia(), PDO::PARAM_STR);
            $query->bindValue(':area_atendimento', $this->getAreaAtendimento(), PDO::PARAM_STR);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para excluir pessoa_servico (prestador)
    public function excluirPessoaServico($id_pessoa, $id_servico,)
    {
        // Setar os atributos
        $this->setIdPessoa($id_pessoa);
        $this->setIdSevico($id_servico);

        // Montar query
        $sql = "DELETE FROM tb_pessoa_servico WHERE id_pessoa_servico = :id_pessoa_servico";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_pessoa_servico', $this->getIdPessoaServico(), PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

?>
