<?php
require_once __DIR__ . '/config/database.php';

// Conexão com o banco
$db = new Database();
$conn = $db->getConnection();

// Busque todos os serviços disponíveis
$stmt = $conn->prepare("SELECT id, titulo, descricao, tipo_servico FROM tb_solicita_servico ORDER BY data_solicitacao DESC");
$stmt->execute();
$servicos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Serviços Disponíveis - Chama Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h2 class="mb-4">Serviços Disponíveis</h2>
        <?php if (empty($servicos)): ?>
            <div class="alert alert-info">Nenhum serviço disponível no momento.</div>
        <?php else: ?>
            <div class="row">
                <?php foreach ($servicos as $servico): ?>
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($servico['titulo']); ?></h5>
                                <h6 class="card-subtitle mb-2 text-muted"><?php echo htmlspecialchars($servico['tipo_servico']); ?></h6>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($servico['descricao'])); ?></p>
                                <!-- Adicione mais detalhes ou botões se desejar -->
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
        <a href="HomePage.php" class="btn btn-secondary mt-4"><i class="bi bi-arrow-left"></i> Voltar para Home</a>
    </div>
</body>
</html>
