<?php
/* BMD 2026-11-10 image import: CLI only, no external public endpoint. */
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
require_once ABSPATH.'wp-admin/includes/file.php';
require_once ABSPATH.'wp-admin/includes/media.php';
require_once ABSPATH.'wp-admin/includes/image.php';
global $wpdb;
if((int)$wpdb->get_var("SELECT GET_LOCK('bmd_20261110_media_growth',5)")!==1){fwrite(STDERR,"LOCK\n");exit(1);}
$items=array(
array('id'=>9162,'slug'=>"instagram-comentou-nao-recebeu-direct-automatico",'filename'=>"bmd-instagram-direct-falha-8-testes-2026.webp",'alt'=>"Diagrama minimalista sobre falha no Direct automático do Instagram e oito verificações",'title'=>"Instagram não envia Direct automático: oito testes",'url'=>"https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-e744-820e-babb-4e5546d67d70/raw?se=2026-11-10T16%3A19%3A50Z&sp=r&sv=2026-02-06&sr=b&scid=2bbf13af-9b63-55e7-afda-3be233d67026&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-10T10%3A40%3A52Z&ske=2026-11-11T10%3A40%3A52Z&sks=b&skv=2026-02-06&sig=Jgp0%2BjmA8ByBkxKXC94td5cxAtu5QrTRA8SAnRYMU4g%3D",'size'=>22504),
array('id'=>9163,'slug'=>"meta-ai-conectar-instagram-meta-ads-google-workspace",'filename'=>"bmd-meta-ai-instagram-ads-workspace-2026.webp",'alt'=>"Ilustração minimalista sobre integração Meta AI, Instagram, Meta Ads e Google Workspace",'title'=>"Meta AI com Instagram, anúncios e Google Workspace",'url'=>"https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-409c-820e-9d6a-fcb0d4e10f73/raw?se=2026-11-10T16%3A19%3A50Z&sp=r&sv=2026-02-06&sr=b&scid=5e3f2baf-618c-5923-a4c2-70d2966a8eda&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-09T23%3A53%3A07Z&ske=2026-11-10T23%3A53%3A07Z&sks=b&skv=2026-02-06&sig=9tJtELigjskB0vuo/FKslMeukQSRxTBfW7vVKiOn0h0%3D",'size'=>21882)
);
try{
foreach($items as $it){
$p=get_post($it['id']);
if(!$p||$p->post_type!=='post'||$p->post_status!=='publish'||$p->post_name!==$it['slug']){echo "GUARD:post:".$it['id']."\n";exit(2);}
$old=(int)get_post_thumbnail_id($p->ID);
if($old){echo "EXISTS:".$p->ID.":".$old."\n";continue;}
$media=get_posts(array('post_type'=>'attachment','post_status'=>'inherit','posts_per_page'=>1,'post_parent'=>$p->ID,'meta_key'=>'_bmd_approved_20261110','meta_value'=>$it['filename']));
if($media){$aid=(int)$media[0]->ID;}else{
$tmp=download_url($it['url'],30);
if(is_wp_error($tmp)){echo "DOWNLOAD_ERROR:".$p->ID.":".$tmp->get_error_code()."\n";exit(3);}
$size=filesize($tmp);$info=@getimagesize($tmp);
if($size!=$it['size']||$size<10000||$size>100000||!$info||$info[0]!=1200||$info[1]!=630||$info['mime']!=='image/webp'){
@unlink($tmp);echo "IMAGE_GUARD:".$p->ID.":".$size."\n";exit(4);}
$f=array('name'=>$it['filename'],'type'=>'image/webp','tmp_name'=>$tmp,'error'=>0,'size'=>$size);
$aid=media_handle_sideload($f,$p->ID,$it['title']);
if(is_wp_error($aid)){@unlink($tmp);echo "MEDIA_ERROR:".$p->ID.":".$aid->get_error_code()."\n";exit(5);}
update_post_meta((int)$aid,'_wp_attachment_image_alt',$it['alt']);
update_post_meta((int)$aid,'_bmd_approved_20261110',$it['filename']);
}
if(!set_post_thumbnail($p->ID,(int)$aid)){echo "THUMBNAIL_ERROR:".$p->ID."\n";exit(6);}
clean_post_cache($p->ID);
echo "PUBLISHED:".$p->ID.":".$aid.":".wp_get_attachment_url($aid)."\n";
}
}finally{$wpdb->get_var("SELECT RELEASE_LOCK('bmd_20261110_media_growth')");}
