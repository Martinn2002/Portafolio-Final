const swiper = new Swiper(".problemas-slider", {
    slidesPerView: 'auto',
    spaceBetween: 30,
    loop: false,

    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },

    breakpoints: {
        768: {
            slidesPerView: 2,
        },
        1200: {
            slidesPerView: 3,
        }
    }
});

const galeriaProyecto = new Swiper(".galeria-proyecto-slider", {
    effect: "coverflow",
    centeredSlides: true,
    loop: false,
    grabCursor: true,
    autoHeight: true,
    slidesPerView: "auto",

    coverflowEffect: {
        rotate: 0,
        stretch: 0,
        depth: 200,
        modifier: 1.5,
        scale: 0.9,
        slideShadows: true,
    },

    pagination: {
        el: ".galeria-proyecto-slider .swiper-pagination",
        clickable: true,
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const items = document.querySelectorAll('.ul-tecnologias li');
    const paneles = document.querySelectorAll('.panel-tecnologias');
    const indicador = document.querySelector('.indicador-tecnologia');
    const lista = document.querySelector('.ul-tecnologias');

    function moverIndicador(item) {
        const listaRect = lista.getBoundingClientRect();
        const itemRect = item.getBoundingClientRect();
        const offset = (itemRect.top - listaRect.top) + (itemRect.height / 2);
        indicador.style.top = offset + 'px';
    }

    function cambiarPanel(categoria) {
        const wrapper = document.querySelector('.paneles-tecnologia-wrapper');
        const panelNuevo = document.querySelector(`.panel-tecnologias[data-panel="${categoria}"]`);

        panelNuevo.style.display = '';
        panelNuevo.style.opacity = 0;

        const alturaNueva = panelNuevo.scrollHeight;
        console.log('Categoria:', categoria, '| Altura medida:', alturaNueva, '| Altura wrapper actual:', wrapper.offsetHeight);

        wrapper.style.height = wrapper.offsetHeight + 'px';

        requestAnimationFrame(() => {
            wrapper.style.height = alturaNueva + 'px';
            panelNuevo.style.transition = 'opacity 0.3s ease';
            panelNuevo.style.opacity = 1;
        });

        paneles.forEach(function (panel) {
            if (panel.dataset.panel !== categoria) {
                panel.style.opacity = 0;
                setTimeout(() => { panel.style.display = 'none'; }, 300);
            }
        });

        wrapper.addEventListener('transitionend', function liberar(e) {
            if (e.propertyName === 'height') {
                wrapper.style.height = 'auto';
                wrapper.removeEventListener('transitionend', liberar);
            }
        });
    }
    // Posicionar el indicador en el item activo al cargar
    const activoInicial = document.querySelector('.ul-tecnologias li.active');
    if (activoInicial) moverIndicador(activoInicial);

    items.forEach(function (item) {
        item.addEventListener('click', function () {
            const categoria = this.dataset.categoria;

            items.forEach(function (i) {
                i.classList.remove('active');
                i.querySelector('p').classList.remove('active');
            });

            this.classList.add('active');
            this.querySelector('p').classList.add('active');

            moverIndicador(this);
            cambiarPanel(categoria);
        });
    });

    // Reposicionar si cambia el tamaño de ventana
    window.addEventListener('resize', function () {
        const activo = document.querySelector('.ul-tecnologias li.active');
        if (activo) moverIndicador(activo);
    });

});

document.addEventListener('DOMContentLoaded', function () {
    const lightbox = GLightbox({
        selector: '.lightbox-img'
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const contenedor = document.getElementById('contenedor-proyectos');
    const paginador = document.getElementById('paginador-proyectos');
    const numeroPagina = document.getElementById('numero-pagina-actual');
    const botonesFiltro = document.querySelectorAll('.filtros-proyectos button');
    const flechas = document.querySelectorAll('.flecha-paginador');

    function cargarProyectos(categoria, pagina) {
        const formData = new FormData();
        formData.append('action', 'filtrar_proyectos');
        formData.append('categoria', categoria);
        formData.append('pagina', pagina);

        fetch(martin_ajax.url, {
            method: 'POST',
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                contenedor.innerHTML = data.html;

                paginador.dataset.paginaActual = data.pagina;
                paginador.dataset.totalPaginas = data.total_pages;
                paginador.dataset.categoriaActual = categoria;
                numeroPagina.textContent = data.pagina;

                const flechaAnterior = paginador.querySelector('[data-direccion="anterior"]');
                const flechaSiguiente = paginador.querySelector('[data-direccion="siguiente"]');

                flechaAnterior.classList.toggle('inactive', data.pagina <= 1);
                flechaAnterior.classList.toggle('active', data.pagina > 1);

                flechaSiguiente.classList.toggle('inactive', data.pagina >= data.total_pages);
                flechaSiguiente.classList.toggle('active', data.pagina < data.total_pages);
            })
            .catch(err => console.error('Error al cargar proyectos:', err));
    }

    // Click en filtros
    botonesFiltro.forEach(function (boton) {
        boton.addEventListener('click', function () {
            botonesFiltro.forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            const categoria = this.dataset.categoria;
            cargarProyectos(categoria, 1);
        });
    });

    // Click en flechas de paginación
    flechas.forEach(function (flecha) {
        flecha.addEventListener('click', function () {
            if (this.classList.contains('inactive')) return;

            const categoriaActual = paginador.dataset.categoriaActual;
            let paginaActual = parseInt(paginador.dataset.paginaActual);
            const totalPaginas = parseInt(paginador.dataset.totalPaginas);

            if (this.dataset.direccion === 'siguiente' && paginaActual < totalPaginas) {
                paginaActual++;
            } else if (this.dataset.direccion === 'anterior' && paginaActual > 1) {
                paginaActual--;
            }

            cargarProyectos(categoriaActual, paginaActual);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
  const btnMenu = document.querySelector('.btn-menu');
  const nav = document.querySelector('.header-inner nav');

  if (btnMenu && nav) {
    btnMenu.addEventListener('click', function () {
      const abierto = nav.classList.toggle('abierto');
      btnMenu.classList.toggle('abierto', abierto);
      btnMenu.setAttribute('aria-expanded', abierto);
    });
  }
});