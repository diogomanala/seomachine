<?php
/**
 * BMD premium lightweight layout & tools discovery.
 * Maintains native OceanWP menu/mobile behavior; uses CSS only, no framework.
 */
if (!defined('ABSPATH')) { exit; }


/**
 * The sidebar still renders a second copy of the old 8-item menu.
 * Keep original menu settings and legal/footer links untouched.
 * Only suppress this specific, redundant front-end sidebar widget.
 */

/**
 * Article header semantics: show the real post title as the page H1.
 * OceanWP otherwise prints the generic word "Blog" above the story.
 * The existing duplicate H2 is visually suppressed by CSS below.
 */
add_filter('ocean_title',function($title){
    if ( ! is_admin() && ( is_home() || is_front_page() ) ) {
        return 'Marketing digital, SEO e IA';
    }
    if(!is_admin() && is_singular('post')){
        $post_id=get_queried_object_id();
        if($post_id)return get_the_title($post_id);
    }
    return $title;
},30);

add_filter('widget_display_callback', function($instance,$widget,$args){
    if(is_admin())return $instance;
    if(isset($widget->id) && $widget->id==='nav_menu-5') {
        return false;
    }
    return $instance;
}, 10, 3 );


function bmd_premium_tools() {
    return array(
        array('name'=>'Calculadora de ROAS e CAC','desc'=>'Descubra margem, lucro, CPA e ponto de equilíbrio da sua campanha.','url'=>'/calculadora-roas-cac-lucro/','tag'=>'Tráfego pago','mark'=>'01'),
        array('name'=>'Gerador de UTM','desc'=>'Crie links de campanha para Instagram, WhatsApp, Facebook e GA4.','url'=>'/gerador-de-utm/','tag'=>'Analytics','mark'=>'02'),
        array('name'=>'Removedor de metadados','desc'=>'Confira e remova EXIF, XMP e informações de IA das suas imagens.','url'=>'/remover-metadados-ia/','tag'=>'Imagem e IA','mark'=>'03'),
        array('name'=>'Simulador de taxas do cartão','desc'=>'Calcule o valor líquido com as taxas informadas do seu contrato.','url'=>'/simulador-de-vendas-no-cartao-calculo-de-taxas-mercado-pago/','tag'=>'Vendas e taxas','mark'=>'04'),
        array('name'=>'Laboratório de headlines','desc'=>'Revise títulos para artigos, anúncios e Reels com exemplos práticos.','url'=>'/ferramenta-p-criar-e-analisar-as-melhores-headlines-titulos-100-gratis/','tag'=>'Conteúdo e copy','mark'=>'05'),
        array('name'=>'Calculadora de custos do WhatsApp','desc'=>'Simule tarifas da API oficial, provedor, IA e o gasto total em 12 meses.','url'=>'/quanto-custa-automatizar-whatsapp-business-api/#simulador-custo-whatsapp','tag'=>'Automação e IA','mark'=>'06'),
    );
}
function bmd_premium_tools_html( $limit=6 ) {
    $html='<div class="bmd-tool-grid">';
    foreach(array_slice(bmd_premium_tools(),0,max(1,(int)$limit)) as $tool){
        $html.='<a class="bmd-tool-tile" href="'.esc_url(home_url($tool['url'])).'">';
        $html.='<span class="bmd-tool-tile-top"><span class="bmd-tool-tile-mark">'.esc_html($tool['mark']).'</span><span class="bmd-tool-tile-category">'.esc_html($tool['tag']).'</span></span>';
        $html.='<strong>'.esc_html($tool['name']).'</strong>';
        $html.='<span class="bmd-tool-desc">'.esc_html($tool['desc']).'</span>';
        $html.='<span class="bmd-tool-open">Abrir ferramenta <span aria-hidden="true">↗</span></span></a>';
    }
    return $html.'</div>';
}

add_shortcode('bmd_tools_hub', function() {
    $html='<section class="bmd-hub" aria-label="Catálogo de ferramentas">';
    $html.='<p class="bmd-hub-lede">Calcule campanhas, taxas e custos de automação no WhatsApp, crie links rastreáveis, revise títulos e prepare imagens. Seis ferramentas gratuitas, direto no navegador.</p>';
    $html.=bmd_premium_tools_html();

    $html.='<section class="bmd-pro-solutions" aria-labelledby="bmd-pro-title">';
    $html.='<span class="bmd-eyebrow">PARA QUEM CRIA E VENDE</span>';
    $html.='<h2 id="bmd-pro-title">Soluções para projetos profissionais</h2>';
    $html.='<p>Além das ferramentas gratuitas, conheça duas soluções para quem trabalha com criação de sites e automações.</p>';
    $html.='<div class="bmd-pro-links">';
    $html.='<a href="https://siteclonerpro.com.br/" rel="sponsored noopener" target="_blank"><strong>SiteCloner Pro <span aria-hidden="true">↗</span></strong><span>Capture referências de sites e comece sua personalização com uma base adiantada.</span></a>';
    $html.='<a href="https://autodminsta.com/" rel="sponsored noopener" target="_blank"><strong>AutoDMInsta <span aria-hidden="true">↗</span></strong><span>Configure automações de comentários e Direct para campanhas do Instagram.</span></a>';
    $html.='</div></section>';

    $html.='<section class="bmd-hub-next" aria-labelledby="bmd-hub-guides">';
    $html.='<h2 id="bmd-hub-guides">Para ir além das ferramentas</h2>';
    $guides=array(
        'Guia de 23 ferramentas de SEO'=>'/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/',
        'Como descobrir a tecnologia de um site'=>'/como-descobrir-plataforma-do-site/',
        'Quanto custa anunciar no Google Ads'=>'/quanto-custa-google-ads/',
    );
    $html.='<div class="bmd-hub-guide-links">';
    foreach($guides as $title=>$path) $html.='<a href="'.esc_url(home_url($path)).'">'.esc_html($title).'<span aria-hidden="true"> ↗</span></a>';
    $html.='</div></section></section>';
    return $html;
});

/**
 * Simplified main and mobile nav. Original WordPress menu is not modified and
 * remains accessible to footer/sidebar widgets, including all legal pages.
 */
add_filter('wp_nav_menu_items', function($items,$args){
    if(is_admin())return $items;
    $location=isset($args->theme_location)?(string)$args->theme_location:'';
    if(!in_array($location,array('main_menu','mobile_menu'),true))return $items;
    $nav=array(
        'Marketing'=>'/category/marketing-digital/',
        'SEO'=>'/category/seo/',
        'IA & Automação'=>'/category/inteligencia-artificial-automacao/',
        'Ferramentas grátis'=>'/ferramentas-gratis/',
        'Tráfego pago'=>'/category/trafego/',
        'Mídias sociais'=>'/category/midias-sociais-2/',
    );
    $html='';
    foreach($nav as $title=>$path){
        $active=(is_page('ferramentas-gratis') && $path==='/ferramentas-gratis/') ||
            (is_page(array('gerador-de-utm','calculadora-roas-cac-lucro','remover-metadados-ia','simulador-de-vendas-no-cartao-calculo-de-taxas-mercado-pago')) && $path==='/ferramentas-gratis/') ||
            (is_category() && trailingslashit(home_url($path))===trailingslashit(get_term_link(get_queried_object())));
        $classes='menu-item bmd-premium-menu-item'.($path==='/ferramentas-gratis/'?' bmd-nav-tools':'').($active?' current-menu-item':'');
        $html.='<li class="'.esc_attr($classes).'"><a class="menu-link" href="'.esc_url(home_url($path)).'"'.($active?' aria-current="page"':'').'><span class="text-wrap">'.esc_html($title).'</span></a></li>';
    }
    // Preserve the native OceanWP search toggle (and its JS behavior).
    if(strpos((string)($args->menu_class ?? ''), 'main-menu') !== false && preg_match('~<li\b[^>]*class=["\x27][^"\x27]*search-toggle-li[^"\x27]*["\x27][^>]*>.*?</li>~is',$items,$m)){
        $html.=$m[0];
    }
    return $html;
},90,2);

/**
 * Keep the original sidebar/widget banner. On utility pages, the sidebar
 * follows the wide main content; native menu widget is visually redundant.
 */
add_filter('body_class',function($classes){
    $classes[]='bmd-premium-layout';
    if(is_page(array('calculadora-roas-cac-lucro','gerador-de-utm','ferramentas-gratis','remover-metadados-ia','simulador-de-vendas-no-cartao-calculo-de-taxas-mercado-pago'))){
        $classes[]='bmd-tool-page';
    }
    return $classes;
},15);

/**
 * Accessible quick links: homepage and tool pages. Only on those pages.
 */
add_action('ocean_before_content_inner', function(){
    if(!is_home()||is_paged()||is_preview())return;
    echo '<section class="bmd-home-tools" aria-labelledby="bmd-home-tools-label">';
    echo '<div class="bmd-tools-section-heading"><div><span class="bmd-eyebrow">BIBLIOTECA DE RECURSOS</span><h2 id="bmd-home-tools-label">Ferramentas para usar agora.</h2>';
    echo '<p>Menos teoria, mais execução. Recursos gratuitos, direto no navegador.</p></div>';
    echo '<a class="bmd-all-tools-link" href="'.esc_url(home_url('/ferramentas-gratis/')).'">Ver todas as ferramentas <span aria-hidden="true">↗</span></a></div>';
    echo bmd_premium_tools_html(6).'</section>';
},7);

add_filter('the_content',function($content){
    if(!is_page(array('calculadora-roas-cac-lucro','gerador-de-utm','remover-metadados-ia','simulador-de-vendas-no-cartao-calculo-de-taxas-mercado-pago')) ||
        !is_main_query()||!in_the_loop()||is_preview())return $content;
    $back='<nav class="bmd-tool-back" aria-label="Navegação de ferramentas">';
    $back.='<a href="'.esc_url(home_url('/ferramentas-gratis/')).'"><span aria-hidden="true">←</span> Todas as ferramentas</a>';
    $back.='<span aria-hidden="true" class="bmd-tool-back-divider">/</span><span>'.esc_html(get_the_title()).'</span></nav>';
    return $back.$content;
},8);

/**
 * Semantic footer tool navigation: adds discovery without deleting existing
 * footer widgets or any legal links. No changes to ads, privacy or analytics.
 */
add_action('ocean_before_footer_bottom_inner', function(){
    echo '<nav class="bmd-footer-quick" aria-label="Links rápidos de ferramentas"><strong>Ferramentas</strong>';
    foreach(array_slice(bmd_premium_tools(),0,6) as $tool){
        echo '<a href="'.esc_url(home_url($tool['url'])).'">'.esc_html($tool['name']).'</a>';
    }
    echo '<a href="'.esc_url(home_url('/ferramentas-gratis/')).'">Ver todas ↗</a></nav>';
},7);

add_action('wp_head',function(){
    if(is_admin())return;
    echo <<<'BMD_STYLE'
<style id="bmd-premium-layout-css">
:root{--bmd-ink:#172338;--bmd-muted:#59677a;--bmd-line:#e4e8ee;--bmd-brand:#ca4b31;--bmd-surface:#f8f9fb}
html{scroll-behavior:smooth}
body.bmd-premium-layout{color:var(--bmd-ink);font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;line-height:1.65}
body.bmd-premium-layout #site-header{background:#fff!important;border-bottom:1px solid var(--bmd-line);box-shadow:none!important;height:auto!important;min-height:76px}
body.bmd-premium-layout #site-header-inner{display:flex!important;justify-content:space-between!important;align-items:center!important;gap:28px;min-height:76px;height:auto!important;padding-block:12px}
body.bmd-premium-layout #site-logo{flex:0 0 auto!important;float:none!important;width:auto!important;margin:0!important;min-width:0}
body.bmd-premium-layout #site-logo-inner{display:block!important}
body.bmd-premium-layout #site-logo .site-title{display:inline-block!important;font-size:clamp(18px,1.65vw,23px)!important;font-weight:780!important;letter-spacing:-.75px!important;line-height:1.25!important;text-wrap:nowrap;color:#172338!important;padding:0!important}
body.bmd-premium-layout #site-navigation-wrap{position:static!important;float:none!important;flex:1 1 auto!important;margin:0!important;min-width:0}
body.bmd-premium-layout #site-navigation-wrap nav{min-height:0!important}
body.bmd-premium-layout #site-navigation .main-menu{display:flex!important;align-items:center!important;justify-content:flex-end!important;gap:clamp(2px,.5vw,9px)!important;float:none!important;margin:0!important;flex-wrap:nowrap!important}
body.bmd-premium-layout #site-navigation .main-menu>li{float:none!important;flex:0 0 auto;margin:0!important}
body.bmd-premium-layout #site-navigation .main-menu>li>a.menu-link{display:flex!important;align-items:center;justify-content:center;height:auto!important;min-height:42px!important;padding:9px clamp(7px,.9vw,14px)!important;line-height:1.2!important;font-weight:610!important;font-size:clamp(12px,1.05vw,14px)!important;letter-spacing:-.1px;color:#344258!important;white-space:nowrap!important;border-radius:9px}
body.bmd-premium-layout #site-navigation .main-menu>li>a.menu-link:hover,body.bmd-premium-layout #site-navigation .main-menu>li.current-menu-item>a.menu-link{color:var(--bmd-brand)!important;background:#fff4f1!important}
body.bmd-premium-layout #site-navigation .main-menu>li.bmd-nav-tools>a.menu-link{background:#172338!important;color:white!important;border-radius:9px!important;padding-inline:13px!important}
body.bmd-premium-layout #site-navigation .main-menu>li.bmd-nav-tools>a.menu-link:hover{background:#35445c!important}
body.bmd-premium-layout #site-navigation .main-menu>li.search-toggle-li>a{min-width:38px;padding-inline:8px!important}
body.bmd-premium-layout #mobile-dropdown{background:#fff;border-top:1px solid var(--bmd-line)}
body.bmd-premium-layout #mobile-dropdown ul.menu>li{border-bottom:1px solid var(--bmd-line)}
body.bmd-premium-layout #mobile-dropdown ul.menu>li>a{padding:13px 20px!important;font-size:15px;font-weight:600;line-height:1.4}
body.bmd-premium-layout .container{max-width:1180px}
body.bmd-premium-layout #main>.page-header{background:var(--bmd-surface)!important;border-bottom:1px solid var(--bmd-line);margin:0!important;padding:33px 0 37px!important;height:auto!important;min-height:0!important}
body.bmd-premium-layout #main>.page-header>.page-header-inner{display:flex!important;flex-direction:column!important;align-items:flex-start!important;gap:13px!important;height:auto!important;min-height:0!important;padding-top:0!important;padding-bottom:0!important;position:relative!important}
body.bmd-premium-layout #main .page-header .page-header-title{float:none!important;display:block!important;order:1!important;width:100%!important;max-width:950px!important;font-size:clamp(27px,3vw,42px)!important;line-height:1.17!important;letter-spacing:-1.5px!important;font-weight:760!important;color:#172338!important;margin:0!important;padding:0!important;overflow-wrap:break-word!important}
body.bmd-premium-layout #main .page-header .site-breadcrumbs{position:static!important;float:none!important;display:block!important;order:0!important;width:auto!important;max-width:100%!important;font-size:12px!important;line-height:1.45!important;margin:0!important;padding:0!important;inset:auto!important;transform:none!important;color:#65758a!important}
body.bmd-premium-layout .site-breadcrumbs ol.trail-items{display:flex!important;flex-wrap:wrap!important;align-items:center!important;gap:6px!important;margin:0!important;padding:0!important;line-height:1.45!important}
body.bmd-premium-layout .site-breadcrumbs .trail-item{position:static!important;margin:0!important;font-size:12px!important;max-width:100%;word-break:break-word}
body.bmd-premium-layout .site-breadcrumbs a{font-size:12px!important;color:#66778b!important}
body.bmd-premium-layout .site-breadcrumbs .trail-end{display:none!important}
body.bmd-premium-layout .site-breadcrumbs .trail-item:nth-last-child(2) .breadcrumb-sep{display:none!important}
body.bmd-premium-layout #main #content-wrap{padding-top:34px!important;padding-bottom:48px!important}
body.bmd-premium-layout #main #content{min-width:0}
body.bmd-premium-layout #main .entry{font-size:16px;line-height:1.74}
body.bmd-premium-layout #main .entry h2,body.bmd-premium-layout #main .entry h3{letter-spacing:-.45px;line-height:1.35}
body.bmd-premium-layout #main .entry p{margin-bottom:18px}
body.bmd-premium-layout #right-sidebar .sidebar-box.widget_nav_menu{border:1px solid var(--bmd-line);border-radius:10px;padding:12px 18px}
body.bmd-premium-layout #right-sidebar .sidebar-box.widget_nav_menu li a{padding-block:6px}
body.bmd-premium-layout a:focus-visible{outline:2px solid var(--bmd-brand)!important;outline-offset:3px}
body.bmd-premium-layout .bmd-home-topics{border:1px solid var(--bmd-line)!important;border-radius:14px;background:#fff;margin-bottom:30px!important;padding:22px 26px!important}
body.bmd-premium-layout .bmd-home-topics h2{font-size:clamp(21px,2.2vw,27px)!important;letter-spacing:-.7px;line-height:1.3}
body.bmd-premium-layout .bmd-home-topics nav a{display:inline-block;font-weight:650}
body.bmd-premium-layout .bmd-evergreen-guides{border-top:1px solid var(--bmd-line);padding-top:15px}
body.bmd-premium-layout .bmd-evergreen-guides ul{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:4px 22px;list-style:disc}
body.bmd-premium-layout .bmd-home-tools{margin:30px 0 38px}
body.bmd-premium-layout .bmd-tools-section-heading{display:flex;align-items:flex-end;justify-content:space-between;gap:20px;margin-bottom:19px}
body.bmd-premium-layout .bmd-eyebrow{color:var(--bmd-brand);font-weight:800;font-size:11px;letter-spacing:1.5px}
body.bmd-premium-layout .bmd-tools-section-heading h2{font-size:clamp(25px,2.8vw,33px);margin:7px 0 4px;letter-spacing:-1.3px;line-height:1.2}
body.bmd-premium-layout .bmd-tools-section-heading p{color:var(--bmd-muted);margin:0}
body.bmd-premium-layout .bmd-all-tools-link{white-space:nowrap;font-weight:750;font-size:13px;border-bottom:1px solid #d0d9e4;padding-bottom:6px}
body.bmd-premium-layout .bmd-tool-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px}
body.bmd-premium-layout .bmd-tool-tile{display:flex;flex-direction:column;align-items:flex-start;min-width:0;min-height:192px;gap:10px;background:#fff;border:1px solid var(--bmd-line);border-radius:13px;padding:20px 22px!important;text-decoration:none!important;box-shadow:0 4px 18px rgba(21,33,52,.025);transition:border-color .18s ease,transform .18s ease,box-shadow .18s ease}
body.bmd-premium-layout .bmd-tool-tile:hover{border-color:#c1cad7;transform:translateY(-2px);box-shadow:0 12px 32px rgba(24,35,54,.06)}
body.bmd-premium-layout .bmd-tool-tile-top{display:flex;align-items:center;justify-content:space-between;width:100%;gap:14px}
body.bmd-premium-layout .bmd-tool-tile-mark{display:grid;place-items:center;background:#fff2ed;color:var(--bmd-brand);font-weight:850;font-size:13px;width:32px;height:32px;border-radius:9px}
body.bmd-premium-layout .bmd-tool-tile-category{font-size:11px;font-weight:750;letter-spacing:.15px;color:#67758a;background:#f4f6f9;padding:4px 9px;border-radius:6px}
body.bmd-premium-layout .bmd-tool-tile strong{font-size:18px;line-height:1.3;color:var(--bmd-ink);letter-spacing:-.4px}
body.bmd-premium-layout .bmd-tool-desc{font-size:13px;color:var(--bmd-muted);line-height:1.55}
body.bmd-premium-layout .bmd-tool-open{font-weight:780;font-size:13px;color:var(--bmd-brand);margin-top:auto}
body.bmd-premium-layout .bmd-hub-lede{font-size:17px;line-height:1.6;color:var(--bmd-muted);max-width:780px;margin:0 0 25px}
body.bmd-premium-layout .bmd-hub-next{margin-top:44px;border-top:1px solid var(--bmd-line);padding-top:30px}
body.bmd-premium-layout .bmd-hub-next h2{font-size:24px;letter-spacing:-.7px;margin:0 0 15px}
body.bmd-premium-layout .bmd-hub-guide-links{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
body.bmd-premium-layout .bmd-hub-guide-links>a{display:block;padding:18px;border:1px solid var(--bmd-line);border-radius:10px;font-weight:650;line-height:1.45}
body.bmd-premium-layout .bmd-tool-back{font-size:13px;color:var(--bmd-muted);margin-bottom:23px;display:flex;flex-wrap:wrap;gap:10px;align-items:center}
body.bmd-premium-layout .bmd-tool-back>a{font-weight:750;color:var(--bmd-brand)}
body.bmd-premium-layout .bmd-tool-back-divider{color:#9ba8b8}
body.bmd-tool-page #primary{float:none!important;width:100%!important;max-width:100%!important}
body.bmd-tool-page #right-sidebar{float:none!important;clear:both!important;width:100%!important;max-width:100%!important;display:block!important;margin-top:30px;padding:22px 0 0!important;border-top:1px solid var(--bmd-line)}
body.bmd-tool-page #right-sidebar #nav_menu-5{display:none!important}
body.bmd-tool-page #main #content-wrap{max-width:1160px}
body.bmd-tool-page #main .single-page-article .entry>p:first-child{max-width:900px;font-size:17px;color:#546277;line-height:1.65;margin-bottom:25px}
body.bmd-tool-page .bmd-calc{display:grid;grid-template-columns:minmax(0,1.04fr) minmax(0,.96fr);gap:18px!important;align-items:start;max-width:none!important;margin-top:22px}
body.bmd-tool-page .bmd-calc>.bmd-calc-panel{margin:0!important;border:1px solid var(--bmd-line);border-radius:15px;padding:clamp(18px,2vw,25px)!important;box-shadow:0 10px 32px rgba(25,35,49,.035);min-width:0}
body.bmd-tool-page .bmd-calc>.bmd-calc-panel:nth-child(1){grid-column:1;grid-row:1 / span 2}
body.bmd-tool-page .bmd-calc>.bmd-calc-panel:nth-child(2){grid-column:2;grid-row:1}
body.bmd-tool-page .bmd-calc>.bmd-calc-panel:nth-child(3){grid-column:2;grid-row:2}
body.bmd-tool-page .bmd-calc>.bmd-neutral{grid-column:1/-1;margin-top:0}
body.bmd-tool-page .bmd-calc .bmd-calc-result-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
body.bmd-tool-page .bmd-calc h2{font-size:clamp(19px,1.6vw,23px);letter-spacing:-.5px}
body.bmd-tool-page .bmd-calc input{background:#fff!important;border:1px solid #ccd5e0!important;border-radius:9px}
body.bmd-tool-page .bmd-calc label{font-size:13px!important;font-weight:720!important}
body.bmd-tool-page .bmd-calc .bmd-metric{border:1px solid #e7eaf1;background:#f9fafc;border-radius:10px;padding:14px}
body.bmd-tool-page .bmd-calc .bmd-metric-label{font-size:12px}
body.bmd-tool-page .bmd-calc .bmd-metric-val{font-size:clamp(20px,2vw,27px)}
body.bmd-tool-page .bmd-calc .bmd-calc-scenarios{grid-template-columns:repeat(3,minmax(0,1fr));gap:8px}
body.bmd-tool-page .bmd-calc .bmd-scenario{padding:10px 9px;min-width:0}
body.bmd-tool-page .bmd-calc .bmd-scenario output{font-size:17px}
body.bmd-tool-page .bmd-calc .bmd-scenario strong{font-size:12px}
body.bmd-premium-layout .bmd-footer-quick{display:flex;align-items:center;justify-content:center;gap:14px 22px;flex-wrap:wrap;padding:10px 0;font-size:12px}
body.bmd-premium-layout .bmd-footer-quick strong{font-weight:800;color:#c2cbd6}
body.bmd-premium-layout .bmd-footer-quick a{color:inherit;text-decoration:underline;text-underline-offset:3px}
@media(max-width:959px){
body.bmd-premium-layout #site-navigation-wrap{display:none!important}
body.bmd-premium-layout .oceanwp-mobile-menu-icon{display:block!important;float:none!important;margin-left:auto!important}
body.bmd-premium-layout #site-header-inner{padding-inline:16px}
}
@media(max-width:920px){
body.bmd-premium-layout .bmd-tool-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
body.bmd-tool-page .bmd-calc{grid-template-columns:1fr!important}
body.bmd-tool-page .bmd-calc>.bmd-calc-panel:nth-child(1),body.bmd-tool-page .bmd-calc>.bmd-calc-panel:nth-child(2),body.bmd-tool-page .bmd-calc>.bmd-calc-panel:nth-child(3){grid-column:1;grid-row:auto}
body.bmd-premium-layout .bmd-hub-guide-links{grid-template-columns:1fr}
}
@media(max-width:640px){
body.bmd-premium-layout #site-header-inner{min-height:66px;gap:12px}
body.bmd-premium-layout #site-logo .site-title{font-size:19px!important;white-space:normal;max-width:235px}
body.bmd-premium-layout #main>.page-header{padding:23px 0 25px!important}
body.bmd-premium-layout #main .page-header .page-header-title{font-size:clamp(26px,7vw,33px)!important;letter-spacing:-.9px!important}
body.bmd-premium-layout #main #content-wrap{padding-top:24px!important}
body.bmd-premium-layout .bmd-home-topics{padding:18px!important}
body.bmd-premium-layout .bmd-evergreen-guides ul{grid-template-columns:1fr}
body.bmd-premium-layout .bmd-tool-grid{grid-template-columns:1fr}
body.bmd-premium-layout .bmd-tool-tile{min-height:150px!important;padding:17px!important}
body.bmd-premium-layout .bmd-tools-section-heading{flex-direction:column;align-items:flex-start}
body.bmd-tool-page .bmd-calc .bmd-calc-result-grid{grid-template-columns:repeat(2,minmax(0,1fr))}
body.bmd-tool-page .bmd-calc .bmd-calc-fields{grid-template-columns:1fr}
body.bmd-tool-page .bmd-calc .bmd-calc-scenarios{grid-template-columns:1fr}
body.bmd-premium-layout .bmd-footer-quick{justify-content:flex-start}
}
body.bmd-premium-layout .bmd-pro-solutions{margin:42px 0 0;padding-top:30px;border-top:1px solid var(--bmd-line)}
body.bmd-premium-layout .bmd-pro-solutions h2{font-size:clamp(22px,2.5vw,28px);letter-spacing:-.8px;line-height:1.25;margin:9px 0 7px}
body.bmd-premium-layout .bmd-pro-solutions>p{font-size:14px;color:var(--bmd-muted);line-height:1.55;margin:0 0 16px}
body.bmd-premium-layout .bmd-pro-links{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:13px}
body.bmd-premium-layout .bmd-pro-links a{display:flex;flex-direction:column;gap:8px;padding:18px 20px;border:1px solid var(--bmd-line);border-radius:12px;color:var(--bmd-ink)!important;text-decoration:none!important;background:#fbfcfe}
body.bmd-premium-layout .bmd-pro-links a:hover{border-color:#bdc8d7}
body.bmd-premium-layout .bmd-pro-links strong{font-size:16px;font-weight:780}
body.bmd-premium-layout .bmd-pro-links span{font-size:13px;color:var(--bmd-muted);line-height:1.55}
@media(max-width:640px){body.bmd-premium-layout .bmd-pro-links{grid-template-columns:1fr}}
@media(prefers-reduced-motion:reduce){html{scroll-behavior:auto}body.bmd-premium-layout .bmd-tool-tile{transition:none}body.bmd-premium-layout .bmd-tool-tile:hover{transform:none}}

/* Editorial refinement: compact reading grid and clear content hierarchy. */
body.bmd-premium-layout #blog-entries{display:grid!important;grid-template-columns:repeat(2,minmax(0,1fr));align-items:stretch;gap:23px!important;margin:0!important}
body.bmd-premium-layout #blog-entries>.blog-entry{float:none!important;clear:none!important;display:block!important;width:100%!important;margin:0!important;max-width:100%;height:100%}
body.bmd-premium-layout #blog-entries>.blog-entry>.blog-entry-inner{display:flex!important;flex-direction:column!important;align-items:stretch;min-height:100%;background:#fff;border:1px solid #e5eaf1;border-radius:13px;overflow:hidden;box-shadow:0 8px 28px rgba(27,38,56,.025);padding:0 0 17px!important}
body.bmd-premium-layout #blog-entries .thumbnail{margin:0 0 17px!important;overflow:hidden;width:100%;aspect-ratio:16/9;background:#eef2f6;position:relative;flex:0 0 auto}
body.bmd-premium-layout #blog-entries .thumbnail>a{display:block;width:100%;height:100%;position:relative}
body.bmd-premium-layout #blog-entries .thumbnail img{display:block;width:100%!important;height:100%!important;object-fit:cover!important;object-position:center;transform:none!important}
body.bmd-premium-layout #blog-entries .thumbnail .overlay{display:none!important}
body.bmd-premium-layout #blog-entries .blog-entry-header,body.bmd-premium-layout #blog-entries .blog-entry-summary,body.bmd-premium-layout #blog-entries .meta,body.bmd-premium-layout #blog-entries .blog-entry-readmore{float:none!important;display:block;padding:0 19px!important;margin:0 0 12px!important;min-width:0}
body.bmd-premium-layout #blog-entries .blog-entry-header{order:0}
body.bmd-premium-layout #blog-entries .blog-entry-title{margin:0!important;line-height:1.32!important;font-size:clamp(17px,1.65vw,21px)!important;letter-spacing:-.45px!important;font-weight:760!important}
body.bmd-premium-layout #blog-entries .blog-entry-title a{color:#172338!important;text-decoration:none!important}
body.bmd-premium-layout #blog-entries .blog-entry-title a:hover{color:#bb4c31!important}
body.bmd-premium-layout #blog-entries .meta{display:flex!important;align-items:center;flex-wrap:wrap;gap:6px;font-size:11px;color:#7c8899;order:-1;line-height:1.3;margin-bottom:9px!important}
body.bmd-premium-layout #blog-entries .meta>li.meta-author,body.bmd-premium-layout #blog-entries .meta>li.meta-date{display:none!important}
body.bmd-premium-layout #blog-entries .meta .meta-cat{font-size:11px;font-weight:780;letter-spacing:.5px;text-transform:uppercase;color:#bc553c!important;display:block!important}
body.bmd-premium-layout #blog-entries .meta .meta-cat i{display:none}
body.bmd-premium-layout #blog-entries .meta .meta-cat a{color:#bc553c!important}
body.bmd-premium-layout #blog-entries .blog-entry-summary{font-size:13px!important;color:#617085!important;line-height:1.6!important;flex:1 1 auto}
body.bmd-premium-layout #blog-entries .blog-entry-summary p{margin:0!important;line-height:1.65!important;color:#617085!important}
body.bmd-premium-layout #blog-entries .blog-entry-readmore{margin:auto 0 0!important;padding-top:7px!important}
body.bmd-premium-layout #blog-entries .blog-entry-readmore a{border:0!important;padding:0!important;text-transform:none!important;letter-spacing:0!important;color:#ca4b31!important;font-size:13px!important;font-weight:760!important;background:none!important}
body.bmd-premium-layout #blog-entries .blog-entry-readmore a:hover{text-decoration:underline!important}
body.bmd-premium-layout #blog-entries .thumbnail+header.blog-entry-header{order:0}
body.bmd-premium-layout #right-sidebar #nav_menu-5{display:none!important}
body.bmd-premium-layout #right-sidebar .sidebar-box:not(:last-child){margin-bottom:23px}
body.bmd-premium-layout #right-sidebar #search-5{padding:18px;border:1px solid var(--bmd-line);border-radius:12px;background:#fff}
body.bmd-premium-layout #right-sidebar #search-5 input[type=search]{font-size:14px;border-radius:7px;min-height:43px}
body.bmd-premium-layout .bmd-evergreen-guides ul{list-style:none!important;margin:8px 0 0!important;padding:0!important;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px!important}
body.bmd-premium-layout .bmd-evergreen-guides li{list-style:none!important;margin:0!important}
body.bmd-premium-layout .bmd-evergreen-guides li a{display:block;padding:12px 13px;border:1px solid #e7eaf0;border-radius:8px;color:#3a4a60;font-weight:670;line-height:1.45;text-decoration:none;background:#fbfcfd}
body.bmd-premium-layout .bmd-evergreen-guides li a:hover{border-color:#d4b6aa;color:#b44a32;background:#fffaf8}
body.bmd-premium-layout #main .single-post-article .entry{font-size:17px;line-height:1.8}
body.bmd-premium-layout #main .single-post-article .entry h2{margin-top:35px}
body.bmd-premium-layout #main .single-post-article .entry img{max-width:100%;height:auto}
body.bmd-premium-layout #footer-widgets:not(:has(.footer-box > *)){display:none!important}
body.bmd-premium-layout #footer-bottom{padding-top:16px!important;padding-bottom:16px!important}
body.bmd-premium-layout .bmd-footer-quick{border-bottom:1px solid rgba(166,177,193,.23);margin-bottom:12px;padding-bottom:18px;line-height:1.6}
@media(max-width:959px){
body.bmd-premium-layout #blog-entries{gap:17px!important}
body.bmd-premium-layout #blog-entries>.blog-entry>.blog-entry-inner{padding-bottom:14px!important}
}
@media(max-width:680px){
body.bmd-premium-layout #blog-entries{grid-template-columns:minmax(0,1fr)!important;gap:20px!important}
body.bmd-premium-layout .bmd-evergreen-guides ul{grid-template-columns:1fr!important}
body.bmd-premium-layout #blog-entries .blog-entry-header,body.bmd-premium-layout #blog-entries .blog-entry-summary,body.bmd-premium-layout #blog-entries .meta,body.bmd-premium-layout #blog-entries .blog-entry-readmore{padding-left:15px!important;padding-right:15px!important}
body.bmd-premium-layout #blog-entries .blog-entry-title{font-size:20px!important}
}


/* Keep card categories AFTER the cover image and BEFORE the title. */
body.bmd-premium-layout #blog-entries .thumbnail{order:0!important}
body.bmd-premium-layout #blog-entries .meta{order:1!important}
body.bmd-premium-layout #blog-entries .blog-entry-header,body.bmd-premium-layout #blog-entries .thumbnail+header.blog-entry-header{order:2!important}
body.bmd-premium-layout #blog-entries .blog-entry-summary{order:3!important}
body.bmd-premium-layout #blog-entries .blog-entry-readmore{order:4!important}
/* The same post title is now the semantic H1 at the top. Hide duplicate H2. */
body.bmd-premium-layout.single-post #main article .single-post-title{display:none!important}
body.bmd-premium-layout.single-post #main .page-header .page-header-title{max-width:890px!important}


/* MOBILE VIEWPORT FIX: OceanWP's header title was overflowing the
   page-header container on small screens, allowing horizontal panning. */
body.bmd-premium-layout #main > .page-header{box-sizing:border-box!important;width:100%!important;max-width:100%!important;min-width:0!important}
body.bmd-premium-layout #main > .page-header > .page-header-inner{box-sizing:border-box!important;min-width:0!important;max-width:1180px!important;width:calc(100% - 40px)!important;margin-inline:auto!important;padding-inline:0!important}
body.bmd-premium-layout #main > .page-header .page-header-title{box-sizing:border-box!important;min-width:0!important;max-width:100%!important;text-align:left!important;white-space:normal!important;text-wrap:wrap!important;overflow-wrap:anywhere!important;word-break:normal!important;position:static!important;inset:auto!important;transform:none!important}
body.bmd-premium-layout #main > .page-header .site-breadcrumbs{box-sizing:border-box!important;max-width:100%!important;white-space:normal!important;overflow-wrap:anywhere!important;text-align:left!important}
@media(max-width:959px){
html{max-width:100%;overflow-x:clip}
body.bmd-premium-layout{max-width:100%;overflow-x:clip}
body.bmd-premium-layout #outer-wrap,body.bmd-premium-layout #wrap,body.bmd-premium-layout #main,body.bmd-premium-layout #main #content-wrap{box-sizing:border-box!important;max-width:100%!important;min-width:0!important}
body.bmd-premium-layout #site-header-inner{box-sizing:border-box!important;width:calc(100% - 32px)!important;max-width:100%!important;margin-inline:auto!important;min-width:0!important;padding-inline:0!important}
body.bmd-premium-layout #main > .page-header > .page-header-inner{width:calc(100% - 36px)!important;max-width:calc(100% - 36px)!important}
body.bmd-premium-layout #main > .page-header .page-header-title{width:100%!important;margin:0!important;padding:0!important;font-size:clamp(24px,6vw,32px)!important;line-height:1.19!important;letter-spacing:-.7px!important}
body.bmd-premium-layout #main #content-wrap{width:calc(100% - 32px)!important;max-width:calc(100% - 32px)!important;min-width:0!important;padding-inline:0!important;margin-inline:auto!important}
body.bmd-premium-layout .bmd-home-topics,body.bmd-premium-layout .bmd-home-tools,body.bmd-premium-layout #primary,body.bmd-premium-layout #content{box-sizing:border-box!important;max-width:100%!important;min-width:0!important}
body.bmd-premium-layout .bmd-home-topics a,body.bmd-premium-layout .bmd-evergreen-guides a{overflow-wrap:anywhere}
body.bmd-premium-layout #site-logo{min-width:0!important;max-width:calc(100% - 60px)!important}
body.bmd-premium-layout #site-logo .site-title{white-space:normal!important;overflow-wrap:break-word!important;display:block!important}
body.bmd-premium-layout .oceanwp-mobile-menu-icon{flex:0 0 auto!important;position:relative!important;right:auto!important;top:auto!important}
}
/* Final mobile layout guard: use actual viewport units, not a potentially
   oversized inherited OceanWP container width. No JavaScript, no resize hacks. */
@media(max-width:959px){
body.bmd-premium-layout #site-header,
body.bmd-premium-layout #main > .page-header{width:100vw!important;max-width:100vw!important;min-width:0!important}
body.bmd-premium-layout #site-header-inner{width:calc(100vw - 32px)!important;max-width:calc(100vw - 32px)!important;min-width:0!important;gap:12px!important}
body.bmd-premium-layout #main > .page-header > .page-header-inner{width:calc(100vw - 36px)!important;max-width:calc(100vw - 36px)!important;min-width:0!important;display:flex!important;flex-direction:column!important;align-items:flex-start!important;overflow-wrap:anywhere!important}
body.bmd-premium-layout #main > .page-header .page-header-title{display:block!important;flex:0 1 auto!important;max-width:100%!important;min-width:0!important;inline-size:100%!important;white-space:normal!important;overflow-wrap:break-word!important;word-break:normal!important;letter-spacing:-.7px!important;line-height:1.22!important;text-align:start!important}
body.bmd-premium-layout #main #content-wrap{width:calc(100vw - 32px)!important;max-width:calc(100vw - 32px)!important;min-width:0!important}
body.bmd-premium-layout .bmd-home-topics,
body.bmd-premium-layout .bmd-home-tools,
body.bmd-premium-layout #primary,
body.bmd-premium-layout #content{max-width:100%!important;min-width:0!important}
body.bmd-premium-layout #site-header{min-height:66px!important}
body.bmd-premium-layout #site-header-inner{min-height:66px!important;padding-block:8px!important}
body.bmd-premium-layout .oceanwp-mobile-menu-icon{min-width:44px;min-height:44px;display:flex!important;align-items:center;justify-content:center}
body.bmd-premium-layout .oceanwp-mobile-menu-icon a{min-height:44px;display:flex!important;align-items:center;gap:6px}
}

@media(max-width:480px){
body.bmd-premium-layout #main > .page-header{padding-top:20px!important;padding-bottom:23px!important}
body.bmd-premium-layout #main > .page-header .page-header-title{font-size:clamp(24px,6.5vw,29px)!important;line-height:1.2!important}
body.bmd-premium-layout #main #content-wrap{padding-top:20px!important}
body.bmd-premium-layout .bmd-home-topics{padding:17px!important;margin-top:0!important}
}

</style>
BMD_STYLE;
},65);

/** Create only if no page already exists; never overwrite editor content. */
add_action('init',function(){
    if(get_option('bmd_tools_hub_created_20261027',false))return;
    $exists=get_page_by_path('ferramentas-gratis',OBJECT,'page');
    if($exists){
        update_option('bmd_tools_hub_created_20261027','existing_'.$exists->ID,false);
        return;
    }
    $id=wp_insert_post(array(
        'post_type'=>'page','post_status'=>'publish',
        'post_title'=>'Ferramentas de Marketing Digital Grátis — Biblioteca de Recursos',
        'post_name'=>'ferramentas-gratis',
        'post_content'=>'[bmd_tools_hub]',
        'meta_input'=>array(
            'rank_math_title'=>'Ferramentas Grátis de Marketing Digital | BMD',
            'rank_math_description'=>'5 ferramentas grátis: calculadora de ROAS e CAC, UTM, removedor de metadados, simulador de taxas e laboratório de headlines. Sem cadastro.',
            'rank_math_focus_keyword'=>'ferramentas de marketing digital gratis',
        ),
    ),true);
    if(!is_wp_error($id)&&$id)update_option('bmd_tools_hub_created_20261027',(int)$id,false);
},30);

/** Keep the tools hub Rank Math snippet aligned with the actual catalogue. */
add_action('init',function(){
    if(get_option('bmd_tools_hub_five_20261028_v1',false))return;
    $page=get_page_by_path('ferramentas-gratis',OBJECT,'page');
    if(!$page || $page->post_status!=='publish')return;
    update_post_meta($page->ID,'rank_math_description','5 ferramentas grátis: calculadora de ROAS e CAC, UTM, removedor de metadados, simulador de taxas e laboratório de headlines. Sem cadastro.');
    update_option('bmd_tools_hub_five_20261028_v1',(int)$page->ID,false);
},44);
