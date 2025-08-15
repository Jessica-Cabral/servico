(function() {
  "use strict";

  /* Selector helper function */
  const select = (el, all = false) => {
    el = el.trim()
    if (all) {
      return [...document.querySelectorAll(el)]
    } else {
      return document.querySelector(el)
    }
  }

  /* Event listener function */
  const on = (type, el, listener, all = false) => {
    let selectEl = select(el, all);
    if (selectEl) {
      if (all) {
        selectEl.forEach(e => e.addEventListener(type, listener))
      } else {
        selectEl.addEventListener(type, listener)
      }
    }
  }

  /* Scroll event listener */
  const onscroll = (el, listener) => el.addEventListener('scroll', listener)

  /* Scrolls to an element with header offset */
  const scrollto = (el) => {
    let header = select('#header')
    let offset = header.offsetHeight

    let elementPos = select(el).offsetTop
    window.scrollTo({
      top: elementPos - offset,
      behavior: 'smooth'
    })
  }

  /* Toggle .header-scrolled class to #header when page is scrolled */
  let selectHeader = select('#header')
  if (selectHeader) {
    const headerScrolled = () => {
      (window.scrollY > 100) ? 
        selectHeader.classList.add('header-scrolled') : 
        selectHeader.classList.remove('header-scrolled')
    }
    window.addEventListener('load', headerScrolled)
    onscroll(document, headerScrolled)
  }

  /* Back to top button */
  let backtotop = select('.back-to-top')
  if (backtotop) {
    const toggleBacktotop = () => {
      (window.scrollY > 100) ?
        backtotop.classList.add('active') :
        backtotop.classList.remove('active')
    }
    window.addEventListener('load', toggleBacktotop)
    onscroll(document, toggleBacktotop)
  }

  /* Mobile nav toggle */
  on('click', '.mobile-nav-toggle', function(e) {
    select('#navbar').classList.toggle('navbar-mobile')
    this.classList.toggle('bi-list')
    this.classList.toggle('bi-x')
  })

  /* Mobile nav dropdowns activate */
  on('click', '.navbar .dropdown > a', function(e) {
    if (select('#navbar').classList.contains('navbar-mobile')) {
      e.preventDefault()
      this.nextElementSibling.classList.toggle('dropdown-active')
    }
  }, true)

  /* Scrool with ofset on links with a class name .scrollto */
  on('click', '.scrollto', function(e) {
    if (select(this.hash)) {
      e.preventDefault()

      let navbar = select('#navbar')
      if (navbar.classList.contains('navbar-mobile')) {
        navbar.classList.remove('navbar-mobile')
        let navbarToggle = select('.mobile-nav-toggle')
        navbarToggle.classList.toggle('bi-list')
        navbarToggle.classList.toggle('bi-x')
      }
      scrollto(this.hash)
    }
  }, true)

  /* Scroll with ofset on page load with hash links in the url */
  window.addEventListener('load', () => {
    if (window.location.hash) {
      if (select(window.location.hash)) scrollto(window.location.hash)
    }
  });

  /* Initiate projects lightbox */
  const glightbox = GLightbox({
    selector: '.glightbox'
  });

  /* Skills animation */
  let skilsContent = select('.skills-content');
  if (skilsContent) {
    new Waypoint({
      element: skilsContent,
      offset: '80%',
      handler: function(direction) {
        let progress = select('.progress .progress-bar', true);
        progress.forEach((el) => {
          el.style.width = el.getAttribute('aria-valuenow') + '%'
        });
      }
    })
  }

  /* Clients Slider */
  new Swiper('.clients-slider', {
    speed: 400,
    loop: true,
    autoplay: {
      delay: 5000, disableOnInteraction: false
    },
    slidesPerView: 'auto',
    pagination: {
      el: '.swiper-pagination', type: 'bullets', clickable: true
    },
    breakpoints: {
      320: {
        slidesPerView: 2, spaceBetween: 40
      },
      480: {
        slidesPerView: 3, spaceBetween: 60
      },
      640: {
        slidesPerView: 4, spaceBetween: 80
      },
      992: {
        slidesPerView: 6, spaceBetween: 120
      }
    }
  });

  /* Porfolio isotope and filter */
  window.addEventListener('load', () => {
    let projectsContainer = select('.projects-container');
    if (projectsContainer) {
      let projectsIsotope = new Isotope(projectsContainer, {
        itemSelector: '.projects-item',
        layoutMode: 'fitRows'
      });

      let projectsFilters = select('#projects-filters li', true);

      on('click', '#projects-filters li', function(e) {
        e.preventDefault();
        projectsFilters.forEach(el => el.classList.remove('filter-active'));
        this.classList.add('filter-active');

        projectsIsotope.arrange({
          filter: this.getAttribute('data-filter')
        });
        projectsIsotope.on('arrangeComplete', () => AOS.refresh());
      }, true);
    }

  });

  /* Initiate projects lightbox */
  const projectsLightbox = GLightbox({
    selector: '.projects-lightbox'
  });

  /* projects details slider */
  new Swiper('.projects-details-slider', {
    speed: 400,
    loop: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false
    },
    pagination: {
      el: '.swiper-pagination',
      type: 'bullets',
      clickable: true
    }
  });

  /* Animation on scroll */
  window.addEventListener('load', () => {
    AOS.init({
      duration: 1000,
      easing: 'ease-in-out',
      once: true,
      mirror: false
    })
  });

  // Inicializações adicionais seguras
  document.addEventListener('DOMContentLoaded', function() {
    // Habilita dropdowns (Bootstrap 5 usa data-bs-toggle)
    try {
      var dropdownTriggerList = [].slice.call(document.querySelectorAll('.dropdown-toggle'));
      dropdownTriggerList.forEach(function(dd) {
        // Apenas usa API do Bootstrap se disponível
        if (typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
          new bootstrap.Dropdown(dd);
        }
      });
    } catch (e) { console.warn('Dropdown init falhou', e); }

    // Funções globais reutilizáveis para a aplicação
    window.app = window.app || {};

    // substitui impl. anterior por versão mais robusta
    window.app.verDetalhes = async function(servicoId) {
      if (!servicoId) return;
      try {
          const url = `/servico/prestador/detalhes-servico?id=${encodeURIComponent(servicoId)}`;
          const response = await fetch(url, {
              credentials: 'same-origin',
              headers: { 'X-Requested-With': 'XMLHttpRequest' }
          });
          if (response.redirected) {
              window.location.href = response.url;
              return;
          }
          if (!response.ok) throw new Error(`Status ${response.status}`);
          const html = await response.text();
          const container = document.getElementById('detalhesContent');
          container.innerHTML = html;
          const modalEl = document.getElementById('detalhesModal');
          new bootstrap.Modal(modalEl, { keyboard: true }).show();
      } catch (err) {
          console.error('verDetalhes erro:', err);
          alert('Não foi possível carregar detalhes. Tente novamente.');
      }
    };

    window.app.enviarProposta = function(servicoId) {
      var fld = document.getElementById('servicoId');
      if (fld) fld.value = servicoId;
      // injeta csrf automático se faltar (backup)
      var form = document.getElementById('formEnviarProposta');
      if (form && !form.querySelector('input[name="csrf_token"]')) {
        var token = (window.APP && window.APP.csrf) ? window.APP.csrf : '';
        var inp = document.createElement('input'); inp.type='hidden'; inp.name='csrf_token'; inp.value=token;
        form.appendChild(inp);
      }
      var m = document.getElementById('propostaModal'); if (m && typeof bootstrap !== 'undefined') new bootstrap.Modal(m).show();
    };

    // Se existir o form de proposta, previne duplo submit e valida CSRF no cliente (com aviso)
    var formEnviar = document.getElementById('formEnviarProposta');
    if (formEnviar) {
      formEnviar.addEventListener('submit', function(e){
        var token = formEnviar.querySelector('input[name="csrf_token"]');
        if (!token || !token.value) {
          e.preventDefault();
          alert('Token CSRF ausente. Atualize a página e tente novamente.');
          return false;
        }
        // desativa submit button para evitar duplicação
        var btn = formEnviar.querySelector('button[type="submit"]');
        if (btn) btn.disabled = true;
      });
    }
  });

})()