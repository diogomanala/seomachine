<?php
/**
 * BMD 2026-11-15: Small, editorially relevant internal links.
 * Explicit source -> destination map grounded in SERPBear GSC opportunities.
 * Only published posts, one link per source and never duplicate an existing URL.
 * Does not change the database, titles, canonical, metadata, or original slugs.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_serp_commercial_links_20261115() {
    return array(
        'pack-de-templates-em-elementor' => array(
            'target' => 'como-clonar-um-site-completo',
            'before' => 'Se você usa páginas existentes como referências visuais, ',
            'anchor' => 'veja como capturar HTML, CSS e arquivos públicos de um site',
            'after' => ', com autorização, e entenda o que o navegador não consegue copiar. Isso não transforma o resultado automaticamente em um template nativo do Elementor.'
        ),
        'como-colocar-site-no-ar-gratis-vercel-github' => array(
            'target' => 'como-clonar-um-site-completo',
            'before' => 'Se você ainda precisa preparar os arquivos de uma página sua ou autorizada para publicar, ',
            'anchor' => 'aprenda a capturar o front-end com HTML, CSS, imagens e scripts',
            'after' => ' antes de subir o projeto ao GitHub. Confira o pacote e os direitos de uso antes do deploy.'
        ),
        '4-erros-comuns-em-sites-de-negocios' => array(
            'target' => 'como-descobrir-plataforma-do-site',
            'before' => 'Antes de propor uma reformulação, também vale ',
            'anchor' => 'identificar se o site utiliza WordPress ou outra plataforma',
            'after' => '. Esse diagnóstico ajuda a avaliar quais mudanças realmente dependem da tecnologia existente.'
        ),
        'melhores-plataformas-de-automacao-de-marketing-em-2026-guia-completo-para-escolher-a-certa' => array(
            'target' => 'automatizar-direct-instagram-comentarios',
            'before' => 'Para quem quer resolver uma tarefa bem específica, como enviar uma DM depois de um comentário em Reel, confira ',
            'anchor' => 'o tutorial de automação de comentários e Direct no Instagram',
            'after' => '. Ele compara alternativas nativas e plataformas especializadas, sem exigir um CRM completo para um fluxo simples.'
        ),
        'funis-de-marketing-digital-2027-modelos-exemplos' => array(
            'target' => 'automatizar-direct-instagram-comentarios',
            'before' => 'Na etapa de comentário para mensagem, veja ',
            'anchor' => 'como configurar a automação de Direct por comentários no Instagram',
            'after' => ', testar as palavras-chave e respeitar as permissões e limites da plataforma.'
        ),
        'o-que-e-criativo-no-marketing-digital-o-que-e-e-como-aplicar-com-sucesso' => array(
            'target' => 'funis-de-marketing-digital-2027-modelos-exemplos',
            'before' => 'Depois do criativo, planeje a próxima ação do visitante: ',
            'anchor' => 'veja sete funis de marketing para 2027 com exemplos e métricas',
            'after' => ', desde conteúdo que gera conversas até diagnóstico e proposta.'
        )
    );
}

add_filter('the_content', function($content) {
    if (is_admin() || !is_singular('post') || !is_main_query() ||
        !in_the_loop() || is_preview() || is_feed() ||
        !is_string($content) || strlen($content) < 800) {
        return $content;
    }
    $slug = (string)get_post_field('post_name', get_the_ID());
    $plan = bmd_serp_commercial_links_20261115();
    if (!isset($plan[$slug]) ||
        strpos($content, 'bmd-serp-context-20261115') !== false) {
        return $content;
    }

    $link = $plan[$slug];
    if (strpos($content, '/' . $link['target'] . '/') !== false) {
        return $content; // No second link to a destination already linked.
    }
    $target = get_page_by_path($link['target'], OBJECT, 'post');
    if (!$target || $target->post_status !== 'publish' ||
        $target->post_name === $slug) {
        return $content; // Never publish a link to a missing/draft post.
    }

    $url = get_permalink($target->ID);
    if (!$url) { return $content; }
    $paragraph = '<p class="bmd-serp-context-20261115" style="margin:20px 0;line-height:1.7;">';
    $paragraph .= esc_html($link['before']);
    $paragraph .= '<a href="' . esc_url($url) . '">' . esc_html($link['anchor']) . '</a>';
    $paragraph .= esc_html($link['after']) . '</p>';

    // Insert after the second editorial paragraph when possible, not as a
    // repeating sales box at the bottom of every article.
    if (!preg_match_all('~</p>~i', $content, $m, PREG_OFFSET_CAPTURE)) {
        return $content;
    }
    $idx = count($m[0]) > 1 ? 1 : 0;
    $end = $m[0][$idx][1] + strlen($m[0][$idx][0]);
    return substr($content, 0, $end) . "\n" . $paragraph . substr($content, $end);
}, 46);
