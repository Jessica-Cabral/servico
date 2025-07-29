// Inicializa AOS (Animate On Scroll)
AOS.init({
    duration: 800,
    easing: 'ease-in-out',
    once: true
});

// Botão voltar ao topo
window.addEventListener('scroll', function() {
    var backToTop = document.querySelector('.back-to-top');
    if (window.pageYOffset > 300) {
        backToTop.classList.add('active');
    } else {
        backToTop.classList.remove('active');
    }
});

// Efeito de onda nos botões
document.addEventListener('click', function(e) {
    if (e.target.closest('.wave-effect')) {
        let button = e.target.closest('.wave-effect');
        let ripple = document.createElement('span');
        ripple.className = 'ripple';

        let rect = button.getBoundingClientRect();
        let size = Math.max(rect.width, rect.height);
        let x = e.clientX - rect.left - size/2;
        let y = e.clientY - rect.top - size/2;

        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';

        button.appendChild(ripple);

        setTimeout(() => {
            ripple.remove();
        }, 600);
    }
});

// Suaviza scroll para links internos
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();

        const targetId = this.getAttribute('href');
        if(targetId === '#') return;

        const targetElement = document.querySelector(targetId);
        if(targetElement) {
            window.scrollTo({
                top: targetElement.offsetTop - 80,
                behavior: 'smooth'
            });
        }
    });
});

// Carrega recursos críticos primeiro
document.addEventListener('DOMContentLoaded', function() {
    // Pré-carrega imagens importantes
    const imagesToLoad = [
        'assets/img/marketplace-hero.svg',
        'assets/img/app-mockup.png',
        'assets/img/testimonials/1.jpg',
        'assets/img/testimonials/2.jpg'
    ];

    imagesToLoad.forEach(img => {
        const image = new Image();
        image.src = img;
    });
});
