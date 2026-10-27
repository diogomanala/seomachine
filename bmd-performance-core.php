<?php
/**
 * Plugin Name: BMD Performance Core
 * Description: Otimizacao seletiva e recomendacoes editoriais discretas do blog.
 * Version: 2.1.1
 * Author: Blog de Marketing Digital
 * Requires PHP: 7.4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/** Premium OceanWP design and organized free tools directory. */
$bmd_premium_layout_file = __DIR__ . '/bmd-premium-layout.php';
if ( is_file( $bmd_premium_layout_file ) ) {
    require_once $bmd_premium_layout_file;
}



/**
 * WordPress 6.9+ still prints ~3KB emoji detection module in the footer.
 * Browser-native emojis work without it; admin and editor are unchanged.
 */
add_action( 'init', function() {
    if ( is_admin() ) { return; }
    remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
    remove_action( 'wp_print_styles', 'print_emoji_styles' );
}, 1 );


/**
 * The browser-only UTM tool has no captcha-backed submission. On this single
 * page suppress third-party reCAPTCHA JS emitted through WordPress enqueue.
 * Keep reCAPTCHA untouched on contact, Elementor forms and other pages.
 */
add_filter( 'script_loader_tag', function( $tag, $handle, $src ) {
    if ( ! is_admin() && ( is_page( 'gerador-de-utm' ) || is_page( 'calculadora-roas-cac-lucro' ) ) &&
        preg_match( '~(?:^|https?://)(?:www\.)?google\.com/recaptcha/api\.js~i', html_entity_decode( (string) $src ) ) ) {
        return '';
    }
    return $tag;
}, 99, 3 );

function bmd_perf_is_formless_post() {
    if ( is_admin() || is_user_logged_in() || is_preview() || is_customize_preview() ) { return false; }
    if ( is_home() || is_front_page() || is_page( 'gerador-de-utm' ) || is_page( 'calculadora-roas-cac-lucro' ) ) { return true; }
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
        'Gerador de UTM grátis para campanhas' => '/gerador-de-utm/',
        'Calculadora de ROAS, CAC e lucro' => '/calculadora-roas-cac-lucro/',
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


/**
 * Link-worthy utility: lightweight GA4 campaign URL builder.
 * Entirely browser-side, no remote APIs, pixels, cookies or storage.
 */
add_shortcode( 'bmd_utm_builder', function () {
    return <<<'BMDUTMHTML'
<div id="bmd-utm-tool" class="bmd-utm">
<style>
.bmd-utm{--ink:#172237;--muted:#5c697d;--accent:#d54f26;font-family:inherit;color:var(--ink);max-width:970px;margin:24px 0}.bmd-utm *{box-sizing:border-box}
.bmd-utm .bmd-card{border:1px solid #e4e9f0;border-radius:15px;padding:22px;background:#fff;box-shadow:0 8px 30px rgba(25,38,55,.035)}
.bmd-utm .bmd-utm-presets{display:flex;flex-wrap:wrap;gap:8px;margin:0 0 18px}
.bmd-utm .bmd-preset{background:#f4f6f9;color:#293a52;border:1px solid #dce3ed;border-radius:999px;padding:7px 11px;font-size:13px;cursor:pointer}
.bmd-utm .bmd-preset:hover,.bmd-utm .bmd-preset:focus-visible{border-color:#d54f26;color:#ad3e20}
.bmd-utm .bmd-fields{display:grid;grid-template-columns:1fr 1fr;gap:14px}
.bmd-utm .bmd-full{grid-column:1/-1}.bmd-utm label{display:block;font-weight:650;font-size:14px;margin:0 0 5px}
.bmd-utm input{display:block;width:100%;border:1px solid #cdd5e0;border-radius:8px;padding:11px 12px;background:#fff;color:#172237;font-size:15px;line-height:1.4;min-height:43px}
.bmd-utm input:focus{outline:2px solid #f0b6a3;outline-offset:1px;border-color:#ca4a27}
.bmd-utm .bmd-help{display:block;font-size:12px;color:#637084;margin:5px 0 0}
.bmd-utm .bmd-result{background:#f8fafc;border:1px solid #e1e7ee;padding:14px;border-radius:10px;margin-top:18px}
.bmd-utm .bmd-result output{display:block;overflow-wrap:anywhere;line-height:1.6;min-height:35px;font-size:14px;font-family:ui-monospace,SFMono-Regular,Consolas,monospace;color:#243852}
.bmd-utm .bmd-actions{display:flex;flex-wrap:wrap;align-items:center;gap:10px;margin-top:10px}
.bmd-utm .bmd-primary{padding:11px 19px;border:0;border-radius:8px;background:#d54f26;color:#fff;font-weight:700;cursor:pointer}
.bmd-utm .bmd-primary:hover{background:#ae3f20}.bmd-utm .bmd-primary:disabled{background:#9ca3af;cursor:not-allowed}
.bmd-utm .bmd-status{font-size:13px;color:#59687a}.bmd-utm .bmd-status[role=alert]{color:#af2e1a}
.bmd-utm .bmd-detail{border:1px solid #e4e9f0;border-radius:8px;margin:13px 0;padding:8px 12px}
.bmd-utm .bmd-detail summary{cursor:pointer;font-weight:600}
@media(max-width:650px){.bmd-utm .bmd-card{padding:15px}.bmd-utm .bmd-fields{grid-template-columns:1fr}.bmd-utm input{font-size:16px}}
</style>
<div class="bmd-card">
<p style="margin:0 0 11px;color:#5c697d;font-size:14px">Escolha um canal ou preencha os campos. O link é gerado neste navegador.</p>
<div class="bmd-utm-presets" aria-label="Modelos de canais">
<button type="button" class="bmd-preset" data-source="instagram" data-medium="organic_social">Instagram orgânico</button>
<button type="button" class="bmd-preset" data-source="instagram" data-medium="paid_social">Instagram Ads</button>
<button type="button" class="bmd-preset" data-source="facebook" data-medium="paid_social">Facebook Ads</button>
<button type="button" class="bmd-preset" data-source="whatsapp" data-medium="referral">WhatsApp</button>
<button type="button" class="bmd-preset" data-source="newsletter" data-medium="email">E-mail</button>
<button type="button" class="bmd-preset" data-source="youtube" data-medium="organic_video">YouTube</button>
</div>
<form id="bmd-utm-form" autocomplete="off" novalidate>
<div class="bmd-fields">
<div class="bmd-full"><label for="bmd-url">URL do site ou página <span aria-hidden="true">*</span></label><input id="bmd-url" name="landing_url" type="text" inputmode="url" placeholder="https://seusite.com.br/oferta/" required><span class="bmd-help">Aceita URLs com parâmetros já existentes e preserva a âncora (#).</span></div>
<div><label for="bmd-source">Origem — utm_source *</label><input id="bmd-source" name="utm_source" placeholder="instagram" required><span class="bmd-help">A plataforma que enviou a visita.</span></div>
<div><label for="bmd-medium">Mídia — utm_medium *</label><input id="bmd-medium" name="utm_medium" placeholder="paid_social" required><span class="bmd-help">O tipo do canal: email, paid_social, referral...</span></div>
<div class="bmd-full"><label for="bmd-campaign">Campanha — utm_campaign *</label><input id="bmd-campaign" name="utm_campaign" placeholder="lancamento_outubro" required><span class="bmd-help">Use uma convenção estável para nomes e campanhas.</span></div>
</div>
<details class="bmd-detail"><summary>Campos avançados (opcionais)</summary><div class="bmd-fields" style="margin-top:13px">
<div><label for="bmd-content">Criativo — utm_content</label><input id="bmd-content" name="utm_content" placeholder="reels_abertura_a"></div>
<div><label for="bmd-term">Termo — utm_term</label><input id="bmd-term" name="utm_term" placeholder="palavra_chave"></div>
<div class="bmd-full"><label for="bmd-id">ID — utm_id</label><input id="bmd-id" name="utm_id" placeholder="campanha_001"></div>
</div></details>
<div class="bmd-result"><strong>URL com UTM</strong><output id="bmd-utm-output" aria-live="polite">Preencha a URL e os três campos obrigatórios.</output>
<div class="bmd-actions"><button class="bmd-primary" type="submit" id="bmd-utm-copy" disabled>Copiar URL</button><span class="bmd-status" id="bmd-utm-status" aria-live="polite"></span></div>
</div></form>
</div>
<p style="font-size:13px;color:#637084;margin-top:11px">Privacidade: a URL e os parâmetros digitados não são enviados ao nosso servidor. A geração é local, no seu dispositivo.</p>
<script>
(function(){
'use strict';
var root=document.getElementById('bmd-utm-tool');if(!root||root.dataset.ready==='1')return;root.dataset.ready='1';
var form=root.querySelector('#bmd-utm-form'),out=root.querySelector('#bmd-utm-output'),copy=root.querySelector('#bmd-utm-copy'),status=root.querySelector('#bmd-utm-status');
var ids={'utm_source':'bmd-source','utm_medium':'bmd-medium','utm_campaign':'bmd-campaign','utm_content':'bmd-content','utm_term':'bmd-term','utm_id':'bmd-id'};
var last='';
function clean(v){return String(v||'').trim().normalize('NFD').replace(/[\u0300-\u036f]/g,'').toLowerCase().replace(/[^a-z0-9._-]+/g,'_').replace(/^_+|_+$/g,'').replace(/_+/g,'_');}
function field(k){return root.querySelector('#'+ids[k]).value;}
function make(){
var val=root.querySelector('#bmd-url').value.trim();
if(!val){return{error:'Informe a URL de destino.'};}
if(!/^https?:\/\//i.test(val)){if(/^[a-z][a-z0-9+.-]*:/i.test(val))return{error:'Use apenas URLs HTTP ou HTTPS.'};val='https://'+val;}
var u;try{u=new URL(val);}catch(e){return{error:'Informe uma URL válida.'};}
if(!/^https?:$/.test(u.protocol)||!u.hostname||(!u.hostname.includes('.')&&u.hostname!=='localhost'))return{error:'Informe um site válido com HTTP ou HTTPS.'};
for(var k of ['utm_source','utm_medium','utm_campaign'])if(!clean(field(k)))return{error:'Preencha origem, mídia e campanha.'};
for(var key in ids){var value=clean(field(key));if(value)u.searchParams.set(key,value);else if(u.searchParams.has(key))u.searchParams.delete(key);}
return{url:u.href};
}
function render(){var v=make();last=v.url||'';out.textContent=last||'Preencha uma URL válida, origem, mídia e campanha.';copy.disabled=!last;status.textContent='';status.removeAttribute('role');}
root.querySelectorAll('input').forEach(function(i){i.addEventListener('input',render);});
root.querySelectorAll('[data-source]').forEach(function(b){b.addEventListener('click',function(){root.querySelector('#bmd-source').value=b.dataset.source;root.querySelector('#bmd-medium').value=b.dataset.medium;render();root.querySelector('#bmd-campaign').focus();});});
form.addEventListener('submit',function(e){
e.preventDefault();var v=make();if(!v.url){status.textContent=v.error;status.setAttribute('role','alert');return;}
var done=function(){status.textContent='URL copiada!';status.removeAttribute('role');};
var fallback=function(){var temp=document.createElement('textarea');temp.value=v.url;temp.style.position='fixed';temp.style.opacity='0';document.body.appendChild(temp);temp.select();var ok=false;try{ok=document.execCommand('copy');}catch(e){}document.body.removeChild(temp);if(ok)done();else{status.textContent='Cópia automática indisponível. Selecione a URL acima.';status.setAttribute('role','alert');}};
if(navigator.clipboard&&navigator.clipboard.writeText)navigator.clipboard.writeText(v.url).then(done,fallback);else fallback();
});
render();
})();
</script>
</div>
BMDUTMHTML;
} );

/**
 * Add context-sensitive internal discovery links only to verified relevant guides.
 * Does not auto-edit stored content, rankings, or sponsored ads.
 */
add_filter( 'the_content', function( $content ) {
    if ( is_admin() || ! is_main_query() || ! in_the_loop() || ! is_singular( 'post' ) || is_preview() || is_feed() ) {
        return $content;
    }
    $eligible = array(
        'quanto-custa-google-ads',
        'automatizar-direct-instagram-comentarios',
        'mensagens-black-friday-2026-whatsapp-instagram',
        'ferramentas-digitais-para-analisar-desempenho-de-campanhas-online',
    );
    if ( ! in_array( get_post_field( 'post_name', get_the_ID() ), $eligible, true ) ) {
        return $content;
    }
    $target = home_url( '/gerador-de-utm/' );
    if ( stripos( $content, '/gerador-de-utm/' ) !== false ) { return $content; }
    $link = '<aside class="bmd-related-utm" style="border-top:1px solid #e6e9ee;margin:24px 0 10px;padding:13px 0;font-size:.93em;line-height:1.65;">';
    $link .= '<strong>Recurso gratuito:</strong> Para identificar de onde vêm suas visitas, use nosso ';
    $link .= '<a href="' . esc_url( $target ) . '">gerador de links UTM para Google Analytics 4</a>. ';
    $link .= 'Monte URLs rastreáveis para anúncios, e-mails e redes sociais sem cadastro.</aside>';
    return $content . "\n" . $link;
}, 39 );

/**
 * Campaign economics utility. Browser-only scripts and styles are emitted
 * exclusively where the [bmd_campaign_calculator] shortcode is rendered.
 */
$campaign_calculator_path = __DIR__ . '/bmd-campaign-calculator.php';
if ( is_file( $campaign_calculator_path ) ) {
    require_once $campaign_calculator_path;
}

/**
 * Editorial internal links from tightly related existing guides.
 */
add_filter( 'the_content', function( $content ) {
    if ( is_admin() || ! is_main_query() || ! in_the_loop() || ! is_singular( 'post' ) ||
         is_feed() || is_preview() ) {
        return $content;
    }
    $related = array(
        'quanto-custa-google-ads',
        'ferramentas-digitais-para-analisar-desempenho-de-campanhas-online',
        'estrategia-diferenciada-e-eficaz-para-alavancar-vendas-no-ecommerce',
    );
    $slug = (string) get_post_field( 'post_name', get_the_ID() );
    if ( ! in_array( $slug, $related, true ) ||
         stripos( $content, '/calculadora-roas-cac-lucro/' ) !== false ) {
        return $content;
    }
    // One compact resources section instead of two consecutive promotions.
    $existing_note = strpos( $content, 'class="bmd-related-utm"' );
    if ( $existing_note !== false ) {
        $close = strpos( $content, '</aside>', $existing_note );
        if ( $close !== false ) {
            $insert = '<br><strong>Planejamento:</strong> Simule também seu ';
            $insert .= '<a href="' . esc_url( home_url( '/calculadora-roas-cac-lucro/' ) ) . '">ROAS, CAC e lucro de campanhas</a>.';
            return substr_replace( $content, $insert, $close, 0 );
        }
    }
    $tool = '<aside class="bmd-related-calc" style="border-top:1px solid #e6e9ee;margin:14px 0;padding:14px 0;font-size:.93em;line-height:1.65;">';
    $tool .= '<strong>Ferramenta gratuita:</strong> Descubra se sua campanha está dando lucro usando nossa ';
    $tool .= '<a href="' . esc_url( home_url( '/calculadora-roas-cac-lucro/' ) ) . '">calculadora de ROAS, CAC e resultado líquido</a>. ';
    $tool .= 'Simule campanhas e custos em segundos, sem cadastro.</aside>';
    return $content . "\n" . $tool;
}, 40 );
