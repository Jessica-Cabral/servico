<header id="header" class="fixed-top">
    <div class="top-bar d-none d-md-block py-1" style="background-color: #0c1524; color: #ffffff;">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <div class="contact-info d-flex align-items-center">
                    <i class="bi bi-envelope me-2"></i><a href="mailto:contato@chamaservico.com" class="text-white text-decoration-none small">contato@chamaservico.com</a>
                    <i class="bi bi-phone ms-4 me-2"></i><span class="small">(11) 1234-5678</span>
                </div>
                <div class="social-links d-flex align-items-center">
                    <a href="#" class="text-white me-3"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="text-white me-3"><i class="bi bi-linkedin"></i></a>
                    <a href="#" class="text-white"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" role="navigation" aria-label="Menu Público">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/servico/home">
                <i class="bi bi-tools me-2"></i> Chama Serviço
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarPublico"
                    aria-controls="navbarPublico" aria-expanded="false" aria-label="Alternar navegação">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarPublico">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link" href="/servico/home"><i class="bi bi-house-door me-1"></i> Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/servico/sobre"><i class="bi bi-info-circle me-1"></i> Sobre nós</a></li>
                    <li class="nav-item"><a class="nav-link" href="/servico/contato"><i class="bi bi-chat-dots me-1"></i> Contato</a></li>
                    <li class="nav-item"><a class="nav-link" href="/servico/login"><i class="bi bi-box-arrow-in-right me-1"></i> Entrar</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-warning text-dark ms-2" href="/servico/cadusuario"><i class="bi bi-person-plus me-1"></i> Cadastre-se</a></li>
                </ul>
            </div>
        </div>
    </nav>
</header>

<style>

    #header .top-bar {
        font-size: 0.85rem;
    }
    
    #header .navbar {
        padding-top: 0.7rem;
        padding-bottom: 0.7rem;
    }
    
    #header .nav-link {
        position: relative;
        transition: color 0.3s;
    }
    
    #header .nav-link:hover {
        color: #ffb347 !important;
    }
    
    #header .nav-link::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: 0;
        left: 50%;
        background-color: #ffb347;
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }
    
    #header .nav-link:hover::after {
        width: 70%;
    }
    
    #header .dropdown-menu {
        border-radius: 0.5rem;
        margin-top: 0.5rem;
        border: none;
    }
    
    #header .dropdown-item {
        transition: all 0.2s;
    }
    
    #header .dropdown-item:hover {
        background-color: #f8f9fa;
        padding-left: 1.5rem;
        color: #ffb347;
    }
    
    #header .btn-warning {
        background-color: #ffb347;
        border-color: #ffb347;
        color: #1a2233;
        font-weight: 500;
        transition: all 0.3s;
    }
    
    #header .btn-warning:hover {
        background-color: #ff9d17;
        border-color: #ff9d17;
        transform: translateY(-2px);
    }
    
    #header .btn-outline-light:hover {
        transform: translateY(-2px);
    }
    
    /* Adicionar espaço para o conteúdo principal após o cabeçalho fixo */
    body {
        padding-top: calc(60px + 2.5rem); /* Altura base do navbar + espaço adicional */
    }
    
    /* Para páginas com a barra superior visível em desktop */
    @media (min-width: 768px) {
        body {
            padding-top: calc(60px + 30px + 2.5rem); /* Altura navbar + altura top-bar + espaço adicional */
        }
    }
    
    /* Classe auxiliar para quando precisar de mais espaço (ex: páginas com título grande) */
    .content-padding-lg {
        padding-top: 8rem !important;
    }
    
    /* Classe para quando precisar de menos espaço */
    .content-padding-sm {
        padding-top: 5rem !important;
    }
    
    /* Ajuste para mobile */
    @media (max-width: 991.98px) {
        #header .navbar-collapse {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-top: 0.5rem;
            background-color: #2c3e50;
        }
        
        #header .nav-link::after {
            display: none;
        }
    }
</style>

<!-- Script para ajustar dinamicamente o espaçamento do conteúdo -->
<script>
    // Executa quando o DOM estiver carregado
    document.addEventListener('DOMContentLoaded', function() {
        // Calcula a altura real do cabeçalho
        const header = document.getElementById('header');
        if (header) {
            const headerHeight = header.offsetHeight;
            document.body.style.paddingTop = (headerHeight + 20) + 'px'; // altura + margem extra
            
            // Adiciona classe para o primeiro elemento de conteúdo principal, se existir
            const mainContent = document.querySelector('main') || document.querySelector('.main-content');
            if (mainContent) {
                mainContent.style.marginTop = '2rem';
            }
        }
    });
    
    // Recalcula se a janela for redimensionada
    window.addEventListener('resize', function() {
        const header = document.getElementById('header');
        if (header) {
            const headerHeight = header.offsetHeight;
            document.body.style.paddingTop = (headerHeight + 20) + 'px';
        }
    });
</script>