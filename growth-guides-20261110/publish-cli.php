<?php
/**
 * BMD 2026-11-10: publish two verified evergreen guides from UTF-8 HTML.
 * CLI-only, idempotent, guarded by exact slug, single-run MySQL advisory lock.
 */
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
global $wpdb;
$lock='bmd_20261110_growth_guides';
if((int)$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,5)',$lock))!==1){
 fwrite(STDERR,"ERROR:busy\n");exit(1);
}
try{
$items=array(
array(
'slug'=>'instagram-comentou-nao-recebeu-direct-automatico',
'file'=>'/home/u783725167/bmd-dm-troubleshoot-20261110.html',
'title'=>'Comentou no Instagram e não recebeu o Direct automático? 8 testes',
'seo'=>'Instagram não envia Direct Automático? 8 Testes (2026)',
'description'=>'Comentaram no Reels, mas a DM automática não chegou? Confira permissões, palavra-chave, prazo, logs e 8 testes para descobrir a falha.',
'focus'=>'instagram não envia direct automatico',
'excerpt'=>'Guia prático para descobrir por que o comentário no Instagram não disparou uma DM: tabela de sintomas, permissões, regras da API e um teste controlado.',
'marker'=>'id="dm-tabela"'
),
array(
'slug'=>'meta-ai-conectar-instagram-meta-ads-google-workspace',
'file'=>'/home/u783725167/bmd-meta-ai-business-20261110.html',
'title'=>'Meta AI com Instagram, Meta Ads e Google Workspace: como usar',
'seo'=>'Meta AI com Instagram e Meta Ads: Como Conectar (2026)',
'description'=>'Veja como usar o Meta AI com Instagram, Meta Ads e Google Workspace. Aprenda a conferir acesso, proteger dados e usar 8 prompts de análise.',
'focus'=>'meta ai instagram meta ads',
'excerpt'=>'Meta AI para empresas: integrações com Instagram, anúncios e Google Workspace, passo a passo de verificação, permissões e oito comandos úteis.',
'marker'=>'id="meta-prompts"'
));
$cat=get_category_by_slug('marketing-digital');
$categories=$cat&&!is_wp_error($cat)?array((int)$cat->term_id):array((int)get_option('default_category'));
foreach($items as $x){
 $html=@file_get_contents($x['file']);
 if(!is_string($html)||strlen($html)<10500||strpos($html,'class="bmd27')===false||
 strpos($html,$x['marker'])===false||strpos($html,'10 de novembro de 2026')===false){
  fwrite(STDERR,"ERROR:invalid-html:".$x['slug']."\n");exit(2);
 }
 $post=$wpdb->get_row($wpdb->prepare("SELECT ID,post_status FROM {$wpdb->posts} WHERE post_type='post' AND post_name=%s AND post_status!='trash' LIMIT 1",$x['slug']));
 if($post){
  if($post->post_status!=='publish'){fwrite(STDERR,"ERROR:existing-not-published:".$x['slug']."\n");exit(3);}
  echo "EXISTS:".$post->ID.":".get_permalink((int)$post->ID)."\n";continue;
 }
 $id=wp_insert_post(array('post_author'=>1,'post_type'=>'post','post_status'=>'publish','post_title'=>$x['title'],
 'post_name'=>$x['slug'],'post_content'=>$html,'post_excerpt'=>$x['excerpt'],'post_category'=>$categories,
 'comment_status'=>'closed','ping_status'=>'closed'),true);
 if(is_wp_error($id)||!$id){fwrite(STDERR,"ERROR:publish:".$x['slug']."\n");exit(4);}
 $verify=get_post((int)$id);
 if(!$verify||$verify->post_status!=='publish'||$verify->post_name!==$x['slug']){
  fwrite(STDERR,"ERROR:post-guard:".$x['slug'].":".$id."\n");exit(5);
 }
 update_post_meta((int)$id,'rank_math_title',$x['seo']);
 update_post_meta((int)$id,'rank_math_description',$x['description']);
 update_post_meta((int)$id,'rank_math_focus_keyword',$x['focus']);
 echo "PUBLISHED:".$id.":".get_permalink((int)$id)."\n";
}
update_option('bmd_20261110_growth_guides_published',date('c'),false);
}finally{
 $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)',$lock));
}
