<?php
//definir o cabecalho como arquivo json
header('Content-Type: application/json; charset=utf-8');
//nao mostrar erros
error_reporting(E_ALL & ~E_NOTICE & E_WARNING);
// Define um token estático para exemplo
define('API_TOKEN', '781e5e245d69b566979b86e28d23f2c7');

// Função para verificar se o token enviado é válido
function verificarToken($headers) {
    if (!isset($headers['Authorization'])) {
        return false;
    }

    // O formato esperado é: "Authorization: Bearer TOKEN"
    $authHeader = $headers['Authorization'];
    if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
        $token = $matches[1];
        return $token === API_TOKEN;
    }

    return false;
}

// Captura os headers da requisição
$headers = getallheaders();

// Verifica o token
if (!verificarToken($headers)) {
    http_response_code(401);
    print json_encode(['erro' => 'Token inválido ou ausente.']);
    exit;
}
//AUTOLOAD
include_once 'autoload.php';
//verificar se o autor foi passado como parametro
$method = $_SERVER['REQUEST_METHOD']; //vai capturar qual metodo estou fazendo a requisicao
//verificar se o autor foi passado como parametro
$input = json_decode(file_get_contents('php://input'), true);
//var_dump($input); //vai mostrar o conteudo do arquivo json
switch ($method) {
    //inserir
    case 'GET':
        //Consultar
        try {
            //Instaanciar a classe TipoServico
            $objTipoServico = new TipoServico();
            //consultar 
            $tipo_servico = $objTipoServico->consultarTipoServico($input['descricao_tipo_servico']);
            //Gera o JSON
            print json_encode($tipo_servico);

        } catch (PDOException $e) {

            print json_encode(['error' => $e->getMessage()]);
        }

        break;
    case 'POST':
        //verificar se a descrição do tipo de serviço foi passada como parâmetro
        if (isset($input['descricao_tipo_servico'])) {
            //a descrição foi passada como parâmetro
            try {
                //instanciar a classe TipoServico
                $objTipoServico = new TipoServico();
                //invocar o método inserirTipoServico
                $objTipoServico->cadastrarTipoServico($input['descricao_tipo_servico']);
                //gerar o retorno em JSON
                print json_encode(['sucesso' => 'true']);
            } catch (PDOException $e) {
                //erro ao inserir o tipo de serviço
                print json_encode(['error' => $e->getMessage()]);
            }
        } else {
            //a descrição não foi passada como parâmetro
            print json_encode(['error' => 'A descrição do tipo de serviço é obrigatória!']);
        }
        break;
    //alterar
    case 'PUT':
        //verificar se a descrição e o ID do tipo de serviço foram passados como parâmetros
        if (isset($input['descricao_tipo_servico']) and isset($input['id_tipo_servico'])) {
            //os parâmetros foram passados
            try {
                //instanciar a classe TipoServico
                $objTipoServico = new TipoServico();
                //invocar o método alterarTipoServico
                $objTipoServico->AlterarTipoServico($input['id_tipo_servico'], $input['descricao_tipo_servico']);
                //gerar o retorno em JSON
                print json_encode(['sucesso' => 'true']);
            } catch (PDOException $e) {
                //erro ao alterar o tipo de serviço
                print json_encode(['error' => $e->getMessage()]);
            }
        } else {
            //os parâmetros não foram passados
            print json_encode(['error' => 'A descrição e o ID do tipo de serviço são obrigatórios!']);
        }
        break;
    //deletar
    case 'DELETE':
        //verificar se o ID do tipo de serviço foi passado como parâmetro
        if (isset($input['id_tipo_servico'])) {
            //o ID foi passado como parâmetro
            try {
                //instanciar a classe TipoServico
                $objTipoServico = new TipoServico();
                //invocar o método excluirTipoServico
                $objTipoServico->excluirDescricaoTipoServico($input['id_tipo_servico']);
                //gerar o retorno em JSON
                print json_encode(['sucesso' => 'true']);
            } catch (PDOException $e) {
                //erro ao excluir o tipo de serviço
                print json_encode(['error' => $e->getMessage()]);
            }
        } else {
            //o ID não foi passado como parâmetro
            print json_encode(['error' => 'O ID do tipo de serviço é obrigatório!']);
        }
        break;
    //default
    default:
        //método não permitido
        print "Método não permitido";
        break;
    }