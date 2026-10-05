<?php get_header(); ?>

<?php
$categoria = get_post_meta(get_the_ID(), 'categoria', true);

// Labels dinámicos según el tipo de proyecto
$labels = [
    'contexto'      => 'Contexto',
    'problemas'     => 'Problemas',
    'investigacion' => 'Investigación',
    'desafios'      => 'Desafíos y Soluciones',
];

if ($categoria === 'Diseño / Rediseño') {
    $labels['problemas']     = 'Problemas detectados';
    $labels['investigacion'] = 'Auditoría visual';
    $labels['desafios']      = 'Decisiones de diseño';
} elseif ($categoria === 'Web Apps') {
    $labels['contexto']      = 'Objetivo';
    $labels['problemas']     = 'Problema del usuario';
    $labels['investigacion'] = 'Exploración y sistema';
    $labels['desafios']      = 'Retos y decisiones';
}

$url_sitio  = get_field('url_sitio');
$url_codigo = get_field('url_codigo');
$tags       = get_the_terms(get_the_ID(), 'proyecto_tag');
?>

<main>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <a href="<?php echo esc_url(home_url('/proyectos')); ?>" class="d-flex align-items-center gap-2 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                        class="active bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                    </svg>
                    Volver a proyectos
                </a>
                <h1><?php the_title(); ?></h1>

                <?php if ($tags) : ?>
                    <ul class="tags-tarjeta list-unstyled d-flex mb-0 align-items-center">
                        <?php foreach ($tags as $tag) : ?>
                            <li><span class="p-pequeno"><?php echo esc_html($tag->name); ?></span></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>

                <p><?php echo esc_html(get_the_excerpt()); ?></p>

                <?php if ($url_sitio || $url_codigo) : ?>
                    <ul class="botones-hero list-unstyled d-flex mb-0 align-items-center">
                        <?php if ($url_sitio) : ?>
                            <li><a href="<?php echo esc_url($url_sitio); ?>" target="_blank" rel="noopener"><button>Ver Sitio</button></a></li>
                        <?php endif; ?>
                        <?php if ($url_codigo) : ?>
                            <li><a href="<?php echo esc_url($url_codigo); ?>" target="_blank" rel="noopener"><button>Ver Código</button></a></li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <div class="col-md-7">
                <?php if (has_post_thumbnail()) : ?>
                    <a href="<?php the_post_thumbnail_url('full'); ?>" class="lightbox-img">
                        <?php the_post_thumbnail('full', ['class' => 'foto-main-proyecto w-100', 'alt' => get_the_title()]); ?>
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4" id="Contexto">
                <h2><?php echo esc_html($labels['contexto']); ?></h2>
                <p><?php echo esc_html(get_field('contexto')); ?></p>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-6">
                        <div class="tarjeta-intereses">
                            <div class="texto-tarjeta-intereses text-center">
                                <p class="subtitulo">Público Objetivo</p>
                                <p class="p-mediano text-start"><?php echo esc_html(get_field('publico_objetivo')); ?></p>
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/target-audience-computer-svgrepo-com 1.png" alt="" class="icono-intereses">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="tarjeta-intereses">
                            <div class="texto-tarjeta-intereses text-center">
                                <p class="subtitulo">Objetivo Del Proyecto</p>
                                <p class="p-mediano text-start"><?php echo esc_html(get_field('objetivo_proyecto')); ?></p>
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/mountain-climb-svgrepo-com 1.png" alt="" class="icono-intereses">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="tarjeta-intereses">
                            <div class="texto-tarjeta-intereses text-center">
                                <p class="subtitulo">Duración Del Proyecto</p>
                                <p class="p-mediano text-center"><?php echo esc_html(get_field('duracion')); ?></p>
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/calendar-svgrepo-com 1.png" alt="" class="icono-intereses">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mt-3">
                        <div class="tarjeta-intereses">
                            <div class="texto-tarjeta-intereses text-center">
                                <p class="subtitulo">Rol</p>
                                <p class="p-mediano text-center"><?php echo esc_html(get_field('rol_texto')); ?></p>
                                <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/person-svgrepo-com 1.png" alt="" class="icono-intereses">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php
        $problema_titulo = get_field('problema_principal_titulo');
        $problema_texto  = get_field('problema_principal_texto');
        $problema_imagen = get_field('problema_principal_imagen');
        $problemas_secs  = get_field('problemas_secundarios');
        ?>
        <?php if ($problema_titulo || $problemas_secs) : ?>
            <div class="row mt-4" id="Problemas">
                <div class="col-md-6">
                    <h2 class="mb-0"><?php echo esc_html($labels['problemas']); ?></h2>
                </div>

                <div class="row mt-3">
                    <?php if ($problema_titulo) : ?>
                        <div class="col-md-6">
                            <div class="tarjeta-proyecto">
                                <div class="texto-tarjeta-problemas">
                                    <div class="titulo-problema d-flex align-items-center gap-2 mb-3">
                                        <span class="numero-problema">1</span>
                                        <p class="subtitulo mb-0"><?php echo esc_html($problema_titulo); ?></p>
                                    </div>
                                    <?php if ($problema_imagen) : ?>
                                        <a href="<?php echo esc_url($problema_imagen); ?>" class="lightbox-img">
                                            <img src="<?php echo esc_url($problema_imagen); ?>" alt="">
                                        </a>
                                    <?php endif; ?>
                                    <p class="p-mediano"><?php echo esc_html($problema_texto); ?></p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($problemas_secs) : ?>
                        <div class="col-md-6">
                            <ul class="list-unstyled d-flex flex-column h-100 mb-0 justify-content-between">
                                <?php $num = 2;
                                foreach ($problemas_secs as $prob) : ?>
                                    <li>
                                        <div class="tarjeta-proyecto">
                                            <div class="texto-tarjeta-problemas">
                                                <div class="titulo-problema d-flex align-items-center gap-2 mb-3">
                                                    <span class="numero-problema"><?php echo $num; ?></span>
                                                    <p class="subtitulo mb-0"><?php echo esc_html($prob['titulo']); ?></p>
                                                </div>
                                                <p class="p-mediano"><?php echo esc_html($prob['texto']); ?></p>
                                            </div>
                                        </div>
                                    </li>
                                <?php $num++;
                                endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <?php $investigacion = get_field('investigacion'); ?>
        <?php if ($investigacion) : ?>
            <div class="row mt-4" id="Investigacion">
                <h2><?php echo esc_html($labels['investigacion']); ?></h2>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($investigacion) : ?>
        <div class="row">
            <div class="swiper problemas-slider">
                <div class="swiper-wrapper">
                    <?php $num = 1;
                    foreach ($investigacion as $item) : ?>
                        <div class="swiper-slide <?php echo $item['imagen'] ? 'slide-con-imagen' : ''; ?>">
                            <div class="tarjeta-proyecto <?php echo $item['imagen'] ? 'd-flex flex-row align-items-start gap-4' : ''; ?>">
                                <div class="texto-tarjeta-problemas <?php echo $item['imagen'] ? 'orden-texto' : ''; ?>">
                                    <div class="titulo-problema d-flex align-items-center gap-2 mb-3">
                                        <span class="numero-problema"><?php echo $num; ?></span>
                                        <p class="subtitulo mb-0"><?php echo esc_html($item['titulo']); ?></p>
                                    </div>
                                    <p class="p-mediano">
                                        <?php echo esc_html($item['texto']); ?>
                                    </p>
                                </div>

                                <?php if ($item['imagen']) : ?>
                                    <a href="<?php echo esc_url($item['imagen']); ?>" class="lightbox-img orden-imagen">
                                        <img src="<?php echo esc_url($item['imagen']); ?>" alt="" class="img-problema">
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php $num++;
                    endforeach; ?>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <?php $desafios = get_field('desafios'); ?>
        <?php if ($desafios) : ?>
            <div class="row mt-4" id="Desafios">
                <h2><?php echo esc_html($labels['desafios']); ?></h2>

                <?php foreach ($desafios as $i => $par) : ?>
                    <div class="row <?php echo $i > 0 ? 'mt-3' : ''; ?>">
                        <div class="col-md-5">
                            <div class="tarjeta-intereses">
                                <div class="texto-tarjeta-intereses text-center">
                                    <p class="subtitulo">Problema</p>
                                    <p class="p-mediano text-start"><?php echo esc_html($par['problema']); ?></p>
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/danger-triangle-svgrepo-com 4.png" alt="" class="icono-intereses icono-problemas">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1 d-flex justify-content-center align-items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                                class="active bi bi-arrow-right" viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                            </svg>
                        </div>
                        <div class="col-md-6">
                            <div class="tarjeta-intereses">
                                <div class="texto-tarjeta-intereses text-center">
                                    <p class="subtitulo">Solución</p>
                                    <p class="p-mediano text-start"><?php echo esc_html($par['solucion']); ?></p>
                                    <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/img/check-mark-correct-svgrepo-com 3.png" alt="" class="icono-intereses icono-problemas">
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php $galeria = get_field('galeria_final'); ?>
        <?php if ($galeria) : ?>
            <div class="row mt-4">
                <div class="col-md-6">
                    <h2>Resultado Final</h2>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <?php if ($galeria) : ?>
        <div class="row">
            <div class="swiper galeria-proyecto-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($galeria as $img_url) : ?>
                        <div class="swiper-slide">
                            <a href="<?php echo esc_url($img_url); ?>" class="lightbox-img">
                                <img src="<?php echo esc_url($img_url); ?>" alt="">
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="container">
        <?php $conclusiones = get_field('conclusiones'); ?>
        <?php if ($conclusiones) : ?>
            <div class="row mt-4">
                <div class="col-md-7">
                    <h3>Conclusiones</h3>
                    <p><?php echo esc_html($conclusiones); ?></p>
                    <ul class="list-unstyled d-flex flex-column mb-0 gap-3">
                        <li>
                            <a href="<?php echo esc_url(home_url('/')); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-house" viewBox="0 0 16 16">
                                    <path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293zM13 7.207V13.5a.5.5 0 0 1-.5.5h-9a.5.5 0 0 1-.5-.5V7.207l5-5z" />
                                </svg>
                                Volver al inicio
                            </a>
                        </li>
                        <li>
                            <a href="<?php echo esc_url(home_url('/proyectos')); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-arrow-left" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd"
                                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                                </svg>
                                Volver a Proyectos
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="col-md-5">
                    <div class="tarjeta-proyecto">
                        <div class="texto-tarjeta-problemas">
                            <div class="titulo-problema d-flex align-items-center gap-2 mb-3">
                                <p class="subtitulo mb-0">Contenidos</p>
                            </div>
                            <p class="p-mediano">Volver a</p>
                            <ul class="d-flex flex-column mb-0 gap-3 links-contenidos">
                                <li><a href="#Contexto"><?php echo esc_html($labels['contexto']); ?></a></li>
                                <li><a href="#Problemas"><?php echo esc_html($labels['problemas']); ?></a></li>
                                <li><a href="#Investigacion"><?php echo esc_html($labels['investigacion']); ?></a></li>
                                <li><a href="#Desafios"><?php echo esc_html($labels['desafios']); ?></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="row seccion-proyectos mt-4">
            <div class="col-md-12 d-flex justify-content-between">
                <h2>Otros Proyectos</h2>
            </div>
            <div class="row tarjetas-proyectos">
                <?php
                $otros = new WP_Query([
                    'post_type'      => 'proyecto',
                    'posts_per_page' => 3,
                    'post__not_in'   => [get_the_ID()],
                    'orderby'        => 'rand',
                ]);

                if ($otros->have_posts()) :
                    while ($otros->have_posts()) : $otros->the_post();
                        $cat_otro  = get_post_meta(get_the_ID(), 'categoria', true);
                        $tags_otro_proyecto = get_the_terms(get_the_ID(), 'proyecto_tag');
                ?>
                        <div class="col-md-4">
                            <a href="<?php the_permalink(); ?>">
                                <div class="tarjeta-proyecto">
                                    <div class="imagen-tarjeta-proyecto">
                                        <span class="categoria"><?php echo esc_html($cat_otro); ?></span>
                                        <?php if (has_post_thumbnail()) : ?>
                                            <?php the_post_thumbnail('tarjeta-proyecto', ['alt' => get_the_title()]); ?>
                                        <?php endif; ?>
                                    </div>
                                    <div class="texto-tarjeta-proyecto">
                                        <h3><?php the_title(); ?></h3>
                                        <p><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                                        <?php if ($tags_otro_proyecto) : ?>
                                            <ul class="tags-tarjeta list-unstyled d-flex mb-0 align-items-center">
                                                <?php foreach ($tags_otro_proyecto as $tag) : ?>
                                                    <li><span class="p-pequeno"><?php echo esc_html($tag->name); ?></span></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        <?php endif; ?>
                                        <div class="link-flecha d-flex">
                                            <p class="p-mediano boton-detalle">Detalles
                                                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor" class="bi bi-arrow-right" viewBox="0 0 16 16">
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
                endif;
                ?>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>