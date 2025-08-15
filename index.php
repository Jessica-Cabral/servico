<?php
// index.php - Versão Corrigida

// A SESSÃO DEVE SER A PRIMEIRA COISA NO SCRIPT.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Carrega todas as classes automaticamente
require_once __DIR__ . '/autoload.php';

// A classe Router, que é o mapa do site
class Router
{
    private $routes = [];

    public function __construct()
    {
        $this->routes = [
            // Públicas
            '' => 'HomeController@index',
            'home' => 'HomeController@index',
            'login' => 'LoginController@index',
            'login/autenticar' => 'LoginController@autenticar',
            'logout' => 'LoginController@logout',
            'sobre' => 'SobreController@index',
            'contato' => 'ContatoController@index',
            'termos' => 'TermosController@index',
            // Cliente
            'cadusuario' => 'CadUsuarioController@index',
            'cadusuario/criar' => 'UsuarioController@criar',
            'cadusuario/editar' => 'CadUsuarioController@editar',
            'cadusuario/listar' => 'CadUsuarioController@listar',
            'cadusuario/excluir' => 'CadUsuarioController@excluir',
            'cliente/dashboard' => 'ClienteController@dashboard',
            'cliente/avaliar-servico'     => 'ClienteController@avaliarServico',
            'cliente/gerenciar-proposta'  => 'ClienteController@gerenciarProposta',
            'cliente/meusservicos' => 'ClienteController@meusServicos', // Roteamento adicionado
            'cliente/novoservico' => 'ClienteController@novoServico',
            // Prestador
            'prestador/dashboard' => 'PrestadorController@dashboard',
            'prestador/oportunidades' => 'PrestadorController@oportunidades',
            'prestador/minhaspropostas' => 'PrestadorController@minhasPropostas',
            'prestador/minhas-propostas' => 'PrestadorController@minhasPropostas',
            'prestador/detalhes-servico' => 'PrestadorController@detalhesServico',
            'prestador/editar-proposta' => 'PrestadorController@editarProposta',
            'prestador/cancelar-proposta' => 'PrestadorController@cancelarProposta',
            'prestador/iniciar-trabalho' => 'PrestadorController@iniciarTrabalho',
        // Adicionada a rota para NovoServico
        ];
    }

    public function route($url)
    {
        // normaliza e remove barras extras
        $url = strtolower(trim($url, '/'));
        // colapsa múltiplas barras internas em uma só (ex: prestador//minhas-propostas.php -> prestador/minhas-propostas.php)
        $url = preg_replace('#/{2,}#', '/', $url);

        if (array_key_exists($url, $this->routes)) {
            list($controller, $method) = explode('@', $this->routes[$url]);

            $controllerFile = __DIR__ . "/controllers/{$controller}.php";
            if (file_exists($controllerFile)) {
                require_once $controllerFile;

                if (class_exists($controller)) {
                    $controllerInstance = new $controller();
                    if (method_exists($controllerInstance, $method)) {
                        call_user_func([$controllerInstance, $method]);
                        return;
                    }
                }
            }
        }

        // tenta sem extensão .php (legacy)
        $urlNoExt = preg_replace('/\.php$/', '', $url);
        if ($urlNoExt !== $url && array_key_exists($urlNoExt, $this->routes)) {
            list($controller, $method) = explode('@', $this->routes[$urlNoExt]);
            $controllerFile = __DIR__ . "/controllers/{$controller}.php";
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                if (class_exists($controller)) {
                    $controllerInstance = new $controller();
                    if (method_exists($controllerInstance, $method)) {
                        call_user_func([$controllerInstance, $method]);
                        return;
                    }
                }
            }
        }

        // Fallback: tenta mapear nomes legacy (ex.: arquivos .php ou nomes com hífen)
        $legacyMap = [
            // arquivos/views que podem ser chamados diretamente por links antigos
            'prestador/oportunidades.php'     => 'prestador/oportunidades',
            'prestador/oportunidades'         => 'prestador/oportunidades',
            'prestador/minhas-propostas.php'  => 'prestador/minhaspropostas',
            'prestador/minhas-propostas'      => 'prestador/minhaspropostas',
            'prestador/minhaspropostas'       => 'prestador/minhaspropostas',
            'detalhes-oportunidade.php'       => 'prestador/detalhes-servico',
            'detalhes-oportunidade'           => 'prestador/detalhes-servico',
            'prestador/detalhes-oportunidade' => 'prestador/detalhes-servico',
        ];

        if (isset($legacyMap[$url])) {
            $mapped = $legacyMap[$url];
            if (array_key_exists($mapped, $this->routes)) {
                list($controller, $method) = explode('@', $this->routes[$mapped]);
                $controllerFile = __DIR__ . "/controllers/{$controller}.php";
                if (file_exists($controllerFile)) {
                    require_once $controllerFile;
                    if (class_exists($controller)) {
                        $controllerInstance = new $controller();
                        if (method_exists($controllerInstance, $method)) {
                            // repopula $_GET se necessário (analógico)
                            // chama método mapeado
                            call_user_func([$controllerInstance, $method]);
                            return;
                        }
                    }
                }
            }
        }

        header("HTTP/1.0 404 Not Found");
        header('Location: /servico/home');
        exit();
    }
}

$url = $_GET['url'] ?? '';
$router = new Router();
$router->route($url);
