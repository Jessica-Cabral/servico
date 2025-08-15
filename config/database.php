<?php

/**
 * Classe responsável pela conexão com o banco de dados.
 * Utiliza o padrão Singleton para garantir uma única instância de conexão.
 */
class Database
{
    private static $instance = null;
    private $conn;

    // Configurações do banco de dados
    private $host = 'localhost';
    private $db_name = 'bd_servicos';
    private $username = 'root';
    private $password = '';
    private $charset = 'utf8mb4';
    private $port = 3306;

    /**
     * Construtor privado para implementar o padrão Singleton
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * Método para estabelecer a conexão
     */
    private function connect()
    {
        try {
            $dsn = "mysql:host={$this->host};port={$this->port};dbname={$this->db_name};charset={$this->charset}";
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => false
            ];
            $this->conn = new PDO($dsn, $this->username, $this->password, $options);
        } catch (PDOException $e) {
            error_log("Erro de conexão PDO: " . $e->getMessage());
            throw new Exception("Erro de conexão com o banco de dados: " . $e->getMessage());
        }
    }

    /**
     * Método estático para obter a instância única da classe
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Retorna a conexão PDO
     */
    public function getConnection()
    {
        return $this->conn;
    }

    /**
     * Testa a conexão com o banco
     */
    public function testConnection()
    {
        try {
            $stmt = $this->conn->query("SELECT 1");
            return $stmt !== false;
        } catch (PDOException $e) {
            error_log("Erro no teste de conexão: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Fecha explicitamente a conexão PDO (opcional)
     */
    public function close()
    {
        $this->conn = null;
        self::$instance = null;
    }

    /**
     * Previne a clonagem da instância
     */
    private function __clone() {}

    /**
     * Previne a desserialização da instância
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
