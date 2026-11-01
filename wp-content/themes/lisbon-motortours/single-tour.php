<?php get_header(); ?>

<?php while (have_posts()) : the_post(); ?>

<div class="tour-page">

    <h1 class="tour-title"><?php the_title(); ?></h1>

    <?php
    $images = get_attached_media('image', get_the_ID());
    if ($images) :
    ?>
        <div class="tour-slider">
            <?php foreach ($images as $image) :
                $img = wp_get_attachment_image_src($image->ID, 'large'); ?>
                <div class="slide">
                    <img src="<?php echo esc_url($img[0]); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                </div>
            <?php endforeach; ?>

            <button class="slider-btn prev" aria-label="Anterior">‹</button>
            <button class="slider-btn next" aria-label="Seguinte">›</button>
        </div>
    <?php endif; ?>

    <div class="tour-description">
        <?php the_content(); ?>
    </div>

    <?php
    $start  = lmt_tour_date('tour_date');
    $end    = lmt_tour_date('tour_end_date');
    $riders = lmt_tour_meta('tour_max_riders');
    $days   = lmt_tour_days();
    ?>

    <div class="tour-details-box">
        <h2>Detalhes do Tour</h2>

        <div class="tour-details-line">
            <?php if ($start) : ?>
                <div class="detail-item"><span>📅 <?php echo esc_html($start); ?></span></div>
            <?php endif; ?>
            <?php if ($end) : ?>
                <div class="detail-item"><span>📅 <?php echo esc_html($end); ?></span></div>
            <?php endif; ?>
            <?php if ($riders) : ?>
                <div class="detail-item"><span>👥 <?php echo esc_html($riders); ?> Motociclistas</span></div>
            <?php endif; ?>
            <?php if ($days) : ?>
                <div class="detail-item"><span>⏱ <?php echo esc_html($days); ?> Dias</span></div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    $documents = [
        'tour_programa' => '📄 Programa do Tour',
        'tour_termos'   => '📑 Termos e Condições',
        'tour_brochura' => '📘 Brochura',
    ];
    $documents = array_filter(array_map(function ($key, $label) {
        $url = lmt_tour_meta($key);
        return $url ? ['url' => $url, 'label' => $label] : null;
    }, array_keys($documents), $documents));
    ?>

    <?php if ($documents) : ?>
        <div class="tour-documents">
            <h2>Documentação do Tour</h2>
            <ul class="tour-documents-list">
                <?php foreach ($documents as $doc) : ?>
                    <li>
                        <a href="<?php echo esc_url($doc['url']); ?>" target="_blank" rel="noopener">
                            <?php echo esc_html($doc['label']); ?>
                        </a>
                        <a href="<?php echo esc_url($doc['url']); ?>" download class="download-link">
                            Download
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="tour-form-section">
        <h2>Registar para esta Tour</h2>
        <?php
        $form = lmt_tour_meta('tour_form');
        if ($form) {
            echo do_shortcode($form);
        } else {
            $subject = rawurlencode('Registo: ' . get_the_title());
            $mailto = 'mailto:' . get_option('admin_email') . '?subject=' . $subject;
            echo '<a class="cta-button" href="' . esc_url($mailto) . '">Pedir informações por email</a>';
        }
        ?>
    </div>

</div>

<?php endwhile; ?>

<?php get_footer(); ?>
