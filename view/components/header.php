<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/servico/assets/img/favicon.png">
    <title>Chama Serviço | <?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Página Inicial'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/servico/assets/css/global.css">
    <?php if (isset($customCss)) echo '<link rel="stylesheet" href="' . htmlspecialchars($customCss) . '">'; ?>
</head>

<body>
    <header class="bg-gradient" style="background: linear-gradient(135deg, var(--primary-color), var(--accent-color));">
        <nav class="navbar navbar-expand-lg navbar-dark container py-3">
            <a class="navbar-brand" href="/servico/home">
                <img src="/servico/assets/img/logochamaser.png" alt="Chama Serviço" style="width: 150px;">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/servico/home">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/servico/about">Sobre</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/servico/contact">Contato</a>
                    </li>
                    <?php if (isset($_SESSION['cliente_id'])): ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/servico/cliente/dashboard">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/servico/logout">Sair</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/servico/index.php?url=Login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/servico/cadusuario">Cadastrar</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    <?php if (isset($content)): echo $content;
    endif; ?>