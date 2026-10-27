<?php
/**
 * Plugin Name: BMD Performance Core
 * Description: Reducao seletiva de recursos na home e em artigos; preserva formularios, AdSense, Analytics e administracao.
 * Version: 1.2.0
 * Author: Blog de Marketing Digital
 * Requires PHP: 7.4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bmd_perf_is_formless_post() {
    if ( is_admin() || is_user_logged_in() || is_preview() || is_customize_preview() ) { return false; }
    if ( is_home() || is_front_page() ) { return true; }
    if ( ! is_singular( 'post' ) ) { return false; }
    $post_id = get_queried_object_id();
    if ( ! $post_id ) { return false; }
    $content = (string) get_post_field( 'post_content', $post_id );
    if ( preg_match( '~<form\b|\[contact-form|\[wpforms|\[forminator|\[gravityform|\[fluentform|\[elementor-template|recaptcha|g-recaptcha|elementor-form|form-field~i', $content ) ) { return false; }
    return true;
}

add_action( 'wp_enqueue_scripts', function () {
    if ( ! bmd_perf_is_formless_post() ) { return; }
    wp_dequeue_style( 'dashicons' );
    wp_dequeue_script( 'comment-reply' );
    wp_dequeue_script( 'wp-embed' );
}, 999 );

$GLOBALS['bmd_perf_head_buffer'] = false;
add_action( 'wp_head', function () {
    if ( ! bmd_perf_is_formless_post() ) { return; }
    $GLOBALS['bmd_perf_head_buffer'] = ob_get_level();
    ob_start();
}, -99999 );

add_action( 'wp_head', function () {
    $start_level = $GLOBALS['bmd_perf_head_buffer'];
    if ( $start_level === false || ob_get_level() !== $start_level + 1 ) { return; }
    $head = ob_get_clean();
    $GLOBALS['bmd_perf_head_buffer'] = false;
    $head = preg_replace(
        '~<script\b(?=[^>]*\bsrc\s*=\s*[\x22\x27]https?://(?:www\.)?google\.com/recaptcha/api\.js(?:\?[^\x22\x27]*)?[\x22\x27])[^>]*>\s*</script>~i',
        '',
        $head
    );
    echo $head;
}, 99999 );

foreach ( array( 'litespeed_optimize_js_excludes', 'litespeed_optm_js_defer_exc', 'litespeed_optm_gm_js_exc' ) as $hook ) {
    add_filter( $hook, function ( $excludes ) {
        return array_unique( array_merge( (array) $excludes, array( 'adsbygoogle' ) ) );
    } );
}


/**
 * Temporary read-only diagnostics: fixed internal URLs only, cached for 2 min.
 * No credentials, content, or arbitrary user-provided URLs are returned.
 */
add_action( 'rest_api_init', function () {
    register_rest_route( 'bmd-performance/v1', '/status', array(
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function () {
            $cached = get_transient( 'bmd_performance_diagnostic_20261027b' );
            if ( is_array( $cached ) ) { return rest_ensure_response( $cached ); }

            $loc = get_nav_menu_locations();
            $menu = get_term_by( 'slug', 'menuzao', 'nav_menu' );
            $res = array(
                'plugin_version' => '1.2.0',
                'theme' => wp_get_theme()->get_stylesheet(),
                'menus' => array(
                    'menuzao_id' => $menu ? (int) $menu->term_id : null,
                    'menuzao_items' => $menu ? count( wp_get_nav_menu_items( $menu ) ?: array() ) : 0,
                    'active_location_ids' => array_map( 'intval', $loc ),
                ),
                'pages' => array(),
            );
            foreach ( array( 'home' => '/', 'post' => '/como-clonar-um-site-completo/', 'contact' => '/contato/' ) as $name => $path ) {
                $response = wp_remote_get( home_url( $path ), array(
                    'timeout' => 9,
                    'redirection' => 2,
                    'sslverify' => true,
                    'headers' => array( 'Accept' => 'text/html' ),
                    'user-agent' => 'BMD-Performance-Diagnostics/1.0',
                ) );
                if ( is_wp_error( $response ) ) {
                    $res['pages'][$name] = array( 'error' => substr( $response->get_error_message(), 0, 100 ) );
                    continue;
                }
                $body = (string) wp_remote_retrieve_body( $response );
                $head = explode( '</head>', $body, 2 )[0];
                $headers = wp_remote_retrieve_headers( $response );
                $res['pages'][$name] = array(
                    'http' => (int) wp_remote_retrieve_response_code( $response ),
                    'bytes' => strlen( $body ),
                    'recaptcha_script' => strpos( $head, 'recaptcha/api.js' ) !== false,
                    'adsbygoogle_script' => strpos( $head, 'adsbygoogle.js' ) !== false,
                    'dashicons_stylesheet' => preg_match( '~dashicons(?:\.min)?\.css~i', $head ) === 1,
                    'simple_social_buttons' => stripos( $body, 'simple-social-buttons' ) !== false,
                    'links_to_privacy' => strpos( $body, '/politica-de-privacidade/' ) !== false,
                    'headers' => array(
                        'cache_control' => isset( $headers['cache-control'] ) ? (string) $headers['cache-control'] : '',
                        'x_litespeed_cache' => isset( $headers['x-litespeed-cache'] ) ? (string) $headers['x-litespeed-cache'] : '',
                        'x_hcdn_cache_status' => isset( $headers['x-hcdn-cache-status'] ) ? (string) $headers['x-hcdn-cache-status'] : '',
                        'age' => isset( $headers['age'] ) ? (string) $headers['age'] : '',
                    ),
                );
            }
            set_transient( 'bmd_performance_diagnostic_20261027b', $res, 120 );
            return rest_ensure_response( $res );
        },
    ) );
} );
