<?php
/**
 * One-time import of two user-approved RDC PNG images into WordPress media,
 * with private content backup. Self-disables when both assets are integrated.
 */
if(!defined('ABSPATH')){exit;}
add_action('init', static function(){
 $post=get_post(9137);
 if(!$post||$post->post_type!=='post'||$post->post_status!=='publish'||
    $post->post_name!=='rdc-1002-2025-documentos-clinica-odontologica'){return;}
 if(get_option('bmd_rdc_approved_assets_20261109')==='complete'){
   if(is_file(__FILE__))@rename(__FILE__,__FILE__.'.disabled');return;
 }
 require_once ABSPATH.'wp-admin/includes/file.php';
 require_once ABSPATH.'wp-admin/includes/image.php';
 require_once ABSPATH.'wp-admin/includes/media.php';
 $items=array(
   array('key'=>'_bmd_rdc_cover_att_20261109','url'=>'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-0cc4-820e-ab60-40b665f7ce61/raw?se=2026-11-09T18%3A42%3A38Z&sp=r&sv=2026-02-06&sr=b&scid=4ab7a1e0-2552-5351-b287-ac62250d911b&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-09T11%3A28%3A52Z&ske=2026-11-10T11%3A28%3A52Z&sks=b&skv=2026-02-06&sig=Ad01Gb97ScDZgBoCAU/o7Sfxc9GYVl8rbfzN1u6YZcM%3D','name'=>'rdc-1002-2025-odontologia-guia-visual.png','title'=>'RDC 1002/2025: documentos, POPs e checklist odontológico','alt'=>'RDC 1.002/2025 para clínicas odontológicas: documentação, POPs e checklist de organização'),
   array('key'=>'_bmd_rdc_check_att_20261109','url'=>'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-1584-820e-9148-8fa1e63dd6e2/raw?se=2026-11-09T18%3A42%3A37Z&sp=r&sv=2026-02-06&sr=b&scid=dcb10310-253b-5a0b-aaad-88df2f990b5c&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-09T12%3A25%3A43Z&ske=2026-11-10T12%3A25%3A43Z&sks=b&skv=2026-02-06&sig=IDFmLsCLM1krniJ40lud%2B%2BzgkDcBqbNDzEQau09X3dY%3D','name'=>'rdc-1002-2025-checklist-inicial.png','title'=>'Checklist inicial da RDC 1002/2025','alt'=>'Checklist inicial da RDC 1.002/2025: documentação, POPs, registros, resíduos, água e revisão pelo responsável técnico'),
 );
 foreach($items as $asset){
  $id=(int)get_post_meta(9137,$asset['key'],true);
  if($id && get_post($id))continue;
  $tmp=download_url($asset['url'],35);
  if(is_wp_error($tmp)){update_option('bmd_rdc_approved_assets_error_20261109', 'download:'.$asset['name'].':'.$tmp->get_error_code(),false);return;}
  $f=array('name'=>$asset['name'],'type'=>'image/png','tmp_name'=>$tmp,'error'=>0,'size'=>(int)filesize($tmp));
  if($f['size']<100000 || $f['size']>6000000){@unlink($tmp);update_option('bmd_rdc_approved_assets_error_20261109','invalid-size:'.$asset['name'],false);return;}
  $attachment=media_handle_sideload($f,9137,$asset['title']);
  if(is_wp_error($attachment)){@unlink($tmp);update_option('bmd_rdc_approved_assets_error_20261109','media:'.$asset['name'].':'.$attachment->get_error_code(),false);return;}
  update_post_meta((int)$attachment,'_wp_attachment_image_alt',$asset['alt']);
  update_post_meta(9137,$asset['key'],(int)$attachment);
 }
 $featured=(int)get_post_meta(9137,'_bmd_rdc_cover_att_20261109',true);
 $check=(int)get_post_meta(9137,'_bmd_rdc_check_att_20261109',true);
 if(!$featured || !$check || !get_post($featured) || !get_post($check))return;
 if((int)get_post_thumbnail_id(9137)!==$featured)set_post_thumbnail(9137,$featured);
 $marker='bmd-rdc-checklist-aprovada-20261109';
 if(strpos($post->post_content,$marker)===false){
  $figure='<figure id="'.$marker.'" class="bmd-rdc-checklist-image" style="max-width:850px;margin:24px auto 28px;text-align:center">'.wp_get_attachment_image($check,'large',false,array('loading'=>'lazy','decoding'=>'async','style'=>'width:100%;height:auto;max-width:760px;border-radius:16px;border:1px solid #e1eeeb;box-shadow:0 6px 24px rgba(24,67,68,.05)')).'<figcaption style="font-size:12px;color:#678083;margin-top:10px">Imagem de referência para iniciar a organização. Os modelos e registros precisam ser adaptados e revisados pelo responsável técnico.</figcaption></figure>';
  $anchor='<h2>Checklist para começar a organizar a pasta documental</h2>';
  if(strpos($post->post_content,$anchor)===false){update_option('bmd_rdc_approved_assets_error_20261109','missing-insertion-anchor',false);return;}
  add_post_meta(9137,'_bmd_rdc_before_images_20261109',$post->post_content,true);
  $body=str_replace($anchor,$anchor.$figure,$post->post_content);
  $result=wp_update_post(array('ID'=>9137,'post_content'=>$body),true);
  if(is_wp_error($result)){update_option('bmd_rdc_approved_assets_error_20261109','save-failed',false);return;}
 }
 update_option('bmd_rdc_approved_assets_20261109','complete',false);
 delete_option('bmd_rdc_approved_assets_error_20261109');
 clean_post_cache(9137);
 if(has_action('litespeed_purge_post'))do_action('litespeed_purge_post',9137);
 if(is_file(__FILE__))@rename(__FILE__,__FILE__.'.disabled');
},50);
