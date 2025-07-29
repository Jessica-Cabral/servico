<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Tipos de Serviços | Chama Serviço</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600|Poppins:400,600,700" rel="stylesheet">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <!-- AOS -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/responsive.css" rel="stylesheet">
    <style>
        /* Custom improvements for professional and responsive look */
        body {
            background: #f8f9fa;
            font-family: 'Poppins', 'Open Sans', Arial, sans-serif;
        }
        .navbar, #header {
            background: linear-gradient(90deg, #2c3e50 0%, #3498db 100%);
            box-shadow: 0 2px 8px rgba(44,62,80,0.08);
        }
        .navbar .nav-link, #header .nav-link {
            color: #fff !important;
            font-weight: 500;
            letter-spacing: 0.5px;
        }
        .navbar .nav-link.active, #header .nav-link.active {
            color: #ffd700 !important;
        }
        .banner {
            background: linear-gradient(135deg, #3498db 0%, #6dd5fa 100%);
            color: #fff;
            padding: 60px 0 30px 0;
            border-radius: 0 0 30px 30px;
            margin-bottom: 2rem;
        }
        .banner h2 {
            font-weight: 700;
            font-size: 2.2rem;
        }
        .projects .projects-item {
            margin-bottom: 2rem;
        }
        .projects-wrap {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 16px rgba(44,62,80,0.08);
            overflow: hidden;
            transition: box-shadow 0.2s;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .projects-wrap:hover {
            box-shadow: 0 8px 32px rgba(44,62,80,0.15);
            transform: translateY(-4px) scale(1.01);
        }
        .projects-info {
            padding: 1.2rem 1rem 1rem 1rem;
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .projects-info h4 {
            font-size: 1.2rem;
            font-weight: 600;
            color: #2c3e50;
        }
        .projects-info p {
            color: #3498db;
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        .projects-links a {
            color: #3498db;
            margin-right: 10px;
            font-size: 1.3rem;
            transition: color 0.2s;
        }
        .projects-links a:hover {
            color: #2c3e50;
        }
        @media (max-width: 991.98px) {
            .banner {
                padding: 40px 0 20px 0;
                border-radius: 0 0 20px 20px;
            }
            .projects-info {
                padding: 1rem 0.7rem 0.7rem 0.7rem;
            }
        }
        @media (max-width: 767.98px) {
            .banner h2 {
                font-size: 1.5rem;
            }
            .projects-info h4 {
                font-size: 1rem;
            }
        }
        .footer-top {
            background: #2c3e50;
            color: #fff;
            padding: 40px 0 20px 0;
        }
        .footer-top h4 {
            color: #ffd700;
        }
        .footer-top a, .footer-top a:visited {
            color: #fff;
            text-decoration: none;
        }
        .footer-top a:hover {
            color: #ffd700;
        }
        .footer-newsletter input[type="email"] {
            border-radius: 20px 0 0 20px;
            border: none;
            padding: 8px 16px;
        }
        .footer-newsletter input[type="submit"] {
            border-radius: 0 20px 20px 0;
            border: none;
            background: #ffd700;
            color: #2c3e50;
            font-weight: 600;
            padding: 8px 20px;
        }
        .back-to-top {
            background: #3498db;
            color: #fff;
            border-radius: 50%;
            width: 44px;
            height: 44px;
            font-size: 1.5rem;
            box-shadow: 0 2px 8px rgba(44,62,80,0.15);
        }
        .back-to-top:hover {
            background: #2c3e50;
            color: #ffd700;
        }
    </style>
</head>
<body>
<!-- INÍCIO - CABEÇALHO -->
<header id="header" class="fixed-top d-flex align-items-center shadow-sm">
  <div class="container d-flex align-items-center justify-content-between">
    <div class="logo">
      <a href="index.html">
        <img src="assets/img/logo.png" alt="logo" style="height: 40px;">
      </a>
    </div>
    <nav id="navbar" class="navbar">
      <ul class="mb-0">
        <li><a class="nav-link active" href="index.html">Home</a></li>
        <li><a class="nav-link" href="Servicos.html">Serviços</a></li>
        <li><a class="nav-link" href="CadUsuario.html">Crie sua conta</a></li>
        <li><a class="nav-link" href="Login.html">Entrar</a></li>
      </ul>
      <i class="bi bi-list mobile-nav-toggle"></i>
    </nav>
  </div>
</header>
<!-- FIM - CABEÇALHO -->

<main id="main" style="margin-top: 80px;">
    <!-- Banner -->
    <section id="banner" class="banner text-center">
        <div class="container">
            <h2 data-aos="fade-down">Principais serviços pedidos</h2>
            <p data-aos="fade-up" class="mb-0">Serviços mais realizados em cada categoria</p>
            <nav aria-label="breadcrumb" class="d-flex justify-content-center mt-3">
                <ol class="breadcrumb bg-transparent p-0 m-0">
                    <li class="breadcrumb-item"><a href="index.html" class="text-white-50">Home</a></li>
                    <li class="breadcrumb-item active text-white" aria-current="page">Mais pedidos</li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Serviços em Destaque -->
    <section id="projects" class="projects py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12 text-center">
                    <ul id="projects-filters" class="list-inline">
                        <li class="list-inline-item filter-active" data-filter="*">Todos</li>
                        <li class="list-inline-item" data-filter=".filter-app">Eletricista</li>
                        <li class="list-inline-item" data-filter=".filter-card">Serviços Domésticos</li>
                        <li class="list-inline-item" data-filter=".filter-web">Tecnologia</li>
                    </ul>
                </div>
            </div>
            <div class="row projects-container g-4" data-aos="fade-up">
                <!-- Card 1 -->
                <div class="col-lg-4 col-md-6 projects-item filter-app">
                    <div class="projects-wrap h-100">
                        <img src="assets/img/projects/eletricista.png" class="img-fluid" alt="Eletricista">
                        <div class="projects-info">
                            <h4>Eletricista</h4>
                            <p>Solicitar Orçamento</p>
                            <div class="projects-links">
                                <a href="assets/img/projects/projects-1.jpg" data-gallery="projectsGallery" class="projects-lightbox" title="Eletricista"><i class="bx bx-plus"></i></a>
                                <a href="projects-details.html" title="Mais Detalhes"><i class="bx bx-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 2 -->
                <div class="col-lg-4 col-md-6 projects-item filter-card">
                    <div class="projects-wrap h-100">
                        <img src="assets/img/projects/domestico.jpg" class="img-fluid" alt="Serviços Domésticos">
                        <div class="projects-info">
                            <h4>Serviços Domésticos</h4>
                            <p>Solicitar Orçamento</p>
                            <div class="projects-links">
                                <a href="assets/img/projects/projects-2.jpg" data-gallery="projectsGallery" class="projects-lightbox" title="Serviços Domésticos"><i class="bx bx-plus"></i></a>
                                <a href="projects-details.html" title="Mais Detalhes"><i class="bx bx-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 3 -->
                <div class="col-lg-4 col-md-6 projects-item filter-web">
                    <div class="projects-wrap h-100">
                        <img src="assets/img/projects/tecnologia.jpg" class="img-fluid" alt="Tecnologia">
                        <div class="projects-info">
                            <h4>Tecnologia</h4>
                            <p>Solicitar Orçamento</p>
                            <div class="projects-links">
                                <a href="assets/img/projects/projects-3.jpg" data-gallery="projectsGallery" class="projects-lightbox" title="Tecnologia"><i class="bx bx-plus"></i></a>
                                <a href="projects-details.html" title="Mais Detalhes"><i class="bx bx-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 4 -->
                <div class="col-lg-4 col-md-6 projects-item filter-card">
                    <div class="projects-wrap h-100">
                        <img src="assets/img/projects/trasportentrega.jpg" class="img-fluid" alt="Transporte e Logística">
                        <div class="projects-info">
                            <h4>Transporte e Logística</h4>
                            <p>Solicitar Orçamento</p>
                            <div class="projects-links">
                                <a href="assets/img/projects/projects-4.jpg" data-gallery="projectsGallery" class="projects-lightbox" title="Transporte e Logística"><i class="bx bx-plus"></i></a>
                                <a href="projects-details.html" title="Mais Detalhes"><i class="bx bx-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 5 -->
                <div class="col-lg-4 col-md-6 projects-item filter-web">
                    <div class="projects-wrap h-100">
                        <img src="assets/img/projects/saudebemestar.jpg" class="img-fluid" alt="Saúde e Bem-Estar">
                        <div class="projects-info">
                            <h4>Saúde e Bem-Estar</h4>
                            <p>Solicitar Orçamento</p>
                            <div class="projects-links">
                                <a href="assets/img/projects/projects-5.jpg" data-gallery="projectsGallery" class="projects-lightbox" title="Saúde e Bem-Estar"><i class="bx bx-plus"></i></a>
                                <a href="projects-details.html" title="Mais Detalhes"><i class="bx bx-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Card 6 -->
                <div class="col-lg-4 col-md-6 projects-item filter-app">
                    <div class="projects-wrap h-100">
                        <img src="assets/img/projects/aprendizado.jpg" class="img-fluid" alt="Educação e Aprendizagem">
                        <div class="projects-info">
                            <h4>Educação e Aprendizagem</h4>
                            <p>Solicitar Orçamento</p>
                            <div class="projects-links">
                                <a href="assets/img/projects/projects-6.jpg" data-gallery="projectsGallery" class="projects-lightbox" title="Educação e Aprendizagem"><i class="bx bx-plus"></i></a>
                                <a href="projects-details.html" title="Mais Detalhes"><i class="bx bx-link"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Adicione mais cards conforme necessário -->
            </div>
        </div>
    </section>
</main>

<!--START - FOOTER-->
<footer id="footer">
    <div class="footer-top">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-3 col-md-6 footer-info">
                    <img src="assets/img/logo-2.png" alt="logo-2" style="height: 36px;">
                    <p class="mt-2 small">Encontre profissionais qualificados para qualquer serviço, com segurança e praticidade.</p>
                </div>
                <div class="col-lg-3 col-md-6 footer-links">
                    <h4>Links Úteis</h4>
                    <ul class="list-unstyled">
                        <li><a href="#">Home</a></li>
                        <li><a href="about.html">Sobre nós</a></li>
                        <li><a href="services.html">Serviços</a></li>
                        <li><a href="#">Termos de serviço</a></li>
                        <li><a href="#">Política de Privacidade</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 footer-contact">
                    <h4>Entre em contato</h4>
                    <p>
                        <strong>Endereço:</strong> Rua Exemplo, 123<br>
                        São Paulo, SP, Brasil<br>
                        <strong>Telefone:</strong> (11) 1234-5678<br>
                        <strong>Email:</strong> contato@chamaservico.com<br>
                    </p>
                    <div class="social-links mt-2">
                        <a href="#" class="twitter"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="facebook"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="instagram"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="linkedin"><i class="bi bi-linkedin"></i></a>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6 footer-newsletter">
                    <h4>Nossa Newsletter</h4>
                    <p>Receba novidades e promoções exclusivas em seu e-mail.</p>
                    <form action="" method="post" class="d-flex">
                        <input type="email" name="email" class="form-control" placeholder="Seu e-mail">
                        <input type="submit" value="Inscreva-se" class="btn btn-warning ms-2">
                    </form>
                </div>
            </div>
        </div>
    </div>
</footer>
<a href="#" class="back-to-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
</a>

<!-- Vendor JS Files -->
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://unpkg.com/isotope-layout@3/dist/isotope.pkgd.min.js"></script>
<script src="assets/vendor/swiper/swiper-bundle.min.js"></script>
<script src="assets/vendor/glightbox/js/glightbox.min.js"></script>
<script src="assets/vendor/purecounter/purecounter.js"></script>
<script src="assets/vendor/waypoints/noframework.waypoints.js"></script>
<script src="assets/js/main.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>