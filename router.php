<?php
require_once __DIR__ . '/controllers/ControllerLogin.class.php';
require_once __DIR__ . '/controllers/ClienteController.class.php';

$url = explode('?', $_SERVER['REQUEST_URI']);
$pagina = isset($url[1]) ? $url[1] : null;

if ($pagina) {
    $objController = new ControllerLogin();
    $objController->redirecionar($pagina);
}

// ROTAS DE AÇÃO
if (isset($_POST['Login'])) {
    $objController = new ControllerLogin();
    $email = htmlspecialchars($_POST['email']);
    $senha = htmlspecialchars($_POST['senha']);
    $objController->validar($email, $senha);
}

if (isset($_GET['pagina']) && $_GET['pagina'] === 'dashboard') {
    $controller = new ClienteController();
    $controller->dashboard();
    exit();
}

if (isset($_GET['pagina']) && $_GET['pagina'] === 'avaliar-servico' && isset($_GET['id'])) {
    $controller = new ClienteController();
    $controller->avaliarServico($_GET['id']);
    exit();
}

isset($_POST['excluir_pessoa']) ||
    isset($_POST['alterar_pessoa_admin']) ||
    isset($_POST['consultar_pessoa_admin']) ||
    isset($_POST['excluir_pessoa_admin']) ||
    isset($_POST['alterar_prestador_admin']) ||
    isset($_POST['consultar_prestador_admin']) ||
    isset($_POST['excluir_prestador_admin']);

if (isset($_GET['pagina']) && $_GET['pagina'] === 'dashboard') {
    $controller = new ClienteController();
    $controller->dashboard();
    exit();
}

if (isset($_GET['pagina']) && $_GET['pagina'] === 'avaliar-servico' && isset($_GET['id'])) {
    $controller = new ClienteController();
    $controller->avaliarServico($_GET['id']);
    exit();
}

class Router {
    private $routes = [];

    public function __construct() {
        // Definir rotas disponíveis
        $this->routes = [
            'home' => 'HomeController@index',
            'login' => 'LoginController@index',
            'dashboard' => 'PrestadorController@dashboard',
            'perfil' => 'PrestadorController@perfil',
            'oportunidades' => 'PrestadorController@oportunidades',
            'propostas' => 'PrestadorController@propostas'
        ];
    }

    public function route($url) {
        // Remove barras extras e sanitiza a URL
        $url = trim($url, '/');
        $url = filter_var($url, FILTER_SANITIZE_URL);

        // Verifica se a rota existe
        if (array_key_exists($url, $this->routes)) {
            list($controller, $method) = explode('@', $this->routes[$url]);
            
            // Verifica se o arquivo do controller existe
            $controllerFile = __DIR__ . "/controllers/{$controller}.php";
            if (file_exists($controllerFile)) {
                require_once $controllerFile;
                
                // Instancia o controller e chama o método
                $controllerInstance = new $controller();
                if (method_exists($controllerInstance, $method)) {
                    $controllerInstance->$method();
                    return;
                }
            }
        }

        // Rota não encontrada
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . '/views/404.php';
    }
}
