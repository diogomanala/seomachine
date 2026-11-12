<?php
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
require_once ABSPATH.'wp-admin/includes/file.php';
require_once ABSPATH.'wp-admin/includes/media.php';
require_once ABSPATH.'wp-admin/includes/image.php';
$p=get_post(9179);
if(!$p||$p->post_type!=='post'||$p->post_status!=='publish'||$p->post_name!=='kit-gravar-reels-celular-barato-configurador'){echo "GUARD:target\n";exit(1);}
$old=(int)get_post_thumbnail_id(9179);
if($old){echo "EXISTS:featured:".$old."\n";exit(0);}
$url="https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-e108-820e-a0c6-71c5e5b3e746/raw?se=2026-11-12T10%3A03%3A34Z&sp=r&sv=2026-06-06&sr=b&scid=116a930b-54af-56aa-b455-b312445ef260&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-12T01%3A21%3A22Z&ske=2026-11-13T01%3A21%3A22Z&sks=b&skv=2026-06-06&sig=bWUb2q328j3iAyqZuA4GaBju4huNKkedY%2BVcVtlqxhQ%3D";
$tmp=download_url($url,30);
if(is_wp_error($tmp)){echo "ERROR:download:".$tmp->get_error_code()."\n";exit(2);}
$bytes=(int)filesize($tmp);$img=@getimagesize($tmp);
if($bytes!==31264||!$img||$img[0]!==1200||$img[1]!==630||$img['mime']!=='image/webp'){@unlink($tmp);echo "ERROR:invalid-image:".$bytes."\n";exit(3);}
$file=array('name'=>'kit-reels-celular-blog-marketing-digital-2026.webp','type'=>'image/webp','tmp_name'=>$tmp,'error'=>0,'size'=>$bytes);
$id=media_handle_sideload($file,9179,'Kit para gravar Reels com celular: configurador por orçamento');
if(is_wp_error($id)){@unlink($tmp);echo "ERROR:media:".$id->get_error_code()."\n";exit(4);}
update_post_meta((int)$id,'_wp_attachment_image_alt','Kit para gravar Reels com celular: ilustração de smartphone, microfones de lapela e iluminação para vídeos');
if(!set_post_thumbnail(9179,(int)$id)){echo "ERROR:featured\n";exit(5);}
update_post_meta(9179,'_bmdkit26_cover_20261112',(int)$id);
clean_post_cache(9179);
echo "COVER_PUBLISHED:9179:".$id.":".wp_get_attachment_url($id)."\n";
