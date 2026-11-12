<?php
/**
 * BMD | New standalone affiliate kit generator, 12 Nov 2026
 * CLI-only, idempotent, no changes to existing articles.
 */
if(PHP_SAPI!=='cli'){http_response_code(403);exit(1);}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
global $wpdb;
$slug='kit-gravar-reels-celular-barato-configurador';
if((int)$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,5)','bmdkit26_install'))!==1){echo "FAIL:lock\n";exit(2);}
try {
 $found=get_page_by_path($slug,OBJECT,'post');
 if($found){
  if($found->post_status!=='publish'){echo "FAIL:existing-nonpublished:".$found->ID."\n";exit(3);}
  update_option('bmdkit26_post_id',(int)$found->ID,false);
  echo "EXISTS:".$found->ID.":".get_permalink($found)."\n";exit(0);
 }
 $file='/home/u783725167/bmdkit26-article.html';
 $html=@file_get_contents($file);
 if(!is_string($html)||strlen($html)<10500||strpos($html,'id="bmdkit26"')===false||
 strpos($html,'id="bk-form"')===false||strpos($html,'id="bk-products"')===false||
 strpos($html,'id="bk-result-title"')===false||
 strpos($html,'<style')!==false||strpos($html,'<script')!==false||
 strpos($html,'supercompra0d-20')===false||
 strpos($html,'como associado da Amazon, eu recebo por compras qualificadas')===false
 ){echo "FAIL:content-invalid\n";exit(4);}
 $style=ABSPATH.'wp-content/mu-plugins/bmdkit26-style.php';
 $script=ABSPATH.'wp-content/mu-plugins/bmdkit26-script.php';
 if(!is_file($style)||!is_file($script)){echo "FAIL:missing-scripts\n";exit(5);}
 $cat=get_category_by_slug('marketing-digital');
 $cid=$cat&&!is_wp_error($cat)?(int)$cat->term_id:(int)get_option('default_category');
 $id=wp_insert_post(array(
 'post_type'=>'post','post_status'=>'publish','post_author'=>1,
 'post_name'=>$slug,
 'post_title'=>'Kit para gravar Reels com celular: monte seu estúdio por orçamento',
 'post_content'=>$html,
 'post_excerpt'=>'Configure um kit para gravar Reels no celular por orçamento, tipo de conexão e estilo de vídeo. Compare tripés, iluminação e microfones com links Amazon.',
 'post_category'=>array($cid),
 'comment_status'=>'closed','ping_status'=>'closed'
 ),true);
 if(is_wp_error($id)||!$id){echo "FAIL:publish:".(is_wp_error($id)?$id->get_error_message():'unknown')."\n";exit(6);}
 $post=get_post($id);
 if(!$post||$post->post_name!==$slug||$post->post_status!=='publish'){echo "FAIL:verify:".$id."\n";exit(7);}
 update_post_meta($id,'rank_math_title','Kit para Gravar Reels com Celular: Monte o Seu (2026)');
 update_post_meta($id,'rank_math_description','Monte seu kit de Reels por orçamento, celular USB-C ou Lightning e tipo de vídeo. Recomendações práticas de tripés, iluminação e microfones.');
 update_post_meta($id,'rank_math_focus_keyword','kit para gravar reels com celular');
 update_option('bmdkit26_post_id',(int)$id,false);
 update_option('bmdkit26_published_20261112',gmdate('c'),false);
 clean_post_cache($id);
 if(has_action('litespeed_purge_post'))do_action('litespeed_purge_post',$id);
 echo "PUBLISHED:".$id.":".get_permalink($id)."\n";
}finally{$wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)','bmdkit26_install'));}
