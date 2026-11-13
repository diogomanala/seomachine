<?php
/**
 * BMD: Read-only indexation diagnostics, admin-only, no paid service.
 * The Google decides whether/when to index: this panel detects site-side barriers.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_ix_20261113_normalize_url($url) {
    $url = trim((string)$url);
    if ($url === '') { return ''; }
    $url = strtok($url, '#');
    return untrailingslashit(strtolower($url));
}

function bmd_ix_20261113_robots_noindex($post_id) {
    $meta = get_post_meta($post_id, 'rank_math_robots', true);
    if (is_string($meta)) {
        $meta = maybe_unserialize($meta);
        if (is_string($meta)) { $meta = preg_split('/[,\\s]+/', $meta); }
    }
    return is_array($meta) && in_array('noindex', $meta, true);
}

function bmd_ix_20261113_link_targets($html) {
    $urls = array();
    if (preg_match_all('~<a\\b[^>]*href\\s*=\\s*([\\x22\\x27])([^\\x22\\x27]+)\\1~i', (string)$html, $matches)) {
        foreach ($matches[2] as $url) {
            $url = html_entity_decode($url, ENT_QUOTES, 'UTF-8');
            if (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
                $url = home_url($url);
            }
            $host = wp_parse_url($url, PHP_URL_HOST);
            if (!$host || strtolower((string)$host) !== strtolower((string)wp_parse_url(home_url(), PHP_URL_HOST))) { continue; }
            $urls[bmd_ix_20261113_normalize_url($url)] = true;
        }
    }
    return $urls;
}

function bmd_ix_20261113_check_endpoint($path, $expect_sitemap) {
    $url = home_url($path);
    $res = wp_remote_get($url, array('timeout' => 5, 'redirection' => 2, 'user-agent' => 'BMD-Index-Diagnostics/1.0'));
    if (is_wp_error($res)) {
        return array('label' => 'Não verificado', 'message' => 'Acesso interno indisponível: ' . $res->get_error_message());
    }
    $code = (int)wp_remote_retrieve_response_code($res);
    $content = (string)wp_remote_retrieve_body($res);
    if ($code !== 200) {
        return array('label' => 'Atenção', 'message' => 'HTTP ' . $code . '. Confirme no navegador e no Search Console.');
    }
    if ($expect_sitemap && stripos($content, '<sitemapindex') === false && stripos($content, '<urlset') === false) {
        return array('label' => 'Atenção', 'message' => 'HTTP 200, mas a resposta não parece um sitemap XML válido.');
    }
    return array('label' => 'OK', 'message' => 'HTTP 200' . ($expect_sitemap ? ', XML encontrado.' : '.') );
}

function bmd_ix_20261113_render_admin() {
    if (!current_user_can('manage_options')) { wp_die('Sem permissão.'); }
    $posts = get_posts(array(
        'post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 35,
        'orderby' => 'date', 'order' => 'DESC', 'suppress_filters' => false
    ));
    $incoming = array(); $targets = array(); $outgoing = array();
    foreach ($posts as $post) {
        $link = bmd_ix_20261113_normalize_url(get_permalink($post));
        $targets[$link] = $post->ID;
        $outgoing[$post->ID] = bmd_ix_20261113_link_targets($post->post_content);
        $incoming[$post->ID] = 0;
    }
    foreach ($posts as $source) {
        foreach ($outgoing[$source->ID] as $dest => $yes) {
            if (isset($targets[$dest]) && $targets[$dest] !== $source->ID) {
                $incoming[$targets[$dest]]++;
            }
        }
    }
    $public = (int)get_option('blog_public', 1);
    echo '<div class="wrap"><h1>Diagnóstico de indexação BMD</h1>';
    echo '<p>Auditoria técnica local, sem GSC Wizard nem serviço externo pago. <strong>Não confirma indexação no Google</strong>; identifica fatores que podem dificultar descoberta ou rastreamento.</p>';
    echo '<p><strong>Visibilidade global:</strong> ' . ($public === 1 ? '<span style="color:#166534">Permitida nas configurações do WordPress</span>' : '<span style="color:#b91c1c">WordPress está solicitando que buscadores não indexem o site</span>') . '</p>';
    $sitemap = bmd_ix_20261113_check_endpoint('/sitemap_index.xml', true);
    $robots = bmd_ix_20261113_check_endpoint('/robots.txt', false);
    echo '<p><strong>Sitemap:</strong> <a href="' . esc_url(home_url('/sitemap_index.xml')) . '" target="_blank" rel="noopener">Abrir sitemap</a> · ' . esc_html($sitemap['label']) . ': ' . esc_html($sitemap['message']) . '</p>';
    echo '<p><strong>Robots.txt:</strong> <a href="' . esc_url(home_url('/robots.txt')) . '" target="_blank" rel="noopener">Abrir robots.txt</a> · ' . esc_html($robots['label']) . ': ' . esc_html($robots['message']) . '</p>';
    echo '<p>Os links recebidos abaixo são contados <strong>somente entre os 35 artigos recentes</strong>; zero não prova que o artigo esteja órfão no site inteiro. Para confirmar indexação, use Inspeção de URL no <a href="https://search.google.com/search-console/" target="_blank" rel="noopener">Search Console</a>.</p>';
    echo '<div style="max-width:1250px;overflow:auto"><table class="widefat striped"><thead><tr><th>Artigo recente</th><th>Publicado</th><th>Noindex</th><th>Canônica</th><th>Links internos recebidos*</th><th>Links internos feitos</th></tr></thead><tbody>';
    foreach ($posts as $p) {
        $url = get_permalink($p);
        $canonical = trim((string)get_post_meta($p->ID, 'rank_math_canonical_url', true));
        $canonical_issue = $canonical !== '' && bmd_ix_20261113_normalize_url($canonical) !== bmd_ix_20261113_normalize_url($url);
        $is_noindex = bmd_ix_20261113_robots_noindex($p->ID);
        $internal_count = 0;
        foreach ($outgoing[$p->ID] as $link => $yes) {
            if ($link !== bmd_ix_20261113_normalize_url($url)) { $internal_count++; }
        }
        echo '<tr><td><a href="' . esc_url($url) . '" target="_blank" rel="noopener">' . esc_html(get_the_title($p)) . '</a></td>';
        echo '<td>' . esc_html(get_the_date('d/m/Y', $p)) . '</td>';
        echo '<td>' . ($is_noindex ? '<strong style="color:#b91c1c">SIM</strong>' : 'Não detectado') . '</td>';
        echo '<td>' . ($canonical_issue ? '<strong style="color:#b45309">Diferente da URL</strong>' : 'Sem divergência detectada') . '</td>';
        echo '<td>' . (int)$incoming[$p->ID] . '</td><td>' . $internal_count . '</td></tr>';
    }
    if (!$posts) { echo '<tr><td colspan="6">Nenhum artigo publicado encontrado.</td></tr>'; }
    echo '</tbody></table></div>';
    echo '<p><small>*Somente entre os 35 artigos recentes analisados. O plugin não altera posts, canonical, robots, sitemap ou plugins de terceiros.</small></p>';
    echo '<h2>Prioridade recomendada</h2><p>Corrigir noindex ou canônica indevida, conferir sitemap e criar links contextuais para artigos novos. Publicar conteúdo útil e aguardar dados de rastreamento. Não repetir pedidos de indexação via API destinada a vagas e lives.</p></div>';
}

add_action('admin_menu', function () {
    add_management_page('Diagnóstico de indexação BMD', 'Indexação BMD', 'manage_options', 'bmd-indexation-diagnostic', 'bmd_ix_20261113_render_admin');
});
