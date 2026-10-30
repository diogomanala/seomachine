<?php
/**
 * BMD contato sem Elementor Pro (2026-10-30).
 * Apenas frontend da página Contato, sem modificar URL, dados ou conteúdo.
 * Renderiza após o conteúdo, independentemente do editor da página.
 */
if (!defined('ABSPATH')) { exit; }

add_action('ocean_after_content_inner', static function() {
    if (is_admin() || is_feed() || is_preview() || !is_page(1928)) {
        return;
    }
    $url='https://www.instagram.com/ferramentas.vip/';
    echo '<section class="bmd-contact-independent" aria-labelledby="bmd-contact-independent-title" style="max-width:780px;margin:22px 0 36px;padding:26px;border:1px solid #e4e7eb;border-radius:14px;background:#fff;color:#20252c">';
    echo '<h2 id="bmd-contact-independent-title" style="font-size:clamp(1.3rem,2vw,1.7rem);margin:0 0 12px">Entre em contato</h2>';
    echo '<p style="margin:0 0 18px">Para falar sobre anúncios, parcerias, correções de artigos ou outras dúvidas, envie uma mensagem pelo nosso perfil oficial no Instagram.</p>';
    echo '<a href="'.esc_url($url).'" target="_blank" rel="noopener noreferrer" style="display:inline-block;background:#005ff4;color:#fff;border-radius:9px;padding:12px 20px;text-decoration:none;font-weight:700">Falar pelo Instagram</a>';
    echo '<p style="margin:16px 0 0;font-size:.88rem;color:#555e6a">O formulário antigo foi desativado. Nenhuma informação é coletada nesta página.</p>';
    echo '</section>';
},15);
