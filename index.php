<?php get_header(); ?>

<main>
    <div class="container mt-4">
        <div class="row">
            <div class="col-12 col-md-7">
                <h1>
                    Hola! <br> Soy Martín Pérez.
                </h1>
                <p>Desarrollo sitios web modernos, inspirado en nuevas ideas y tendencias</p>
                <ul class="botones-hero list-unstyled d-flex flex-wrap mb-0 align-items-center">
                    <li><a href="<?php echo esc_url(home_url('/sobre-mi')); ?>"><button>Sobre mí</button></a></li>
                    <li><a href="<?php echo esc_url(home_url('/#contacto')); ?>"><button>Contactame</button></a></li>
                    <li>
                        <a href="<?php echo esc_url(get_template_directory_uri()); ?>/assets/cv.pdf" download>
                            <button>Descargar CV</button>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="foto-contenedor offset-md-1 col-12 col-md-4">
                <div class="puntos"></div>
                <img class="fotomia-main img-fluid" src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/foto-main.jpeg" alt="Martín Pérez">
                <div class="borde-naranja"></div>
            </div>
        </div>

        <div class="row seccion-proyectos mt-4">
            <div class="col-12 d-flex justify-content-between">
                <h2>Mis Ultimos Proyectos</h2>
                <a href="<?php echo esc_url(home_url('/proyectos')); ?>">Ver todos los proyectos</a>
            </div>
            <div class="col-12">
                <div class="row tarjetas-proyectos">

                    <?php
                    $proyectos = new WP_Query([
                        'post_type'      => 'proyecto',
                        'posts_per_page' => 3,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    ]);

                    if ($proyectos->have_posts()) :
                        while ($proyectos->have_posts()) : $proyectos->the_post();
                            $categoria = get_post_meta(get_the_ID(), 'categoria', true);
                            $tags      = get_the_tags();
                    ?>
                            <div class="col-12 col-sm-6 col-md-4 mb-4">
                                <a href="<?php the_permalink(); ?>">
                                    <div class="tarjeta-proyecto">
                                        <div class="imagen-tarjeta-proyecto">
                                            <span class="categoria"><?php echo esc_html($categoria); ?></span>
                                            <?php if (has_post_thumbnail()) : ?>
                                                <?php the_post_thumbnail('large', ['alt' => get_the_title(), 'class' => 'img-fluid']); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="texto-tarjeta-proyecto">
                                            <h3><?php the_title(); ?></h3>
                                            <p><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                                            <?php $tags = get_the_terms(get_the_ID(), 'proyecto_tag'); ?>
                                            <?php if ($tags) : ?>
                                                <ul class="tags-tarjeta list-unstyled d-flex flex-wrap mb-0 align-items-center">
                                                    <?php foreach ($tags as $tag) : ?>
                                                        <li><span class="p-pequeno"><?php echo esc_html($tag->name); ?></span></li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            <?php endif; ?>
                                            <div class="link-flecha d-flex">
                                                <p class="p-mediano boton-detalle">Detalles
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26"
                                                        fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
                                                        <path fill-rule="evenodd"
                                                            d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                                                    </svg>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php
                        endwhile;
                        wp_reset_postdata();
                    else : ?>
                        <p>No hay proyectos aún.</p>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>

    <div class="background-tecnologias mt-5">
        <div class="container container-tecnologias">
            <div class="row">
                <div class="col-12 col-md-3">
                    <h2 id="tecnologias">Tecnologías</h2>
                    <ul class="ul-tecnologias d-flex flex-wrap flex-md-column">
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
                <div class="col-12 col-md-9">
                    <div class="paneles-tecnologia-wrapper">
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
                                        <div class="col-12 col-sm-6 col-md-4 mb-4 d-flex">
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
    </div>

</main>
<?php get_footer(); ?>