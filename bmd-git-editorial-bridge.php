<?php
/**
 * BMD Editorial Git Bridge.
 * Conteudo editorial confiavel versionado no GitHub e entregue pelo Git da Hostinger.
 * Sem APIs externas, credenciais no repositorio, SQL direto ou alteracoes no tema.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_git_editorial_jobs() {
    $dir = __DIR__ . '/editorial/queue';
    if (!is_dir($dir)) { return array(); }
    $paths = glob($dir . '/*.json');
    if (!is_array($paths)) { return array(); }
    sort($paths, SORT_STRING);
    return array_slice($paths, 0, 30);
}

function bmd_git_editorial_digest() {
    $fingerprints = array();
    foreach (bmd_git_editorial_jobs() as $path) {
        $fingerprints[] = basename($path) . ':' . hash_file('sha256', $path);
    }
    return hash('sha256', implode('|', $fingerprints));
}

add_action('init', function () {
    if (defined('WP_INSTALLING') && WP_INSTALLING) { return; }
    $files = bmd_git_editorial_jobs();
    if (!$files) { return; }
    $hash = bmd_git_editorial_digest();
    if (get_option('bmd_git_editorial_scheduled_hash', '') === $hash) { return; }
    update_option('bmd_git_editorial_scheduled_hash', $hash, false);
    if (!wp_next_scheduled('bmd_git_editorial_process')) {
        wp_schedule_single_event(time() + 15, 'bmd_git_editorial_process');
    }
}, 40);

function bmd_git_editorial_result($job_id, $message) {
    update_option('bmd_git_editorial_result_' . md5($job_id), $message, false);
}

function bmd_git_editorial_upload_image($relative, $title, $alt, $post_id) {
    if (!is_string($relative) || !preg_match('~^assets/editorial/[a-z0-9._/-]+\\.(?:svg|webp|png|jpg|jpeg)$~i', $relative)) {
        return new WP_Error('image_path', 'Caminho de imagem nao permitido.');
    }
    $source = realpath(__DIR__ . '/' . $relative);
    $base = realpath(__DIR__ . '/assets/editorial');
    if (!$source || !$base || strpos($source, $base . DIRECTORY_SEPARATOR) !== 0 || !is_readable($source) || filesize($source) > 4 * 1024 * 1024) {
        return new WP_Error('image_missing', 'Imagem ausente, fora da pasta ou muito grande.');
    }
    $ext = strtolower(pathinfo($source, PATHINFO_EXTENSION));
    $bytes = file_get_contents($source);
    if ($bytes === false) { return new WP_Error('image_read', 'Nao foi possivel ler a imagem.'); }
    $name = sanitize_file_name(pathinfo($source, PATHINFO_FILENAME));
    $webp = false;
    if ($ext === 'svg') {
        // SVG permanece disponivel como asset do plugin, sem upload SVG inseguro.
        if (!class_exists('Imagick')) {
            return array('attachment_id' => 0, 'url' => plugins_url($relative, __FILE__), 'format' => 'svg');
        }
        try {
            $im = new Imagick();
            $im->setBackgroundColor(new ImagickPixel('white'));
            $im->setResolution(144, 144);
            $im->readImageBlob($bytes, 'bmd-safe-svg.svg');
            $im->setImageFormat('webp');
            $im->setImageCompressionQuality(82);
            $im->resizeImage(1200, 630, Imagick::FILTER_LANCZOS, 1, true);
            $bytes = $im->getImagesBlob();
            $im->clear();
            $webp = true;
        } catch (Exception $e) {
            return array('attachment_id' => 0, 'url' => plugins_url($relative, __FILE__), 'format' => 'svg');
        }
    } elseif ($ext === 'webp') {
        $webp = true;
    } elseif (function_exists('wp_get_image_editor')) {
        $editor = wp_get_image_editor($source);
        if (!is_wp_error($editor)) {
            $editor->resize(1200, 630, false);
            $tmp = wp_tempnam($name . '.webp');
            if ($tmp) {
                $saved = $editor->save($tmp, 'image/webp');
                if (!is_wp_error($saved) && is_readable($tmp)) {
                    $bytes = file_get_contents($tmp);
                    $webp = true;
                }
                @unlink($tmp);
            }
        }
    }
    $file = $name . '-' . substr(hash('sha256', $bytes), 0, 12) . ($webp ? '.webp' : '.' . $ext);
    $existing = get_posts(array('post_type' => 'attachment', 'post_status' => 'inherit', 'meta_key' => '_bmd_git_asset_hash', 'meta_value' => hash('sha256', $bytes), 'fields' => 'ids', 'posts_per_page' => 1));
    if ($existing) {
        $id = (int)$existing[0];
        return array('attachment_id' => $id, 'url' => wp_get_attachment_url($id), 'format' => $webp ? 'webp' : $ext);
    }
    $upload = wp_upload_bits($file, null, $bytes);
    if (!empty($upload['error'])) { return new WP_Error('image_upload', $upload['error']); }
    $mime = $webp ? 'image/webp' : ($ext === 'png' ? 'image/png' : 'image/jpeg');
    $attachment = wp_insert_attachment(array(
        'post_title' => sanitize_text_field($title),
        'post_mime_type' => $mime,
        'post_status' => 'inherit',
    ), $upload['file'], $post_id, true);
    if (is_wp_error($attachment)) { return $attachment; }
    update_post_meta($attachment, '_wp_attachment_image_alt', sanitize_text_field($alt));
    update_post_meta($attachment, '_bmd_git_asset_hash', hash('sha256', $bytes));
    require_once ABSPATH . 'wp-admin/includes/image.php';
    $meta = wp_generate_attachment_metadata($attachment, $upload['file']);
    if (!is_wp_error($meta)) { wp_update_attachment_metadata($attachment, $meta); }
    return array('attachment_id' => (int)$attachment, 'url' => $upload['url'], 'format' => $webp ? 'webp' : $ext);
}

function bmd_git_editorial_apply($job) {
    if (!is_array($job)) { return new WP_Error('job_json', 'JSON invalido.'); }
    $id = isset($job['id']) ? (string)$job['id'] : '';
    if (!preg_match('/^[a-z0-9-]{8,64}$/', $id)) { return new WP_Error('job_id', 'ID da tarefa invalido.'); }
    $action = isset($job['action']) ? $job['action'] : '';
    if (!in_array($action, array('create', 'append'), true)) { return new WP_Error('job_action', 'Acao nao permitida.'); }
    $slug = isset($job['slug']) ? sanitize_title($job['slug']) : '';
    if (!$slug || $slug !== $job['slug']) { return new WP_Error('job_slug', 'Slug invalido.'); }
    $body = isset($job['body']) ? $job['body'] : '';
    if (!is_string($body) || strlen($body) > 100000 || strlen($body) < 100) { return new WP_Error('job_body', 'Conteudo fora dos limites.'); }
    $body = wp_kses_post($body);
    $existing = get_page_by_path($slug, OBJECT, 'post');
    $post_id = 0;
    if ($action === 'create') {
        if ($existing && get_post_meta($existing->ID, '_bmd_git_editorial_source', true) !== $id) {
            return new WP_Error('job_conflict', 'Slug ja pertence a outro artigo.');
        }
        $title = sanitize_text_field(isset($job['title']) ? $job['title'] : '');
        if (!$title) { return new WP_Error('job_title', 'Titulo vazio.'); }
        $status = (isset($job['status']) && $job['status'] === 'publish') ? 'publish' : 'draft';
        // Sem alterar um artigo publicado acidentalmente em nova revisao do arquivo.
        if ($existing && $existing->post_status === 'publish' && $status === 'draft') { $status = 'publish'; }
        $args = array(
            'post_type' => 'post', 'post_status' => $status,
            'post_title' => $title, 'post_name' => $slug,
            'post_content' => $body,
            'post_excerpt' => sanitize_text_field(isset($job['excerpt']) ? $job['excerpt'] : ''),
            'post_category' => array(7),
        );
        if ($existing) {
            $post_id = (int)$existing->ID;
            if (!metadata_exists('post', $post_id, '_bmd_git_editorial_backup_' . $id)) {
                if (!add_post_meta($post_id, '_bmd_git_editorial_backup_' . $id, get_post_field('post_content', $post_id), true)) {
                    return new WP_Error('backup_failed', 'Backup nao criado.');
                }
            }
            $args['ID'] = $post_id;
            $res = wp_update_post($args, true);
        } else {
            $res = wp_insert_post($args, true);
        }
        if (is_wp_error($res)) { return $res; }
        $post_id = (int)$res;
        update_post_meta($post_id, '_bmd_git_editorial_source', $id);
    } else {
        if (!$existing || (int)$existing->ID !== (int)(isset($job['post_id']) ? $job['post_id'] : 0) || $existing->post_status !== 'publish') {
            return new WP_Error('job_mismatch', 'ID, slug ou status do artigo nao confere.');
        }
        $post_id = (int)$existing->ID;
        $old = (string)$existing->post_content;
        $marker = 'id="bmd-' . esc_attr($id) . '"';
        if (strpos($old, $marker) !== false) { return $post_id; }
        $body = '<section class="bmd-git-note" id="bmd-' . esc_attr($id) . '">' . $body . '</section>';
        if (!add_post_meta($post_id, '_bmd_git_editorial_backup_' . $id, $old, true)) {
            return new WP_Error('backup_failed', 'Backup ja existe ou falhou; nenhuma alteracao realizada.');
        }
        $res = wp_update_post(array('ID' => $post_id, 'post_content' => $old . "\n" . $body), true);
        if (is_wp_error($res)) { return $res; }
    }
    if ($action === 'create' && !empty($job['image']) && is_array($job['image'])) {
        $media = bmd_git_editorial_upload_image($job['image']['path'], $job['title'], $job['image']['alt'], $post_id);
        if (!is_wp_error($media)) {
            if (!empty($media['attachment_id'])) {
                set_post_thumbnail($post_id, $media['attachment_id']);
            }
            update_post_meta($post_id, '_bmd_git_editorial_image_url', esc_url_raw($media['url']));
        } else {
            bmd_git_editorial_result($id . '-image', $media->get_error_message());
        }
    }
    clean_post_cache($post_id);
    if (has_action('litespeed_purge_post')) { do_action('litespeed_purge_post', $post_id); }
    return $post_id;
}

add_action('bmd_git_editorial_process', function () {
    foreach (bmd_git_editorial_jobs() as $path) {
        $raw = file_get_contents($path);
        $job = json_decode($raw, true);
        $id = isset($job['id']) ? (string)$job['id'] : basename($path);
        $digest = hash('sha256', $raw);
        $key = 'bmd_git_editorial_done_' . md5($id);
        if (get_option($key, '') === $digest) { continue; }
        $res = bmd_git_editorial_apply($job);
        if (is_wp_error($res)) {
            bmd_git_editorial_result($id, $res->get_error_code() . ': ' . $res->get_error_message());
        } else {
            update_option($key, $digest, false);
            bmd_git_editorial_result($id, 'OK: post ' . (int)$res);
        }
    }
}, 10);

add_action('admin_menu', function () {
    add_management_page('BMD Git Editorial', 'BMD Git Editorial', 'manage_options', 'bmd-git-editorial', function () {
        if (!current_user_can('manage_options')) { return; }
        echo '<div class="wrap"><h1>BMD Git Editorial</h1><p>Arquivos em GitHub /editorial/queue. Webhook Hostinger entrega o plugin; WP-Cron aplica as tarefas com backup e sem servicos pagos.</p>';
        foreach (bmd_git_editorial_jobs() as $path) {
            $job = json_decode(file_get_contents($path), true);
            $id = isset($job['id']) ? (string)$job['id'] : basename($path);
            echo '<p><strong>' . esc_html($id) . ':</strong> ' . esc_html(get_option('bmd_git_editorial_result_' . md5($id), 'Aguardando execucao')) . '</p>';
        }
        echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '"><input type="hidden" name="action" value="bmd_git_editorial_run">';
        wp_nonce_field('bmd_git_editorial_run');
        submit_button('Processar agora');
        echo '</form></div>';
    });
});
add_action('admin_post_bmd_git_editorial_run', function () {
    if (!current_user_can('manage_options') || !check_admin_referer('bmd_git_editorial_run')) { wp_die('Acesso negado'); }
    do_action('bmd_git_editorial_process');
    wp_safe_redirect(admin_url('tools.php?page=bmd-git-editorial'));
    exit;
});
add_action('wp_head', function () {
    if (!is_singular('post')) { return; }
    echo '<style>.entry-content .bmd-git-note{margin:26px 0;padding:20px 22px;background:#f5f8fc;color:#182433;border:1px solid #c7d5e5;border-radius:14px;font-size:1rem;line-height:1.7}.entry-content .bmd-git-note a{color:#0649a6;text-decoration:underline;text-underline-offset:2px}.entry-content .bmd-git-note h2,.entry-content .bmd-git-note h3{color:#152338;margin:0 0 12px}.entry-content .bmd-git-note a:focus-visible{outline:3px solid #ffbf47;outline-offset:3px}@media(max-width:600px){.entry-content .bmd-git-note{padding:15px}}</style>';
}, 91);
