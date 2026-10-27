<?php
/**
 * One-time backed-up editorial differentiation of overlapping automation posts.
 * No background work. Bounded: four exact IDs; no redirects or canonical changes.
 */
if (!defined('ABSPATH')) exit;
add_action('init', function() {
  $jobs = array(
    7987 => 'automacao-de-marketing-nutricao-de-leads',
    7979 => 'automacao-de-marketing-nutrir-leads',
    8452 => 'melhores-ferramentas-de-automacao-de-marketing-em-2026',
    8429 => 'melhores-plataformas-de-automacao-de-marketing-em-2026-guia-completo-para-escolher-a-certa'
  );
  foreach($jobs as $id=>$slug){
    $done='bmd_automation_intent_audit_20261027_'.$id;
    if(get_option($done,false)) continue;
    $post=get_post($id);
    if(!$post||$post->post_type!=='post'||$post->post_status!=='publish'||$post->post_name!==$slug)continue;
    $old=(string)$post->post_content;
    if(strlen($old)<500)continue;
    $updated=$old;
    $new_title=null;
    $new_desc=null;
    $changes=0;
    if($id===7987){
      $draft=__DIR__.'/bmd-segmentacao-leads-refresh.html';
      if(!is_file($draft))continue;
      $updated=file_get_contents($draft);
      if(!is_string($updated)||strlen($updated)<8000)continue;
      $new_title='Segmentação de Leads: 5 Fluxos de Automação com Exemplos';
      $new_desc='Aprenda segmentação de leads e 5 fluxos de automação com gatilhos, regras de saída, exemplos, métricas e cuidados com dados pessoais.';
      $changes=1;
    } elseif($id===7979) {
      $intro='<aside class="bmd-editorial-focus" style="border-left:3px solid #c85136;padding:13px 16px;margin:0 0 26px;background:#fafbfc;color:#304053;">';
      $intro.='<strong>Este é o guia geral de nutrição de leads.</strong> Aqui você aprende a planejar automações, escolher ferramentas e estruturar uma jornada completa. ';
      $intro.='Para modelos de mensagens, critérios de entrada e saída e cinco sequências aplicáveis, acesse o ';
      $intro.='<a href="'.esc_url(home_url('/automacao-de-marketing-nutricao-de-leads/')).'">manual de segmentação de leads e fluxos práticos</a>.';
      $intro.='</aside>';
      if(strpos($old,'bmd-editorial-focus')===false){$updated=$intro.$old;$changes=1;}
    } elseif($id===8452) {
      $bad='Em 2026, a empresa lançou o “Predictive Send Time” com deep learning, aumentando taxas de abertura em até 35%.';
      $fixed='A ActiveCampaign oferece envio preditivo para ajustar horários de e-mails a partir de padrões anteriores de engajamento de cada contato, sujeito aos requisitos do plano. Consulte a <a href="https://help.activecampaign.com/hc/pt-br/articles/360000030839-Como-usar-o-envio-preditivo-para-automatizar-os-hor%C3%A1rios-de-envio" rel="noopener noreferrer">documentação oficial de envio preditivo</a> antes de contratar.';
      $updated=str_replace($bad,$fixed,$old,$changes);
    } elseif($id===8429) {
      $bad='Testamos e analisamos as 7 principais plataformas de automação de marketing disponíveis no Brasil em 2026:';
      $fixed='Reunimos e comparamos sete plataformas de automação de marketing oferecidas no Brasil em 2026:';
      $updated=str_replace($bad,$fixed,$updated,$count_one);$changes+=$count_one;
      $bad2='Este guia compara automação, CRM, IA, preços reais e adequação ao mercado brasileiro para que você escolha com clareza.';
      $fixed2='Este guia compara recursos, CRM, IA e condições comerciais divulgadas pelos fornecedores. Planos e valores podem mudar; confirme as informações diretamente com cada plataforma antes de decidir.';
      $updated=str_replace($bad2,$fixed2,$updated,$count_two);$changes+=$count_two;
    }
    if(!$changes||$updated===$old)continue;
    $backup='_bmd_original_automation_intent_20261027';
    if(!metadata_exists('post',$id,$backup) && !add_post_meta($id,$backup,$old,true))continue;
    if(!metadata_exists('post',$id,'_bmd_original_automation_title_20261027'))add_post_meta($id,'_bmd_original_automation_title_20261027',(string)$post->post_title,true);
    if(!metadata_exists('post',$id,'_bmd_original_automation_desc_20261027'))add_post_meta($id,'_bmd_original_automation_desc_20261027',(string)get_post_meta($id,'rank_math_description',true),true);
    $fields=array('ID'=>$id,'post_content'=>wp_slash($updated));
    if($new_title)$fields['post_title']=$new_title;
    $result=wp_update_post($fields,true);
    if(is_wp_error($result)||(int)$result!==$id)continue;
    if($new_desc)update_post_meta($id,'rank_math_description',$new_desc);
    update_option($done,(int)$changes,false);
  }
},28);
