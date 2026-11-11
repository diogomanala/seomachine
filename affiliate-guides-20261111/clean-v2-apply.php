<?php
/**
 * One-time guarded replacement for post #9166. CLI only.
 * Retires old duplicate overlay; creates private backup; preserves permalink.
 */
if(PHP_SAPI!=='cli') {http_response_code(403);exit;}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
global $wpdb;
if((int)$wpdb->get_var("SELECT GET_LOCK('bmd_aff_cleanup_20261111',5)")!==1){echo "ERROR:busy\n";exit(2);}
try {
 $post=get_post(9166);
 $slug='melhores-microfones-lapela-sem-fio-para-gravar-reels';
 if(!$post||$post->post_type!=='post'||$post->post_status!=='publish'||$post->post_name!==$slug){echo "ERROR:wrong-target\n";exit(3);}
 $oldPath=ABSPATH.'wp-content/mu-plugins/bmd-affiliate-premium-20261111.php';
 $disabledPath=$oldPath.'.disabled';
 $newPlugin=ABSPATH.'wp-content/mu-plugins/bmd-affiliate-clean-v2-20261111.php';
 $htmlPath='/home/u783725167/bmd-aff-clean-v2-20261111.html';
 $new=@file_get_contents($htmlPath);
 if(!is_string($new)||strlen($new)<12000||strpos($new,'id="bmdshop-v2"')===false||
   strpos($new,'<style')!==false||strpos($new,'<script')!==false||
   strpos($new,'.aff-guide{')!==false||
   substr_count($new,'tag=supercompra0d-20')<6||
   strpos($new,'Não realizamos testes práticos')===false||
   !is_file($newPlugin)){echo "ERROR:content-or-plugin-guard\n";exit(4);}
 $current=(string)$post->post_content;
 if(strpos($current,'id="bmdshop-v2"')!==false){
   echo "EXISTS:already-updated\n";exit(0);
 }
 if(strpos($current,'class="aff-guide"')===false && strpos($current,'.aff-guide{')===false){
   echo "ERROR:old-content-guard\n";exit(5);
 }
 $backupKey='_bmd_aff_original_before_cleanup_20261111';
 if(!metadata_exists('post',9166,$backupKey)){
   $backup=array('post_title'=>$post->post_title,'post_excerpt'=>$post->post_excerpt,
    'post_content'=>$current,'rank_math_title'=>get_post_meta(9166,'rank_math_title',true),
    'rank_math_description'=>get_post_meta(9166,'rank_math_description',true));
   if(!add_post_meta(9166,$backupKey,$backup,true)){echo "ERROR:backup\n";exit(6);}
 }
 // The older plugin prepends a duplicate comparison; it must not render again.
 $retired=false;
 if(is_file($oldPath)&&!is_file($disabledPath)){
   if(!rename($oldPath,$disabledPath)){echo "ERROR:retire-old-overlay\n";exit(7);}
   $retired=true;
 }
 $result=wp_update_post(array('ID'=>9166,'post_content'=>$new),true);
 if(is_wp_error($result)||(int)$result!==9166){
   if($retired)@rename($disabledPath,$oldPath);
   echo "ERROR:wp_update_post\n";exit(8);
 }
 update_post_meta(9166,'rank_math_title','Microfone de Lapela Sem Fio para Reels: Qual Comprar? (2026)');
 update_post_meta(9166,'rank_math_description','Comparativo de microfones para gravar Reels: confira compatibilidade com celular e câmera, três kits, vantagens, limites e links Amazon.');
 update_option('bmd_aff_cleanup_20261111_done',date('c'),false);
 clean_post_cache(9166);
 if(has_action('litespeed_purge_post'))do_action('litespeed_purge_post',9166);
 echo "DONE:post=9166;old_overlay_retired=".($retired?'yes':'already-disabled').";content_bytes=".strlen($new)."\n";
} finally {
 $wpdb->get_var("SELECT RELEASE_LOCK('bmd_aff_cleanup_20261111')");
}
