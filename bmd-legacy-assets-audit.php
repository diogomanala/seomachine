<?php
if(!defined('ABSPATH'))exit;
/** Temporary read-only audit; returned fields refer only to published post slugs and aggregate counts. */
add_action('rest_api_init',function(){
 register_rest_route('bmd-performance/v1','/legacy-assets-audit',array(
 'methods'=>'GET','permission_callback'=>'__return_true',
 'callback'=>function(){
  global $wpdb;
  $host='wordpress-8020-blogmktdigital.cloudclusters.net';
  $pattern='%'.$wpdb->esc_like($host.'/wp-content/uploads/').'%';
  $count=(int)$wpdb->get_var($wpdb->prepare(
    "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type IN ('post','page') AND post_status='publish' AND post_content LIKE %s",$pattern));
  $rows=$wpdb->get_results($wpdb->prepare(
    "SELECT ID,post_name,post_content FROM {$wpdb->posts} WHERE post_type IN ('post','page') AND post_status='publish' AND post_content LIKE %s ORDER BY post_date ASC LIMIT 35",$pattern));
  $examples=array();$exists=0;$missing=0;$nonupload=0;
  foreach($rows as $row){
   $urls=array();preg_match_all('~https?://(?:www\.)?wordpress-8020-blogmktdigital\.cloudclusters\.net/wp-content/uploads/[^"\\x27<>\s]+~i',$row->post_content,$m);
   foreach(array_unique($m[0]??array()) as $raw){
    $path=(string)wp_parse_url(html_entity_decode($raw),PHP_URL_PATH);
    if(strpos($path,'/wp-content/uploads/')!==0){$nonupload++;continue;}
    $local=ABSPATH.ltrim(rawurldecode($path),'/');
    if(is_file($local))$exists++;else $missing++;
    $urls[]=array('old_path'=>$path,'local_media_exists'=>is_file($local));
   }
   $examples[]=array('id'=>(int)$row->ID,'slug'=>$row->post_name,'found'=>count($urls),'broken_files'=>count(array_filter($urls,function($v){return !$v['local_media_exists'];})));
  }
  $placeholders=(int)$wpdb->get_var(
   "SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type='post' AND post_status='publish' AND post_content LIKE '%_wp_link_placeholder%'");
  return rest_ensure_response(array('affected_posts'=>$count,'sample_count'=>count($rows),'sample_existing_media'=>$exists,'sample_missing_media'=>$missing,'legacy_link_placeholder_posts'=>$placeholders,'examples'=>$examples));
 }
 ));
});
