<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand fw-bold" href="clienteDashboard.php">
            <i class="fas fa-tools me-2"></i>
            Chama Serviço
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link<?php if(basename($_SERVER['PHP_SELF']) == 'clienteDashboard.php') echo ' active'; ?>" href="clienteDashboard.php"><i class="fas fa-home me-1"></i> Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php if(basename($_SERVER['PHP_SELF']) == 'novo-servico.php') echo ' active'; ?>" href="novo-servico.php"><i class="fas fa-plus me-1"></i> Novo Serviço</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link<?php if(basename($_SERVER['PHP_SELF']) == 'meus-servicos.php') echo ' active'; ?>" href="meus-servicos.php"><i class="fas fa-list me-1"></i> Meus Serviços</a>
                </li>
             
            </ul>
            <ul class="navbar-nav align-items-center">
                <!-- Notificações -->
                <li class="nav-item dropdown me-3">
                    <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notificationBadge" style="display: none;">
                            0
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end notification-dropdown">
                        <div class="dropdown-header d-flex justify-content-between align-items-center">
                            <span>Notificações</span>
                            <button class="btn btn-sm btn-link p-0" onclick="marcarTodasLidas()">
                                <small>Marcar todas como lidas</small>
                            </button>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div id="notificationList" style="max-height: 300px; overflow-y: auto;">
                            <div class="dropdown-item-text text-center text-muted">
                                <i class="fas fa-spinner fa-spin"></i> Carregando...
                            </div>
                        </div>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-center" href="notificacoes.php">
                            <small>Ver todas as notificações</small>
                        </a>
                    </div>
                </li>
                <!-- Fim Notificações -->
                <!-- Usuário -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle me-1"></i>
                        <?php echo htmlspecialchars($_SESSION['cliente_nome'] ?? 'Cliente'); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i> Configurações</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-warning" href="../../switch-user.php?type=prestador"><i class="fas fa-exchange-alt me-2"></i> Trocar para Prestador</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="../../Login.php" method="post" style="margin:0;">
                                <button type="submit" name="logout" class="dropdown-item">
                                    <i class="fas fa-sign-out-alt me-2"></i> Sair
                                </button>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

