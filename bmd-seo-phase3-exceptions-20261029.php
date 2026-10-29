<?php
/**
 * BMD SEO em Massa — Fase 3, 29/10/2026.
 * Eight exact, verified historical references only. No guessed redirects,
 * external link edits, slug changes, noindex changes or Indexing API calls.
 * One private per-post backup before updates. Reports remain reviewable.
 */
if (!defined('ABSPATH')) { exit; }

function bmdseo3_expected_post($id) {
    $p=get_post((int)$id);
    return ($p && in_array($p->post_type,array('post','page'),true) && $p->post_status==='publish') ? $p : false;
}
function bmdseo3_public_permalink($id) {
    $p=bmdseo3_expected_post($id);
    return $p ? get_permalink($p->ID) : '';
}
function bmdseo3_change_once($content, $find, $replace, &$changes, $label) {
    $count=substr_count($content,$find);
    if ($count===1 && $find!==$replace) {
        $content=str_replace($find,$replace,$content);
        $changes[]=$label;
    }
    return $content;
}
function bmdseo3_change_pattern($content,$pattern,$replace,$changesLabel,&$changes) {
    $count=0;
    $new=preg_replace_callback($pattern,static function($m)use($replace,&$count){
        $count++;
        return is_callable($replace)?call_user_func($replace,$m):$replace;
    },$content);
    if (is_string($new) && $count===1 && $new!==$content) {
        $changes[]=$changesLabel;
        return $new;
    }
    return $content;
}
function bmdseo3_correct_one($id, &$results) {
    $p=bmdseo3_expected_post($id);
    if (!$p) { $results[$id]=array('status'=>'skipped','reason'=>'Post ou página não publicado'); return; }
    if (get_post_meta($id,'_elementor_edit_mode',true)==='builder') {
        $results[$id]=array('status'=>'skipped','reason'=>'Layout Elementor requer edição do builder');return;
    }
    $before=(string)$p->post_content;
    if (strlen($before)<80 || strlen($before)>300000) {
        $results[$id]=array('status'=>'skipped','reason'=>'Tamanho de conteúdo fora da faixa de segurança');return;
    }
    $after=$before; $changes=array();
    $old='https://wordpress-8020-blogmktdigital.cloudclusters.net';

    if ($id===990) {
        // The attachment ID was verified against both WP metadata and disk.
        $url=wp_get_attachment_url(993);
        $file=get_attached_file(993);
        if ($url && $file && is_file($file)) {
            $after=bmdseo3_change_once($after,$old.'/selo-blogmktdigital.jpg',
                $url,$changes,'Selo histórico aponta novamente ao arquivo original (mídia #993)');
        }
    }
    if ($id===1893) {
        // The old screenshot no longer exists. Don't insert a different image.
        $pattern='~<img\b[^>]*\bsrc=["\x27]'.preg_quote($old.'/insta.jpg','~').'["\x27][^>]*\/?>~i';
        $after=bmdseo3_change_pattern($after,$pattern,
            '<p class="bmd-historic-media-note"><em>A captura de tela histórica não está mais disponível.</em></p>',
            'Imagem antiga inexistente substituída por aviso acessível',$changes);
    }
    if ($id===3438) {
        // An embedded 3150px iframe used an absent HTML archive.
        $link=bmdseo3_public_permalink(4982);
        if ($link) {
            $pattern='~<center>\s*<iframe\b[^>]*\bsrc=["\x27]'.
                preg_quote($old.'/facebook-emoticons.html','~').
                '["\x27][^>]*>\s*</iframe>\s*</center>~is';
            $replacement='<p class="bmd-historic-media-note">O catálogo incorporado original não está mais disponível. ';
            $replacement.='Consulte <a href="'.esc_url($link).'">nossa página de emojis para copiar e usar</a>.</p>';
            $after=bmdseo3_change_pattern($after,$pattern,$replacement,
                'Iframe extinto substituído por orientação para conteúdo publicado',$changes);
        }
    }
    if ($id===8203) {
        // Remove the broken clickable wrapper, preserving its exact inner text.
        $pattern='~<a\b[^>]*\bhref=["\x27]'.
            preg_quote('https://blogdemarketingdigital.com.br/riscos-da-ia-no-dia-a-dia/','~').
            '["\x27][^>]*>(.*?)</a>~is';
        $after=bmdseo3_change_pattern($after,$pattern,
            static function($m){return $m[1];},
            'Referência inexistente removida, mantendo a recomendação de segurança',$changes);
    }
    if ($id===8211) {
        $url=bmdseo3_public_permalink(8319);
        if ($url) {
            $after=bmdseo3_change_once($after,
                'https://blogdemarketingdigital.com.br/como-criar-funil-vendas/',
                $url,$changes,'Link de funil atualizado para o guia publicado (#8319)');
        }
    }
    if ($id===8345) {
        // These are *not* actual archives, so keep labels, not false links.
        $after=bmdseo3_change_once($after,
            '<a href="/empreendedorismo">Empreendedorismo</a>',
            '<span>Empreendedorismo</span>',$changes,
            'Breadcrumb Empreendedorismo sem link inventado');
        $after=bmdseo3_change_once($after,
            '<a href="/negocio-online">Negócio Online</a>',
            '<span>Negócio Online</span>',$changes,
            'Breadcrumb Negócio Online sem link inventado');
    }
    if ($id===8651) {
        $url=bmdseo3_public_permalink(8616);
        if ($url) {
            $after=bmdseo3_change_once($after,
                'https://blogdemarketingdigital.com.br/vpn-para-bet365-em-2026-funciona-qual-escolher-e-o-que-saber-antes-de-usar/',
                $url,$changes,'Link antigo corrigido para a URL real do artigo de VPN (#8616)');
        }
    }
    if ($after===$before) {
        $results[$id]=array('status'=>'unchanged','reason'=>'Referência já corrigida ou substituição não comprovada');
        return;
    }
    $meta='_bmdseo_phase3_backup_20261029';
    if (metadata_exists('post',$id,$meta)) {
        $results[$id]=array('status'=>'skipped','reason'=>'Já existe backup da fase 3; não sobrescrever');return;
    }
    if (!add_post_meta($id,$meta,array('content'=>$before,'title'=>$p->post_title,'date'=>current_time('mysql')),true)) {
        $results[$id]=array('status'=>'error','reason'=>'Falha ao guardar original');return;
    }
    $res=wp_update_post(array('ID'=>$id,'post_content'=>$after),true);
    if (is_wp_error($res)) {
        $results[$id]=array('status'=>'error','reason'=>'WordPress recusou atualização; backup preservado');
        return;
    }
    $results[$id]=array('status'=>'repaired','changes'=>$changes,'count'=>count($changes));
    update_post_meta($id,'_bmdseo_phase3_changes_20261029',$changes);
}
function bmdseo3_run_once() {
    $key='bmdseo_phase3_results_20261029_v1';
    if (get_option($key,false)!==false)return;
    $first=get_option('bmdseo_bulk_state_20261029_v1',false);
    $media=get_option('bmdseo_bulk_media_state_20261029_v1',false);
    if (!is_array($first) || ($first['status']??'')!=='complete' ||
        !is_array($media) || ($media['status']??'')!=='complete')return;
    $lock='bmdseo_phase3_lock_20261029';
    if(get_transient($lock))return;
    set_transient($lock,1,120);
    $results=array();
    foreach(array(990,1893,3438,8203,8211,8345,8651) as $id) {
        bmdseo3_correct_one($id,$results);
    }
    update_option($key,array('finished'=>current_time('mysql'),'posts'=>$results),false);
    delete_transient($lock);
}
add_action('init','bmdseo3_run_once',95);

function bmdseo3_exception_rows() {
    $first=get_option('bmdseo_bulk_state_20261029_v1',array());
    $issues=is_array($first)?($first['issues']??array()):array();
    $allowed=array('arquivo_host_antigo','link_interno_sem_destino_confirmado',
        'conteudo_curto','titulo_revisar','pagina_noindex','canonical_personalizada');
    $rows=array();
    foreach((array)$issues as $item) {
        if (!in_array($item['kind']??'',$allowed,true)) continue;
        $id=(int)($item['post_id']??0);
        $kind=$item['kind'];
        $url=(string)($item['url']??'');
        $p=$id?get_post($id):false;
        $status='Revisar';
        $reason='Verificar o conteúdo e o contexto antes de modificar.';
        if(in_array($kind,array('arquivo_host_antigo','link_interno_sem_destino_confirmado'),true)) {
            $current=$p?(string)$p->post_content:'';
            if ($current!=='' && $url!=='' && strpos($current,$url)===false) {
                $changes=(array)get_post_meta($id,'_bmdseo_phase3_changes_20261029',true);
                if ($changes) {
                    $status='Resolvido';
                    $reason=implode(' | ',$changes);
                }
            }
            if($status!=='Resolvido'){
                $status='Link exige revisão';
                $reason='Referência original ainda presente ou correção não comprovada.';
            }
        } elseif ($kind==='conteudo_curto') {
            $content=$p?(string)$p->post_content:'';
            if($p && (get_post_meta($id,'_elementor_edit_mode',true)==='builder'
              || preg_match('~<(?:iframe|figure|video|audio|canvas)\b|\[bmd_|wp:shortcode|wp:embed|wp:block~i',$content)
              || in_array((string)$p->post_name,array('ferramentas-gratis','remover-metadados-ia'),true))) {
                $status='Possível falso positivo';
                $reason='Página com mídia, ferramenta, shortcode ou builder: avaliar renderização real.';
            } else {
                $status='Revisão editorial';
                $reason='Pouco texto no post_content; checar utilidade antes de reescrever.';
            }
        } elseif ($kind==='titulo_revisar') {
            $status='Revisão editorial';
            $reason='Título longo: não alterar automaticamente slug, H1 ou intenção.';
        } else {
            $status='Revisão técnica';
            $reason='Canonical ou noindex pode ser intencional.';
        }
        $rows[]=array('id'=>$id,'title'=>(string)($item['post']??''),'kind'=>$kind,
            'url'=>$url,'status'=>$status,'reason'=>$reason);
    }
    return $rows;
}
add_action('admin_menu',function(){
    add_management_page('Exceções SEO — BMD','Exceções SEO','manage_options',
        'bmd-seo-exceptions','bmdseo3_admin_page');
});
function bmdseo3_admin_page(){
    if(!current_user_can('manage_options'))wp_die('Sem permissão.');
    $rows=bmdseo3_exception_rows();
    $result=get_option('bmdseo_phase3_results_20261029_v1',array());
    $resolved=count(array_filter($rows,static function($r){return $r['status']==='Resolvido';}));
    ?>
    <div class="wrap" style="max-width:1150px;margin-top:26px">
      <h1>Exceções SEO <small style="font-size:14px;font-weight:normal;color:#71757b">Fase 3 · BMD</small></h1>
      <p>Diagnóstico de links históricos, arquivos antigos e títulos/conteúdos que exigem revisão. Nenhum slug, redirecionamento, canonical, noindex, anúncio ou disavow foi alterado nesta fase.</p>
      <div style="display:flex;flex-wrap:wrap;gap:12px;margin:20px 0">
        <div style="background:#fff;border:1px solid #ddd;padding:19px 24px;border-radius:12px"><strong style="display:block;font-size:30px"><?php echo count($rows); ?></strong><span>Ocorrências analisadas</span></div>
        <div style="background:#fff;border:1px solid #ddd;padding:19px 24px;border-radius:12px"><strong style="display:block;font-size:30px"><?php echo (int)$resolved; ?></strong><span>Links/recursos resolvidos</span></div>
        <div style="background:#fff;border:1px solid #ddd;padding:19px 24px;border-radius:12px"><strong style="display:block;font-size:30px"><?php echo count($rows)-$resolved; ?></strong><span>Revisões ou falsos positivos</span></div>
      </div>
      <p><a class="button button-primary" href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=bmdseo3_csv'),'bmdseo3_csv')); ?>">Exportar relatório completo (CSV)</a></p>
      <table class="widefat striped"><thead><tr><th>ID</th><th>Tipo</th><th>Status</th><th>Detalhe e orientação</th></tr></thead><tbody>
      <?php foreach($rows as $r): ?>
       <tr><td><?php echo (int)$r['id']; ?></td><td><?php echo esc_html(str_replace('_',' ',$r['kind'])); ?></td><td><strong><?php echo esc_html($r['status']); ?></strong></td>
       <td><?php echo esc_html($r['reason']); ?><?php if($r['url']): ?>
         <p style="font-size:11px;word-break:break-all;color:#62666c"><?php echo esc_html($r['url']); ?></p>
         <?php endif; ?></td></tr>
      <?php endforeach; ?>
      </tbody></table>
      <?php if(empty($result)): ?><p>Correções ainda não foram confirmadas. Atualize a página e confira o estado.</p><?php endif; ?>
    </div>
    <?php
}
add_action('admin_post_bmdseo3_csv',function(){
    if(!current_user_can('manage_options'))wp_die('Sem permissão.');
    check_admin_referer('bmdseo3_csv');
    nocache_headers();
    header('Content-Type:text/csv; charset=UTF-8');
    header('Content-Disposition:attachment; filename="bmd-seo-excecoes-fase3-20261029.csv"');
    $f=fopen('php://output','w');if(!$f)wp_die('Falha de exportação.');
    fwrite($f,"\xEF\xBB\xBF");
    fputcsv($f,array('ID','Título','Tipo','URL original','Status','Explicação'),';');
    foreach(bmdseo3_exception_rows() as $r){
        $cells=array($r['id'],$r['title'],$r['kind'],$r['url'],$r['status'],$r['reason']);
        foreach($cells as &$c){
            $c=(string)$c;
            if(preg_match('/^\s*[=+\-@]/u',$c))$c="'".$c;
        } unset($c);
        fputcsv($f,$cells,';');
    }
    fclose($f);
    exit;
});
