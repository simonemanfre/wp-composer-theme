<?php

define( 'THEME_URL', get_template_directory_uri() . '/' );
define( 'THEME_DIR', dirname(__FILE__).'/' );

//ENABLE CUSTOM POST TYPE
//require_once(THEME_DIR . 'inc/trapstudio/cpt.php');

require_once(THEME_DIR . 'inc/trapstudio/scripts.php');
require_once(THEME_DIR . 'inc/trapstudio/api.php');
require_once(THEME_DIR . 'inc/trapstudio/ajax-api.php');
require_once(THEME_DIR . 'inc/trapstudio/security.php');
require_once(THEME_DIR . 'inc/trapstudio/backoffice.php');

//DISABLE COMMENTS
require_once(THEME_DIR . 'inc/trapstudio/comments.php');

//ACF
if( function_exists('acf_add_options_page') ):
    require_once(THEME_DIR . 'inc/trapstudio/acf.php');
endif;

//WOOCOMMERCE
if( class_exists('woocommerce') ):
    require_once(THEME_DIR . 'inc/trapstudio/woocommerce.php');
endif;


//MENU
add_theme_support( 'nav-menus' );
if ( function_exists( 'register_nav_menus' ) ) {
	register_nav_menus( array('Primario' => __( 'Navigazione primaria') ) );
	//register_nav_menus( array('Secondario' => __( 'Navigazione secondaria') ) );
}


//THUMBNAILS
add_theme_support('post-thumbnails' );
//add_image_size('customThumbSize', 180, 120, true);


//DISABILITO EDITOR TEMA
function disable_mytheme_action() {
    define('DISALLOW_FILE_EDIT', TRUE);
}
add_action('init','disable_mytheme_action');


//DISABLE EDITOR FULLSCREEN BY DEFAULT
function ghub_disable_editor_fullscreen_mode() {
	$script = "window.onload = function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } }";
	wp_add_inline_script( 'wp-blocks', $script );
}
add_action( 'enqueue_block_editor_assets', 'ghub_disable_editor_fullscreen_mode' );


//MOVE YOAST SETTINGS PANEL IN EDITOR TO BOTTOM
function yoasttobottom() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', 'yoasttobottom');


//WEBP
add_filter( 'image_editor_output_format', function( $formats ) {
    $formats['image/jpeg'] = 'image/webp';
    $formats['image/png'] = 'image/webp';
    return $formats;
} );

function filter_webp_quality( $quality, $mime_type ) {
    if ( 'image/webp' === $mime_type ) {
        return 75;
    }
    return $quality;
}
add_filter( 'wp_editor_set_quality', 'filter_webp_quality', 10, 2 );


//AGGIUNGO SUPPORTO EXCERPT NELLE PAGINE
add_post_type_support('page', 'excerpt');


//DISABILITO GLOBAL STYLES (da testare)
/*
add_action( 'init', function(){
    remove_action( 'wp_enqueue_scripts', 'wp_enqueue_global_styles' );
    remove_action( 'wp_footer', 'wp_enqueue_global_styles', 1 );
} );
*/


//LIMIT EXCERPT (Per limitare le parole dei riassunti)
/*
function tn_custom_excerpt_length( $length ) {
    return 10;
}
add_filter( 'excerpt_length', 'tn_custom_excerpt_length', 999 );
*/


//CF7
if (function_exists('wpcf7')) {
    add_filter('wpcf7_autop_or_not', '__return_false');
}


//LIMITO REVISIONI POSTS
add_filter( 'wp_revisions_to_keep', 'filter_function_name', 10, 2 );

function filter_function_name( $num, $post ) {
    return 10;
}


//RESPONSIVE EMBEDS
add_filter('embed_oembed_html', 'bs_embed_oembed_html', 99, 4);
function bs_embed_oembed_html($html, $url, $attr, $post_id) {
  return '<div class="embed-responsive embed-responsive-16by9">' . $html . '</div>';
}


//TIMESTAMP
function the_debug_timestamp(){
    echo date("YmdHis");
}


/* DEBUG LOG 

INSERIRE IN WP-CONFIG.PHP:
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);

UTILIZZO:
write_log($valore_da_stampare);

(Il file di debug si troverà in FTP dentro wp-content)
*/

if (!function_exists('write_log')) {
    function write_log($log) {
        if (true === WP_DEBUG) {
            if (is_array($log) || is_object($log)) {
                error_log(print_r($log, true));
            } else {
                error_log($log);
            }
        }
    }
}
	
?>