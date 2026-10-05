const proyectosContainer = document.querySelector('#lista-proyectos');
const proyectosStatus = document.querySelector('#proyectos-status');

const crearTarjetaProyecto = (proyecto) => {
    const tecnologias = proyecto.tecnologias
        .map((tecnologia) => `<li><span class="p-pequeno">${tecnologia}</span></li>`)
        .join('');

    return `
        <article class="col-md-4">
            <div class="tarjeta-proyecto h-100">
                <div class="imagen-tarjeta-proyecto">
                    <span class="categoria">${proyecto.categoria}</span>
                    <img src="${proyecto.imagen}" alt="${proyecto.imagenAlt}" loading="lazy">
                </div>
                <div class="texto-tarjeta-proyecto">
                    <h3>${proyecto.titulo}</h3>
                    <p class="p-pequeno">${proyecto.descripcion}</p>
                    <ul class="tags-tarjeta list-unstyled d-flex mb-0 align-items-center">
                        ${tecnologias}
                    </ul>
                    <div class="link-flecha d-flex">
                        <a class="p-mediano" href="${proyecto.url}">
                            ${proyecto.urlTexto}
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" viewBox="0 0 16 16" aria-hidden="true">
                                <path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </article>
    `;
};

const cargarProyectos = async () => {
    try {
        const respuesta = await fetch('data/proyectos.json');
        if (!respuesta.ok) {
            throw new Error(`No se pudo cargar el JSON (${respuesta.status})`);
        }

        const datos = await respuesta.json();
        const proyectos = Array.isArray(datos) ? datos : datos.proyectos;

        if (!Array.isArray(proyectos)) {
            throw new Error('El JSON no contiene un arreglo de proyectos');
        }

        proyectosContainer.innerHTML = proyectos.map(crearTarjetaProyecto).join('');
        proyectosStatus.remove();
    } catch (error) {
        proyectosStatus.textContent = 'No pudimos cargar los proyectos. Revisa el archivo data/proyectos.json.';
        proyectosStatus.classList.add('error-proyectos');
        console.error(error);
    }
};

cargarProyectos();
