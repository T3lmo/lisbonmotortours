<?php get_header(); ?>

<section class="hero hero-video">

    <video autoplay muted loop playsinline>
        <source src="<?php echo get_template_directory_uri(); ?>/assets/video/hero.mp4" type="video/mp4">
    </video>

    <div class="hero-overlay"></div>
        <div class="hero-content">
            <h1><?php bloginfo('name'); ?></h1>
            <p><?php bloginfo('description'); ?></p>
            <a href="<?php echo esc_url(get_post_type_archive_link('tour')); ?>" class="cta-button">
                Explorar Tours
            </a>
        </div>
</section>

<div class="page-content active">
    <h2 class="page-title"><?php the_title(); ?></h2>

    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</div>

<?php get_footer(); ?>