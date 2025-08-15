<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chama Serviço | Plataforma de Serviços - Encontre Profissionais Qualificados</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <!-- Preload de recursos importantes -->
    <link rel="preload" href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" as="style">

    <!-- Favicon -->
    <link rel="icon" href="assets/img/favicon.png" type="image/x-icon">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/img/apple-touch-icon.png">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:400,600,700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="stylesheet" href="assets/css/homepage.css">

    <style>
        /* Garante espaço para o menu fixo */
        body {
            padding-top: 110px;
        }
    </style>
</head>

<body>
    <!--MENU-->
    <?php require_once __DIR__ . '/../components/menu-publico.php'; ?>


    <main id="main">
        <!-- Hero Section -->
        <section class="hero-section">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6" data-aos="fade-right">
                        <h1>Precisando de um serviço? Encontre o profissional ideal!</h1>
                        <p>Conectamos quem precisa de serviço com quem oferece. Rápido, fácil e seguro.</p>
                        <div class="d-flex flex-wrap">
                            <a href="CadUsuario.php" class="btn btn-cta shadow wave-effect" data-aos="zoom-in">
                                <i class="bi bi-person-plus me-2"></i>Solicitar um serviço
                            </a>
                            <a href="CadUsuario.php" class="btn btn-cta shadow wave-effect" data-aos="zoom-in">
                                <i class="bi bi-briefcase me-2"></i>Sou prestador
                            </a>
                        </div>
                    </div>
                    <div class="col-lg-6" data-aos="fade-left">
                        <div class="d-flex justify-content-center align-items-center h-100">
                            <img
                                src="assets/img/marketplace-hero.png"
                                alt="Ilustração profissional do marketplace Chama Serviço"
                                class="img-fluid floating shadow rounded-4 border border-2 border-light"
                                style="max-width: 90%; background: #fff; padding: 16px;"
                                loading="lazy">
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Como Funciona -->
        <section class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title">Como funciona?</h2>
                <p class="lead">Em poucos passos você resolve o que precisa</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-pencil-square"></i></div>
                        <h5 class="fw-bold">1. Solicite</h5>
                        <p class="mb-0">Descreva o serviço que precisa de forma simples e rápida.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-chat-dots"></i></div>
                        <h5 class="fw-bold">2. Receba propostas</h5>
                        <p class="mb-0">Prestadores qualificados enviam orçamentos personalizados.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-hand-thumbs-up"></i></div>
                        <h5 class="fw-bold">3. Escolha</h5>
                        <p class="mb-0">Compare avaliações, preços e feche com o melhor profissional.</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 mb-4" data-aos="fade-up" data-aos-delay="400">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-shield-check"></i></div>
                        <h5 class="fw-bold">4. Avalie</h5>
                        <p class="mb-0">Serviço realizado com qualidade e segurança garantida.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Vantagens -->
        <section class="container py-5 bg-light rounded-4">
            <div class="text-center mb-5">
                <h2 class="section-title">Por que usar o Chama Serviço?</h2>
                <p class="lead">Vantagens para clientes e prestadores</p>
            </div>
            <div class="row">
                <div class="col-lg-6" data-aos="fade-right">
                    <div class="p-4">
                        <h4 class="mb-4"><i class="bi bi-people me-2"></i> Para quem precisa de serviços</h4>
                        <ul class="advantage-list">
                            <li><i class="bi bi-check-circle-fill"></i>Facilidade e agilidade na contratação</li>
                            <li><i class="bi bi-check-circle-fill"></i>Prestadores verificados e avaliados</li>
                            <li><i class="bi bi-check-circle-fill"></i>Negociação direta e transparente</li>
                            <li><i class="bi bi-check-circle-fill"></i>Diversas opções de profissionais</li>
                            <li><i class="bi bi-check-circle-fill"></i>Segurança e garantia na contratação</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6" data-aos="fade-left">
                    <div class="p-4">
                        <h4 class="mb-4"><i class="bi bi-person-badge me-2"></i> Para prestadores de serviços</h4>
                        <ul class="advantage-list">
                            <li><i class="bi bi-check-circle-fill"></i>Mais visibilidade para seu trabalho</li>
                            <li><i class="bi bi-check-circle-fill"></i>Acesso a novos clientes diariamente</li>
                            <li><i class="bi bi-check-circle-fill"></i>Sem intermediadores ou taxas abusivas</li>
                            <li><i class="bi bi-check-circle-fill"></i>Perfil completo com portfólio e avaliações</li>
                            <li><i class="bi bi-check-circle-fill"></i>Ferramentas para gerenciar seus serviços</li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Para quem é -->
        <section class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title">Para quem é a plataforma?</h2>
                <p class="lead">Conectamos quem precisa com quem oferece serviços</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-5 mb-4" data-aos="fade-right">
                    <div class="forwho-card">
                        <div class="icon"><i class="bi bi-people"></i></div>
                        <h4 class="fw-bold">Clientes</h4>
                        <p>Pessoas que precisam de serviços domésticos, reformas, reparos, instalações, montagens, serviços técnicos e muito mais.</p>
                        <a href="CadUsuario.php" class="btn btn-sm btn-primary mt-2 wave-effect">Quero contratar</a>
                    </div>
                </div>
                <div class="col-lg-5 mb-4" data-aos="fade-left">
                    <div class="forwho-card">
                        <div class="icon"><i class="bi bi-person-badge"></i></div>
                        <h4 class="fw-bold">Prestadores</h4>
                        <p>Profissionais autônomos, técnicos, artesãos e empresas que querem mais clientes e visibilidade para seus serviços.</p>
                        <a href="CadUsuario.php" class="btn btn-sm btn-primary mt-2 wave-effect">Quero trabalhar</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Serviços populares -->
        <section class="container py-5 bg-light rounded-4">
            <div class="text-center mb-5">
                <h2 class="section-title">Serviços mais procurados</h2>
                <p class="lead">Alguns dos serviços mais solicitados em nossa plataforma</p>
            </div>
            <div class="row">
                <div class="col-md-3 col-6 mb-4" data-aos="zoom-in">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-tools"></i></div>
                        <h6 class="fw-bold">Encanador</h6>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-lightning-charge"></i></div>
                        <h6 class="fw-bold">Eletricista</h6>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-brush"></i></div>
                        <h6 class="fw-bold">Pintor</h6>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-house-gear"></i></div>
                        <h6 class="fw-bold">Diarista</h6>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="0">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-hammer"></i></div>
                        <h6 class="fw-bold">Pedreiro</h6>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="100">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-tree"></i></div>
                        <h6 class="fw-bold">Jardineiro</h6>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="200">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-laptop"></i></div>
                        <h6 class="fw-bold">Técnico de TI</h6>
                    </div>
                </div>
                <div class="col-md-3 col-6 mb-4" data-aos="zoom-in" data-aos-delay="300">
                    <div class="step-card">
                        <div class="step-icon"><i class="bi bi-car-front"></i></div>
                        <h6 class="fw-bold">Mecânico</h6>
                    </div>
                </div>
            </div>

        </section>

        <!-- App em breve -->
        <section class="app-section container my-5" data-aos="fade-up">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h3 class="fw-bold mb-3">Baixe o app Chama Serviço em breve!</h3>
                    <p class="mb-4">Contrate e gerencie seus serviços onde estiver. Disponível em breve para Android e iOS.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#" class="btn btn-light rounded-pill px-4 disabled wave-effect" tabindex="-1">
                            <i class="bi bi-google-play me-2"></i> Google Play
                        </a>
                        <a href="#" class="btn btn-light rounded-pill px-4 disabled wave-effect" tabindex="-1">
                            <i class="bi bi-apple me-2"></i> App Store
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 text-center">
                    <img src="assets/img/logochamaser.png" alt="App Chama Serviço" class="img-fluid floating" style="max-width: 280px;">
                </div>
            </div>
        </section>

        <!-- Depoimentos -->
        <section class="container py-5">
            <div class="text-center mb-5">
                <h2 class="section-title">O que estão dizendo</h2>
                <p class="lead">Avaliações de clientes e prestadores</p>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-5 mb-4" data-aos="fade-right">
                    <div class="testimonial-card d-flex">
                        <img src="assets/img/testimonials/1.jpg" alt="Cliente 1" class="avatar">
                        <div>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <strong>Maria Silva</strong>
                            <p class="mb-0 small">"Encontrei um ótimo eletricista em minutos para resolver um problema urgente em casa. Profissional pontual, educado e com ótimo preço. Recomendo!"</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 mb-4" data-aos="fade-left">
                    <div class="testimonial-card d-flex">
                        <img src="assets/img/testimonials/2.jpg" alt="Cliente 2" class="avatar">
                        <div>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                            <strong>João Pereira</strong>
                            <p class="mb-0 small">"Como prestador, consegui aumentar meus clientes em 40% usando a plataforma. Agora consigo gerenciar melhor meus horários e serviços."</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 mb-4" data-aos="fade-right">
                    <div class="testimonial-card d-flex">
                        <img src="assets/img/testimonials/3.jpg" alt="Cliente 3" class="avatar">
                        <div>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                            <strong>Ana Costa</strong>
                            <p class="mb-0 small">"Contratei uma diarista excelente para ajudar em casa. A plataforma facilita a busca por profissionais confiáveis e com boas avaliações."</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 mb-4" data-aos="fade-left">
                    <div class="testimonial-card d-flex">
                        <img src="assets/img/testimonials/4.jpg" alt="Cliente 4" class="avatar">
                        <div>
                            <div class="rating">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                            <strong>Carlos Mendes</strong>
                            <p class="mb-0 small">"Reformei meu banheiro encontrando todos os profissionais necessários em um só lugar. Foi muito prático e economizei tempo."</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Final -->
        <section class="cta-final container-fluid my-5">
            <div class="container">
                <h2 class="mb-4">Pronto para facilitar sua vida?</h2>
                <p class="mb-5 lead">Junte-se a milhares de pessoas que já usam o Chama Serviço</p>
                <a href="CadUsuario.php" class="btn btn-lg me-2 wave-effect"><i class="bi bi-person-plus me-2"></i>Quero me cadastrar</a>
                <a href="contact.html" class="btn btn-lg wave-effect"><i class="bi bi-chat-dots me-2"></i>Fale conosco</a>
            </div>
        </section>
    </main>

    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row gy-4">
                    <div class="col-lg-3 col-md-6 footer-info">
                        <i class="bi bi-tools"> </i>
                        <span class="fw-bold">Chama Serviço</span>
                        <p class="small">Encontre profissionais qualificados para qualquer serviço, com segurança e praticidade.</p>
                    </div>
                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Links Úteis</h4>
                        <ul>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Home</a></li>
                            <li><a href="about.html"><i class="bi bi-chevron-right"></i> Sobre nós</a></li>
                            <li><a href="HomeServicos.php"><i class="bi bi-chevron-right"></i> Serviços</a></li>
                            <li><a href="#"><i class="bi bi-chevron-right"></i> Termos de serviço</a></li>

                        </ul>
                    </div>
                    <div class="col-lg-3 col-md-6 footer-contact">
                        <h4>Entre em contato</h4>
                        <p>
                            <i class="bi bi-geo-alt"></i> <strong>Endereço:</strong> Taguatinga, 123 - Brasília, DF<br>
                            <i class="bi bi-telephone"></i> <strong>Telefone:</strong> (11) 1234-5678<br>
                            <i class="bi bi-envelope"></i> <strong>Email:</strong> contato@chamaservico.com<br>
                            <i class="bi bi-file-text"></i> <strong>CNPJ:</strong> 00.000.000/0001-00
                        </p>
                        <div class="social-links mt-3">
                            <a href="#"><i class="bi bi-facebook"></i></a>
                            <a href="#"><i class="bi bi-instagram"></i></a>
                            <a href="#"><i class="bi bi-linkedin"></i></a>
                            <a href="#"><i class="bi bi-youtube"></i></a>
                            <a href="#"><i class="bi bi-whatsapp"></i></a>
                        </div>
                    </div>

                    <div class="text-center mt-5 pt-3 border-top border-secondary">
                        <p class="small mb-0">
                            &copy; <script>
                                document.write(new Date().getFullYear())
                            </script> Chama Serviço. Todos os direitos reservados.
                            <a href="#" data-bs-toggle="modal" data-bs-target="#modalPoliticaPrivacidade" class="ms-2 text-decoration-underline" style="color:#21C47E;">Política de Privacidade</a>
                        </p>
                    </div>
                </div>
            </div>
    </footer>

    <!-- Modal Política de Privacidade -->
    <div class="modal fade" id="modalPoliticaPrivacidade" tabindex="-1" aria-labelledby="modalPoliticaPrivacidadeLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalPoliticaPrivacidadeLabel">
                        <i class="bi bi-shield-lock-fill me-2"></i>Política de Privacidade
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body" style="max-height:60vh; overflow-y:auto;">
                    <h6>1. Introdução</h6>
                    <p>
                        Esta é uma política de privacidade fictícia para fins de demonstração. Ao utilizar a plataforma Chama Serviço, você concorda com os termos aqui descritos.
                    </p>
                    <h6>2. Coleta de Dados</h6>
                    <p>
                        Coletamos informações fornecidas por você no cadastro, como nome, e-mail, telefone e dados de serviços solicitados ou prestados.
                    </p>
                    <h6>3. Uso das Informações</h6>
                    <p>
                        Os dados são utilizados para facilitar a conexão entre clientes e prestadores, melhorar a experiência do usuário e enviar comunicações relevantes.
                    </p>
                    <h6>4. Compartilhamento</h6>
                    <p>
                        Não compartilhamos suas informações pessoais com terceiros, exceto quando necessário para a execução dos serviços ou por exigência legal.
                    </p>
                    <h6>5. Segurança</h6>
                    <p>
                        Adotamos medidas técnicas e administrativas para proteger seus dados contra acessos não autorizados.
                    </p>
                    <h6>6. Direitos do Usuário</h6>
                    <p>
                        Você pode solicitar a atualização ou exclusão de seus dados pessoais a qualquer momento, entrando em contato com nosso suporte.
                    </p>
                    <h6>7. Alterações</h6>
                    <p>
                        Esta política pode ser alterada a qualquer momento. Recomendamos revisá-la periodicamente.
                    </p>
                    <h6>8. Contato</h6>
                    <p>
                        Em caso de dúvidas, entre em contato pelo e-mail: contato@chamaservico.com
                    </p>
                    <p class="text-muted small mt-3">Esta política é fictícia e serve apenas para fins de exemplo.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-up-short"></i>
    </a>

    <!-- Vendor JS Files -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="assets/js/homepage.js"></script>
</body>

</html>