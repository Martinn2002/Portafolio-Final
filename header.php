<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <header>
        <span class="logo">
            <a class="p-mediano" href="<?php echo esc_url(home_url()); ?>">
                Martín Pérez
            </a>
        </span>
        <div class="container">
            <div class="header-inner">
                <button class="btn-menu" aria-label="Abrir menú" aria-expanded="false">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
                <nav>
                    <ul class="list-unstyled d-flex mb-0 align-items-center">
                        <li><a class="p-mediano <?php echo is_front_page() ? 'active' : ''; ?>" href="<?php echo esc_url(home_url('/')); ?>">Inicio</a></li>
                        <li><a class="p-mediano <?php echo is_page('proyectos') ? 'active' : ''; ?>" href="<?php echo esc_url(home_url('/proyectos')); ?>">Proyectos</a></li>
                        <li><a class="p-mediano" href="<?php echo esc_url(home_url('/#tecnologias')); ?>">Tecnologías</a></li>
                        <li><a class="p-mediano" href="<?php echo esc_url(home_url('/#contacto')); ?>">Contacto</a></li>
                        <li><a class="p-mediano <?php echo is_page('sobre-mi') ? 'active' : ''; ?>" href="<?php echo esc_url(home_url('/sobre-mi')); ?>">Sobre mí</a></li>
                    </ul>
                </nav>
            </div>
        </div>
    </header>