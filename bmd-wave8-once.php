<?php
/**
 * One-time editorial migration. Exact post checks; private originals preserved.
 * Original permalinks, categories, publication dates and comment settings retained.
 */
if(!defined('ABSPATH'))exit;
add_action('init',function(){
 $jobs=array(
 1869=>array('slug'=>'como-melhorar-posts-twitter','title'=>'Como Melhorar Posts no X (Twitter): 9 Técnicas e Exemplos','description'=>'Melhore posts no X (Twitter) com 9 técnicas, exemplos, horários testados na sua audiência e métricas. Guia prático e atualizado.','pieces'=>array('bmd-wave8-x-a.txt','bmd-wave8-x-b.txt')),
 553=>array('slug'=>'6-erros-mais-comuns-de-estrategia-nas-midias-sociais','title'=>'6 Erros de Estratégia nas Redes Sociais e Como Corrigir','description'=>'Conheça seis erros de estratégia nas redes sociais e como corrigir canais mal escolhidos, falta de metas, atendimento e métricas de negócios.','pieces'=>array('bmd-wave8-social-a.txt','bmd-wave8-social-b.txt'))
 );
 foreach($jobs as $id=>$job){
  $done='bmd_editorial_wave8_done_20261027_'.$id;
  if(get_option($done,false))continue;
  $post=get_post($id);
  if(!$post||$post->post_type!=='post'||$post->post_status!=='publish'||$post->post_name!==$job['slug'])continue;
  $html='';
  foreach($job['pieces'] as $file){
   $path=__DIR__.'/'.$file;
   if(!is_file($path))continue 2;
   $html.=file_get_contents($path);
  }
  if(strlen($html)<5000)continue;
  $old=(string)$post->post_content;
  if(strlen($old)<150)continue;
  $backup='_bmd_editorial_original_wave8_20261027';
  if(!metadata_exists('post',$id,$backup)&&!add_post_meta($id,$backup,$old,true))continue;
  if(!metadata_exists('post',$id,'_bmd_title_wave8_20261027'))add_post_meta($id,'_bmd_title_wave8_20261027',$post->post_title,true);
  if(!metadata_exists('post',$id,'_bmd_desc_wave8_20261027'))add_post_meta($id,'_bmd_desc_wave8_20261027',(string)get_post_meta($id,'rank_math_description',true),true);
  $r=wp_update_post(array('ID'=>$id,'post_title'=>$job['title'],'post_content'=>wp_slash($html)),true);
  if(is_wp_error($r)||(int)$r!==$id)continue;
  update_post_meta($id,'rank_math_description',$job['description']);
  update_option($done,'success',false);
 }
},28);
