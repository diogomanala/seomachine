<?php
/**
 * BMD SEO Mass Repair — 29 October 2026.
 *
 * One-time bounded crawl of published WP posts/pages, with safe, reversible
 * normalization of confirmed internal URLs only. Never promises indexation,
 * submits arbitrary posts to Google's restricted Indexing API, rewrites
 * external links, changes slugs or publishes drafts.
 *
 * WordPress admin panel: Tools > SEO em Massa. Bulk process is WP-Cron-safe
 * and can also be invoked by a trusted CLI scheduler with WP loaded.
 *
 * All mutations create a private postmeta backup, and use wp_update_post so
 * WordPress revisions and Rank Math hooks continue to work normally.
 */
if (!defined('ABSPATH')) { exit; }

if (!defined('BMDSEO_BULK_OPTION')) {
    define('BMDSEO_BULK_OPTION', 'bmdseo_bulk_state_20261029_v1');
}
if (!defined('BMDSEO_BULK_BACKUP_META')) {
    define('BMDSEO_BULK_BACKUP_META', '_bmdseo_original_content_20261029');
}
if (!defined('BMDSEO_BULK_VERSION')) {
    define('BMDSEO_BULK_VERSION', '1.0.0');
}

function bmdseo_bulk_default_state() {
    return array(
        'version' => BMDSEO_BULK_VERSION,
        'status' => 'pending',
        'cursor' => 0,
        'scanned' => 0,
        'links' => 0,
        'repaired_posts' => 0,
        'repaired_links' => 0,
        'issues' => array(),
        'issue_counts' => array(),
        'checked' => 0,
        'started' => current_time('mysql'),
        'updated' => '',
        'completed' => '',
        'error' => ''
    );
}
function bmdseo_bulk_get_state() {
    $state = get_option(BMDSEO_BULK_OPTION, false);
    return is_array($state) ? array_merge(bmdseo_bulk_default_state(), $state) : bmdseo_bulk_default_state();
}
function bmdseo_bulk_issue(&$state, $kind, $id, $detail, $url, $severity='review') {
    if (!isset($state['issue_counts'][$kind])) { $state['issue_counts'][$kind] = 0; }
    $state['issue_counts'][$kind]++;
    if (count($state['issues']) >= 1900) { return; }
    $state['issues'][] = array(
        'kind' => sanitize_key($kind),
        'post_id' => (int)$id,
        'post' => sanitize_text_field(get_the_title($id)),
        'detail' => sanitize_text_field($detail),
        'url' => substr(sanitize_text_field($url), 0, 350),
        'severity' => sanitize_key($severity)
    );
}
function bmdseo_bulk_path_is_archive($path) {
    return (bool)preg_match('~^/(?:wp-admin|wp-json|wp-content|category|tag|author|feed|comments|search|page)/|^/robots\.txt$|^/sitemap(?:_index)?\.xml$|^/post-sitemap\d*\.xml$|^/page-sitemap\.xml$|^/category-sitemap\.xml$~i', $path);
}
/**
 * Normalize one internal link. Returns the new URL on proven, safe match;
 * otherwise returns null. Issues are logged separately from mutations.
 */
function bmdseo_bulk_check_url($original, $post_id, $attribute, &$state) {
    $raw = trim(html_entity_decode($original, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    if ($raw === '' || $raw[0]==='#' || preg_match('~^(mailto:|tel:|javascript:|data:|blob:)~i', $raw)) { return null; }
    $root_host = 'blogdemarketingdigital.com.br';
    $old_hosts = array('wordpress-8020-blogmktdigital.cloudclusters.net');
    $is_relative = ($raw[0]==='/' && substr($raw, 0, 2)!=='//');
    if (!$is_relative && !preg_match('~^https?://~i', $raw) && substr($raw, 0, 2)!=='//') { return null; }
    $candidate = substr($raw, 0, 2)==='//' ? 'https:'.$raw : $raw;
    $parts = wp_parse_url($is_relative ? home_url($candidate) : $candidate);
    if (!is_array($parts)) {
        bmdseo_bulk_issue($state, 'url_invalida', $post_id, 'URL não pôde ser interpretada', $original);
        return null;
    }
    $host = strtolower(rtrim((string)(isset($parts['host']) ? $parts['host'] : $root_host), '.'));
    $host_no_www = preg_replace('~^www\.~', '', $host);
    $same_domain = ($host_no_www === $root_host);
    $legacy_host = in_array($host, $old_hosts, true);
    if (!$same_domain && !$legacy_host) {
        if ($attribute === 'href') { $state['checked']++; }
        return null; // External links are NEVER silently rewritten.
    }
    if (isset($parts['port']) && !in_array((int)$parts['port'], array(80,443), true)) {
        bmdseo_bulk_issue($state, 'porta_incomum', $post_id, 'Link interno usa porta não padrão', $original);
        return null;
    }
    $path = isset($parts['path']) ? (string)$parts['path'] : '/';
    if ($path === '') { $path = '/'; }
    if ($path[0] !== '/') { return null; }
    $query = isset($parts['query']) ? '?'.$parts['query'] : '';
    $fragment = isset($parts['fragment']) ? '#'.$parts['fragment'] : '';
    // Preserve query URLs; they may implement searches, auth or tracking.
    if (preg_match('~^/(?:baixar-video-instagram|baixar-video-instagram-2)(?:/|$)~i', $path)) {
        bmdseo_bulk_issue($state, 'ferramenta_aposentada_410', $post_id,
            'Link aponta para a ferramenta Instagram retirada (HTTP 410)', $original, 'high');
        return null;
    }
    if (preg_match('~^/extrator-de-emails-e-leads(?:/|$)~i', $path)) {
        bmdseo_bulk_issue($state, 'ferramenta_aposentada_301', $post_id,
            'Link antigo redireciona para a biblioteca de ferramentas; revisar contexto', $original);
        return null;
    }
    if (bmdseo_bulk_path_is_archive($path)) {
        if ($legacy_host) {
            bmdseo_bulk_issue($state, 'endereco_antigo_recurso', $post_id,
                'Recurso em hospedagem antiga requer verificação antes de trocar', $original);
            return null;
        }
        // Same-site resource is safe to upgrade from HTTP to HTTPS.
        if ($same_domain && !$query && preg_match('~^/wp-content/uploads/~i', $path)) {
            $new = home_url($path) . $fragment;
            return $raw !== $new ? $new : null;
        }
        return null;
    }
    if (preg_match('~\.[a-z0-9]{2,7}$~i', $path) && $path !== '/') {
        if ($legacy_host) {
            bmdseo_bulk_issue($state,'arquivo_host_antigo',$post_id,
                'Arquivo na hospedagem antiga; destino atual não comprovado',$original);
        }
        return null;
    }
    if ($query && !$legacy_host) { return null; }
    if ($attribute === 'src') { return null; } // Do not treat images as pages.
    $target_id = 0;
    $canonical = '';
    if ($path === '/') {
        $canonical = home_url('/');
    } else {
        $local_url = home_url($path);
        $target_id = (int)url_to_postid($local_url);
        if (!$target_id) {
            $normalized_path = trim(rawurldecode($path), '/');
            $found = get_page_by_path($normalized_path, OBJECT, array('post','page'));
            if ($found && get_post_status($found->ID)==='publish') {
                $target_id = (int)$found->ID;
            }
        }
        if ($target_id && get_post_status($target_id)==='publish') {
            $canonical = get_permalink($target_id);
        }
    }
    if (!$canonical) {
        $kind = $legacy_host ? 'link_hospedagem_antiga_sem_destino' : 'link_interno_sem_destino_confirmado';
        bmdseo_bulk_issue($state, $kind, $post_id,
            'Destino não encontrado como post/página publicada; verificar custom route, redirect ou erro', $original, 'review');
        return null;
    }
    // Preserve query and fragment to avoid losing user navigation.
    $new = $canonical . $query . $fragment;
    $old_wo_fragment = preg_replace('~#.*$~', '', $raw);
    if ($raw !== $new) {
        $is_host_fix = $legacy_host || ($same_domain && (
            $host !== $root_host ||
            (isset($parts['scheme']) && strtolower($parts['scheme'])!=='https') ||
            (rtrim($path,'/') !== rtrim(wp_parse_url($canonical, PHP_URL_PATH),'/'))
        ));
        if ($is_host_fix && (!isset($parts['query']) || $legacy_host)) {
            return $new;
        }
        // Already-valid relative links work correctly and should remain
        // relative. Mass-converting them into absolute URLs adds no SEO value.
    }
    return null;
}

function bmdseo_bulk_rewrite_content($content, $id, &$state, &$fix_count) {
    $fix_count = 0;
    $pattern = '~(<(?:a|img|source|iframe)\b[^>]*?\b(href|src)\s*=\s*)(["\'])(.*?)\3~is';
    $out = preg_replace_callback($pattern, function($m) use ($id,&$state,&$fix_count) {
        $state['links']++;
        $attr = strtolower($m[2]);
        $replacement = bmdseo_bulk_check_url($m[4],$id,$attr,$state);
        if ($replacement===null) { return $m[0]; }
        // Attribute-safe encoding, preserving original quote style.
        $escape = htmlspecialchars($replacement, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        if ($escape === $m[4]) { return $m[0]; }
        $fix_count++;
        return $m[1].$m[3].$escape.$m[3];
    }, (string)$content);
    return is_string($out) ? $out : $content;
}

function bmdseo_bulk_check_metadata($id,&$state) {
    $post = get_post($id);
    if (!$post) { return; }
    $robots = get_post_meta($id,'rank_math_robots',true);
    if (is_array($robots) && in_array('noindex',$robots,true)) {
        bmdseo_bulk_issue($state,'pagina_noindex',$id,
            'Post publicado marcado como noindex em Rank Math; revisar intenção','', 'review');
    }
    $canonical = (string)get_post_meta($id,'rank_math_canonical_url',true);
    if ($canonical !== '') {
        $expected = get_permalink($id);
        if (untrailingslashit($canonical) !== untrailingslashit($expected)) {
            bmdseo_bulk_issue($state,'canonical_personalizada',$id,
                'Canonical personalizada diverge do endereço público; conferir antes de alterar',
                $canonical, 'review');
        }
    }
    // Meaningful titles and excerpts cannot be invented automatically.
    $title = trim((string)$post->post_title);
    if ($title==='' || mb_strlen($title)>115) {
        bmdseo_bulk_issue($state,'titulo_revisar',$id,
            $title===''?'Post sem título':'Título muito longo: revisar clareza e intenção',
            get_permalink($id));
    }
    if (strlen(wp_strip_all_tags($post->post_content)) < 300) {
        bmdseo_bulk_issue($state,'conteudo_curto',$id,
            'Post com menos de 300 caracteres visíveis: revisar utilidade e mídia',
            get_permalink($id));
    }
}

function bmdseo_bulk_process_batch($limit=28) {
    global $wpdb;
    $limit = max(1,min(70,(int)$limit));
    $state = bmdseo_bulk_get_state();
    if ($state['status']==='complete') {
        return array('status'=>'complete','scanned'=>(int)$state['scanned'],
            'repaired_posts'=>(int)$state['repaired_posts'],
            'repaired_links'=>(int)$state['repaired_links'],'issue_counts'=>$state['issue_counts']);
    }
    if (get_transient('bmdseo_bulk_running_20261029')) {
        return array('status'=>'busy','scanned'=>(int)$state['scanned']);
    }
    set_transient('bmdseo_bulk_running_20261029',1,95);
    try {
        $ids = $wpdb->get_col($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_status='publish' AND post_type IN ('post','page') AND ID > %d ORDER BY ID ASC LIMIT %d",
            (int)$state['cursor'], $limit
        ));
        if (!is_array($ids)) { $ids=array(); }
        foreach ($ids as $id) {
            $id=(int)$id;
            $post=get_post($id);
            if (!$post || $post->post_status!=='publish') { continue; }
            $state['cursor']=$id;
            $state['scanned']++;
            bmdseo_bulk_check_metadata($id,$state);
            $content=(string)$post->post_content;
            $fix_count=0;
            $updated=bmdseo_bulk_rewrite_content($content,$id,$state,$fix_count);
            if ($fix_count && $updated!==$content) {
                // Protect existing Elementor/shortcode layouts: only rewrite known
                // URL attributes in ordinary post content; skip builder postmeta.
                $is_builder=get_post_meta($id,'_elementor_edit_mode',true)==='builder';
                if ($is_builder) {
                    bmdseo_bulk_issue($state,'elementor_edicao_manual',$id,
                        'URLs normalizáveis em conteúdo de Elementor: não alterar builder automaticamente',
                        get_permalink($id));
                } elseif (!metadata_exists('post',$id,BMDSEO_BULK_BACKUP_META) &&
                    add_post_meta($id,BMDSEO_BULK_BACKUP_META,$content,true)) {
                    $res=wp_update_post(array('ID'=>$id,'post_content'=>$updated),true);
                    if (!is_wp_error($res)) {
                        $state['repaired_posts']++;
                        $state['repaired_links'] += $fix_count;
                        update_post_meta($id,'_bmdseo_fixed_at_20261029',current_time('mysql'));
                    } else {
                        bmdseo_bulk_issue($state,'erro_salvar',$id,
                            'Falha no WordPress ao gravar URL normalizada',get_permalink($id),'high');
                    }
                } else {
                    bmdseo_bulk_issue($state,'backup_existente',$id,
                        'Correção encontrada, mas existe backup anterior ou não foi possível criar backup',
                        get_permalink($id));
                }
            }
        }
        $state['updated']=current_time('mysql');
        if (count($ids)<$limit) {
            $state['status']='complete';
            $state['completed']=current_time('mysql');
            // Report duplicate titles, without blindly changing titles.
            $dupes=$wpdb->get_results(
                "SELECT post_title, COUNT(*) AS duplicates FROM {$wpdb->posts}
                 WHERE post_status='publish' AND post_type IN ('post','page')
                 AND post_title<>'' GROUP BY post_title HAVING COUNT(*)>1 LIMIT 70"
            );
            foreach ((array)$dupes as $d) {
                if (!is_object($d)) { continue; }
                bmdseo_bulk_issue($state,'titulo_duplicado',0,
                    'Título repetido em '.(int)$d->duplicates.' publicações',
                    (string)$d->post_title);
            }
        } else {
            $state['status']='running';
        }
        update_option(BMDSEO_BULK_OPTION,$state,false);
        if ($state['status']!=='complete' && !wp_next_scheduled('bmdseo_bulk_scheduled_batch_20261029')) {
            wp_schedule_single_event(time()+45,'bmdseo_bulk_scheduled_batch_20261029');
        }
    } catch (Throwable $e) {
        $state['status']='error';
        $state['error']='Ocorreu um erro no processamento. Verifique o log PHP.';
        $state['updated']=current_time('mysql');
        update_option(BMDSEO_BULK_OPTION,$state,false);
        error_log('BMD SEO bulk: '.$e->getMessage());
    }
    delete_transient('bmdseo_bulk_running_20261029');
    return array(
        'status'=>$state['status'],
        'scanned'=>(int)$state['scanned'],
        'repaired_posts'=>(int)$state['repaired_posts'],
        'repaired_links'=>(int)$state['repaired_links'],
        'issues'=>array_sum($state['issue_counts']),
        'issue_counts'=>$state['issue_counts'],
        'last_id'=>(int)$state['cursor'],
        'updated'=>$state['updated']
    );
}

add_action('bmdseo_bulk_scheduled_batch_20261029',function(){
    bmdseo_bulk_process_batch(28);
});

// One-time kickoff; never restart completed work on subsequent site visits.
add_action('init',function(){
    $state=get_option(BMDSEO_BULK_OPTION,false);
    if ($state!==false || wp_next_scheduled('bmdseo_bulk_scheduled_batch_20261029')) { return; }
    add_option(BMDSEO_BULK_OPTION,bmdseo_bulk_default_state(),'','no');
    wp_schedule_single_event(time()+25,'bmdseo_bulk_scheduled_batch_20261029');
},50);

/** Admin-only inspection, progress, manual batch trigger and CSV export. */
add_action('admin_menu',function(){
    add_management_page('Auditoria SEO em massa','SEO em Massa','manage_options',
        'bmd-seo-em-massa','bmdseo_bulk_admin_page');
});
function bmdseo_bulk_admin_page() {
    if (!current_user_can('manage_options')) { wp_die('Acesso não autorizado.'); }
    $s=bmdseo_bulk_get_state();
    $nonce=wp_create_nonce('bmdseo_bulk_action');
    ?>
    <div class="wrap bmdseo-bulk-admin">
      <h1>SEO em Massa <small>Auditoria e reparo de links</small></h1>
      <p class="description">Revisa todos os artigos e páginas publicados em lotes. Corrige apenas links internos cujo endereço definitivo é confirmado pelo WordPress. Não força indexação, não altera links externos, títulos nem URLs canônicas.</p>
      <div class="bmdseo-bulk-grid" id="bmdseo-bulk-stats">
       <div><strong data-bmd="scanned"><?php echo esc_html((string)$s['scanned']); ?></strong><span>Páginas verificadas</span></div>
       <div><strong data-bmd="repaired_posts"><?php echo esc_html((string)$s['repaired_posts']); ?></strong><span>Publicações corrigidas</span></div>
       <div><strong data-bmd="repaired_links"><?php echo esc_html((string)$s['repaired_links']); ?></strong><span>Links restaurados</span></div>
       <div><strong data-bmd="issues"><?php echo esc_html((string)array_sum($s['issue_counts'])); ?></strong><span>Ocorrências na 1ª análise</span></div>
      </div>
      <section class="bmdseo-bulk-section" aria-labelledby="bmdseo-bulk-status-label">
       <h2 id="bmdseo-bulk-status-label">Andamento: <span id="bmdseo-bulk-phase"><?php echo esc_html($s['status']); ?></span></h2>
       <p>O processamento divide o trabalho em lotes para evitar sobrecarga no PHP, com backup privado por artigo antes de qualquer alteração.</p>
       <button type="button" class="button button-primary" id="bmdseo-bulk-start" <?php disabled($s['status']==='complete'); ?>><?php echo $s['status']==='complete' ? 'Auditoria concluída' : 'Executar todos os lotes agora'; ?></button>
       <a class="button" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=bmdseo_bulk_export'), 'bmdseo_bulk_download')); ?>">Baixar relatório CSV</a>
       <span role="status" aria-live="polite" id="bmdseo-bulk-feedback"></span>
      </section>
      <section class="bmdseo-bulk-section">
       <h2>O que é corrigido automaticamente?</h2>
       <p><strong>Sim:</strong> links internos HTTP, com www e URLs da hospedagem antiga se uma página correspondente estiver publicada; todos com backup e revisão registrada.</p>
       <p><strong>Revisão humana:</strong> links para ferramentas aposentadas, URLs sem destino confirmado, canônicas personalizadas, noindex, conteúdos curtos e títulos duplicados. Um erro 404 não autoriza inventar um redirecionamento.</p>
       <p><strong>Indexação:</strong> o relatório de sitemaps e o Search Console são os meios apropriados para acompanhar a descoberta e indexação. O Google não permite indexação instantânea garantida de posts comuns.</p>
      </section>
      <section class="bmdseo-bulk-section">
       <h2>Ocorrências por tipo</h2>
       <table class="widefat striped"><thead><tr><th>Tipo</th><th>Ocorrências</th></tr></thead><tbody id="bmdseo-bulk-issue-counts">
        <?php foreach($s['issue_counts'] as $key=>$count): ?>
         <tr><td><?php echo esc_html($key); ?></td><td><?php echo (int)$count; ?></td></tr>
        <?php endforeach; ?>
       </tbody></table>
       <p>O relatório registra os achados da primeira varredura. Recursos da hospedagem antiga podem ter sido recuperados na etapa 2; compare com o relatório de mídia abaixo.</p>
      </section>
      <?php if (function_exists('bmdseo_media_admin_section')) { bmdseo_media_admin_section(); } ?>
    </div>
    <?php
}

add_action('admin_enqueue_scripts',function($hook){
    if ($hook!=='tools_page_bmd-seo-em-massa') { return; }
    $css=__DIR__.'/assets/bmd-seo-bulk-admin-20261029.css';
    $js=__DIR__.'/assets/bmd-seo-bulk-admin-20261029.js';
    if (is_file($css)) {
        wp_enqueue_style('bmd-seo-bulk-admin',plugins_url('assets/bmd-seo-bulk-admin-20261029.css',__FILE__),array(),filemtime($css));
    }
    if (is_file($js)) {
        wp_enqueue_script('bmd-seo-bulk-admin',plugins_url('assets/bmd-seo-bulk-admin-20261029.js',__FILE__),array(),filemtime($js),true);
        wp_add_inline_script('bmd-seo-bulk-admin',
            'window.BmdSeoBulk='.wp_json_encode(array(
                'ajax'=>admin_url('admin-ajax.php'),
                'nonce'=>wp_create_nonce('bmdseo_bulk_action')
            )).';','before');
    }
});

add_action('wp_ajax_bmdseo_bulk_step',function(){
    if (!current_user_can('manage_options')) { wp_send_json_error('Acesso não autorizado',403); }
    check_ajax_referer('bmdseo_bulk_action','nonce');
    $r=bmdseo_bulk_process_batch(28);
    wp_send_json_success($r);
});

add_action('admin_post_bmdseo_bulk_export',function(){
    if (!current_user_can('manage_options')) { wp_die('Acesso não autorizado.'); }
    check_admin_referer('bmdseo_bulk_download');
    $state=bmdseo_bulk_get_state();
    nocache_headers();
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="bmd-auditoria-seo-em-massa-20261029.csv"');
    $f=fopen('php://output','w');
    if (!$f) { wp_die('Não foi possível exportar.'); }
    fwrite($f,"\xEF\xBB\xBF");
    fputcsv($f,array('Tipo','ID da publicação','Título','Descrição','URL / valor','Severidade'),';');
    foreach($state['issues'] as $issue){
        $cells=array(
            $issue['kind'],(int)$issue['post_id'],$issue['post'],
            $issue['detail'],$issue['url'],$issue['severity']
        );
        // Avoid spreadsheet formula injection in externally controlled URLs.
        foreach($cells as &$cell){if(is_string($cell)&&preg_match('/^[\s]*[=+\-@]/u',$cell))$cell="'".$cell;}unset($cell);
        fputcsv($f,$cells,';');
    }
    fclose($f);
    exit;
});
