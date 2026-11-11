<?php
/**
 * BMD one-time researched query expansion for post 9166.
 * Safe, reversible, idempotent, CLI only.
 */
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
global $wpdb;
if((int)$wpdb->get_var("SELECT GET_LOCK('bmd_aff_microfones_related_20261111',5)")!==1){echo "ERROR:LOCK\n";exit(2);}
try{
$id=9166;
$p=get_post($id);
if(!$p||$p->post_type!=='post'||$p->post_status!=='publish'||$p->post_name!=='melhores-microfones-lapela-sem-fio-para-gravar-reels'){echo "ERROR:POST_GUARD\n";exit(3);}
$source='/home/u783725167/bmd-microfones-related-queries-20261111.html';
$add=@file_get_contents($source);
if(!is_string($add)||strlen($add)<6000||substr_count($add,'<h2>')<5||strpos($add,'[bmd_aff_perguntas]')===false||strpos($add,'holl yland')!==false){echo "ERROR:CONTENT_GUARD\n";exit(4);}
$anchor='<h2>Perguntas frequentes sobre microfones para Reels</h2>';
if(strpos($p->post_content,'id="bmd-aff-intencoes-compra"')===false){
  if(strpos($p->post_content,$anchor)===false||strpos($p->post_content,'[bmd_comparativo]')!==0){echo "ERROR:ANCHOR_GUARD\n";exit(5);}
  if(!metadata_exists('post',$id,'_bmd_aff_before_related_20261111')){
    if(!add_post_meta($id,'_bmd_aff_before_related_20261111',$p->post_content,true)){echo "ERROR:BACKUP\n";exit(6);}
  }
  $body=str_replace($anchor,trim($add)."\n".$anchor,$p->post_content);
  $res=wp_update_post(array('ID'=>$id,'post_content'=>$body),true);
  if(is_wp_error($res)||(int)$res!==$id){echo "ERROR:WRITE\n";exit(7);}
  echo "ARTICLE_EXPANDED\n";
}else echo "ARTICLE_ALREADY_EXPANDED\n";
$questions=array(
array('question'=>"Microfone de lapela sem fio funciona para gravar Reels direto no Instagram?",'answer'=>"Pode funcionar, mas o aplicativo nem sempre usa a mesma entrada externa da câmera nativa. Faça uma gravação curta com o receptor conectado e ouça o resultado. Se falhar apenas no Instagram, grave na câmera do celular e importe o vídeo para o Reels."),
array('question'=>"Por que meu microfone de lapela USB-C não funciona no Android?",'answer'=>"Confira o encaixe, a carga dos transmissores e se o aplicativo selecionou o microfone externo. Em alguns modelos OPPO, Vivo, OnePlus, Realme e iQOO, a Hollyland orienta ativar OTG ou escolher a entrada externa. Confirme também o modelo na lista de compatibilidade."),
array('question'=>"Qual microfone de lapela funciona no iPhone 14 ou anterior?",'answer'=>"Para iPhone com conector Lightning, procure uma versão com receptor Lightning compatível com o aparelho. No LARK M2 existem kits Lightning e Combo. Um kit vendido apenas com receptor USB-C não deve ser tratado como equivalente."),
array('question'=>"Precisa de Bluetooth, Wi-Fi ou internet para usar o LARK M2?",'answer'=>"Não é preciso internet para gravar. O conjunto usa transmissão sem fio própria entre transmissores e receptor; o receptor precisa estar conectado ao celular ou à câmera. Não se deve presumir que outros modelos sem fio funcionem sem receptor."),
array('question'=>"Como tirar o chiado e reduzir o vento do microfone de lapela?",'answer'=>"Revise a posição do transmissor para evitar atrito com a roupa, use proteção contra vento em ambientes externos e teste a redução de ruído ativada e desativada. Se a voz ficar artificial, ajuste o ganho e compare gravações curtas."),
array('question'=>"O que muda entre o LARK M2 Combo e o LARK M2 USB-C?",'answer'=>"O Combo descrito pela Hollyland reúne receptores para câmera, USB-C e Lightning, enquanto a versão USB-C tem foco em telefones com essa conexão. O nome do produto pode ser semelhante, mas os acessórios da caixa e a compatibilidade são diferentes."),
array('question'=>"O LARK M2 grava em estéreo diretamente no celular?",'answer'=>"A FAQ da Hollyland informa suporte ao modo estéreo na utilização com câmera. A gravação no celular depende do sistema e não deve ser presumida como estéreo. Se isso for requisito de trabalho, confira o dispositivo e o formato de saída."),
array('question'=>"40 horas de bateria significam que o microfone grava 40 horas seguidas?",'answer'=>"Não. A fabricante informa até 10 horas por carga do transmissor e cerca de 40 horas totais ao contar as recargas proporcionadas pelo estojo, sob condições específicas. São medidas diferentes e variam de acordo com o uso.")
);
$meta=array(
 'main'=>'microfone de lapela sem fio para gravar Reels',
 'related'=>"microfone de lapela funciona no Instagram Reels
microfone de lapela USB-C não funciona Android
microfone de lapela para iPhone 14 Lightning
Hollyland LARK M2 Combo vs USB-C
microfone lapela precisa de Bluetooth ou internet
como saber se celular usa microfone externo
microfone sem fio chiado e vento
bateria LARK M2 40 horas",
 'sources'=>"https://www.hollyland.com/pt-br/product/lark-m2
https://br.hollyland.com/pt/products/lark-m2
https://www.hollyland.com/faq/lark-m2-compatibility-list-for-mobile
https://developers.google.com/search/docs/specialty/ecommerce/write-high-quality-reviews"
);
update_post_meta($id,'_bmd_aff_related_questions_v1',$questions);
update_post_meta($id,'_bmd_aff_search_plan_v1',$meta);
if(get_post_meta($id,'rank_math_focus_keyword',true)==='')update_post_meta($id,'rank_math_focus_keyword','microfone de lapela sem fio para Reels');
clean_post_cache($id);
if(has_action('litespeed_purge_post'))do_action('litespeed_purge_post',$id);
echo "FAQ_SAVED:".count($questions)."
";
echo "DONE:".get_permalink($id)."
";
}finally{$wpdb->get_var("SELECT RELEASE_LOCK('bmd_aff_microfones_related_20261111')");}
