<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard do Cliente - Chama Serviço</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: none;
        }

        .welcome-section {
            background: linear-gradient(135deg, #2c3e50 0%, #4f6fa5 100%);
            color: white;
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .profile-img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid rgba(255, 255, 255, 0.2);
        }
    </style>
</head>

<body>
    <?php require_once __DIR__ . '/../components/menu-cliente.php'; ?>

    <div class="container py-4">
        <div class="welcome-section">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h1>Olá, <?php echo htmlspecialchars($cliente_nome); ?>!</h1>
                    <p>Bem-vindo ao seu painel de controle. Aqui você pode gerenciar seus serviços, ver propostas e muito mais.</p>
                </div>
                <div class="col-lg-4 text-center">
                    <img src="<?php echo htmlspecialchars($cliente_foto); ?>" alt="Foto de perfil" class="profile-img">
                    <h5 class="mt-2 mb-1"><?php echo htmlspecialchars($cliente_nome); ?></h5>
                    <span class="badge bg-light text-dark">Cliente</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-grid-3x3-gap-fill me-2"></i> Menu Rápido
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <a href="/servico/cliente/novoServico"
                                    class="btn btn-light w-100 p-4 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-plus-circle fs-1 mb-2 text-primary"></i>
                                    <span>Solicitar Serviço</span>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="/servico/cliente/meusServicos"
                                    class="btn btn-light w-100 p-4 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-list-check fs-1 mb-2 text-success"></i>
                                    <span>Meus Serviços</span>
                                </a>
                            </div>
                            <div class="col-md-4 mb-3">
                                <a href="/servico/cliente/mensagens"
                                    class="btn btn-light w-100 p-4 h-100 d-flex flex-column align-items-center justify-content-center">
                                    <i class="bi bi-chat-dots fs-1 mb-2 text-info"></i>
                                    <span>Mensagens</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <i class="bi bi-person me-2"></i> Meu Perfil
                    </div>
                    <div class="card-body">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <strong>Email:</strong> <?php echo htmlspecialchars($cliente_email); ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Telefone:</strong> <?php echo htmlspecialchars($cliente_telefone); ?>
                            </li>
                            <li class="list-group-item">
                                <strong>Membro desde:</strong>
                                <?php
                                // A variável $dados_cliente é fornecida pelo Controller
                                $data_cadastro_formatada = isset($dados_cliente['data_cadastro']) ?
                                    date('d/m/Y', strtotime($dados_cliente['data_cadastro'])) :
                                    'Não informado';
                                echo htmlspecialchars($data_cadastro_formatada);
                                ?>
                            </li>
                        </ul>
                        <a href="/servico/cliente/editarPerfil" class="btn btn-primary w-100 mt-3">
                            <i class="bi bi-pencil-square me-2"></i>Editar Perfil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>