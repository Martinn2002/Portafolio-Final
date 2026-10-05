<?php get_header(); ?>

<main>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6">
                <h1>Martín Pérez.</h1>
                <p class="subtitulo">Diseñador y Desarrollador Web</p>
                <p>Tengo un enfoque fuerte en la programación. Llegué a este mundo combinando mi paso por ingeniería
                    informática con el interés de crear experiencias web amenas tanto desde lo que ve el ojo desde
                    fuera hasta como funciona por dentro.</p>
                <p>
                    Disfruto tomar un diseño y llevarlo a código, viendo cómo se transforma en algo real. Me enfoco
                    en que cada proyecto sea claro, intuitivo y agradable de navegar.</p>
                <ul class="botones-hero list-unstyled d-flex mb-0 align-items-center">
                    <li>
                        <a href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/cv.pdf" download>
                            <button>Descargar CV</button>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="foto-contenedor offset-2 col-md-4">
                <div class="puntos"></div>
                <img class="fotomia-main" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/foto-main.jpeg" alt="Martín Pérez">
                <div class="borde-naranja"></div>
            </div>
        </div>

        <div class="row mt-4">
            <h2>Lo que puedo aportar a un equipo</h2>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="tarjeta-proyecto">
                    <div class="texto-tarjeta-sobre-mi text-center">
                        <p class="subtitulo">Resolución de problemas</p>
                        <p>Analizo distintas opciones antes de decidir, buscando siempre la solución más clara,
                            eficiente y funcional.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tarjeta-proyecto">
                    <div class="texto-tarjeta-sobre-mi text-center">
                        <p class="subtitulo">Trabajo en equipo</p>
                        <p>Me adapto bien a distintos entornos y mantengo una comunicación clara para colaborar de
                            forma efectiva.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-6">
                <div class="tarjeta-proyecto">
                    <div class="texto-tarjeta-sobre-mi text-center">
                        <p class="subtitulo">Pensamiento analítico</p>
                        <p>Me gusta entender los problemas desde distintos puntos de vista para tomar mejores
                            decisiones.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="tarjeta-proyecto">
                    <div class="texto-tarjeta-sobre-mi text-center">
                        <p class="subtitulo">Organización</p>
                        <p>Trabajo de forma ordenada, siguiendo estructuras e instrucciones con precisión.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="background-tecnologias mt-5">
        <div class="container container-tecnologias">
            <div class="row">
                <div class="col-md-3">
                    <h2 id="tecnologias">Tecnologías</h2>
                    <ul class="ul-tecnologias">
                        <div class="indicador-tecnologia"></div>
                        <?php
                        $categorias = ['Lenguajes', 'Frameworks', 'CMS', 'Herramientas'];
                        $primera = true;
                        foreach ($categorias as $cat) :
                        ?>
                            <li class="<?php echo $primera ? 'active' : ''; ?>" data-categoria="<?php echo esc_attr($cat); ?>">
                                <p class="<?php echo $primera ? 'active' : ''; ?>"><?php echo esc_html($cat); ?></p>
                            </li>
                        <?php $primera = false;
                        endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-9">
                    <?php foreach ($categorias as $cat) : ?>
                        <div class="panel-tecnologias row"
                            data-panel="<?php echo esc_attr($cat); ?>"
                            style="<?php echo $cat !== 'Lenguajes' ? 'display:none;' : ''; ?>">

                            <?php
                            $tecs = new WP_Query([
                                'post_type'      => 'tecnologia',
                                'posts_per_page' => -1,
                                'meta_key'       => 'categoria_tec',
                                'meta_value'     => $cat,
                                'orderby'        => 'title',
                                'order'          => 'ASC',
                            ]);

                            if ($tecs->have_posts()) :
                                while ($tecs->have_posts()) : $tecs->the_post();
                                    $descripcion = get_post_meta(get_the_ID(), 'descripcion', true);
                                    $porcentaje  = get_post_meta(get_the_ID(), 'porcentaje', true);
                            ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="tarjeta-tecnologia">
                                            <p class="p-titulo"><?php the_title(); ?></p>
                                            <p class="p-mediano"><?php echo esc_html($descripcion); ?></p>
                                            <div class="barra-progreso-container">
                                                <div class="barra-progreso" style="width: <?php echo esc_attr($porcentaje); ?>%"></div>
                                            </div>
                                            <ul class="list-unstyled d-flex mb-0 justify-content-between">
                                                <li class="p-pequeno">Dominio</li>
                                                <li class="p-pequeno"><?php echo esc_html($porcentaje); ?>%</li>
                                            </ul>
                                        </div>
                                    </div>
                                <?php
                                endwhile;
                                wp_reset_postdata();
                            else : ?>
                                <p class="p-mediano">No hay tecnologías en esta categoría aún.</p>
                            <?php endif; ?>

                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mt-4">
            <h2>Intereses Personales</h2>
        </div>
        <div class="row">
            <div class="col-md-7">
                <div class="tarjeta-intereses">
                    <div class="texto-tarjeta-intereses text-center pb-0">
                        <p class="subtitulo">Música</p>
                        <p class="p-mediano">La música es gran parte de mi vida y de mi proceso creativo</p>
                    </div>
                    <iframe data-testid="embed-iframe" style="border-radius:12px"
                        src="https://open.spotify.com/embed/playlist/0vI93qfVuWwaKzPxO5FFwj?utm_source=generator&theme=0"
                        width="100%" height="152" frameBorder="0" allowfullscreen=""
                        allow="autoplay; clipboard-write; encrypted-media; fullscreen; picture-in-picture"
                        loading="lazy"></iframe>
                </div>
            </div>
            <div class="col-md-5">
                <div class="tarjeta-intereses">
                    <div class="texto-tarjeta-intereses text-center">
                        <p class="subtitulo">Fútbol</p>
                        <p class="p-mediano text-start">El fútbol me interesa sobre todo por el análisis, los datos y la
                            táctica. Suelo ver los partidos de forma más observadora, tratando de entender decisiones
                            y dinámicas de juego.</p>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/soccer-ball-svgrepo-com 1.png" alt="" class="icono-intereses">
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <div class="tarjeta-intereses">
                    <div class="texto-tarjeta-intereses text-center">
                        <p class="subtitulo">Videojuegos</p>
                        <p class="p-mediano text-start">Me interesan los videojuegos especialmente cuando tienen
                            gestión y estrategia, donde las decisiones y la administración de recursos tienen un
                            impacto.</p>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/console-joystick-gaming-videogames-play-svgrepo-com 1.png" alt="" class="icono-intereses">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tarjeta-intereses">
                    <div class="texto-tarjeta-intereses text-center">
                        <p class="subtitulo">WWE</p>
                        <p class="text-start">Me interesa por el espectáculo, narrativa y construcción de
                            personajes. Me gusta observar cómo crean historias, las dinamicas entre los heroes y
                            villanos y los momentos memorables.</p>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/wwe-svgrepo-com 1.png" alt="" class="icono-intereses">
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="tarjeta-intereses">
                    <div class="texto-tarjeta-intereses text-center">
                        <p class="subtitulo">Creacion de contenido</p>
                        <p class="text-start">Disfruto crear contenido relacionado con música y videojuegos.
                            Compartir lo que me apasiona con quienes también lo viven.</p>
                        <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/youtube-168-svgrepo-com 1.png" alt="" class="icono-intereses">
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>

<?php get_footer(); ?>