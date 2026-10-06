const escapeHTML = (value = '') => String(value).replace(/[&<>'"]/g, (char) => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[char]));
const iconArrow = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" /></svg>';

const cargarJSON = async (ruta) => {
    const respuesta = await fetch(ruta);
    if (!respuesta.ok) throw new Error(`No se pudo cargar ${ruta}`);
    return respuesta.json();
};

const crearTarjetaProyecto = (proyecto) => `
    <div class="col-12 col-sm-6 col-md-4 mb-4">
        <a class="enlace-tarjeta-proyecto" href="proyecto.html?id=${encodeURIComponent(proyecto.id)}">
            <article class="tarjeta-proyecto">
                <div class="imagen-tarjeta-proyecto">
                    <span class="categoria">${escapeHTML(proyecto.categoria)}</span>
                    <img src="${escapeHTML(proyecto.imagen)}" alt="${escapeHTML(proyecto.imagenAlt || proyecto.titulo)}" loading="lazy">
                </div>
                <div class="texto-tarjeta-proyecto">
                    <h3>${escapeHTML(proyecto.titulo)}</h3>
                    <p>${escapeHTML(proyecto.descripcion)}</p>
                    <ul class="tags-tarjeta list-unstyled d-flex flex-wrap mb-0 align-items-center">
                        ${(proyecto.tags || []).map((tag) => `<li><span class="p-pequeno">${escapeHTML(tag)}</span></li>`).join('')}
                    </ul>
                    <div class="link-flecha d-flex"><span class="p-mediano boton-detalle">Detalles ${iconArrow}</span></div>
                </div>
            </article>
        </a>
    </div>`;

const mostrarError = (elemento, mensaje) => {
    if (elemento) elemento.innerHTML = `<div class="col-12"><p class="p-mediano">${mensaje}</p></div>`;
};

const renderizarProyectos = async () => {
    const contenedor = document.querySelector('[data-proyectos]');
    if (!contenedor) return;
    try {
        const datos = await cargarJSON('data/proyectos.json');
        const proyectos = datos.proyectos || [];
        const esListado = contenedor.id === 'contenedor-proyectos';
        let categoriaActual = 'Todos';
        let paginaActual = 1;
        const porPagina = 6;
        const botones = [...document.querySelectorAll('.filtros-proyectos button')];
        const paginador = document.querySelector('#paginador-proyectos');

        const pintar = () => {
            const filtrados = categoriaActual === 'Todos' ? proyectos : proyectos.filter((proyecto) => proyecto.categoria === categoriaActual);
            const totalPaginas = Math.max(1, Math.ceil(filtrados.length / porPagina));
            paginaActual = Math.min(paginaActual, totalPaginas);
            const visibles = esListado ? filtrados.slice((paginaActual - 1) * porPagina, paginaActual * porPagina) : filtrados.slice(0, 3);
            contenedor.innerHTML = visibles.length ? visibles.map(crearTarjetaProyecto).join('') : '<div class="col-12"><p>No hay proyectos en esta categoría aún.</p></div>';
            if (paginador) {
                paginador.querySelector('#numero-pagina-actual').textContent = paginaActual;
                paginador.querySelector('[data-direccion="anterior"]').classList.toggle('inactive', paginaActual === 1);
                paginador.querySelector('[data-direccion="siguiente"]').classList.toggle('inactive', paginaActual >= totalPaginas);
            }
        };

        botones.forEach((boton) => boton.addEventListener('click', () => {
            botones.forEach((item) => item.classList.remove('active'));
            boton.classList.add('active');
            categoriaActual = boton.dataset.categoria;
            paginaActual = 1;
            pintar();
        }));
        paginador?.querySelectorAll('[data-direccion]').forEach((flecha) => flecha.addEventListener('click', () => {
            if (flecha.classList.contains('inactive')) return;
            paginaActual += flecha.dataset.direccion === 'siguiente' ? 1 : -1;
            pintar();
        }));
        pintar();
    } catch (error) {
        mostrarError(contenedor, 'No pudimos cargar los proyectos. Revisa data/proyectos.json.');
        console.error(error);
    }
};

const renderizarTecnologias = async () => {
    const contenedor = document.querySelector('[data-tecnologias]');
    if (!contenedor) return;
    try {
        const datos = await cargarJSON('data/tecnologias.json');
        const categorias = datos.categorias || [];
        const lista = contenedor.querySelector('.ul-tecnologias');
        const paneles = contenedor.querySelector('.paneles-tecnologia-wrapper');
        categorias.forEach((categoria, indice) => {
            const boton = document.createElement('li');
            boton.dataset.categoria = categoria.nombre;
            boton.className = indice === 0 ? 'active' : '';
            boton.innerHTML = `<p class="${indice === 0 ? 'active' : ''}">${escapeHTML(categoria.nombre)}</p>`;
            lista.appendChild(boton);
            const panel = document.createElement('div');
            panel.className = 'panel-tecnologias row';
            panel.dataset.panel = categoria.nombre;
            panel.hidden = indice !== 0;
            panel.innerHTML = categoria.items.map((tec) => `<div class="col-12 col-sm-6 col-md-4 mb-4 d-flex"><article class="tarjeta-tecnologia"><p class="p-titulo">${escapeHTML(tec.nombre)}</p><p class="p-mediano">${escapeHTML(tec.descripcion)}</p><div class="barra-progreso-container"><div class="barra-progreso" style="width:${Math.max(0, Math.min(100, Number(tec.porcentaje) || 0))}%"></div></div><ul class="list-unstyled d-flex mb-0 justify-content-between"><li class="p-pequeno">Dominio</li><li class="p-pequeno">${tec.porcentaje}%</li></ul></article></div>`).join('');
            paneles.appendChild(panel);
        });
        const indicador = lista.querySelector('.indicador-tecnologia');
        const items = [...lista.querySelectorAll('li')];
        const moverIndicador = (item) => {
            if (!indicador || !item) return;
            indicador.style.top = `${item.offsetTop + (item.offsetHeight / 2)}px`;
        };
        requestAnimationFrame(() => moverIndicador(items[0]));
        items.forEach((item) => item.addEventListener('click', () => {
            items.forEach((elemento) => { elemento.classList.remove('active'); elemento.querySelector('p').classList.remove('active'); });
            item.classList.add('active');
            item.querySelector('p').classList.add('active');
            moverIndicador(item);
            paneles.querySelectorAll('.panel-tecnologias').forEach((panel) => { panel.hidden = panel.dataset.panel !== item.dataset.categoria; });
        }));
        window.addEventListener('resize', () => {
            moverIndicador(lista.querySelector('li.active'));
        });
    } catch (error) {
        contenedor.querySelector('.paneles-tecnologia-wrapper').innerHTML = '<p class="p-mediano">No pudimos cargar las tecnologías.</p>';
        console.error(error);
    }
};

const renderizarDetalle = async () => {
    const contenedor = document.querySelector('[data-detalle-proyecto]');
    if (!contenedor) return;
    try {
        const datos = await cargarJSON('data/proyectos.json');
        const id = new URLSearchParams(window.location.search).get('id');
        const proyecto = (datos.proyectos || []).find((item) => item.id === id) || datos.proyectos?.[0];
        if (!proyecto) throw new Error('No hay proyectos disponibles');
        document.title = `${proyecto.titulo} · Portafolio`;
        const labels = proyecto.categoria === 'Diseño / Rediseño' ? { problemas: 'Problemas detectados', investigacion: 'Auditoría visual', desafios: 'Decisiones de diseño' } : { problemas: 'Problemas', investigacion: 'Investigación', desafios: 'Desafíos y Soluciones' };
        contenedor.innerHTML = `<div class="row"><div class="col-md-5"><a href="proyectos.html" class="d-flex align-items-center gap-2 mb-4">← Volver a proyectos</a><h1>${escapeHTML(proyecto.titulo)}</h1><ul class="tags-tarjeta list-unstyled d-flex flex-wrap align-items-center">${(proyecto.tags || []).map((tag) => `<li><span class="p-pequeno">${escapeHTML(tag)}</span></li>`).join('')}</ul><p>${escapeHTML(proyecto.descripcion)}</p><ul class="botones-hero list-unstyled d-flex flex-wrap align-items-center"><li><a href="${escapeHTML(proyecto.urlSitio || '#')}" target="_blank" rel="noopener"><button>Ver sitio</button></a></li><li><a href="${escapeHTML(proyecto.urlCodigo || '#')}" target="_blank" rel="noopener"><button>Ver código</button></a></li></ul></div><div class="col-md-7"><img class="foto-main-proyecto w-100" src="${escapeHTML(proyecto.imagen)}" alt="${escapeHTML(proyecto.imagenAlt || proyecto.titulo)}"></div></div><div class="row mt-4"><div class="col-md-4"><h2>Contexto</h2><p>${escapeHTML(proyecto.contexto)}</p></div><div class="col-md-8"><div class="row">${[['Público objetivo', proyecto.publicoObjetivo], ['Objetivo del proyecto', proyecto.objetivoProyecto], ['Duración', proyecto.duracion], ['Rol', proyecto.rol]].map(([titulo, texto]) => `<div class="col-md-6 mb-3"><div class="tarjeta-intereses"><div class="texto-tarjeta-intereses text-center"><p class="subtitulo">${titulo}</p><p class="p-mediano text-start">${escapeHTML(texto)}</p></div></div></div>`).join('')}</div></div></div><div class="row mt-4"><h2>${labels.problemas}</h2><p>${proyecto.problemas?.length ? proyecto.problemas.map((item) => `${escapeHTML(item.titulo)}: ${escapeHTML(item.texto)}`).join(' · ') : 'Este contenido se puede completar desde data/proyectos.json.'}</p></div><div class="row mt-4"><h2>${labels.investigacion}</h2><p>${proyecto.investigacion?.length ? proyecto.investigacion.map((item) => `${escapeHTML(item.titulo)}: ${escapeHTML(item.texto)}`).join(' · ') : 'Este contenido se puede completar desde data/proyectos.json.'}</p></div><div class="row mt-4"><h2>${labels.desafios}</h2><p>${proyecto.desafios?.length ? proyecto.desafios.map((item) => `${escapeHTML(item.problema)} → ${escapeHTML(item.solucion)}`).join(' · ') : 'Este contenido se puede completar desde data/proyectos.json.'}</p></div>`;
    } catch (error) {
        contenedor.innerHTML = '<p>No pudimos cargar este proyecto. Revisa data/proyectos.json.</p>';
        console.error(error);
    }
};

document.addEventListener('DOMContentLoaded', () => {
    renderizarProyectos();
    renderizarTecnologias();
    renderizarDetalle();
    renderizarPluginsDetalle();
    const btnMenu = document.querySelector('.btn-menu');
    const nav = document.querySelector('.header-inner nav');
    btnMenu?.addEventListener('click', () => { const abierto = nav.classList.toggle('abierto'); btnMenu.classList.toggle('abierto', abierto); btnMenu.setAttribute('aria-expanded', abierto); });
});


const renderizarPluginsDetalle = async () => {
    const contenedor = document.querySelector('[data-detalle-proyecto]');
    if (!contenedor) return;

    try {
        const datos = await cargarJSON('data/proyectos.json');
        const id = new URLSearchParams(window.location.search).get('id');
        const proyecto = (datos.proyectos || []).find((item) => item.id === id) || datos.proyectos?.[0];
        if (!proyecto) return;

        const crearSlideInformativo = (item, indice) => `
            <div class="swiper-slide ${item.imagen ? 'slide-con-imagen' : ''}">
                <article class="tarjeta-proyecto ${item.imagen ? 'd-flex flex-row align-items-start gap-4' : ''}">
                    <div class="texto-tarjeta-problemas ${item.imagen ? 'orden-texto' : ''}">
                        <div class="titulo-problema d-flex align-items-center gap-2 mb-3">
                            <span class="numero-problema">${indice + 1}</span>
                            <p class="subtitulo mb-0">${escapeHTML(item.titulo)}</p>
                        </div>
                        <p class="p-mediano">${escapeHTML(item.texto)}</p>
                    </div>
                    ${item.imagen ? `<a href="${escapeHTML(item.imagen)}" class="lightbox-img orden-imagen"><img src="${escapeHTML(item.imagen)}" alt="${escapeHTML(item.titulo)}" class="img-problema"></a>` : ''}
                </article>
            </div>`;

        const secciones = [];
        if (proyecto.problemas?.length) {
            secciones.push(`<section class="row mt-4"><h2>Problemas visuales</h2><div class="swiper problemas-slider"><div class="swiper-wrapper">${proyecto.problemas.map(crearSlideInformativo).join('')}</div><div class="swiper-button-prev"></div><div class="swiper-button-next"></div></div></section>`);
        }
        if (proyecto.investigacion?.length) {
            secciones.push(`<section class="row mt-4"><h2>Material de investigación</h2><div class="swiper problemas-slider"><div class="swiper-wrapper">${proyecto.investigacion.map(crearSlideInformativo).join('')}</div><div class="swiper-button-prev"></div><div class="swiper-button-next"></div></div></section>`);
        }
        if (proyecto.galeria?.length) {
            secciones.push(`<section class="row mt-4"><h2>Resultado final</h2><div class="swiper galeria-proyecto-slider"><div class="swiper-wrapper">${proyecto.galeria.map((imagen, indice) => `<div class="swiper-slide"><a href="${escapeHTML(imagen)}" class="lightbox-img"><img src="${escapeHTML(imagen)}" alt="${escapeHTML(proyecto.titulo)} · imagen ${indice + 1}"></a></div>`).join('')}</div><div class="swiper-pagination"></div></div></section>`);
        }
        if (proyecto.conclusiones) {
            secciones.push(`<section class="row mt-4"><div class="col-md-7"><h2>Conclusiones</h2><p>${escapeHTML(proyecto.conclusiones)}</p></div></section>`);
        }
        contenedor.insertAdjacentHTML('beforeend', secciones.join(''));

        if (window.Swiper) {
            document.querySelectorAll('.problemas-slider').forEach((slider) => {
                new Swiper(slider, {
                    slidesPerView: 'auto',
                    spaceBetween: 30,
                    loop: false,
                    navigation: {
                        nextEl: slider.querySelector('.swiper-button-next'),
                        prevEl: slider.querySelector('.swiper-button-prev')
                    },
                    breakpoints: { 768: { slidesPerView: 2 }, 1200: { slidesPerView: 3 } }
                });
            });
            const galeria = document.querySelector('.galeria-proyecto-slider');
            if (galeria) {
                new Swiper(galeria, {
                    effect: 'coverflow',
                    centeredSlides: true,
                    loop: false,
                    grabCursor: true,
                    autoHeight: true,
                    slidesPerView: 'auto',
                    coverflowEffect: { rotate: 0, stretch: 0, depth: 200, modifier: 1.5, scale: 0.9, slideShadows: true },
                    pagination: { el: galeria.querySelector('.swiper-pagination'), clickable: true }
                });
            }
        }
        if (window.GLightbox) GLightbox({ selector: '.lightbox-img' });
    } catch (error) {
        console.error('No se pudieron cargar los plugins del detalle:', error);
    }
};
