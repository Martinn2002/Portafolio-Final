<?php
echo '<!-- USANDO page-proyectos.php -->';
get_header();
?>

<main>
    <div class="decoracion decoracion-puntos"></div>
    <div class="decoracion decoracion-circulo"></div>
    <div class="decoracion-luz"></div>
    <div class="container">
        <div class="row mt-4">
            <div class="col-md-6">
                <h1>Explora mis trabajos</h1>
                <p>En esta sección encontrarás una recopilación de todos mis proyectos. Puedes filtrar categorias y
                    acceder a cada proyecto para conocer sus detalles en profundidad.</p>
            </div>
            <div class="row">
                <ul class="list-unstyled d-flex gap-5 mb-0 mt-4 justify-content-center filtros-proyectos">
                    <li><button class="active" data-categoria="Todos">Todos</button></li>
                    <li><button data-categoria="CMS">CMS</button></li>
                    <li><button data-categoria="Diseño / Rediseño">Diseño y Rediseño</button></li>
                    <li><button data-categoria="Web Apps">Web Apps</button></li>
                </ul>
            </div>

            <div class="row">
                <div class="row seccion-proyectos">
                    <div class="row tarjetas-proyectos" id="contenedor-proyectos">
                        <?php
                        $proyectos = new WP_Query([
                            'post_type'      => 'proyecto',
                            'posts_per_page' => 6,
                            'paged'          => 1,
                            'orderby'        => 'date',
                            'order'          => 'DESC',
                        ]);

                        if ($proyectos->have_posts()) :
                            while ($proyectos->have_posts()) : $proyectos->the_post();
                                martin_render_tarjeta_proyecto();
                            endwhile;
                            $total_paginas = $proyectos->max_num_pages;
                            wp_reset_postdata();
                        else :
                            $total_paginas = 1;
                            echo '<div class="col-12"><p>No hay proyectos aún.</p></div>';
                        endif;
                        ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="paginador d-flex justify-content-center align-items-center gap-3 mb-4"
                id="paginador-proyectos"
                data-pagina-actual="1"
                data-total-paginas="<?php echo esc_attr($total_paginas); ?>"
                data-categoria-actual="Todos">

                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                    class="inactive bi bi-arrow-left flecha-paginador" data-direccion="anterior" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8" />
                </svg>

                <p class="mb-0 weight-bold" id="numero-pagina-actual">1</p>

                <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                    class="<?php echo $total_paginas > 1 ? 'active' : 'inactive'; ?> bi bi-arrow-right flecha-paginador"
                    data-direccion="siguiente" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8" />
                </svg>
            </div>
        </div>
    </div>
</main>

<?php get_footer(); ?>