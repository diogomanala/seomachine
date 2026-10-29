<?php
/**
 * BMD 2026-10-29: editorial journey links for four EXISTING articles.
 * Internal link audit: those exact destination URLs do not yet appear in
 * the current article body, but they are useful to the reader's next task.
 *
 * This module only renders via the_content and never rewrites post_content,
 * moves an existing URL, changes metadata, or duplicates already present links.
 * Safe to disable by removing the module require in the main plugin.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_search_journeys_oct29() {
    return array(
        'como-prospectar-clientes-para-vender-sites-com-mais-eficiencia' => array(
            'heading' => 'Antes de apresentar uma proposta, diagnostique o site',
            'intro' => 'Uma abordagem comercial útil começa com três verificações: entender a tecnologia existente, observar a experiência que o visitante encontra e identificar melhorias comprováveis. Use os guias abaixo antes de criar uma demonstração para o cliente.',
            'links' => array(
                array('/como-descobrir-plataforma-do-site/', 'Identifique a plataforma e os sinais públicos da tecnologia', 'Diferencie WordPress, Shopify, Webflow e projetos modernos sem tentar acessar o painel privado.'),
                array('/como-clonar-um-site-completo/', 'Estude o front-end de uma página que você tem permissão para analisar', 'Veja o que uma captura de HTML, CSS e recursos públicos consegue — e o que ela não consegue — preservar.'),
                array('/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/', 'Prepare um diagnóstico SEO sem comprar ferramentas', 'Confira indexação, experiência mobile e estrutura com fontes oficiais e um checklist manual.')
            )
        ),
        'como-clonar-um-site-completo' => array(
            'heading' => 'Precisa editar o resultado no WordPress?',
            'intro' => 'Capturar o front-end de uma página e ter uma landing page editável são etapas diferentes. Antes de entregar o projeto ao cliente, confira como funciona a conversão e quais ajustes continuam manuais.',
            'links' => array(
                array('/como-clonar-landing-page-editar-elementor/', 'Da captura à landing page editável no Elementor', 'Entenda o fluxo de conversão, importação e revisão visual, inclusive suas limitações.')
            )
        ),
        'como-clonar-landing-page-editar-elementor' => array(
            'heading' => 'Do diagnóstico à entrega da landing page',
            'intro' => 'Se você ainda não sabe em qual tecnologia o site de referência foi criado, comece pela identificação. Se está montando uma proposta, transforme o levantamento técnico em uma apresentação compreensível para o cliente.',
            'links' => array(
                array('/como-descobrir-plataforma-do-site/', 'Descubra se a página usa WordPress ou outra plataforma', 'Veja indícios de CMS, frameworks e ferramentas de construção, sem confundir sinais com certeza.'),
                array('/como-prospectar-clientes-para-vender-sites-com-mais-eficiencia/', 'Apresente uma melhoria concreta ao possível cliente', 'Estruture diagnóstico, protótipo e proposta comercial sem depender de promessas de resultado.')
            )
        ),
        '4-erros-comuns-em-sites-de-negocios' => array(
            'heading' => 'Transforme os problemas do site em tarefas concretas',
            'intro' => 'Antes de refazer o site ou contratar mais plugins, confirme se os problemas estão na descoberta pelo Google, na experiência mobile ou na clareza da oferta.',
            'links' => array(
                array('/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/', 'Faça a auditoria inicial com ferramentas gratuitas', 'Use as cinco verificações manuais do checklist de SEO e copie as tarefas que ainda faltam.')
            )
        )
    );
}

add_action('wp_enqueue_scripts', function() {
    if (is_admin() || !is_singular('post')) { return; }
    $post_id = get_queried_object_id();
    if (!$post_id) { return; }
    $slug = (string)get_post_field('post_name', $post_id);
    $plans = bmd_search_journeys_oct29();
    if (!isset($plans[$slug])) { return; }
    $file = __DIR__ . '/assets/bmd-search-journeys-20261029.css';
    if (is_file($file)) {
        wp_enqueue_style('bmd-search-journeys-20261029',
            plugins_url('assets/bmd-search-journeys-20261029.css', __FILE__),
            array(), filemtime($file));
    }
}, 40);

add_filter('the_content', function($content) {
    if (is_admin() || !is_singular('post') || !is_main_query() || !in_the_loop() || is_preview()) {
        return $content;
    }
    $post_id = get_the_ID();
    if (!$post_id) { return $content; }
    $slug = (string)get_post_field('post_name', $post_id);
    $plans = bmd_search_journeys_oct29();
    if (!isset($plans[$slug]) || strlen($content) < 2500 ||
        strpos($content, 'bmd-search-journey-20261029') !== false) {
        return $content;
    }
    $plan = $plans[$slug];
    $missing = array();
    foreach ($plan['links'] as $link) {
        $path = $link[0];
        if (strpos($content, $path) !== false) { continue; } // Already linked.
        $missing[] = $link;
    }
    if (!$missing) { return $content; }

    $title_id = 'bmd-journey-' . sanitize_title($slug);
    $html = '<section class="bmd-search-journey-20261029" aria-labelledby="' . esc_attr($title_id) . '">';
    $html .= '<span class="bmd-journey-eyebrow">LEITURA PRÁTICA · PRÓXIMO PASSO</span>';
    $html .= '<h2 id="' . esc_attr($title_id) . '">' . esc_html($plan['heading']) . '</h2>';
    $html .= '<p class="bmd-journey-intro">' . esc_html($plan['intro']) . '</p><ul>';
    foreach ($missing as $link) {
        $html .= '<li><a href="' . esc_url(home_url($link[0])) . '">';
        $html .= '<strong>' . esc_html($link[1]) . '</strong>';
        $html .= '<span>' . esc_html($link[2]) . '</span>';
        $html .= '</a></li>';
    }
    $html .= '</ul></section>';

    // Keep editorial order: place immediately before the theme's generic
    // "Leia também" block if already in the filter chain.
    $position = strpos($content, '<aside aria-label="Conteúdos relacionados"');
    if ($position === false) {
        $position = strpos($content, '<aside class="bmd-related-posts"');
    }
    if ($position === false) { return $content . $html; }
    return substr($content, 0, $position) . $html . substr($content, $position);
}, 92);
