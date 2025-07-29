<?php
//classe de controle
class ControllerAdmin
{
    //atributos

    //métodos

    //redirecionar pagina
    public function redirecionar($pagina)
    {
        //iniciar sessao
        session_start();
        //incluir menu
        $menu = $this->menu();
        //incluir a view
        require_once 'view/' . $pagina . '.php';
    }

    //validar login
    public function LoginAdmin($email, $senha)
    {
        //instanciar a classe Usuário
        $objUsuario = new Usuario();
        //validar usuario
        if ($objUsuario->validarAdmin($email, $senha) == true) {
            //iniciar sessao
            session_start();
            //iniciar variaves de sessao
            $_SESSION['email'] = $email;
            $_SESSION['perfil'] = $objUsuario->perfilUsuario($email);
            //menu
            $menu = $this->menu();
            //incluir a view
            include_once 'view/principal.php';
        } else {
            include_once 'LoginAdmin.php';
            $this->mostrarMensagem("Login ou senha inválidos!");
        }
    }

    public function validarSessao()
    {
        //verificar variaveis de sessao
        if (!isset($_SESSION['email']) and !isset($_SESSION['perfil'])) {
            //acesso negado
            header("location: LoginAdmin.php");
        }
    }
}