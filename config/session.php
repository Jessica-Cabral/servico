<?php
class Session {
    public static function start() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function isLoggedIn() {
        self::start();
        return isset($_SESSION['cliente_id']);
    }

    public static function requireClientLogin() {
        if (!self::isLoggedIn()) {
            header('Location: /servico/login');
            exit();
        }
    }
}
?>
