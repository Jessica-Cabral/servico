<?php
session_start();
require_once __DIR__ . '/../../models/Prestador.class.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $foto_perfil = null;

    $erros = [];
    if (!$id) $erros[] = "ID inválido.";
    if (!$nome) $erros[] = "Nome é obrigatório.";
    if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = "Email inválido.";

    // Upload da foto de perfil
    if (isset($_FILES['foto_perfil']) && $_FILES['foto_perfil']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['foto_perfil']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'gif'];
        if (in_array($ext, $permitidas)) {
            $pastaUploads = __DIR__ . '/uploads/';
            if (!is_dir($pastaUploads)) {
                mkdir($pastaUploads, 0777, true);
            }
            $nomeArquivo = 'perfil_' . $id . '_' . time() . '.' . $ext;
            $destino = $pastaUploads . $nomeArquivo;
            if (move_uploaded_file($_FILES['foto_perfil']['tmp_name'], $destino)) {
                // Caminho relativo para salvar no banco e exibir no HTML
                $foto_perfil = 'view/prestador/uploads/' . $nomeArquivo;
            } else {
                $erros[] = "Erro ao salvar a foto de perfil.";
            }
        } else {
            $erros[] = "Formato de imagem não permitido.";
        }
    }

    if (count($erros) === 0) {
        $prestador = new Prestador();
        $dados = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $telefone
        ];
        if ($foto_perfil) {
            $dados['foto_perfil'] = $foto_perfil;
        }
        if (!empty($senha)) {
            if (strlen($senha) < 6) {
                $erros[] = "A senha deve ter pelo menos 6 caracteres.";
            } else {
                $dados['senha'] = password_hash($senha, PASSWORD_DEFAULT);
            }
        }

        if (count($erros) === 0) {
            $ok = $prestador->updateCompleto($id, $dados);
            if ($ok) {
                $_SESSION['prestador_nome'] = $nome;
                header('Location: prestadorDashboard.php?sucesso=1');
                exit;
            } else {
                $erros[] = "Erro ao atualizar dados no banco.";
            }
        }
    }
    $_SESSION['erros_perfil'] = $erros;
    header('Location: prestadorDashboard.php?erro=1');
    exit;
} else {
    header('Location: prestadorDashboard.php');
    exit;
}
?>


<div class="container mt-4 mb-4">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-sm">
                <div class="card-body">
                    <p class="form-title text-center">*Edite o seu Cadastro</p>
                    <form action="atualizar_perfil.php" method="post" enctype="multipart/form-data" id="formPerfil">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($prestador_dados['id'] ?? ''); ?>">
                        <div class="text-center mb-3">
                            <img src="<?php
                                $foto_perfil = !empty($prestador_dados['foto_perfil']) ? $prestador_dados['foto_perfil'] : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
                                if (!preg_match('/^https?:\/\//', $foto_perfil)) {
                                    $foto_perfil = '/' . ltrim($foto_perfil, '/');
                                }
                                echo htmlspecialchars($foto_perfil);
                            ?>" alt="Foto do Perfil" class="perfil-img mb-2">
                            <div class="input-group mt-2">
                                <label class="input-group-text" for="foto_perfil"><i class="fas fa-camera"></i></label>
                                <input class="form-control" type="file" id="foto_perfil" name="foto_perfil" accept="image/*">
                            </div>
                            <small id="fileName" class="text-muted"></small>
                        </div>
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($prestador_dados['nome'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($prestador_dados['email'] ?? ''); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" class="form-control" id="telefone" name="telefone" value="<?php echo htmlspecialchars($prestador_dados['telefone'] ?? ''); ?>">
                        </div>
                        <div class="mb-3">
                            <label for="senha" class="form-label">Nova Senha</label>
                            <input type="password" class="form-control" id="senha" name="senha" minlength="6" autocomplete="new-password">
                            <small class="form-text text-muted">Preencha apenas se desejar alterar a senha (mínimo 6 caracteres).</small>
                        </div>
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-custom btn-custom-primary">
                                <i class="fas fa-save me-1"></i> Salvar Alterações
                            </button>
                            <a href="prestadorDashboard.php" class="btn btn-custom btn-custom-accent">
                                <i class="fas fa-arrow-left me-1"></i> Voltar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Visualização do nome do arquivo selecionado
document.getElementById('foto_perfil').addEventListener('change', function(e) {
    const fileName = e.target.files[0] ? e.target.files[0].name : '';
    document.getElementById('fileName').textContent = fileName ? 'Selecionado: ' + fileName : '';
});

// Validação de senha no cliente
document.getElementById('formPerfil').addEventListener('submit', function(e) {
    const senha = document.getElementById('senha').value;
    if (senha && senha.length < 6) {
        e.preventDefault();
        alert('A senha deve ter pelo menos 6 caracteres.');
        document.getElementById('senha').focus();
    }
});
</script>
