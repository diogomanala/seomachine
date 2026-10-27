<?php
/**
 * Plugin Name: BMD Performance Core
 * Description: Otimizacao seletiva e recomendacoes editoriais discretas do blog.
 * Version: 1.8.2
 * Author: Blog de Marketing Digital
 * Requires PHP: 7.4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }


/**
 * WordPress 6.9+ still prints ~3KB emoji detection module in the footer.
 * Browser-native emojis work without it; admin and editor are unchanged.
 */
add_action( 'init', function() {
    if ( is_admin() ) { return; }
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
}, 1 );


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
    // Apply only when content does not use galleries, sliders, or lightbox.
    $content = is_singular( 'post' ) ? (string) get_post_field( 'post_content', get_queried_object_id() ) : '';
    $interactive_media = preg_match( '~\[(?:gallery|envira|slider|carousel)\b|wp-block-gallery|data-fancybox|lightbox|oceanwp-slider|flickity|swiper|slick|elementor-gallery~i', $content );
    if ( ! $interactive_media ) {
        $unneeded = array( 'ow-flickity', 'oceanwp-slider', 'ow-magnific-popup', 'oceanwp-lightbox', 'oceanwp-select' );
        foreach ( $unneeded as $unused_handle ) {
            wp_dequeue_script( $unused_handle );
        }
        // OceanWP accumulates vendor deps in subsequent scroll/utility scripts.
        // Remove only the unused vendor dependencies from OceanWP-owned scripts;
        // never change third-party dependencies, navigation or core jQuery.
        $scripts = wp_scripts();
        if ( $scripts && ! empty( $scripts->registered ) ) {
            foreach ( $scripts->registered as $handle => $script ) {
                $src = isset( $script->src ) ? (string) $script->src : '';
                if ( strpos( $src, '/themes/oceanwp/assets/js/' ) === false ) {
                    continue;
                }
                if ( in_array( $handle, $unneeded, true ) ) {
                    continue;
                }
                $script->deps = array_values( array_diff( (array) $script->deps, array( 'ow-flickity', 'ow-magnific-popup' ) ) );
            }
        }
        wp_dequeue_script( 'ow-flickity' );
        wp_dequeue_script( 'ow-magnific-popup' );
    }
    wp_dequeue_script( 'comment-reply' );
    wp_dequeue_script( 'wp-embed' );
}, 999 );

/**
 * On the blog listing, prioritize the lead article image only.
 * Subsequent featured images can load as they approach the viewport.
 * This does not affect single posts, the contact page, or the Hostinger banner.
 */
add_filter( 'post_thumbnail_html', function( $html ) {
    if ( is_admin() || is_user_logged_in() || ! ( is_home() || is_front_page() ) ) {
        return $html;
    }
    static $featured = 0;
    if ( ! preg_match( '~<img\b~i', $html ) ) {
        return $html;
    }
    $featured++;
    if ( $featured <= 1 ) {
        return $html;
    }
    if ( preg_match( '~\bloading\s*=\s*["\x27][^"\x27]+["\x27]~i', $html ) ) {
        $html = preg_replace( '~\bloading\s*=\s*(["\x27])[^"\x27]*\1~i', 'loading="lazy"', $html, 1 );
    } else {
        $html = preg_replace( '~<img\b~i', '<img loading="lazy"', $html, 1 );
    }
    $html = preg_replace( '~\sfetchpriority\s*=\s*(["\x27])high\1~i', '', $html, 1 );
    return $html;
}, 50 );

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

/**
 * Evergreen internal navigation for the first blog home page only.
 * Uses real existing editorial category URLs; no external CSS/JS, ads or widgets.
 * Executed before the OceanWP archive loop and suppressed on paginated archives.
 */
add_action( 'ocean_before_content_inner', function () {
    if ( is_admin() || ! is_home() || is_paged() || is_feed() || is_preview() ) {
        return;
    }
    $topics = array(
        'Marketing Digital' => '/category/marketing-digital/',
        'SEO' => '/category/seo/',
        'Inteligência artificial e automação' => '/category/inteligencia-artificial-automacao/',
        'Tráfego pago' => '/category/trafego/',
        'Criação de sites e WordPress' => '/category/wordpress/',
        'Mídias sociais' => '/category/midias-sociais-2/',
    );
    echo '<section class="bmd-home-topics" aria-labelledby="bmd-home-topics-title" style="margin:0 0 22px;padding:0 0 16px;border-bottom:1px solid #e7ebf0;">';
    echo '<h2 id="bmd-home-topics-title" style="font-size:1.24em;line-height:1.35;margin:0 0 8px;">Marketing digital na prática: explore nossos guias</h2>';
    echo '<p style="margin:0 0 8px;line-height:1.5;">Aprenda estratégias de marketing digital, encontre ferramentas e acompanhe tutoriais de SEO, IA, anúncios e desenvolvimento web.</p>';
    echo '<nav aria-label="Assuntos do blog"><p style="margin:0;line-height:1.8;">';
    $first = true;
    foreach ( $topics as $name => $path ) {
        if ( ! $first ) { echo ' <span aria-hidden="true" style="color:#9099a5;padding:0 5px;">·</span> '; }
        echo '<a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $name ) . '</a>';
        $first = false;
    }
    echo '</p></nav>';
    $guides = array(
        'Análise SEO gratuita: 23 ferramentas' => '/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/',
        'Como descobrir a tecnologia de um site' => '/como-descobrir-plataforma-do-site/',
        'Como automatizar o Direct no Instagram' => '/automatizar-direct-instagram-comentarios/',
        'Quanto custa anunciar no Google Ads' => '/quanto-custa-google-ads/',
    );
    echo '<div class="bmd-evergreen-guides" style="margin-top:13px;">';
    echo '<h3 style="font-size:1.05em;line-height:1.4;margin:0 0 6px;">Guias essenciais</h3>';
    echo '<ul style="margin:0;padding-left:20px;line-height:1.8;">';
    foreach ( $guides as $label => $path ) {
        echo '<li><a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a></li>';
    }
    echo '</ul></div></section>';
}, 5 );

/**
 * Original decision table for an established, manually researched SEO guide.
 * Only post 2402. No injected third-party scripts or sales promotions.
 */
add_filter( 'the_content', function ( $content ) {
    if ( is_admin() || ! is_main_query() || ! in_the_loop() || ! is_singular( 'post' ) ||
        get_the_ID() !== 2402 || is_preview() || is_feed() || strpos( $content, 'bmd-seo-quick-compare' ) !== false ) {
        return $content;
    }
    $rows = array(
        array( 'Google Search Console', 'Buscas, cliques, indexação e sitemaps', 'Grátis; é preciso verificar o site' ),
        array( 'PageSpeed Insights', 'LCP, CLS e problemas de carregamento', 'Grátis; analisa uma URL por vez' ),
        array( 'Screaming Frog SEO Spider', 'Rastrear links, títulos e erros técnicos', 'Versão gratuita: até 500 URLs por rastreamento' ),
        array( 'Ahrefs Free', 'Backlinks e auditoria do próprio domínio', 'Site verificado; créditos mensais limitados' ),
        array( 'Google Trends', 'Interesse e sazonalidade dos termos', 'Grátis; não mede volume absoluto de buscas' ),
        array( 'Rich Results Test', 'Elegibilidade para resultados avançados', 'Grátis; não garante exibição de rich results' ),
    );
    $table = '<section id="bmd-seo-quick-compare" aria-labelledby="bmd-seo-quick-title" style="margin:24px 0 28px;">';
    $table .= '<h2 id="bmd-seo-quick-title">Comparativo rápido: qual ferramenta de SEO gratuita usar?</h2>';
    $table .= '<p>Escolha pela tarefa, não pela promessa de uma nota única. Primeiro confira os dados reais do seu site; depois use rastreadores e comparadores para investigar problemas específicos.</p>';
    $table .= '<div style="max-width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;">';
    $table .= '<table style="width:100%;min-width:540px;border-collapse:collapse;font-size:.94em;"><thead><tr>';
    foreach( array( 'Ferramenta', 'Melhor para', 'Limite importante' ) as $h ) {
        $table .= '<th scope="col" style="text-align:left;border-bottom:2px solid #d7dce3;padding:9px 11px;">' . esc_html( $h ) . '</th>';
    }
    $table .= '</tr></thead><tbody>';
    foreach ( $rows as $row ) {
        $table .= '<tr>';
        foreach ( $row as $cell ) {
            $table .= '<td style="vertical-align:top;border-bottom:1px solid #e5e7eb;padding:8px 11px;">' . esc_html( $cell ) . '</td>';
        }
        $table .= '</tr>';
    }
    $table .= '</tbody></table></div>';
    $table .= '<p style="font-size:.85em;margin-top:9px;color:#667085;">Os limites gratuitos podem mudar. Consulte as condições atuais antes de escolher uma ferramenta.</p>';
    $table .= '</section>';
    if ( strpos( $content, '</p>' ) !== false ) {
        return preg_replace_callback( '~</p>~i', function ( $m ) use ( $table ) { return $m[0] . $table; }, $content, 1 );
    }
    return $table . $content;
}, 24 );
