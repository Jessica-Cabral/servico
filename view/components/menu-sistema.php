<?php
// Iniciar sessão se necessário
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Determina o tipo de usuário e o nome
$nome_usuario = 'Usuário';
$tipo_usuario = '';
$dashboard_link = '/servico/login'; // Link padrão

if (isset($_SESSION['cliente_id'])) {
    $nome_usuario = $_SESSION['cliente_nome'] ?? 'Cliente';
    $tipo_usuario = 'cliente';
    $dashboard_link = '/servico/cliente/dashboard';
} elseif (isset($_SESSION['prestador_id'])) {
    $nome_usuario = $_SESSION['prestador_nome'] ?? 'Prestador';
    $tipo_usuario = 'prestador';
    $dashboard_link = '/servico/prestador/dashboard';
}

?>
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(90deg, #1a2233 60%, #ffb347 100%);">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $dashboard_link; ?>">
            <i class="bi bi-tools"></i> Chama Serviço
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dashboardNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="dashboardNavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo $dashboard_link; ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
                </li>
                <?php if ($tipo_usuario === 'cliente'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/servico/cliente/meusServicos"><i class="bi bi-list-check"></i> Meus Serviços</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/servico/cliente/novoServico"><i class="bi bi-plus-circle"></i> Novo Serviço</a>
                    </li>
                <?php elseif ($tipo_usuario === 'prestador'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/servico/prestador/oportunidades"><i class="bi bi-search"></i> Oportunidades</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/servico/prestador/minhasPropostas"><i class="bi bi-handshake"></i> Minhas Propostas</a>
                    </li>
                <?php endif; ?>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($nome_usuario); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person"></i> Meu Perfil</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item" href="/servico/login/logout"><i class="bi bi-box-arrow-right"></i> Sair</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>