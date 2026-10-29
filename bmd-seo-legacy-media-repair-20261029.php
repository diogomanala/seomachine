<?php
/**
 * BMD SEO Mass Repair phase 2 — restore broken migrated-media references.
 *
 * Known former WP host: wordpress-8020-blogmktdigital.cloudclusters.net
 * Rewrite an old media URL only if its exact file physically exists under
 * wp-content/uploads/ on the present installation. Missing files remain
 * untouched and are exported for human review. No remote downloads/crawls.
 *
 * Independent per-post backup and WordPress revision before any mutation.
 */
if (!defined('ABSPATH')) { exit; }

define('BMDSEO_MEDIA_STATE', 'bmdseo_bulk_media_state_20261029_v1');
define('BMDSEO_MEDIA_BACKUP', '_bmdseo_original_media_content_20261029');

function bmdseo_media_initial_state() {
    return array(
        'status'=>'pending', 'cursor'=>0, 'scanned'=>0,
        'posts_fixed'=>0, 'assets_fixed'=>0, 'missing'=>0,
        'examples'=>array(), 'updated'=>'', 'finished'=>''
    );
}
function bmdseo_media_state() {
    $s=get_option(BMDSEO_MEDIA_STATE,false);
    return is_array($s) ? array_merge(bmdseo_media_initial_state(),$s) : bmdseo_media_initial_state();
}
function bmdseo_media_process_batch($limit=40) {
    global $wpdb;
    $limit=max(1,min(60,(int)$limit));
    $s=bmdseo_media_state();
    if ($s['status']==='complete') {
        return array('status'=>'complete','scanned'=>$s['scanned'],'posts_fixed'=>$s['posts_fixed'],
            'assets_fixed'=>$s['assets_fixed'],'missing'=>$s['missing'],'examples'=>$s['examples']);
    }
    if (get_transient('bmdseo_media_lock_20261029')) {
        return array('status'=>'busy','scanned'=>$s['scanned']);
    }
    set_transient('bmdseo_media_lock_20261029',1,100);
    try {
        $ids=$wpdb->get_col($wpdb->prepare(
            "SELECT ID FROM {$wpdb->posts} WHERE post_status='publish' AND post_type IN ('post','page') AND ID>%d ORDER BY ID ASC LIMIT %d",
            (int)$s['cursor'],$limit
        ));
        if (!is_array($ids)) { $ids=array(); }
        $uploads=wp_get_upload_dir();
        $uploads_real=isset($uploads['basedir']) ? realpath($uploads['basedir']) : false;
        $uploads_base=$uploads_real ? trailingslashit(wp_normalize_path($uploads_real)) : false;
        if (!$uploads_base) { throw new RuntimeException('Diretório de uploads não encontrado'); }

        foreach ($ids as $raw_id) {
            $id=(int)$raw_id;
            $post=get_post($id);
            if (!$post || $post->post_status!=='publish') { continue; }
            $s['cursor']=$id;
            $s['scanned']++;
            $content=(string)$post->post_content;
            if (stripos($content,'wordpress-8020-blogmktdigital.cloudclusters.net')===false) { continue; }
            $fixed=0;
            $missing_names=array();

            $rewritten=preg_replace_callback(
                '~https?://wordpress-8020-blogmktdigital\.cloudclusters\.net/[^\s"\'<>),]+~i',
                function($m) use($id,$uploads_base,&$fixed,&$missing_names) {
                    $old=$m[0];
                    $url=wp_parse_url($old);
                    if (!is_array($url)) { return $old; }
                    $path=isset($url['path'])?(string)$url['path']:'';
                    if (strpos($path,'/wp-content/uploads/')!==0) {
                        return $old;
                    }
                    $relative=substr($path,strlen('/wp-content/uploads/'));
                    if ($relative==='' || strpos($relative,'..')!==false) { return $old; }
                    $relative_path=rawurldecode($relative);
                    $filename=realpath($uploads_base.$relative_path);
                    if ($filename===false ||
                        strpos(wp_normalize_path($filename),$uploads_base)!==0 ||
                        !is_file($filename)) {
                        $missing_names[$path]=true;
                        return $old;
                    }
                    $new=home_url('/wp-content/uploads/'.$relative);
                    if (isset($url['query'])) { $new.='?'.$url['query']; }
                    if (isset($url['fragment'])) { $new.='#'.$url['fragment']; }
                    if ($old!==$new) { $fixed++; }
                    return $new;
                }, $content
            );
            if (!is_string($rewritten)) { $rewritten=$content; }
            $s['missing']+=count($missing_names);
            foreach(array_keys($missing_names) as $missing_path) {
                if(count($s['examples'])>=180)break;
                $s['examples'][]=array('post_id'=>$id,'title'=>sanitize_text_field($post->post_title),
                    'path'=>substr($missing_path,0,330),'reason'=>'Arquivo não localizado nos uploads atuais');
            }
            if ($fixed>0 && $rewritten!==$content) {
                if (get_post_meta($id,'_elementor_edit_mode',true)==='builder') {
                    if(count($s['examples'])<180) {
                        $s['examples'][]=array('post_id'=>$id,'title'=>sanitize_text_field($post->post_title),
                            'path'=>get_permalink($id),'reason'=>'Recurso do Elementor: editar no próprio construtor');
                    }
                } elseif (!metadata_exists('post',$id,BMDSEO_MEDIA_BACKUP) &&
                    add_post_meta($id,BMDSEO_MEDIA_BACKUP,$content,true)) {
                    $res=wp_update_post(array('ID'=>$id,'post_content'=>$rewritten),true);
                    if (!is_wp_error($res)) {
                        $s['posts_fixed']++;
                        $s['assets_fixed']+=$fixed;
                        update_post_meta($id,'_bmdseo_media_fixed_at_20261029',current_time('mysql'));
                    } elseif (count($s['examples'])<180) {
                        $s['examples'][]=array('post_id'=>$id,'title'=>sanitize_text_field($post->post_title),
                            'path'=>get_permalink($id),'reason'=>'Falha ao salvar no WordPress');
                    }
                } elseif (count($s['examples'])<180) {
                    $s['examples'][]=array('post_id'=>$id,'title'=>sanitize_text_field($post->post_title),
                        'path'=>get_permalink($id),'reason'=>'Backup anterior já existe; conferir antes de sobrescrever');
                }
            }
        }

        $s['updated']=current_time('mysql');
        if (count($ids)<$limit) {
            $s['status']='complete';
            $s['finished']=current_time('mysql');
        } else {
            $s['status']='running';
        }
        update_option(BMDSEO_MEDIA_STATE,$s,false);
        if ($s['status']!=='complete' && !wp_next_scheduled('bmdseo_media_scheduled_batch_20261029')) {
            wp_schedule_single_event(time()+55,'bmdseo_media_scheduled_batch_20261029');
        }
    } catch(Throwable $e) {
        $s['status']='error';
        $s['updated']=current_time('mysql');
        update_option(BMDSEO_MEDIA_STATE,$s,false);
        error_log('BMD media restore: '.$e->getMessage());
    }
    delete_transient('bmdseo_media_lock_20261029');
    return array('status'=>$s['status'],'scanned'=>$s['scanned'],
        'posts_fixed'=>$s['posts_fixed'],'assets_fixed'=>$s['assets_fixed'],
        'missing'=>$s['missing'],'examples'=>array_slice($s['examples'],0,8));
}
add_action('bmdseo_media_scheduled_batch_20261029',function(){
    bmdseo_media_process_batch(30);
});
add_action('init',function(){
    if(get_option(BMDSEO_MEDIA_STATE,false)!==false ||
       wp_next_scheduled('bmdseo_media_scheduled_batch_20261029'))return;
    $first=get_option('bmdseo_bulk_state_20261029_v1',false);
    if (!is_array($first) || $first['status']!=='complete') { return; }
    add_option(BMDSEO_MEDIA_STATE,bmdseo_media_initial_state(),'','no');
    wp_schedule_single_event(time()+35,'bmdseo_media_scheduled_batch_20261029');
},70);

/** Add phase-2 summary to the same admin report, no frontend assets. */
function bmdseo_media_admin_section() {
    $s=bmdseo_media_state();
    echo '<section class="bmdseo-bulk-section" id="bmdseo-bulk-media"><h2>Arquivos da hospedagem antiga</h2>';
    echo '<p>Etapa 2: substitui somente os links para imagens e arquivos de uploads que foram encontrados fisicamente no servidor atual. Nenhuma imagem é baixada ou gerada por aproximação.</p>';
    echo '<div class="bmdseo-bulk-grid">';
    foreach(array(
        'Publicações verificadas'=>(int)$s['scanned'],
        'Publicações recuperadas'=>(int)$s['posts_fixed'],
        'URLs de recursos reparadas'=>(int)$s['assets_fixed'],
        'Caminhos ausentes'=>(int)$s['missing']
    ) as $label=>$value) {
        echo '<div><strong>'.esc_html((string)$value).'</strong><span>'.esc_html($label).'</span></div>';
    }
    echo '</div><p><strong>Etapa:</strong> '.esc_html($s['status']).'. Arquivos ausentes permanecem inalterados para evitar imagens quebradas.</p>';
    echo '<a class="button" href="'.esc_url(wp_nonce_url(admin_url('admin-post.php?action=bmdseo_media_export'), 'bmdseo_media_download')).'">Exportar recursos ausentes</a>';
    echo '</section>';
}
add_action('admin_post_bmdseo_media_export',function(){
    if(!current_user_can('manage_options'))wp_die('Sem permissão.');
    check_admin_referer('bmdseo_media_download');
    $s=bmdseo_media_state();
    nocache_headers();
    header('Content-Type: text/csv; charset=UTF-8');
    header('Content-Disposition: attachment; filename="bmd-recursos-hospedagem-antiga-20261029.csv"');
    $out=fopen('php://output','w');
    if(!$out)wp_die('Não foi possível exportar.');
    fwrite($out,"\xEF\xBB\xBF");
    fputcsv($out,array('Post ID','Título','Caminho antigo','Motivo'),';');
    foreach($s['examples'] as $x) {
        $cells=array((int)$x['post_id'],$x['title'],$x['path'],$x['reason']);
        foreach($cells as &$cell) {
            if(is_string($cell) && preg_match('/^[\s]*[=+\-@]/u',$cell))$cell="'".$cell;
        } unset($cell);
        fputcsv($out,$cells,';');
    }
    fclose($out);
    exit;
});
