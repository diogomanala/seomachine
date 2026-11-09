<?php
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
if(get_option('bmd_rdc_media_cleanup_final_20261109',false)){echo "DONE:already\n";exit;}
$p=get_post(9137);
if(!$p||$p->post_name!=='rdc-1002-2025-documentos-clinica-odontologica'||
 (int)get_post_thumbnail_id(9137)!==9147){
 echo "STOP:wrong-post-or-featured\n";exit(1);
}
$removed=array();$skipped=array();
foreach(array(9140,9141,9142,9143,9144,9145,9146,9149,9150,9151) as $id){
 $a=get_post($id);
 if(!$a){$skipped[]=$id;continue;}
 $url=(string)wp_get_attachment_url($id);
 if($a->post_type!=='attachment'||(int)$a->post_parent!==9137||
 $a->post_mime_type!=='image/png'||
 strpos($url,'/2026/11/rdc-1002-2025-')===false||
 strpos($p->post_content,$url)!==false){
 echo "SKIP:".$id."\n";$skipped[]=$id;continue;
 }
 if(wp_delete_attachment($id,true))$removed[]=$id;else$skipped[]=$id;
}
update_option('bmd_rdc_media_cleanup_final_20261109',json_encode(array('removed'=>$removed,'skipped'=>$skipped)),false);
echo "DONE:removed=".implode(',',$removed).";skipped=".implode(',',$skipped)."\n";
