<?php

// ─── SOPORTE BÁSICO DEL TEMA ───────────────────────────────────────────────
function martin_setup()
{
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'martin_setup');


// ─── ENCOLAR ESTILOS Y SCRIPTS ─────────────────────────────────────────────
function martin_encolar_assets()
{

    wp_enqueue_style(
        'google-fonts',
        'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=DM+Serif+Display:ital@0;1&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'bootstrap',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css',
        [],
        '5.3.8'
    );

    wp_enqueue_style(
        'swiper',
        'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css',
        [],
        '12'
    );

    wp_enqueue_style(
        'estilo-principal',
        get_template_directory_uri() . '/assets/estilo.css',
        ['bootstrap', 'swiper'],
        wp_get_theme()->get('Version')
    );

    wp_enqueue_script(
        'bootstrap-js',
        'https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js',
        [],
        '5.3.8',
        true
    );

    wp_enqueue_script(
        'swiper-js',
        'https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js',
        [],
        '12',
        true
    );

    wp_enqueue_script(
        'script-principal',
        get_template_directory_uri() . '/assets/javascript/script.js',
        ['bootstrap-js', 'swiper-js'],
        wp_get_theme()->get('Version'),
        true
    );
}
add_action('wp_enqueue_scripts', 'martin_encolar_assets');


// ─── CUSTOM POST TYPE: PROYECTOS ───────────────────────────────────────────
function martin_registrar_proyecto()
{
    register_post_type('proyecto', [
        'labels' => [
            'name'               => 'Proyectos',
            'singular_name'      => 'Proyecto',
            'add_new'            => 'Añadir nuevo',
            'add_new_item'       => 'Añadir nuevo proyecto',
            'edit_item'          => 'Editar proyecto',
            'new_item'           => 'Nuevo proyecto',
            'view_item'          => 'Ver proyecto',
            'search_items'       => 'Buscar proyectos',
            'not_found'          => 'No se encontraron proyectos',
            'not_found_in_trash' => 'No hay proyectos en la papelera',
        ],
        'public'        => true,
        'has_archive'   => true,
        'show_in_menu'  => true,
        'menu_icon'     => 'dashicons-portfolio',
        'supports'      => ['title', 'editor', 'thumbnail', 'excerpt'],
        'rewrite'       => ['slug' => 'proyecto'],
        'has_archive'   => false,
    ]);
}
add_action('init', 'martin_registrar_proyecto');


// ─── META BOX: CATEGORÍA DEL PROYECTO ─────────────────────────────────────
function martin_agregar_metabox_proyecto()
{
    add_meta_box(
        'proyecto_categoria',
        'Categoría del proyecto',
        'martin_render_metabox_categoria',
        'proyecto',
        'side',
        'default'
    );
}
add_action('add_meta_boxes', 'martin_agregar_metabox_proyecto');

function martin_render_metabox_categoria($post)
{
    $valor = get_post_meta($post->ID, 'categoria', true);
    wp_nonce_field('proyecto_categoria_nonce', 'proyecto_nonce');
?>
    <label for="categoria">Categoría (ej: CMS, Frontend, etc.)</label>
    <input
        type="text"
        id="categoria"
        name="categoria"
        value="<?php echo esc_attr($valor); ?>"
        style="width:100%; margin-top:5px;">
<?php
}

function martin_guardar_metabox_proyecto($post_id)
{
    if (
        ! isset($_POST['proyecto_nonce']) ||
        ! wp_verify_nonce($_POST['proyecto_nonce'], 'proyecto_categoria_nonce')
    ) return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    if (isset($_POST['categoria'])) {
        update_post_meta(
            $post_id,
            'categoria',
            sanitize_text_field($_POST['categoria'])
        );
    }
}
add_action('save_post_proyecto', 'martin_guardar_metabox_proyecto');


// ─── PROCESAR FORMULARIO DE CONTACTO ───────────────────────────────────────
function martin_procesar_contacto()
{

    if (
        ! isset($_POST['contacto_nonce']) ||
        ! wp_verify_nonce($_POST['contacto_nonce'], 'contacto_form')
    ) {
        wp_die('Solicitud no válida.');
    }

    $nombre  = sanitize_text_field($_POST['nombre'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $mensaje = sanitize_textarea_field($_POST['mensaje'] ?? '');

    if (empty($nombre) || empty($email) || empty($mensaje)) {
        wp_redirect(home_url('/#contacto') . '?contacto=error');
        exit;
    }

    if (! is_email($email)) {
        wp_redirect(home_url('/#contacto') . '?contacto=email-invalido');
        exit;
    }

    $destinatario = get_option('admin_email');
    $asunto       = "Nuevo mensaje de contacto de $nombre";
    $cuerpo       = "Nombre: $nombre\nEmail: $email\n\nMensaje:\n$mensaje";
    $headers      = [
        'Content-Type: text/plain; charset=UTF-8',
        "Reply-To: $nombre <$email>",
    ];

    $enviado = wp_mail($destinatario, $asunto, $cuerpo, $headers);

    wp_redirect(home_url('/#contacto') . ($enviado ? '?contacto=ok' : '?contacto=error'));
    exit;
}
add_action('admin_post_enviar_contacto', 'martin_procesar_contacto');
add_action('admin_post_nopriv_enviar_contacto', 'martin_procesar_contacto');

// ─── CUSTOM POST TYPE: TECNOLOGÍAS ────────────────────────────────────────
function martin_registrar_tecnologia()
{
    register_post_type('tecnologia', [
        'labels' => [
            'name'               => 'Tecnologías',
            'singular_name'      => 'Tecnología',
            'add_new'            => 'Añadir nueva',
            'add_new_item'       => 'Añadir nueva tecnología',
            'edit_item'          => 'Editar tecnología',
            'not_found'          => 'No se encontraron tecnologías',
        ],
        'public'       => false,
        'show_ui'      => true,
        'show_in_menu' => true,
        'menu_icon'    => 'dashicons-hammer',
        'supports'     => ['title'],
        'rewrite'      => false,
    ]);
}
add_action('init', 'martin_registrar_tecnologia');


// ─── META BOX: DATOS DE TECNOLOGÍA ────────────────────────────────────────
function martin_agregar_metabox_tecnologia()
{
    add_meta_box(
        'tecnologia_datos',
        'Datos de la tecnología',
        'martin_render_metabox_tecnologia',
        'tecnologia',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'martin_agregar_metabox_tecnologia');

function martin_render_metabox_tecnologia($post)
{
    $descripcion = get_post_meta($post->ID, 'descripcion', true);
    $porcentaje  = get_post_meta($post->ID, 'porcentaje', true);
    $categoria   = get_post_meta($post->ID, 'categoria_tec', true);

    wp_nonce_field('tecnologia_nonce_action', 'tecnologia_nonce');
?>
    <table style="width:100%; border-collapse:collapse;">
        <tr>
            <td style="padding:8px 0; width:140px;"><label>Categoría</label></td>
            <td>
                <select name="categoria_tec" style="width:100%">
                    <?php
                    $opciones = ['Lenguajes', 'Frameworks', 'CMS', 'Herramientas'];
                    foreach ($opciones as $op) {
                        $selected = selected($categoria, $op, false);
                        echo "<option value='$op' $selected>$op</option>";
                    }
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td style="padding:8px 0;"><label>Descripción</label></td>
            <td>
                <input type="text" name="descripcion" value="<?php echo esc_attr($descripcion); ?>" style="width:100%">
            </td>
        </tr>
        <tr>
            <td style="padding:8px 0;"><label>Dominio (0-100)</label></td>
            <td>
                <input type="number" name="porcentaje" value="<?php echo esc_attr($porcentaje); ?>" min="0" max="100" style="width:100%">
            </td>
        </tr>
    </table>
<?php
}

function martin_guardar_metabox_tecnologia($post_id)
{
    if (
        ! isset($_POST['tecnologia_nonce']) ||
        ! wp_verify_nonce($_POST['tecnologia_nonce'], 'tecnologia_nonce_action')
    ) return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;

    $campos = ['descripcion', 'porcentaje', 'categoria_tec'];
    foreach ($campos as $campo) {
        if (isset($_POST[$campo])) {
            update_post_meta($post_id, $campo, sanitize_text_field($_POST[$campo]));
        }
    }
}
add_action('save_post_tecnologia', 'martin_guardar_metabox_tecnologia');

// ─── TAXONOMÍA: TAGS PARA PROYECTOS ───────────────────────────────────────
function martin_registrar_tags_proyecto()
{
    register_taxonomy('proyecto_tag', 'proyecto', [
        'labels' => [
            'name'          => 'Tags del proyecto',
            'singular_name' => 'Tag',
            'add_new_item'  => 'Añadir nuevo tag',
            'search_items'  => 'Buscar tags',
            'all_items'     => 'Todos los tags',
        ],
        'hierarchical'      => false,
        'show_ui'           => true,
        'show_in_menu'      => true,
        'show_admin_column' => true,
        'rewrite'           => false,
    ]);
}
add_action('init', 'martin_registrar_tags_proyecto');

add_image_size('tarjeta-proyecto', 600, 400, true);

// GLightbox CSS
wp_enqueue_style(
    'glightbox',
    'https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css',
    [],
    null
);

// GLightbox JS
wp_enqueue_script(
    'glightbox-js',
    'https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js',
    [],
    null,
    true
);

// ─── ENCOLAR VARIABLES PARA AJAX ──────────────────────────────────────────
function martin_localizar_ajax()
{
    wp_localize_script('script-principal', 'martin_ajax', [
        'url' => admin_url('admin-ajax.php'),
    ]);
}
add_action('wp_enqueue_scripts', 'martin_localizar_ajax');


// ─── FUNCIÓN REUTILIZABLE: RENDERIZAR TARJETA DE PROYECTO ─────────────────
function martin_render_tarjeta_proyecto()
{
    $cat_proyecto = get_post_meta(get_the_ID(), 'categoria', true);
    $tags_proyecto = get_the_terms(get_the_ID(), 'proyecto_tag');
?>
    <div class="col-md-4">
        <a href="<?php the_permalink(); ?>">
            <div class="tarjeta-proyecto">
                <div class="imagen-tarjeta-proyecto">
                    <span class="categoria"><?php echo esc_html($cat_proyecto); ?></span>
                    <?php if (has_post_thumbnail()) : ?>
                        <?php the_post_thumbnail('tarjeta-proyecto', ['alt' => get_the_title()]); ?>
                    <?php endif; ?>
                </div>
                <div class="texto-tarjeta-proyecto">
                    <h3><?php the_title(); ?></h3>
                    <p><?php echo wp_trim_words(get_the_excerpt(), 30); ?></p>
                    <?php if ($tags_proyecto) : ?>
                        <ul class="tags-tarjeta list-unstyled d-flex mb-0 align-items-center">
                            <?php foreach ($tags_proyecto as $tag) : ?>
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
}


// ─── AJAX: FILTRAR Y PAGINAR PROYECTOS ────────────────────────────────────
function martin_filtrar_proyectos()
{

    $categoria = isset($_POST['categoria']) ? sanitize_text_field($_POST['categoria']) : 'Todos';
    $pagina    = isset($_POST['pagina']) ? intval($_POST['pagina']) : 1;

    $args = [
        'post_type'      => 'proyecto',
        'posts_per_page' => 6,
        'paged'          => $pagina,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    if ($categoria !== 'Todos') {
        $args['meta_query'] = [
            [
                'key'   => 'categoria',
                'value' => $categoria,
            ],
        ];
    }

    $query = new WP_Query($args);

    ob_start();

    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
            martin_render_tarjeta_proyecto();
        endwhile;
        wp_reset_postdata();
    else :
        echo '<div class="col-12"><p>No hay proyectos en esta categoría.</p></div>';
    endif;

    $html = ob_get_clean();

    wp_send_json([
        'html'        => $html,
        'total_pages' => $query->max_num_pages,
        'pagina'      => $pagina,
    ]);
}
add_action('wp_ajax_filtrar_proyectos', 'martin_filtrar_proyectos');
add_action('wp_ajax_nopriv_filtrar_proyectos', 'martin_filtrar_proyectos');
