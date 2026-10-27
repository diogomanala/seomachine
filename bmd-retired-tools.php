<?php
/**
 * Retire low-reliability Instagram downloader from public pages.
 * Reversible: WordPress pages are drafts, no media/post records are deleted.
 */
if (!defined('ABSPATH')) { exit; }

add_action('init', function () {
    if (get_option('bmd_video_tool_retired_20261027', false) === 'done') { return; }
    foreach (array('baixar-video-instagram', 'baixar-video-instagram-2') as $slug) {
        $page = get_page_by_path($slug, OBJECT, 'page');
        if (!$page || $page->post_status !== 'publish') { continue; }
        // Limit this operation strictly to pages generated for this tool.
        if (trim((string)$page->post_content) !== '[bmd_instagram_downloader]') { continue; }
        $changed = wp_update_post(array('ID' => $page->ID, 'post_status' => 'draft'), true);
        if (is_wp_error($changed)) { return; }
        clean_post_cache($page->ID);
    }
    $hub = get_page_by_path('ferramentas-gratis', OBJECT, 'page');
    if ($hub && $hub->post_status === 'publish') {
        update_post_meta($hub->ID, 'rank_math_description',
            'Ferramentas gratuitas para marketing digital: calculadora de ROAS e CAC, gerador de links UTM e removedor de metadados de imagens. Sem cadastro.');
    }
    update_option('bmd_video_tool_retired_20261027', 'done', false);
}, 24);

/** A removed and never validated downloader must not remain available via public URL. */
add_action('template_redirect', function () {
    $path = trim((string)(wp_parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: ''), '/');
    if ($path === 'baixar-video-instagram' || $path === 'baixar-video-instagram-2') {
        status_header(410);
        nocache_headers();
        header('Content-Type: text/plain; charset=utf-8');
        echo 'Esta ferramenta não está mais disponível.';
        exit;
    }
    // Preserve the previous redirect for the separately retired old email extractor.
    if ($path === 'extrator-de-emails-e-leads') {
        wp_safe_redirect(home_url('/ferramentas-gratis/'), 301, 'BMD');
        exit;
    }
}, 0);
