<?php
require_once __DIR__ . '/../config/database.php';

class Pessoa
{
    private $conn;
    private $table = 'tb_pessoa';

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function create($dados)
    {
        $sql = "INSERT INTO {$this->table} (nome, email, senha, cpf, telefone, dt_nascimento, tipo) 
                VALUES (:nome, :email, :senha, :cpf, :telefone, :dt_nascimento, :tipo)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':email', $dados['email']);
        $stmt->bindValue(':senha', password_hash($dados['senha'], PASSWORD_DEFAULT));
        $stmt->bindValue(':cpf', $dados['cpf']);
        $stmt->bindValue(':telefone', $dados['telefone']);
        $stmt->bindValue(':dt_nascimento', $dados['dt_nascimento']);
        $stmt->bindValue(':tipo', $dados['tipo']);
        return $stmt->execute() ? $this->conn->lastInsertId() : false;
    }

    public function getById($id)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->conn->query("SELECT * FROM {$this->table} ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update($id, $dados)
    {
        $sql = "UPDATE {$this->table} SET nome=:nome, email=:email, telefone=:telefone, dt_nascimento=:dt_nascimento, tipo=:tipo WHERE id=:id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nome', $dados['nome']);
        $stmt->bindValue(':email', $dados['email']);
        $stmt->bindValue(':telefone', $dados['telefone']);
        $stmt->bindValue(':dt_nascimento', $dados['dt_nascimento']);
        $stmt->bindValue(':tipo', $dados['tipo']);
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }

    public function getByEmail($email)
    {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
