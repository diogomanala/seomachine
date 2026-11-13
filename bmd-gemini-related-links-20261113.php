<?php
/**
 * BMD: Contextual discovery of the November 2026 Gemini 4 Hydrogen article.
 * Render-time only: does not rewrite old article HTML or manipulate sitemap.
 */
if (!defined('ABSPATH')) { exit; }

add_filter('the_content', function ($content) {
    if (is_admin() || is_feed() || is_preview() || !is_main_query() || !in_the_loop() || !is_singular('post')) {
        return $content;
    }
    $slug = (string)get_post_field('post_name', get_the_ID());
    $eligible = array(
        '23-ferramentas-gratuitas-de-seo-e-analise-de-websites',
        'estrategia-de-seo-seu-potencial',
        'como-otimizar-seu-conteudo-para-o-google-sge-guia-completo-para-o-brasil'
    );
    if (!in_array($slug, $eligible, true)) { return $content; }
    $target_slug = 'gemini-4-hydrogen-google-busca-seo-2026';
    $target = get_page_by_path($target_slug, OBJECT, 'post');
    if (!$target || $target->post_status !== 'publish') { return $content; }
    $url = get_permalink($target);
    if (!$url || stripos($content, $target_slug) !== false) { return $content; }
    $note = '<p class="bmd-gemini-related" style="margin:22px 0;padding:14px 0;border-top:1px solid #d6e0eb;color:#28384a;line-height:1.65">';
    $note .= '<strong>Atualização da Busca:</strong> veja <a style="color:#07529b;text-decoration:underline" href="' . esc_url($url) . '">o que mudou com o Gemini 4 Hydrogen no Google e como isso afeta o SEO</a>.</p>';
    return $content . "\n" . $note;
}, 55);
