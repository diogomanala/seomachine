<?php
/**
 * BMD migration: guarded article repair, approved editorial WebP import,
 * reusable affiliate configuration, disable v1.
 * WordPress CLI only. Idempotent with advisory lock.
 */
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
require_once ABSPATH.'wp-admin/includes/file.php';
require_once ABSPATH.'wp-admin/includes/media.php';
require_once ABSPATH.'wp-admin/includes/image.php';
global $wpdb;
if((int)$wpdb->get_var("SELECT GET_LOCK('bmd_aff_premium_migration_20261111',4)")!==1){echo "ERROR:LOCK\n";exit(2);}
try{
 $id=9166;
 $p=get_post($id);
 if(!$p||$p->post_type!=='post'||$p->post_status!=='publish'||$p->post_name!=='melhores-microfones-lapela-sem-fio-para-gravar-reels'){
  echo "ERROR:POST_GUARD\n";exit(3);
 }
 $home='/home/u783725167';
 $coverfile=$home.'/bmd_microfones_capa_editorial_2026.webp';
 $figurefile=$home.'/bmd_microfone_gravacao_reels_2026.webp';
 $bodyfile=$home.'/bmd-aff-repaired-content.html';
 $checks=array(
  array('key'=>'_bmd_aff_cover_id','file'=>$coverfile,'name'=>'microfones-sem-fio-reels-comparativo-editorial.webp','size'=>61282,'w'=>1200,'h'=>675,'title'=>'Comparativo editorial de microfones sem fio para gravar Reels','alt'=>'Imagem editorial ilustrativa com três kits de microfone sem fio, celular e câmera em estúdio'),
  array('key'=>'_bmd_aff_secondary_id','file'=>$figurefile,'name'=>'como-gravar-reels-microfone-lapela-estudio.webp','size'=>55226,'w'=>1080,'h'=>608,'title'=>'Gravação de Reels com microfone sem fio em estúdio','alt'=>'Criador de conteúdo ilustrativo gravando vídeo com celular e microfone sem fio preso à roupa')
 );
 foreach($checks as $a){
  $aid=(int)get_post_meta($id,$a['key'],true);
  if($aid&&get_post($aid)){echo "IMAGE_EXISTS:".$a['key'].":".$aid."\n";continue;}
  $bytes=@filesize($a['file']);$geo=@getimagesize($a['file']);
  if($bytes!==$a['size']||!$geo||$geo['mime']!=='image/webp'||$geo[0]!==$a['w']||$geo[1]!==$a['h']){
   echo "ERROR:IMAGE_GUARD:".$a['key']."\n";exit(4);
  }
  $temp=wp_tempnam($a['name']);
  if(!$temp||!copy($a['file'],$temp)){echo "ERROR:IMAGE_COPY\n";exit(5);}
  $file=array('name'=>$a['name'],'type'=>'image/webp','tmp_name'=>$temp,'error'=>0,'size'=>$bytes);
  $media=media_handle_sideload($file,$id,$a['title']);
  if(is_wp_error($media)){@unlink($temp);echo "ERROR:MEDIA:".$media->get_error_code()."\n";exit(6);}
  update_post_meta((int)$media,'_wp_attachment_image_alt',$a['alt']);
  update_post_meta($id,$a['key'],(int)$media);
  echo "IMAGE_ADDED:".$a['key'].":".$media."\n";
 }
 $cover=(int)get_post_meta($id,'_bmd_aff_cover_id',true);
 if(!get_post_thumbnail_id($id) && $cover)set_post_thumbnail($id,$cover);
 $body=@file_get_contents($bodyfile);
 if(!is_string($body)||strlen($body)<6000||strpos($body,'[bmd_comparativo]')!==0||strpos($body,'[bmd_aff_imagem]')===false||substr_count($body,'tag=supercompra0d-20')<3){
  echo "ERROR:BODY_GUARD\n";exit(7);
 }
 if(strpos($p->post_content,'[bmd_comparativo]')!==0){
  if(strpos($p->post_content,'.aff-guide{')===false || strpos($p->post_content,'B0CP7QXWPN')===false){
   echo "ERROR:OLD_CONTENT_MISMATCH\n";exit(8);
  }
  if(!metadata_exists('post',$id,'_bmd_aff_pre_v2_content_20261111')){
   if(!add_post_meta($id,'_bmd_aff_pre_v2_content_20261111',$p->post_content,true)){echo "ERROR:BACKUP\n";exit(9);}
  }
  $saved=wp_update_post(array('ID'=>$id,'post_content'=>$body),true);
  if(is_wp_error($saved)||$saved!==$id){echo "ERROR:POST_UPDATE\n";exit(10);}
  echo "CONTENT_REPAIRED\n";
 }else echo "CONTENT_ALREADY_REPAIRED\n";
 $cfg=array(
  'headline'=>'Três microfones para gravar Reels. Qual é o ideal para você?',
  'answer'=>'Compare versões compatíveis com celular USB-C, iPhone e câmera. Selecione o tipo de gravação para ver a opção mais adequada.',
  'reviewed'=>'2026-11-11','category'=>'audio',
  'products'=>array(
   array('asin'=>'B0H97WGKS2','name'=>'Hollyland Lark M2 USB-C','badge'=>'Para celular USB-C','best'=>'Gravar Reels no smartphone com porta USB-C','caution'=>'Confirme o suporte do aparelho e do aplicativo a áudio externo. Não inclui receptor dedicado de câmera.'),
   array('asin'=>'B0CP7QXWPN','name'=>'Hollyland Lark M2 Combo','badge'=>'Celular e câmera','best'=>'Alternar entre celular e câmera e gravar duas pessoas','caution'=>'Confira a composição exata do kit e se os receptores atendem seus dispositivos.'),
   array('asin'=>'B0BHWF94KR','name'=>'Hollyland Lark C1','badge'=>'Alternativa para celular','best'=>'Comparar outra linha de microfones de lapela sem fio','caution'=>'Verifique a variante USB-C, disponibilidade, acessórios e compatibilidade informados pelo vendedor.')
  )
 );
 update_post_meta($id,'_bmd_aff_cfg',$cfg);
 echo "CONFIG_SAVED:".count($cfg['products'])."\n";
 $old='/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-content/mu-plugins/bmd-affiliate-premium-20261111.php';
 if(is_file($old)&&!is_file($old.'.disabled')){
  if(!rename($old,$old.'.disabled')){echo "ERROR:DISABLE_OLD\n";exit(11);}
  echo "OLD_PLUGIN_DISABLED\n";
 }
 clean_post_cache($id);
 if(has_action('litespeed_purge_post'))do_action('litespeed_purge_post',$id);
 update_option('bmd_aff_comparativos_v2_migrated','2026-11-11',false);
 echo "DONE:".get_permalink($id)."\n";
}finally{$wpdb->get_var("SELECT RELEASE_LOCK('bmd_aff_premium_migration_20261111')");}
