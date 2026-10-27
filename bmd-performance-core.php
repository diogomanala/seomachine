<?php
/**
 * Plugin Name: BMD Performance Core
 * Description: Otimizacao seletiva e recomendacoes editoriais discretas do blog.
 * Version: 1.3.1
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
