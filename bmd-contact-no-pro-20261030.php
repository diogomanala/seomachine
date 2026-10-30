<?php
/**
 * BMD contato sem Elementor Pro (2026-10-30).
 * Garante um canal de atendimento público sem formulário ou widgets Pro.
 * Não altera conteúdo, URL, metadados nem o banco de dados.
 */
if (!defined('ABSPATH')) { exit; }
add_filter('the_content', static function($content) {
    if (is_admin() || is_feed() || is_preview() || !is_main_query() || !in_the_loop() ||
        !is_page(1928) || (int)get_the_ID() !== 1928) {
        return $content;
    }
    if (strpos($content, 'bmd-contact-independent') !== false) {
        return $content;
    }
    $url='https://www.instagram.com/ferramentas.vip/';
    $html='<section class="bmd-contact-independent" aria-labelledby="bmd-contact-independent-title" style="max-width:780px;margin:22px 0 36px;padding:26px;border:1px solid #e4e7eb;border-radius:14px;background:#fff;color:#20252c">';
    $html.='<h2 id="bmd-contact-independent-title" style="font-size:clamp(1.3rem,2vw,1.7rem);margin:0 0 12px">Entre em contato</h2>';
    $html.='<p style="margin:0 0 18px">Para falar sobre anúncios, parcerias, correções de artigos ou outras dúvidas, envie uma mensagem pelo nosso perfil oficial no Instagram.</p>';
    $html.='<a href="'.esc_url($url).'" target="_blank" rel="noopener noreferrer" style="display:inline-block;background:#005ff4;color:#fff;border-radius:9px;padding:12px 20px;text-decoration:none;font-weight:700">Falar pelo Instagram</a>';
    $html.='<p style="margin:16px 0 0;font-size:.88rem;color:#555e6a">O formulário antigo foi desativado. Nenhuma informação é coletada nesta página.</p>';
    $html.='</section>';
    return $content.$html;
},60);
