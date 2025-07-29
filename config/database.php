<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'bd_servico'; // Corrigido: nome do banco sem espaço e igual ao seu .sql
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8",
                $this->username,
                $this->password,
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch(PDOException $exception) {
            error_log("Erro de conexão com banco de dados: " . $exception->getMessage());
            throw new Exception("Erro de conexão com o banco de dados.");
        }
        return $this->conn;
    }
}
?>

