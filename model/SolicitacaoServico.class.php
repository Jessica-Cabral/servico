<?php
// Incluir classe de conexão
include_once 'Conexao.class.php';

// Classe SolicitacaoServico
class SolicitacaoServico extends Conexao
{
    // Atributos
    private $id_solicitacao;
    private $descricao_solicitacao;
    private $cep;
    private $logradouro;
    private $numero;
    private $complemento;
    private $bairro;
    private $cidade;
    private $uf;
    private $img_solicitacao;
    private $id_status_solicitacao;
    private $data_abertura;
    private $data_fechamento;
    private $id_pessoa;
    private $id_servico;
    // Getters e Setters
   
    public function getIdSolicitacao()
    {
        return $this->id_solicitacao;
    }

    public function setIdSolicitacao($id_solicitacao)
    {
        $this->id_solicitacao = $id_solicitacao;

    }

    public function getDescricaoSolicitacao()
    {
        return $this->descricao_solicitacao;
    }

    public function setDescricaoSolicitacao($descricao_solicitacao)
    {
        $this->descricao_solicitacao = htmlspecialchars(strip_tags($descricao_solicitacao));
    }

    public function getCep()
    {
        return $this->cep;
    }

    public function setCep($cep)
    {
        $this->cep = htmlspecialchars(strip_tags(str_replace(['-', ' '], '', $cep)));
    }

    public function getLogradouro()
    {
        return $this->logradouro;
    }

    public function setLogradouro($logradouro)
    {
        $this->logradouro = $logradouro;

    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function setNumero($numero)
    {
        $this->numero = $numero;

    }

    public function getComplemento()
    {
        return $this->complemento;
    }

    public function setComplemento($complemento)
    {
        $this->complemento = $complemento;
    }

    public function getBairro()
    {
        return $this->bairro;
    }

    public function setBairro($bairro)
    {
        $this->bairro = $bairro;

    }

    public function getCidade()
    {
        return $this->cidade;
    }

    public function setCidade($cidade)
    {
        $this->cidade = $cidade;

    }

    public function getUf()
    {
        return $this->uf;
    }

    public function setUf($uf)
    {
        $this->uf = $uf;

    }
    public function getImgSolicitacao()
    {
        return $this->img_solicitacao;
    }

    public function setImgSolicitacao($img_solicitacao)
    {
        $this->img_solicitacao = $img_solicitacao;
    }

    public function getIdStatusSolicitacao()
    {
        return $this->id_status_solicitacao;
    }

    public function setIdStatusSolicitacao($id_status_solicitacao)
    {
        $this->id_status_solicitacao = $id_status_solicitacao;

    }

    public function getDataAbertura()
    {
        return $this->data_abertura;
    }

    public function setDataAbertura($data_abertura)
    {
        $this->data_abertura = $data_abertura;
    }

    public function getDataFechamento()
    {
        return $this->data_fechamento;
    }

    public function setDataFechamento($data_fechamento)
    {
        $this->data_fechamento = $data_fechamento;
    }

    public function getIdPessoa()
    {
        return $this->id_pessoa;
    }

    public function setIdPessoa($id_pessoa)
    {
        $this->id_pessoa = $id_pessoa;
    }

    public function getIdServico()
    {
        return $this->id_servico;
    }

    public function setIdServico($id_servico)
    {
        $this->id_servico = $id_servico;
    }


    // Método para inserir uma nova solicitação de serviço
  public function cadastrarSolicitacao() {
        // Setar os atributos
        $this->setDescricaoSolicitacao($_POST['descricao_solicitacao']);
        $this->setCep($_POST['cep']);
        $this->setLogradouro($_POST['logradouro']); 
        $this->setNumero($_POST['numero']);
        $this->setComplemento($_POST['complemento']);
        $this->setBairro($_POST['bairro']);
        $this->setCidade($_POST['cidade']);
        $this->setUf($_POST['uf']);
        $this->setIdPessoa($_POST['id_pessoa']);
        $this->setIdServico($_POST['id_servico']);
        // Processar imagens, se houver
        if (isset($_FILES['img_solicitacao'])) {
            $this->processarImagens($_FILES['img_solicitacao']);
        } else {
            $this->setImgSolicitacao(null); // Se não houver imagem, setar como null
        }


        // Montar query
        echo $sql = "INSERT INTO tb_solicitacao
                (descricao_solicitacao, cep, logradouro, numero, complemento, 
                 bairro, cidade, uf, img_solicitacao, id_status_solicitacao, 
                 data_abertura, id_pessoa, id_servico)
                VALUES 
                (:descricao_solicitacao, :cep, :logradouro, :numero, :complemento, 
                 :bairro, :cidade, :uf, :img_solicitacao, 1, 
                 NOW(), :id_pessoa, :id_servico)";

        try {
            // Conectar com o banco
            $bd = $this->conectar();
            // Preparar o sql
            $query = $bd->prepare($sql);
            // Blindagem dos dados
                        $query->bindValue(':descricao_solicitacao', $this->getDescricaoSolicitacao(), PDO::PARAM_STR);
            $query->bindValue(':cep', $this->getCep(), PDO::PARAM_STR);
            $query->bindValue(':logradouro', $this->getLogradouro(), PDO::PARAM_STR);
            $query->bindValue(':numero', $this->getNumero(), PDO::PARAM_STR);
            $query->bindValue(':complemento', $this->getComplemento(), PDO::PARAM_STR);
            $query->bindValue(':bairro', $this->getBairro(), PDO::PARAM_STR);
            $query->bindValue(':cidade', $this->getCidade(), PDO::PARAM_STR);
            $query->bindValue(':uf', $this->getUf(), PDO::PARAM_STR);
            $query->bindValue(':img_solicitacao', $this->getImgSolicitacao(), PDO::PARAM_STR);
            $query->bindValue(':id_status_solicitacao', $this->getIdStatusSolicitacao(), PDO::PARAM_INT);
            $query->bindValue(':id_pessoa', $this->getIdPessoa(), PDO::PARAM_INT);
            $query->bindValue(':id_servico', $this->getIdServico(), PDO::PARAM_INT);

            // Executar a query
            $query->execute();
            
            // Retornar resultado
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao cadastrar solicitação: " . $e->getMessage());
            return false;
        }
    }


    public function processarImagens($arquivos) {
        $nomesArquivos = [];
        
        if(!empty($arquivos['name'][0])) {
            $diretorio = 'uploads/solicitacoes/';
            
            if(!is_dir($diretorio)) {
                mkdir($diretorio, 0777, true);
            }

            foreach($arquivos['name'] as $key => $name) {
                $extensao = pathinfo($name, PATHINFO_EXTENSION);
                $nomeUnico = uniqid() . '.' . $extensao;
                $caminhoCompleto = $diretorio . $nomeUnico;

                if(move_uploaded_file($arquivos['tmp_name'][$key], $caminhoCompleto)) {
                    $nomesArquivos[] = $caminhoCompleto;
                }
            }
        }

        $this->img_solicitacao = !empty($nomesArquivos) ? implode(',', $nomesArquivos) : null;
    }


   
}
?>