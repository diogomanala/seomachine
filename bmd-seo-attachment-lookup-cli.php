<?php
/** CLI-only, read-only lookup for old media replacement candidates. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__,3).'/wp-load.php';
$out=array('badge993'=>array('url'=>wp_get_attachment_url(993),'file'=>get_attached_file(993),'mime'=>get_post_mime_type(993)));
$out['badge993']['exists']=is_file((string)$out['badge993']['file']);
foreach(array('selo','insta','emoji','emoticon') as $term){
 $ids=get_posts(array('post_type'=>'attachment','post_status'=>'inherit','s'=>$term,'posts_per_page'=>10,'fields'=>'ids'));
 $out['media'][$term]=array_map(static function($id){return array('id'=>$id,'title'=>get_the_title($id),'url'=>wp_get_attachment_url($id),'file'=>get_attached_file($id),'exists'=>is_file((string)get_attached_file($id)));},$ids);
}
echo wp_json_encode($out,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
