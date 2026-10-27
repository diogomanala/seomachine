<?php
/**
 * One-shot, bounded factual SEO corrections for four audited posts.
 * Processes no more than two WordPress posts per request, always verifying
 * their IDs/slugs/status and privately backing up old content and SEO fields.
 * Remove after all four are verified in production.
 */
if(!defined('ABSPATH'))exit;
add_action('init',function(){
  $posts=array(
   8276=>array(
    'slug'=>'palavras-chave-de-volume-zero-como-ranquear-em-buscas-que-92-dos-profissionais-ignoram',
    'draft'=>'bmd-statfix-zero-volume.html',
    'title'=>'Palavras-Chave de Volume Zero: Como Encontrar e Priorizar',
    'desc'=>'Aprenda a encontrar e validar palavras-chave de volume zero com Search Console, dúvidas de clientes e pesquisa de intenção, sem métricas inventadas.'
   ),
   8243=>array(
    'slug'=>'como-criar-prompts-que-geram-ideias-virais-guia-do-criador-de-conteudo-em-2026',
    'draft'=>'bmd-statfix-prompts.html',
    'title'=>'5 Prompts para Criar Conteúdo com IA: Reels, Carrosséis e Anúncios',
    'desc'=>'Cinco prompts práticos para criar Reels, carrosséis e anúncios com IA, além de um método de revisão para evitar promessas e estatísticas sem fonte.'
   ),
   8230=>array(
    'slug'=>'como-otimizar-seu-conteudo-para-o-google-sge-guia-completo-para-o-brasil',
    'draft'=>'bmd-statfix-search-ai.html',
    'title'=>'SEO para AI Overviews e AI Mode: O Que Funciona no Google',
    'desc'=>'Entenda o SEO para AI Overviews e AI Mode, o antigo SGE, com checklist técnico, medição e orientações oficiais do Google sem atalhos enganosos.'
   ),
   8336=>array(
    'slug'=>'vibecoding-guia-completo-2026',
    'draft'=>'bmd-statfix-vibecoding.html',
    'title'=>'Vibe Coding em 2026: Guia Prático, Ferramentas e Riscos',
    'desc'=>'Entenda vibe coding, os dados verificáveis de uso de IA na programação e como criar, testar e publicar software com revisão e segurança.'
   )
  );
  $processed=0;
  foreach($posts as $id=>$job){
    if($processed>=2)break;
    $done='bmd_statfix_wave9_20261027_'.$id;
    if(get_option($done,false))continue;
    $p=get_post($id);
    if(!$p||$p->post_type!=='post'||$p->post_status!=='publish'||$p->post_name!==$job['slug'])continue;
    $draft=__DIR__.'/'.$job['draft'];
    if(!is_file($draft))continue;
    $new=file_get_contents($draft);
    if(!is_string($new)||strlen($new)<6000||substr_count($new,'<h2>')<5)continue;
    $old=(string)$p->post_content;
    if(strlen($old)<500)continue;
    $backup='_bmd_original_statfix_wave9_20261027';
    if(!metadata_exists('post',$id,$backup) && !add_post_meta($id,$backup,$old,true))continue;
    $metadata=array(
      '_bmd_original_statfix_title_20261027'=>(string)$p->post_title,
      '_bmd_original_statfix_seotitle_20261027'=>(string)get_post_meta($id,'rank_math_title',true),
      '_bmd_original_statfix_seodesc_20261027'=>(string)get_post_meta($id,'rank_math_description',true)
    );
    foreach($metadata as $key=>$value){
      if(!metadata_exists('post',$id,$key))add_post_meta($id,$key,$value,true);
    }
    $r=wp_update_post(array('ID'=>$id,'post_title'=>$job['title'],'post_content'=>wp_slash($new)),true);
    if(is_wp_error($r)||(int)$r!==$id)continue;
    update_post_meta($id,'rank_math_title',$job['title'].' | Blog de Marketing Digital');
    update_post_meta($id,'rank_math_description',$job['desc']);
    update_option($done,'updated',false);
    $processed++;
  }
},28);
