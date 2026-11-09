<?php
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
if(get_option('bmd_rdc_media_dedupe_20261109_done',false)){echo "DONE:already-cleaned\n";exit;}
$p=get_post(9137);
$cover=(int)get_post_meta(9137,'_bmd_rdc_cover_att_20261109',true);
$check=(int)get_post_meta(9137,'_bmd_rdc_check_att_20261109',true);
if(!$p||$p->post_type!=='post'||$cover!==9147||$check!==9148||(int)get_post_thumbnail_id(9137)!==9147||
strpos($p->post_content,'bmd-rdc-checklist-aprovada-20261109')===false){
 echo "STOP:main-media-guard\n";exit(1);
}
$removed=array();$skipped=array();
foreach(array(9140,9141,9142,9143,9144,9145,9146,9149,9150,9151,9152) as $id){
 $a=get_post($id);
 if(!$a){$skipped[]=$id;continue;}
 $url=wp_get_attachment_url($id);
 if($a->post_type!=='attachment'||$a->post_parent!==9137||$a->post_mime_type!=='image/png'||
 !str_contains($url,'/2026/11/rdc-1002-2025-')||
 strpos($p->post_content,$url)!==false){
  echo "SKIP:guard-".$id."\n";$skipped[]=$id;continue;
 }
 $deleted=wp_delete_attachment($id,true);
 if($deleted){$removed[]=$id;}else{$skipped[]=$id;}
}
update_option('bmd_rdc_media_dedupe_20261109_done',json_encode(array('removed'=>$removed,'skipped'=>$skipped)),false);
echo "DONE:removed=".implode(',',$removed).";skipped=".implode(',',$skipped)."\n";
