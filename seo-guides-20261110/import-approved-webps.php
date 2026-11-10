<?php
/**
 * BMD image import (2026-11-10), CLI only, one-time, two approved WebPs.
 * Not a normal plugin and not exposed over HTTP.
 */
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
require_once ABSPATH.'wp-admin/includes/file.php';
require_once ABSPATH.'wp-admin/includes/media.php';
require_once ABSPATH.'wp-admin/includes/image.php';
global $wpdb;
if((int)$wpdb->get_var("SELECT GET_LOCK('bmd_20261110_media_import',3)")!==1) { echo "ERROR:lock\n";exit(1);}
$items = array(
array('id'=>9158,'slug'=>"avaliacoes-google-sumiram-nao-aparecem",'filename'=>"avaliacoes-google-sumiram-guia-2026.webp",'title'=>"Avaliações do Google sumiram: guia de diagnóstico 2026",'alt'=>"Avaliações do Google sumiram? Ilustração de perfil de empresa no Google com avaliações e uma avaliação ausente",'size'=>40960,'uri'=>"https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-a930-820e-9ed2-39e1236cc731/raw?se=2026-11-10T10%3A41%3A09Z&sp=r&sv=2026-02-06&sr=b&scid=819ec752-ff75-52ed-8bdd-0679fc583ba6&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-09T23%3A47%3A18Z&ske=2026-11-10T23%3A47%3A18Z&sks=b&skv=2026-02-06&sig=zzYCxyxaFa%2BM37uV8kBHYBcgIsIq%2BDR7fAt/lGksAuA%3D"),
array('id'=>9159,'slug'=>"nome-de-usuario-whatsapp-nao-aparece-como-reservar",'filename'=>"nome-usuario-whatsapp-reservar-arroba-2026.webp",'title'=>"Nome de usuário do WhatsApp: como reservar o @",'alt'=>"Como reservar nome de usuário do WhatsApp: ilustração de celular com campo de nome de usuário e símbolo @",'size'=>34244,'uri'=>"https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-58e4-820e-a5eb-b64fef29777c/raw?se=2026-11-10T10%3A41%3A09Z&sp=r&sv=2026-02-06&sr=b&scid=87d5aac0-9b2f-5b42-bd05-eb913c9624c3&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-09T22%3A49%3A01Z&ske=2026-11-10T22%3A49%3A01Z&sks=b&skv=2026-02-06&sig=6t1wVnG5OfYOFVjXG05IuUwuJjpFcnOBylcRDjnot5U%3D")
);
try {
 foreach($items as $it){
  $p=get_post($it['id']);
  if(!$p || $p->post_type!=='post' || $p->post_status!=='publish' || $p->post_name!==$it['slug']){
   echo "ERROR:invalid-target:".$it['id']."\n";exit(2);
  }
  $meta='_bmd_featured_approved_webp_20261110';
  $existing=(int)get_post_meta($p->ID,$meta,true);
  if($existing && get_post($existing) && (int)get_post_thumbnail_id($p->ID)===$existing){
   echo "EXISTS:".$p->ID.":".$existing."\n";continue;
  }
  if((int)get_post_thumbnail_id($p->ID)>0 && (int)get_post_thumbnail_id($p->ID)!==$existing){
   echo "SKIP:other-featured:".$p->ID."\n";continue;
  }
  if($existing && get_post($existing)){
   $aid=$existing;
  } else {
   $tmp=download_url($it['uri'],30);
   if(is_wp_error($tmp)){echo "ERROR:download:".$p->ID.":".$tmp->get_error_code()."\n";exit(3);}
   $size=(int)filesize($tmp);
   if($size!==$it['size'] || $size<10000 || $size>300000) {
     @unlink($tmp);echo "ERROR:unexpected-size:".$p->ID.":".$size."\n";exit(4);
   }
   $finfo=@getimagesize($tmp);
   if(!$finfo || $finfo[0]!==1200 || $finfo[1]!==630 || $finfo['mime']!=='image/webp'){
     @unlink($tmp);echo "ERROR:invalid-image:".$p->ID."\n";exit(5);
   }
   $file=array('name'=>$it['filename'],'type'=>'image/webp','tmp_name'=>$tmp,'error'=>0,'size'=>$size);
   $aid=media_handle_sideload($file,$p->ID,$it['title']);
   if(is_wp_error($aid)){@unlink($tmp);echo "ERROR:media:".$p->ID.":".$aid->get_error_code()."\n";exit(6);}
   update_post_meta($aid,'_wp_attachment_image_alt',$it['alt']);
   add_post_meta($p->ID,$meta,(int)$aid,true);
  }
  if(!set_post_thumbnail($p->ID,(int)$aid)){echo "ERROR:thumbnail:".$p->ID.":".$aid."\n";exit(7);}
  clean_post_cache($p->ID);
  if(has_action('litespeed_purge_post'))do_action('litespeed_purge_post',$p->ID);
  echo "DONE:".$p->ID.":".$aid."\n";
 }
} finally { $wpdb->get_var("SELECT RELEASE_LOCK('bmd_20261110_media_import')"); }
