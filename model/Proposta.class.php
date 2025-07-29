<?php
// Incluir classe de conexão
include_once 'Conexao.class.php';

// Classe SolicitacaoServico
class SolicitacaoServico extends Conexao
{
    // Atributos
    private $id_solicitacao;
    private $id_prestador;
    private $id_servico;
    private $descricao;
    private $id_endereco;
    private $data_abertura;
    private $data_fim;
    private $orcamento;
    private $imagens;
    private $id_status_solicitacao;

    // Getters e Setters
    public function getIdSolicitacao()
    {
        return $this->id_solicitacao;
    }

    public function setIdSolicitacao($id_solicitacao)
    {
        $this->id_solicitacao = $id_solicitacao;
    }

    public function getIdPrestador()
    {
        return $this->id_prestador;
    }

    public function setIdPrestador($id_prestador)
    {
        $this->id_prestador = $id_prestador;
    }

    public function getIdServico()
    {
        return $this->id_servico;
    }

    public function setIdServico($id_servico)
    {
        $this->id_servico = $id_servico;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function setDescricao($descricao)
    {
        $this->descricao = $descricao;
    }

    public function getIdEndereco()
    {
        return $this->id_endereco;
    }

    public function setIdEndereco($id_endereco)
    {
        $this->id_endereco = $id_endereco;
    }

    public function getDataAbertura()
    {
        return $this->data_abertura;
    }

    public function setDataAbertura($data_abertura)
    {
        $this->data_abertura = $data_abertura;
    }

    public function getDataFim()
    {
        return $this->data_fim;
    }

    public function setDataFim($data_fim)
    {
        $this->data_fim = $data_fim;
    }

    public function getOrcamento()
    {
        return $this->orcamento;
    }

    public function setOrcamento($orcamento)
    {
        $this->orcamento = $orcamento;
    }

    public function getImagens()
    {
        return $this->imagens;
    }

    public function setImagens($imagens)
    {
        $this->imagens = $imagens;
    }

    public function getIdStatusSolicitacao()
    {
        return $this->id_status_solicitacao;
    }

    public function setIdStatusSolicitacao($id_status_solicitacao)
    {
        $this->id_status_solicitacao = $id_status_solicitacao;
    }

    // Método para inserir uma solicitação de serviço
    public function inserirSolicitacao($id_prestador, $id_servico, $descricao, $id_endereco, $orcamento, $imagens, $id_status_solicitacao)
    {
        // Setar os atributos
        $this->setIdPrestador($id_prestador);
        $this->setIdServico($id_servico);
        $this->setDescricao($descricao);
        $this->setIdEndereco($id_endereco);
        $this->setDataAbertura(date('Y-m-d H:i:s')); // Data atual
        $this->setOrcamento($orcamento);
        $this->setImagens($imagens);
        $this->setIdStatusSolicitacao($id_status_solicitacao);

        // Montar query
        $sql = "INSERT INTO tb_solicitacao_servico (id_solicitacao, id_prestador, id_servico, descricao, id_endereco, data_abertura, orcamento, imagens, id_status_solicitacao) 
                VALUES (NULL, :id_prestador, :id_servico, :descricao, :id_endereco, :data_abertura, :orcamento, :imagens, :id_status_solicitacao)";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_prestador', $this->getIdPrestador(), PDO::PARAM_INT);
            $query->bindValue(':id_servico', $this->getIdServico(), PDO::PARAM_INT);
            $query->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $query->bindValue(':id_endereco', $this->getIdEndereco(), PDO::PARAM_INT);
            $query->bindValue(':data_abertura', $this->getDataAbertura(), PDO::PARAM_STR);
            $query->bindValue(':orcamento', $this->getOrcamento(), PDO::PARAM_STR);
            $query->bindValue(':imagens', $this->getImagens(), PDO::PARAM_STR);
            $query->bindValue(':id_status_solicitacao', $this->getIdStatusSolicitacao(), PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para consultar solicitações de serviço
    public function consultarSolicitacao($id_prestador = null, $id_status_solicitacao = null)
    {
        // Montar query
        $sql = "SELECT * FROM tb_solicitacao_servico WHERE true ";

        if ($id_prestador != null) {
            $sql .= " AND id_prestador = :id_prestador ";
            $this->setIdPrestador($id_prestador);
        }
        if ($id_status_solicitacao != null) {
            $sql .= " AND id_status_solicitacao = :id_status_solicitacao ";
            $this->setIdStatusSolicitacao($id_status_solicitacao);
        }

        $sql .= " ORDER BY data_abertura DESC";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            if ($id_prestador != null) {
                $query->bindValue(':id_prestador', $this->getIdPrestador(), PDO::PARAM_INT);
            }
            if ($id_status_solicitacao != null) {
                $query->bindValue(':id_status_solicitacao', $this->getIdStatusSolicitacao(), PDO::PARAM_INT);
            }
            $query->execute();
            $resultado = $query->fetchAll(PDO::FETCH_OBJ);
            return $resultado;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para alterar uma solicitação de serviço
    public function alterarSolicitacao($id_solicitacao, $id_prestador, $id_servico, $descricao, $id_endereco, $orcamento, $imagens, $id_status_solicitacao)
    {
        // Setar os atributos
        $this->setIdSolicitacao($id_solicitacao);
        $this->setIdPrestador($id_prestador);
        $this->setIdServico($id_servico);
        $this->setDescricao($descricao);
        $this->setIdEndereco($id_endereco);
        $this->setOrcamento($orcamento);
        $this->setImagens($imagens);
        $this->setIdStatusSolicitacao($id_status_solicitacao);

        // Montar query
        $sql = "UPDATE tb_solicitacao_servico 
                SET id_prestador = :id_prestador, id_servico = :id_servico, descricao = :descricao, 
                    id_endereco = :id_endereco, orcamento = :orcamento, imagens = :imagens, 
                    id_status_solicitacao = :id_status_solicitacao 
                WHERE id_solicitacao = :id_solicitacao";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_solicitacao', $this->getIdSolicitacao(), PDO::PARAM_INT);
            $query->bindValue(':id_prestador', $this->getIdPrestador(), PDO::PARAM_INT);
            $query->bindValue(':id_servico', $this->getIdServico(), PDO::PARAM_INT);
            $query->bindValue(':descricao', $this->getDescricao(), PDO::PARAM_STR);
            $query->bindValue(':id_endereco', $this->getIdEndereco(), PDO::PARAM_INT);
            $query->bindValue(':orcamento', $this->getOrcamento(), PDO::PARAM_STR);
            $query->bindValue(':imagens', $this->getImagens(), PDO::PARAM_STR);
            $query->bindValue(':id_status_solicitacao', $this->getIdStatusSolicitacao(), PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para finalizar uma solicitação de serviço
    public function finalizarSolicitacao($id_solicitacao)
    {
        // Setar os atributos
        $this->setIdSolicitacao($id_solicitacao);
        $this->setDataFim(date('Y-m-d H:i:s')); // Data atual

        // Montar query
        $sql = "UPDATE tb_solicitacao_servico 
                SET data_fim = :data_fim 
                WHERE id_solicitacao = :id_solicitacao";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_solicitacao', $this->getIdSolicitacao(), PDO::PARAM_INT);
            $query->bindValue(':data_fim', $this->getDataFim(), PDO::PARAM_STR);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    // Método para excluir uma solicitação de serviço
    public function excluirSolicitacao($id_solicitacao)
    {
        // Setar os atributos
        $this->setIdSolicitacao($id_solicitacao);

        // Montar query
        $sql = "DELETE FROM tb_solicitacao_servico WHERE id_solicitacao = :id_solicitacao";

        // Executar a query
        try {
            $bd = $this->conectar();
            $query = $bd->prepare($sql);
            $query->bindValue(':id_solicitacao', $this->getIdSolicitacao(), PDO::PARAM_INT);
            $query->execute();
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
}

