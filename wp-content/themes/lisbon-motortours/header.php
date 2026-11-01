<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header>
    <nav>
        <div class="logo">
            <a href="<?php echo esc_url(home_url('/')); ?>">
                <img
                    src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/logo.png"
                    alt="<?php echo esc_attr(get_bloginfo('name')); ?>"
                >
            </a>
        </div>
        <?php
        wp_nav_menu([
            'theme_location' => 'main-menu',
            'menu'           => has_nav_menu('main-menu') ? '' : 'main-menu',
            'container'      => false,
            'menu_class'     => 'nav-links',
            'fallback_cb'    => 'lisbonmotortours_menu_fallback',
        ]);
        ?>
    </nav>
</header>
