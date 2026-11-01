<?php
register_shutdown_function( function () { file_put_contents( '/wordpress/dev/seed.log', print_r( error_get_last(), true ) ); } );
/**
 * Local test only (run by dev/blueprint.json): imports the content file without downloading
 * the media, then attaches the local media files to the tour.
 */
require_once '/wordpress/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/post.php';
require_once ABSPATH . 'wp-admin/includes/comment.php';
require_once ABSPATH . 'wp-admin/includes/taxonomy.php';
require_once ABSPATH . 'wp-admin/includes/import.php';
define( 'WP_LOAD_IMPORTERS', true );
require_once ABSPATH . 'wp-admin/includes/class-wp-importer.php';
foreach ( array( 'php-toolkit/load.php', 'parsers.php', 'compat.php', 'wordpress-importer.php', 'class-wp-import.php' ) as $f ) { if ( file_exists( WP_PLUGIN_DIR . '/wordpress-importer/' . $f ) ) { require_once WP_PLUGIN_DIR . '/wordpress-importer/' . $f; } }

register_tours_cpt();
$importer = new WP_Import();
$importer->fetch_attachments = false;
ob_start();
$importer->import( '/wordpress/dev/content.xml' );
ob_end_clean();

$tour = get_posts( array( 'post_type' => 'tour', 'numberposts' => 1 ) )[0];
$thumb = 0;
foreach ( array( 'GM251010A-1920w.webp', 'pexels-fernanda-w-corso-2149339004-32031408-1.jpg', 'Carpatos-1.jpg', 'Carpatos-montanha-trekking-01_ctRovC6-1.jpg' ) as $file ) {
	$upload = wp_upload_bits( $file, null, file_get_contents( '/wordpress/media/' . $file ) );
	$id = wp_insert_attachment( array(
		'post_mime_type' => wp_check_filetype( $upload['file'] )['type'],
		'post_title'     => $file,
		'post_status'    => 'inherit',
	), $upload['file'], $tour->ID );
	wp_update_attachment_metadata( $id, wp_generate_attachment_metadata( $id, $upload['file'] ) );
	$thumb = $thumb ?: $id;
}
set_post_thumbnail( $tour->ID, $thumb );

$home = get_page_by_path( 'home' );
update_option( 'blogdescription', 'Motorcycle adventure across Romania' );
update_option( 'show_on_front', 'page' );
update_option( 'page_on_front', $home->ID );
flush_rewrite_rules( false );
