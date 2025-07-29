<?php

//matar as variáveis de sessao
unset($_SESSION['email']);
unset($_SESSION['perfil']);
unset($_SESSION['resultado']);

//redirecionar para o login
header("location: LoginAdmin.php");
