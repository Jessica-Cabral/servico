<?php

// A classe de controle a ligação entre os models e as classes
class ControllerLogin
{
    //atributos

    // Método para retornar o menu (implemente conforme sua lógica)
    public function menu()
    {
        // Exemplo de retorno de menu, ajuste conforme necessário
        return [];
    }

    // Método para exibir mensagens na tela
    public function mostrarMensagem($mensagem)
    {
        echo "<div style='color: red; font-weight: bold; margin: 10px 0;'>$mensagem</div>";
    }

    //login

    //redirecionar página
    public function redirecionar($pagina)
    {
        //iniciar sessao
        session_start();
        $menu = $this->menu();
        //incluir a view
       // print 'view/' . $pagina . '.php';
       // die();
        include_once 'view/' . $pagina . '.php';
    }
    

    public function abrirHomepage()
    {
              //incluir a view
        include_once 'view/HomeServico.php';
    }


    
    public function recuperarSenha()
    {
              //incluir a view
        include_once 'view/Recuperar.php';
    }

    public function abrirformpessoa()
    {
              //incluir a view
        include_once 'view/CadPessoa.php';
    }
  
      //validar loginAdmin
    public function validarAdmin($email, $senha)
    {
        //instanciar a classe Usuário
        $objUsuario = new Usuario();
        //validar usuario
        if ($objUsuario->validarAdmin($email, $senha) == true) {
            //iniciar sessao
            session_start();
            //iniciar variaves de sessao
            $_SESSION['email'] = $email;
            $_SESSION['perfil'] = 'admin';      
            //menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/Principal.php';
        } else {
            include_once 'LoginAdmin.php';
            $this->mostrarMensagem("Login ou senha inválidos!");
        }
    }

    //validar Pessoa - login
    public function validar($email, $senha)
    {
        $errorMessage = '';

        if (empty($email) || empty($senha)) {
            $errorMessage = "Por favor, digite seu email e senha.";
            include_once "Login.php";
            return;
        }

        require_once __DIR__ . '/../models/Auth.class.php';
        $objLogin = new Auth();

        if ($objLogin->validarPessoa($email, $senha) == true) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['email'] = $email;
            $_SESSION['perfil'] = $objLogin->perfilPessoa($email);

            $id_pessoa = $objLogin->consultarIdPessoa($email);
            $_SESSION['dadosPessoa'] = $objLogin->consultarDadosPessoa($id_pessoa);

            $perfil = $_SESSION['perfil'];
            if ($perfil == 'cliente' || $perfil == 'clientePrestador') {
                $_SESSION['user_type'] = 'cliente';
                $_SESSION['cliente_id'] = $id_pessoa;
            }
            if ($perfil == 'prestador' || $perfil == 'clientePrestador') {
                $_SESSION['user_type'] = 'prestador';
                $_SESSION['prestador_id'] = $id_pessoa;
            }

            // Corrija o redirecionamento para a raiz se necessário
            header("Location: index.php");
            exit();
        } else {
            $errorMessage = "Login ou senha inválidos!";
            include_once "Login.php";
        }
    }

    public function validarSessao()
    {
        //verificar variaveis de sessao
        if (!isset($_SESSION['email']) and !isset($_SESSION['ativo'])) {
            //acesso negado
            header("location: Login.php");
        }
    }
}