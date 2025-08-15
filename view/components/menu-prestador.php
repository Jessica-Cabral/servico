<?php
if (!defined('MENU_PRESTADOR_INCLUDED')) {
	define('MENU_PRESTADOR_INCLUDED', true);

	// Garante sessão para ler o nome do usuário sem reiniciar sessão se já ativa
	if (session_status() === PHP_SESSION_NONE) {
		@session_start();
	}
	$prestador_nome = $prestador_nome ?? ($_SESSION['prestador_nome'] ?? null);
?>
<nav role="navigation" aria-label="Menu do Prestador" class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" style="z-index:1050;">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/servico/prestador/dashboard" title="Ir ao dashboard">
            <div class="logo-container me-2" style="background: #27ae60; padding: 8px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">
                <i class="fas fa-briefcase" style="font-size: 1.5rem; color: #fff;"></i>
            </div>
            <div>
                <span class="fw-bold" style="letter-spacing: 0.5px;">CHAMA</span>
                <span class="fw-light" style="color: #27ae60;">SERVIÇO</span>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPrestador" aria-controls="navbarPrestador" aria-expanded="false" aria-label="Alternar navegação">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarPrestador">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/servico/prestador/dashboard">
                        <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/servico/prestador/oportunidades">
                        <i class="fas fa-search me-1"></i> Oportunidades
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/servico/prestador/minhaspropostas">
                        <i class="fas fa-handshake me-1"></i> Minhas Propostas
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="https://wa.me/556134032081" target="_blank">
                        <i class="fas fa-headset me-1"></i> Suporte
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="navbarPrestadorDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?= htmlspecialchars($_SESSION['prestador_foto'] ?? '/servico/assets/img/default-avatar.png'); ?>" alt="avatar" style="width:32px;height:32px;border-radius:50%;object-fit:cover;">
                        <span><?= htmlspecialchars($prestador_nome ?? 'Minha Conta'); ?></span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarPrestadorDropdown">
                        <li>
                            <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#perfilModal">
                                <i class="fas fa-user me-2"></i> Perfil
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="/servico/logout">
                                <i class="fas fa-sign-out-alt me-2"></i> Sair
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
<?php
} // fim guard MENU_PRESTADOR_INCLUDED
?>
