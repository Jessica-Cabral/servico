<?php
require_once __DIR__ . '/../config/database.php';

class UsuarioClass
{
    private $conn;
    private $table = 'tb_pessoa';

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function criar($dados)
    {
        try {
            // Sanitização e validação
            $nome = trim($dados['nome'] ?? '');
            $email = trim($dados['email'] ?? '');
            $senha = $dados['senha'] ?? '';
            $cpf = preg_replace('/\D/', '', $dados['cpf'] ?? '');
            $telefone = preg_replace('/\D/', '', $dados['telefone'] ?? '');
            $dt_nascimento = $dados['dt_nascimento'] ?? null;
            $tipo = $dados['tipo'] ?? '';

            if (!$nome || !$email || !$senha || !$tipo) {
                error_log("Cadastro: Campos obrigatórios ausentes.");
                return ['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios.'];
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                error_log("Cadastro: E-mail inválido: $email");
                return ['sucesso' => false, 'mensagem' => 'E-mail inválido.'];
            }
            if (strlen($senha) < 6) {
                error_log("Cadastro: Senha muito curta.");
                return ['sucesso' => false, 'mensagem' => 'A senha deve ter pelo menos 6 caracteres.'];
            }
            if ($cpf && strlen($cpf) != 11) {
                error_log("Cadastro: CPF inválido: $cpf");
                return ['sucesso' => false, 'mensagem' => 'CPF inválido.'];
            }
            if ($this->getByEmail($email)) {
                error_log("Cadastro: E-mail já cadastrado: $email");
                return ['sucesso' => false, 'mensagem' => 'Este e-mail já está cadastrado.'];
            }
            if ($cpf && $this->getByCpf($cpf)) {
                error_log("Cadastro: CPF já cadastrado: $cpf");
                return ['sucesso' => false, 'mensagem' => 'Este CPF já está cadastrado.'];
            }

            // Formatação da data de nascimento (YYYY-MM-DD)
            if ($dt_nascimento && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $dt_nascimento)) {
                $dt_nascimento = date('Y-m-d', strtotime($dt_nascimento));
            }

            $sql = "INSERT INTO {$this->table} (nome, email, senha, tipo, telefone, cpf, dt_nascimento)
                    VALUES (:nome, :email, :senha, :tipo, :telefone, :cpf, :dt_nascimento)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':nome', $nome);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':senha', password_hash($senha, PASSWORD_DEFAULT));
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':telefone', $telefone);
            $stmt->bindValue(':cpf', $cpf ?: null);
            $stmt->bindValue(':dt_nascimento', $dt_nascimento ?: null);

            if ($stmt->execute()) {
                $id = $this->conn->lastInsertId();
                if ($id) {
                    return ['sucesso' => true, 'id' => $id];
                } else {
                    error_log("Cadastro: lastInsertId retornou falso.");
                    echo "DEBUG: lastInsertId retornou falso.<br>";
                    return ['sucesso' => false, 'mensagem' => 'Erro ao obter o ID do novo usuário.'];
                }
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("Cadastro: Erro no execute: " . print_r($errorInfo, true));
                echo "DEBUG: Erro SQL: " . htmlspecialchars($errorInfo[2] ?? 'Erro desconhecido') . "<br>";
                return ['sucesso' => false, 'mensagem' => 'Erro ao inserir usuário: ' . ($errorInfo[2] ?? 'Erro desconhecido')];
            }
        } catch (PDOException $e) {
            error_log("Cadastro: PDOException: " . $e->getMessage());
            echo "DEBUG: PDOException: " . htmlspecialchars($e->getMessage()) . "<br>";
            return ['sucesso' => false, 'mensagem' => 'Erro no insert: ' . $e->getMessage()];
        } catch (Exception $e) {
            error_log("Cadastro: Exception: " . $e->getMessage());
            echo "DEBUG: Exception: " . htmlspecialchars($e->getMessage()) . "<br>";
            return ['sucesso' => false, 'mensagem' => 'Erro inesperado: ' . $e->getMessage()];
        }
    }

    public function listar()
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table}");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("UsuarioModel: Erro ao listar: " . $e->getMessage());
            return false;
        }
    }

    public function atualizar($id, $dados)
    {
        try {
            // Validação básica
            if (empty($dados['nome']) || empty($dados['email']) || empty($dados['tipo'])) {
                return ['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios.'];
            }
            if (!filter_var($dados['email'], FILTER_VALIDATE_EMAIL)) {
                return ['sucesso' => false, 'mensagem' => 'E-mail inválido.'];
            }

            // Verifica duplicidade
            if ($this->getByEmail($dados['email'])) {
                return ['sucesso' => false, 'mensagem' => 'Este e-mail já está cadastrado.'];
            }
            if (!empty($dados['cpf']) && $this->getByCpf($dados['cpf'])) {
                return ['sucesso' => false, 'mensagem' => 'Este CPF já está cadastrado.'];
            }

            $sql = "UPDATE {$this->table} SET nome = :nome, email = :email, senha = :senha, tipo = :tipo, telefone = :telefone, cpf = :cpf, dt_nascimento = :dt_nascimento
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':nome', $dados['nome']);
            $stmt->bindValue(':email', $dados['email']);
            $stmt->bindValue(':senha', password_hash($dados['senha'], PASSWORD_DEFAULT));
            $stmt->bindValue(':tipo', $dados['tipo']);
            $stmt->bindValue(':telefone', $dados['telefone'] ?? '');
            $stmt->bindValue(':cpf', $dados['cpf'] ?? '');
            $stmt->bindValue(':dt_nascimento', $dados['dt_nascimento'] ?? null);
            $stmt->bindValue(':id', $id);

            if ($stmt->execute()) {
                return ['sucesso' => true];
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("UsuarioModel: Erro no execute: " . print_r($errorInfo, true));
                return ['sucesso' => false, 'mensagem' => 'Erro ao atualizar usuário: ' . ($errorInfo[2] ?? 'Erro desconhecido')];
            }
        } catch (PDOException $e) {
            error_log("UsuarioModel: PDOException: " . $e->getMessage());
            return ['sucesso' => false, 'mensagem' => 'Erro na atualização: ' . $e->getMessage()];
        } catch (Exception $e) {
            error_log("UsuarioModel: Exception: " . $e->getMessage());
            return ['sucesso' => false, 'mensagem' => 'Erro inesperado: ' . $e->getMessage()];
        }
    }

    public function excluir($id)
    {
        try {
            $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
            $stmt->bindValue(':id', $id);

            if ($stmt->execute()) {
                return ['sucesso' => true];
            } else {
                $errorInfo = $stmt->errorInfo();
                error_log("UsuarioModel: Erro no execute: " . print_r($errorInfo, true));
                return ['sucesso' => false, 'mensagem' => 'Erro ao excluir usuário: ' . ($errorInfo[2] ?? 'Erro desconhecido')];
            }
        } catch (PDOException $e) {
            error_log("UsuarioModel: PDOException: " . $e->getMessage());
            return ['sucesso' => false, 'mensagem' => 'Erro na exclusão: ' . $e->getMessage()];
        } catch (Exception $e) {
            error_log("UsuarioModel: Exception: " . $e->getMessage());
            return ['sucesso' => false, 'mensagem' => 'Erro inesperado: ' . $e->getMessage()];
        }
    }

    public function getByEmail($email)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = :email");
            $stmt->bindValue(':email', $email);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("UsuarioModel: Erro getByEmail: " . $e->getMessage());
            return false;
        }
    }

    public function getByCpf($cpf)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE cpf = :cpf");
            $stmt->bindValue(':cpf', $cpf);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("UsuarioModel: Erro getByCpf: " . $e->getMessage());
            return false;
        }
    }

    public function getById($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            error_log("UsuarioModel: Erro getById: " . $e->getMessage());
            return false;
        }
    }
}
