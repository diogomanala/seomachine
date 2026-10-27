<?php
if(!defined('ABSPATH'))exit;
add_action('init',function(){
 $id=1515;
 if(get_option('bmd_x_free_tools_refreshed_20261027',false))return;
 $post=get_post($id);
 if(!$post||$post->post_type!=='post'||$post->post_status!=='publish'||$post->post_name!=='3-ferramentas-gratis-para-twitter')return;
 $a=__DIR__.'/bmd-x-article-a.txt';$b=__DIR__.'/bmd-x-article-b.txt';
 if(!is_file($a)||!is_file($b))return;
 $html=file_get_contents($a).file_get_contents($b);
 if(strlen($html)<2900)return;
 $original=(string)$post->post_content;
 if(!metadata_exists('post',$id,'_bmd_x_tools_old_20261027')&&!add_post_meta($id,'_bmd_x_tools_old_20261027',$original,true))return;
 if(!metadata_exists('post',$id,'_bmd_x_tools_title_old_20261027'))add_post_meta($id,'_bmd_x_tools_title_old_20261027',$post->post_title,true);
 $r=wp_update_post(array('ID'=>$id,'post_title'=>'3 Ferramentas Gratuitas para X (Twitter): Pesquisar e Medir','post_content'=>wp_slash($html)),true);
 if(is_wp_error($r)||(int)$r!==$id)return;
 update_option('bmd_x_free_tools_refreshed_20261027','success',false);
},28);
