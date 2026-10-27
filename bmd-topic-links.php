<?php
if(!defined('ABSPATH'))exit;
add_filter('the_content',function($content){
 if(is_admin()||!is_main_query()||!in_the_loop()||!is_singular('post')||is_preview()||is_feed())return $content;
 $slug=(string)get_post_field('post_name',get_the_ID());
 $map=array(
 '3-ferramentas-gratis-para-twitter'=>array('Ferramentas atuais para X'=>'/top10-ferramentas-para-twitter/'),
 'como-melhorar-posts-twitter'=>array('Ferramentas para X'=>'/top10-ferramentas-para-twitter/'),
 'dicas-para-montar-sua-estrategia-nas-midias-sociais'=>array('Campanhas sociais: 5 passos'=>'/5-dicas-para-uma-campanha-de-sucesso-nas-midias-sociais/'),
 '6-erros-mais-comuns-de-estrategia-nas-midias-sociais'=>array('Planejamento de campanha'=>'/5-dicas-para-uma-campanha-de-sucesso-nas-midias-sociais/'),
 );
 if(!isset($map[$slug]))return $content;
 $links=array();
 foreach($map[$slug] as $label=>$path){if(strpos($content,$path)===false)$links[]='<a href="'.esc_url(home_url($path)).'">'.esc_html($label).'</a>';}
 if(!$links)return $content;
 return $content.'<nav class="bmd-topic-next" aria-label="Leituras complementares" style="border-top:1px solid #e4e8ee;margin:24px 0;padding:16px 0"><strong>Continue aprendendo</strong><p style="margin:8px 0">'.implode(' · ',$links).'</p></nav>';
},46);

add_filter('rank_math/frontend/description',function($description){
 if(!is_admin() && is_singular('post') && (int)get_queried_object_id()===1515){
  return 'Veja 3 ferramentas gratuitas para X (Twitter): busque conversas, pesquise tendências e crie links UTM para medir visitas. Guia atualizado com exemplos.';
 }
 return $description;
},30);
