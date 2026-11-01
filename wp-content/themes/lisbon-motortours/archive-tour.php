<?php get_header(); ?>

<div class="page-content active">
    <h2 class="page-title">Tours</h2>

    <?php
    $upcoming = lmt_tours_query('>=', 'ASC');
    $past     = lmt_tours_query('<', 'DESC');
    ?>

    <div class="tours-grid">
        <?php if ($upcoming->have_posts()) : ?>
            <?php while ($upcoming->have_posts()) : $upcoming->the_post(); ?>
                <?php get_template_part('template-parts/tour-card'); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        <?php elseif (!$past->have_posts()) : ?>
            <p>Não há tours agendados de momento.</p>
        <?php else : ?>
            <p>Não há novos tours agendados de momento. Veja os anteriores abaixo.</p>
        <?php endif; ?>
    </div>

    <?php if ($past->have_posts()) : ?>
        <h2 class="page-title tours-past-title">Tours anteriores</h2>
        <div class="tours-grid">
            <?php while ($past->have_posts()) : $past->the_post(); ?>
                <?php get_template_part('template-parts/tour-card'); ?>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
    <?php endif; ?>
</div>

<?php get_footer(); ?>
