<?php get_header(); ?>

<div class="page-content active">
    <h2 class="page-title">Tours</h2>

    <div class="tours-grid">

        <?php
        $tours = lmt_upcoming_tours_query();

        if ($tours->have_posts()) :
            while ($tours->have_posts()) : $tours->the_post();
                $start  = lmt_tour_date('tour_date');
                $end    = lmt_tour_date('tour_end_date');
                $riders = lmt_tour_meta('tour_max_riders');
                $price  = lmt_tour_meta('tour_price');
                $days   = lmt_tour_days();
        ?>
        <a href="<?php the_permalink(); ?>" class="tour-card-link">
            <div class="tour-card">

                <div class="tour-image">
                    <?php the_post_thumbnail('medium'); ?>
                </div>

                <div class="tour-info">
                    <h3><?php the_title(); ?></h3>

                    <?php the_excerpt(); ?>

                    <div class="tour-meta">
                        <?php if ($start && $end) : ?>
                            <span>📅 De <?php echo esc_html($start); ?> a <?php echo esc_html($end); ?></span>
                        <?php elseif ($start) : ?>
                            <span>📅 <?php echo esc_html($start); ?></span>
                        <?php endif; ?>
                        <span>
                            <?php if ($days) : ?>⏱ <?php echo esc_html($days); ?> Dias<?php endif; ?>
                            <?php if ($days && $riders) : ?> - <?php endif; ?>
                            <?php if ($riders) : ?>👥 <?php echo esc_html($riders); ?> motociclistas<?php endif; ?>
                        </span>
                    </div>

                    <?php if ($price) : ?>
                        <div class="tour-price">
                            Desde €<?php echo esc_html($price); ?>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </a>

        <?php
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>Não há tours agendados de momento.</p>';
        endif;
        ?>

    </div>
</div>

<?php get_footer(); ?>
