<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/servico/cliente/dashboard">
            <div class="logo-container me-2" style="background: #ffb347; padding: 8px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center;">
                <i class="bi bi-tools" style="font-size: 1.5rem; color: #1a2233;"></i>
            </div>
            <div>
                <span class="fw-bold" style="letter-spacing: 0.5px;">CHAMA</span>
                <span class="fw-light" style="color: #ffb347;">SERVIÇO</span>
            </div>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="/servico/cliente/dashboard">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/servico/cliente/novoservico">
                        <i class="bi bi-plus-circle me-1"></i> Solicitar Serviço
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/servico/cliente/meusservicos">
                        <i class="bi bi-list-check me-1"></i> Meus Serviços
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/servico/cliente/mensagens">
                        <i class="bi bi-chat-dots me-1"></i> Mensagens
                    </a>
                </li>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i> Minha Conta
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li>
                            <a class="dropdown-item" href="/servico/cliente/editarperfil">
                                <i class="bi bi-person me-2"></i> Editar Perfil
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="/servico/cliente/endereco">
                                <i class="bi bi-geo-alt me-2"></i> Meus Endereços
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <a class="dropdown-item text-danger" href="/servico/logout">
                                <i class="bi bi-box-arrow-right me-2"></i> Sair
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>