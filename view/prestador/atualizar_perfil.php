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

// Exemplo de bloco para edição de cadastro com upload de foto
?>
<p class="form-title">*Edite o seu Cadastro</p>
<div class="mb-3">
  <form action="index.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="id_pessoa" value="<?php echo htmlspecialchars($prestador_dados['id'] ?? ''); ?>">
    <img src="<?php
      $foto_perfil = !empty($prestador_dados['foto_perfil']) ? $prestador_dados['foto_perfil'] : 'https://cdn-icons-png.flaticon.com/512/149/149071.png';
      if (!preg_match('/^https?:\/\//', $foto_perfil)) {
          $foto_perfil = '/' . ltrim($foto_perfil, '/');
      }
      echo htmlspecialchars($foto_perfil);
    ?>" alt="Foto do Perfil" class="perfil-img" style="width:120px;height:120px;object-fit:cover;border-radius:50%;border:2px solid #ffc107;">
    <input class="form-control mt-2" type="file" id="imagem" name="foto_perfil" accept="image/*">
  </form>
</div>
<?php
