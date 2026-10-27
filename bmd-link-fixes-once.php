<?php
if(!defined('ABSPATH'))exit;
add_action('init',function(){
 $id=8429;
 if(get_option('bmd_fixed_automation_links_20261027',false))return;
 $p=get_post($id);
 if(!$p||$p->post_type!=='post'||$p->post_status!=='publish'||$p->post_name!=='melhores-plataformas-de-automacao-de-marketing-em-2026-guia-completo-para-escolher-a-certa')return;
 $old=(string)$p->post_content;
 $replace=array(
 'href="/melhor-ferramenta-email-marketing"'=>'href="/melhor-ferramenta-de-email-marketing-em-2026-guia-definitivo-com-comparativo-completo/"',
 'href="/rdstation-vs-mailchimp"'=>'href="/rd-station-vs-mailchimp-atualizado-2026-qual-a-melhor-ferramenta-de-automacao-para-o-seu-negocio/"'
 );
 $n=0;$new=str_replace(array_keys($replace),array_values($replace),$old,$n);
 if(!$n)return;
 if(!metadata_exists('post',$id,'_bmd_original_automation_links_20261027') && !add_post_meta($id,'_bmd_original_automation_links_20261027',$old,true))return;
 $updated=wp_update_post(array('ID'=>$id,'post_content'=>wp_slash($new)),true);
 if(!is_wp_error($updated)&&(int)$updated===$id)update_option('bmd_fixed_automation_links_20261027',(int)$n,false);
},28);
