<?php get_header(); ?>

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
