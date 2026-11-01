<?php
/**
 * Lisbon MotorTours theme.
 * Tours are a post type with their own fields (no plugin needed).
 */

defined( 'ABSPATH' ) || exit;

define( 'LMT_VERSION', '1.1.0' );

function lisbonmotortours_assets() {
    wp_enqueue_style(
        'lisbon-fonts',
        'https://fonts.googleapis.com/css2?family=Teko:wght@400;500;600;700&display=swap',
        [],
        null
    );

    wp_enqueue_style(
        'lisbon-style',
        get_template_directory_uri() . '/assets/css/main.css',
        [],
        LMT_VERSION
    );

    wp_enqueue_script(
        'lisbon-js',
        get_template_directory_uri() . '/assets/js/main.js',
        [],
        LMT_VERSION,
        true
    );
}
add_action('wp_enqueue_scripts', 'lisbonmotortours_assets');

function lisbonmotortours_features() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form', 'gallery', 'caption', 'style', 'script']);
    register_nav_menus([
        'main-menu' => 'Main Menu'
    ]);
}
add_action('after_setup_theme', 'lisbonmotortours_features');

/** Menu shown until a menu is assigned to "Main Menu". */
function lisbonmotortours_menu_fallback() {
    $items = [
        home_url('/')                      => 'Home',
        get_post_type_archive_link('tour') => 'Tours',
    ];
    foreach (['about' => 'About', 'contact' => 'Contact', 'terms' => 'Terms'] as $slug => $label) {
        $page = get_page_by_path($slug);
        if ($page) {
            $items[get_permalink($page)] = $label;
        }
    }
    echo '<ul class="nav-links">';
    foreach ($items as $url => $label) {
        echo '<li><a href="' . esc_url($url) . '">' . esc_html($label) . '</a></li>';
    }
    echo '</ul>';
}

/** Menu links from the demo content file point at a placeholder address; send them to this site. */
add_filter('wp_setup_nav_menu_item', function ($item) {
    if ('custom' === $item->type && 0 === strpos($item->url, 'https://lisbonmotortours.invalid/')) {
        $item->url = home_url(substr($item->url, strlen('https://lisbonmotortours.invalid')));
    }
    return $item;
});

/* ---------- Tours ---------- */

function register_tours_cpt() {
    register_post_type('tour', [
        'labels' => [
            'name'          => 'Tours',
            'singular_name' => 'Tour',
            'add_new_item'  => 'Add tour',
            'edit_item'     => 'Edit tour',
        ],
        'public'       => true,
        'has_archive'  => true,
        'rewrite'      => ['slug' => 'tours', 'with_front' => false],
        'menu_icon'    => 'dashicons-location-alt',
        'show_in_rest' => true,
        'supports'     => ['title', 'editor', 'thumbnail', 'excerpt']
    ]);
}
add_action('init', 'register_tours_cpt');

// key => [label, input type, help]
function lmt_tour_fields() {
    return [
        'tour_date'       => ['Start date', 'date', ''],
        'tour_end_date'   => ['End date', 'date', ''],
        'tour_max_riders' => ['Max riders', 'number', ''],
        'tour_price'      => ['Price (€)', 'number', 'Shown as "Desde €…".'],
        'tour_programa'   => ['Programme PDF', 'url', 'Upload the file in Media and paste its address.'],
        'tour_termos'     => ['Terms PDF', 'url', ''],
        'tour_brochura'   => ['Brochure PDF', 'url', ''],
        'tour_form'       => ['Registration form shortcode', 'text', 'e.g. [fluentform id="5"]. Leave empty to show an email button.'],
    ];
}

function lmt_register_tour_meta() {
    foreach (lmt_tour_fields() as $key => $field) {
        register_post_meta('tour', $key, [
            'type'          => 'string',
            'single'        => true,
            'show_in_rest'  => true,
            'auth_callback' => function () {
                return current_user_can('edit_posts');
            },
        ]);
    }
}
add_action('init', 'lmt_register_tour_meta');

/** Field value. Dates come back as Y-m-d, whatever way they were stored (20260613 or 2026-06-13). */
function lmt_tour_meta($key, $post_id = 0) {
    $value = trim((string) get_post_meta($post_id ?: get_the_ID(), $key, true));
    if (in_array($key, ['tour_date', 'tour_end_date'], true) && preg_match('/^(\d{4})-?(\d{2})-?(\d{2})$/', $value, $m)) {
        return "$m[1]-$m[2]-$m[3]";
    }
    return $value;
}

function lmt_tour_date($key, $post_id = 0) {
    $value = lmt_tour_meta($key, $post_id);
    return $value ? date_i18n('d/m/Y', strtotime($value)) : '';
}

function lmt_tour_days($post_id = 0) {
    $start = lmt_tour_meta('tour_date', $post_id);
    $end   = lmt_tour_meta('tour_end_date', $post_id);
    if (!$start || !$end) {
        return 0;
    }
    return (new DateTime($start))->diff(new DateTime($end))->days + 1;
}

add_action('add_meta_boxes_tour', function () {
    add_meta_box('lmt_tour_details', 'Tour details', 'lmt_tour_meta_box', 'tour', 'normal', 'high');
});

function lmt_tour_meta_box($post) {
    wp_nonce_field('lmt_tour_save', 'lmt_tour_nonce');
    echo '<style>
        .lmt-fields{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:14px 20px}
        .lmt-fields label{display:block;font-weight:600;margin-bottom:4px}
        .lmt-fields input{width:100%}
        .lmt-fields .wide{grid-column:1/-1}
        .lmt-fields .help{color:#757575;font-size:12px;margin:3px 0 0}
    </style><div class="lmt-fields">';
    foreach (lmt_tour_fields() as $key => $field) {
        [$label, $type, $help] = $field;
        $wide = in_array($type, ['url', 'text'], true) ? ' class="wide"' : '';
        printf(
            '<p%s><label for="%s">%s</label><input type="%s" id="%s" name="%s" value="%s">%s</p>',
            $wide,
            esc_attr($key),
            esc_html($label),
            esc_attr($type),
            esc_attr($key),
            esc_attr($key),
            esc_attr(lmt_tour_meta($key, $post->ID)),
            $help ? '<span class="help">' . esc_html($help) . '</span>' : ''
        );
    }
    echo '</div>';
}

add_action('save_post_tour', function ($post_id) {
    if (!isset($_POST['lmt_tour_nonce']) || !wp_verify_nonce($_POST['lmt_tour_nonce'], 'lmt_tour_save')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    foreach (lmt_tour_fields() as $key => $field) {
        $raw = isset($_POST[$key]) ? wp_unslash($_POST[$key]) : '';
        if ('url' === $field[1]) {
            $value = esc_url_raw($raw);
        } elseif ('date' === $field[1]) {
            $value = preg_match('/^\d{4}-\d{2}-\d{2}$/', $raw) ? str_replace('-', '', $raw) : '';
        } elseif ('number' === $field[1]) {
            $value = '' === $raw ? '' : (string) max(0, (int) $raw);
        } else {
            $value = current_user_can('unfiltered_html') ? $raw : sanitize_text_field($raw);
        }
        update_post_meta($post_id, $key, $value);
    }
});

// Upcoming tours first, in date order. Dates are stored as Ymd, so they compare as numbers.
function lmt_upcoming_tours_query() {
    return new WP_Query([
        'post_type'      => 'tour',
        'posts_per_page' => -1,
        'meta_key'       => 'tour_date',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
        'meta_query'     => [[
            'key'     => 'tour_date',
            'value'   => current_time('Ymd'),
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ]],
    ]);
}
