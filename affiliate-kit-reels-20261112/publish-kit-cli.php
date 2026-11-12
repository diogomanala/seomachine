<?php
/**
 * CLI-only guarded WordPress publisher for the new Reels kit.
 * Do not modify existing articles or overwrite established content.
 */
if(PHP_SAPI!=='cli'){http_response_code(403);exit(1);}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
global $wpdb;
$lock='bmd_reels_kit_publish_20261112';
if((int)$wpdb->get_var("SELECT GET_LOCK('".$lock."',5)")!==1){echo "ERROR:lock\n";exit(2);}
try{
 $slug='kit-para-gravar-reels-em-casa';
 $plugin=ABSPATH.'wp-content/mu-plugins/bmd-kit-reels.php';
 $css=ABSPATH.'wp-content/mu-plugins/bmd-kit-reels.css';
 $js=ABSPATH.'wp-content/mu-plugins/bmd-kit-reels.js';
 $img='/home/u783725167/bmd-reels-kit-cover.webp';
 if(!is_file($plugin)||!is_file($css)||!is_file($js)||!shortcode_exists('bmd_kit_reels')){
  echo "ERROR:assets_or_shortcode_missing\n";exit(3);
 }
 if(filesize($plugin)<16000||filesize($css)<14000||filesize($js)<7200){
  echo "ERROR:assets_short\n";exit(4);
 }
 $existing=get_page_by_path($slug,OBJECT,'post');
 if($existing){echo "EXISTS:".$existing->ID.":".get_permalink($existing)."\n";exit(0);}
 if(!is_file($img)||filesize($img)<30000||filesize($img)>1000000){
  echo "ERROR:image_missing_or_invalid\n";exit(5);
 }
 $prefix=file_get_contents($img,false,null,0,12);
 if(substr($prefix,0,4)!=='RIFF'||substr($prefix,8,4)!=='WEBP'){
  echo "ERROR:image_format\n";exit(6);
 }
 require_once ABSPATH.'wp-admin/includes/image.php';
 require_once ABSPATH.'wp-admin/includes/media.php';
 require_once ABSPATH.'wp-admin/includes/file.php';
 $upload=array('name'=>'kit-reels-estudio-caseiro-2026.webp','tmp_name'=>$img,
  'type'=>'image/webp','error'=>0,'size'=>filesize($img));
 $aid=media_handle_sideload($upload,0,'Foto editorial de estúdio de gravação caseiro para vídeos Reels');
 if(is_wp_error($aid)){echo "ERROR:media:".$aid->get_error_message()."\n";exit(7);}
 update_post_meta($aid,'_wp_attachment_image_alt','Cenário editorial ilustrativo com celular em tripé, microfone de lapela, luz e mesa de gravação para Reels');
 $cat=get_category_by_slug('marketing-digital');
 $post=array(
  'post_title'=>'Kit para gravar Reels em casa: monte seu estúdio por orçamento',
  'post_name'=>$slug,
  'post_type'=>'post',
  'post_status'=>'publish',
  'post_author'=>1,
  'post_content'=>'[bmd_kit_reels]',
  'post_excerpt'=>'Monte seu kit para gravar Reels em casa: escolha orçamento, celular USB-C ou Lightning e tipo de vídeo. Compare microfone, tripé e iluminação com links de compra na Amazon.',
  'comment_status'=>'closed',
  'post_category'=>array($cat?$cat->term_id:(int)get_option('default_category'))
 );
 $id=wp_insert_post($post,true);
 if(is_wp_error($id)||!$id){echo "ERROR:publish:".(is_wp_error($id)?$id->get_error_message():'unknown')."\n";exit(8);}
 set_post_thumbnail($id,$aid);
 wp_update_post(array('ID'=>$aid,'post_parent'=>$id));
 update_post_meta($id,'rank_math_title','Kit para Gravar Reels em Casa: Monte seu Setup (2026)');
 update_post_meta($id,'rank_math_description','Monte um kit para gravar Reels em casa por orçamento. Compare microfones, tripés e iluminação e veja o que comprar para USB-C ou iPhone Lightning.');
 update_post_meta($id,'rank_math_focus_keyword','kit para gravar reels em casa');
 update_post_meta($id,'_bmd_reels_kit_version','1.0.0');
 clean_post_cache($id);
 echo "PUBLISHED:".$id.":".get_permalink($id).":MEDIA=".$aid."\n";
} finally{
 $wpdb->get_var("SELECT RELEASE_LOCK('".$lock."')");
}