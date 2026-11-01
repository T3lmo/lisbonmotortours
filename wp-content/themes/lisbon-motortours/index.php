<?php get_header(); ?>

<div class="page-content active">
    <h2 class="page-title"><?php echo is_home() ? 'Blog' : esc_html(get_the_archive_title()); ?></h2>

    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <article>
                <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                <?php the_excerpt(); ?>
            </article>
        <?php endwhile; ?>
        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <p>Nothing found.</p>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
