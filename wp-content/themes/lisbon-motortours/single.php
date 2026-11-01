<?php get_header(); ?>

<div class="page-content active">
    <?php while (have_posts()) : the_post(); ?>
        <h2 class="page-title"><?php the_title(); ?></h2>
        <p class="post-meta"><?php echo esc_html(get_the_date()); ?></p>
        <div class="generic-content"><?php the_content(); ?></div>
    <?php endwhile; ?>
</div>

<?php get_footer(); ?>
