<?php
/**
 * Plugin Name: BMD Performance Core
 * Description: Otimizacao seletiva e recomendacoes editoriais discretas do blog.
 * Version: 1.4.0
 * Author: Blog de Marketing Digital
 * Requires PHP: 7.4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * One-time guarded switch to Ad Inserter external JavaScript.
 * The complete original option is retained as a non-autoloaded backup.
 * Does not touch the ad code or placements.
 */
add_action( 'init', function () {
    if ( get_option( 'bmd_perf_adinserter_external_20261027', false ) ) { return; }
    $original = get_option( 'ad_inserter' );
    if ( ! is_string( $original ) || substr( $original, 0, 4 ) !== ':AI:' ) { return; }
    $decoded = base64_decode( substr( $original, 4 ), true );
    if ( $decoded === false || ! is_serialized( $decoded ) ) { return; }
    $settings = @unserialize( $decoded, array( 'allowed_classes' => false ) );
    if ( ! is_array( $settings ) || ! isset( $settings['global'] ) || ! is_array( $settings['global'] ) ) { return; }
    $mode = isset( $settings['global']['JAVASCRIPT_CODE_MODE'] ) ? (string) $settings['global']['JAVASCRIPT_CODE_MODE'] : null;
    $jquery = isset( $settings['global']['WAIT_FOR_JQUERY'] ) ? (string) $settings['global']['WAIT_FOR_JQUERY'] : null;
    if ( $mode !== '0' || $jquery !== '0' ) { return; }
    if ( ! add_option( 'bmd_perf_adinserter_original_20261027', $original, '', false ) ) {
        if ( ! is_string( get_option( 'bmd_perf_adinserter_original_20261027' ) ) ) { return; }
    }
    $settings['global']['JAVASCRIPT_CODE_MODE'] = '1';
    $updated = ':AI:' . base64_encode( serialize( $settings ) );
    if ( update_option( 'ad_inserter', $updated ) ) {
        update_option( 'bmd_perf_adinserter_external_20261027', array(
            'old' => '0',
            'new' => '1',
            'old_sha256' => hash( 'sha256', $original ),
            'new_sha256' => hash( 'sha256', $updated ),
        ), false );
    }
}, 20 );

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
 * Editorial recommendation: one subtle, relevant text link in a post.
 * No popups, no images, no tracking JavaScript, no price promises.
 */
add_filter( 'the_content', function( $content ) {
    if ( is_admin() || ! is_main_query() || ! in_the_loop() || ! is_singular( 'post' ) ||
         is_feed() || is_preview() ) {
        return $content;
    }
    $post_id = get_the_ID();
    if ( ! $post_id || post_password_required( $post_id ) ) {
        return $content;
    }
    $subject = remove_accents( strtolower( get_the_title( $post_id ) . ' ' . get_post_field( 'post_name', $post_id ) ) );
    $product = null;
    if ( preg_match( '~(instagram|direct|reels|comentarios)~', $subject ) &&
         preg_match( '~(instagram|direct|automac|automatiz|respostas|comentarios)~', $subject ) ) {
        $product = 'autodminsta';
    } elseif ( preg_match( '~(clonar|clonagem|clone|landing.page|elementor|webflow|framer|site.completo)~', $subject ) ) {
        $product = 'sitecloner';
    }
    if ( ! $product ) {
        return $content;
    }
    $offer_url = $product === 'autodminsta' ? 'https://autodminsta.com/' : 'https://siteclonerpro.com.br/';
    if ( stripos( $content, $offer_url ) !== false ||
         stripos( $content, rtrim( $offer_url, '/' ) ) !== false ) {
        return $content;
    }
    $offer_name = $product === 'autodminsta' ? 'Autodminsta' : 'SiteCloner Pro';
    $description = $product === 'autodminsta'
        ? 'Automatize respostas a comentarios e mensagens no Instagram.'
        : 'Capture referencias visuais e personalize seus projetos web.';
    $box = '<aside class="bmd-native-note" aria-label="Ferramenta relacionada" style="margin:24px 0 10px;padding:11px 14px;border-left:2px solid #bec7d1;background:transparent;font-size:.91em;line-height:1.5;">';
    $box .= '<span style="opacity:.68">Ferramenta relacionada:</span> ';
    $box .= '<a href="' . esc_url( $offer_url ) . '" rel="sponsored nofollow">' . esc_html( $offer_name ) . '</a>';
    $box .= ' <span style="opacity:.8"> — ' . esc_html( $description ) . '</span></aside>';
    return $content . "\n" . $box;
}, 35 );

/* Temporary numerical audit, removed after the comparison. */
add_action( 'rest_api_init', function () {
    register_rest_route( 'bmd-performance/v1', '/js-mode', array(
        'methods' => 'GET',
        'permission_callback' => '__return_true',
        'callback' => function () {
            $raw = get_option( 'ad_inserter' );
            $settings = is_string( $raw ) && substr( $raw, 0, 4 ) === ':AI:'
                ? @unserialize( base64_decode( substr( $raw, 4 ), true ), array( 'allowed_classes' => false ) )
                : array();
            $mode = isset( $settings['global']['JAVASCRIPT_CODE_MODE'] ) ? (string) $settings['global']['JAVASCRIPT_CODE_MODE'] : 'unknown';
            $result = array( 'module_version' => '1.4.0', 'javascript_code_mode' => $mode,
                             'switch_applied' => (bool) get_option( 'bmd_perf_adinserter_external_20261027', false ), 'pages' => array() );
            foreach ( array( 'home' => '/', 'article' => '/como-clonar-um-site-completo/', 'contact' => '/contato/' ) as $label => $path ) {
                $response = wp_remote_get( home_url( $path ), array( 'timeout' => 12, 'redirection' => 2, 'headers' => array( 'Accept' => 'text/html' ) ) );
                if ( is_wp_error( $response ) ) { $result['pages'][$label] = array( 'error' => 'fetch_failed' ); continue; }
                $html = (string) wp_remote_retrieve_body( $response );
                $js_total = 0;
                preg_match_all( '~<script\b([^>]*)>(.*?)</script\s*>~is', $html, $all, PREG_SET_ORDER );
                foreach ( $all as $tag ) {
                    if ( ! preg_match( '~\bsrc\s*=~i', $tag[1] ) ) { $js_total += strlen( trim( $tag[2] ) ); }
                }
                $result['pages'][$label] = array(
                    'http' => (int) wp_remote_retrieve_response_code( $response ),
                    'html_bytes' => strlen( $html ), 'inline_js_bytes' => $js_total,
                    'external_ai_file' => strpos( $html, '/ai-functions' ) !== false,
                    'has_adsense' => strpos( $html, 'adsbygoogle.js' ) !== false,
                    'has_referral' => strpos( $html, 'clubedigitalpro' ) !== false,
                    'has_native_note' => strpos( $html, 'bmd-native-note' ) !== false,
                );
            }
            return rest_ensure_response( $result );
        },
    ) );
} );
