<?php
require_once __DIR__ . '/../models/PrestadorClass.php';

class RecuperarController {
    public static function enviarCodigo($email) {
        $prestador = new Prestador();
        $dados = $prestador->getByEmail($email);
        if (!$dados) return ['sucesso' => false, 'msg' => 'E-mail não encontrado'];

        $codigo = rand(100000, 999999);
        session_start();
        $_SESSION['recuperar_email'] = $email;
        $_SESSION['recuperar_codigo'] = $codigo;

        $assunto = "Recuperação de senha - Chama Serviço";
        $mensagem = "Seu código de recuperação é: $codigo";
        $headers = "From: suporte@chamaservico.com\r\n";
        $enviado = mail($email, $assunto, $mensagem, $headers);

        if ($enviado) return ['sucesso' => true];
        else return ['sucesso' => false, 'msg' => 'Falha ao enviar e-mail'];
    }
}
$_SESSION['recuperar_email'] = $email;
$_SESSION['recuperar_codigo'] = $codigo;

// Envia e-mail (simples)
$assunto = "Recuperação de senha - Chama Serviço";
$mensagem = "Seu código de recuperação é: $codigo";
$headers = "From: suporte@chamaservico.com\r\n";
$enviado = mail($email, $assunto, $mensagem, $headers);

if ($enviado) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['sucesso' => false, 'msg' => 'Falha ao enviar e-mail']);
}
