<?php
/**
 * One-time WP-CLI publisher: Monitor arms 27 in, 2026-11-11.
 * Import editorial assets, assign Amazon ASINs, SEO metadata, FAQ, and sources.
 */
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
require_once ABSPATH.'wp-admin/includes/file.php';
require_once ABSPATH.'wp-admin/includes/image.php';
require_once ABSPATH.'wp-admin/includes/media.php';
global $wpdb;
$lock='bmd_mounts_27_publish_20261111';
if((int)$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s,5)',$lock))!==1){fwrite(STDERR,"LOCK\n");exit(2);}
try{
$slug='suporte-articulado-monitor-27-polegadas';
$existing=get_page_by_path($slug,OBJECT,'post');
if($existing){echo "EXISTS:".$existing->ID.":".get_permalink($existing)."\n";exit(0);}
$root='/home/u783725167/bmd_monitor_27/';
$body=@file_get_contents($root.'article.html');
if(!is_string($body) || strlen($body)<10500 ||
 substr_count($body,'[bmd_comparativo]')!==1 ||
 substr_count($body,'[bmd_aff_imagem]')!==1 ||
 substr_count($body,'[bmd_aff_perguntas]')!==1 ||
 substr_count($body,'[bmd_monitor_compatibilidade]')!==1 ||
 strpos($body,'elg.com.br')===false){echo "ERROR:ARTICLE_GUARD\n";exit(3);}
if(!shortcode_exists('bmd_comparativo')||!shortcode_exists('bmd_aff_perguntas')||!shortcode_exists('bmd_monitor_compatibilidade')){
echo "ERROR:SHORTCODE_MISSING\n";exit(4);}
$assets=array(
 array('file'=>'cover.webp','bytes'=>52164,'width'=>1200,'height'=>675,'name'=>'suporte-articulado-monitor-27-polegadas-editorial.webp','title'=>'Suporte articulado para monitor 27 polegadas: mesa e parede','alt'=>'Ilustração editorial de suportes articulados pretos e brancos em mesa de trabalho e parede','meta'=>'_bmd_mounts_cover_20261111'),
 array('file'=>'vesa.webp','bytes'=>26854,'width'=>1200,'height'=>690,'name'=>'vesa-75x75-100x100-como-medir.webp','title'=>'Como medir VESA 75 × 75 e 100 × 100 mm','alt'=>'Diagrama explicativo com quatro furos VESA e medidas 75 por 75 milímetros e 100 por 100 milímetros','meta'=>'_bmd_aff_secondary_id')
);
foreach($assets as $a){
 $file=$root.$a['file'];$size=@filesize($file);$geo=@getimagesize($file);
 if($size!==$a['bytes']||!$geo||$geo['mime']!=='image/webp'||$geo[0]!==$a['width']||$geo[1]!==$a['height']){
   echo "ERROR:ASSET_GUARD:".$a['file'].":".$size."\n";exit(5);
 }
}
$cat=get_category_by_slug('marketing-digital');
$id=wp_insert_post(array(
 'post_type'=>'post','post_status'=>'draft','post_author'=>1,'post_name'=>$slug,
 'post_title'=>'Suporte articulado para monitor de 27 polegadas: 3 opções e como escolher',
 'post_content'=>$body,
 'post_excerpt'=>'ELG F80N, F80W ou F410? Compare suporte para mesa e parede, peso 2 a 9 kg, VESA 75x75 e 100x100, ajuste a gás e verifique compatibilidade.',
 'post_category'=>array($cat?(int)$cat->term_id:(int)get_option('default_category')),
 'comment_status'=>'closed','ping_status'=>'closed'
),true);
if(is_wp_error($id)||!$id){echo "ERROR:POST_CREATE\n";exit(6);}
echo "POST_DRAFT:".$id."\n";
foreach($assets as $a){
 $tmp=wp_tempnam($a['name']);
 if(!$tmp||!copy($root.$a['file'],$tmp)){echo "ERROR:ASSET_COPY\n";exit(7);}
 $f=array('name'=>$a['name'],'type'=>'image/webp','tmp_name'=>$tmp,'error'=>0,'size'=>$a['bytes']);
 $attachment=media_handle_sideload($f,$id,$a['title']);
 if(is_wp_error($attachment)){@unlink($tmp);echo "ERROR:MEDIA:".$attachment->get_error_code()."\n";exit(8);}
 update_post_meta((int)$attachment,'_wp_attachment_image_alt',$a['alt']);
 update_post_meta($id,$a['meta'],(int)$attachment);
 if($a['file']==='cover.webp')set_post_thumbnail($id,(int)$attachment);
 echo "MEDIA_ADDED:".$attachment."\n";
}
$cfg=array(
 'headline'=>'Suporte para monitor de 27 polegadas: qual combina com sua mesa?',
 'answer'=>'Antes de escolher pela cor, confira o padrão VESA, o peso sem a base e o local de fixação. Compare três opções para mesa ou parede, com links de compra verificados.',
 'reviewed'=>'2026-11-11','category'=>'office',
 'products'=>array(
  array('asin'=>'B0765KZ264','name'=>'ELG F80N preto','badge'=>'Mesa, sem furar','best'=>'Monitor de 27 polegadas numa mesa com borda resistente e espaço para morsa','caution'=>'Suporta VESA 75 × 75 ou 100 × 100 e 2 a 9 kg; confira tampo, espessura e fixação.'),
  array('asin'=>'B09WJD17ZS','name'=>'ELG F80W branco','badge'=>'Setup branco','best'=>'Escritório com mesa clara e necessidade de ajustar a altura sem furar o tampo','caution'=>'É a opção branca de mesa; confira borda, resistência, conteúdo do kit e medidas VESA.'),
  array('asin'=>'B08ZGYHBJ7','name'=>'ELG F410 de parede','badge'=>'Sem ocupar mesa','best'=>'Mesa sem borda livre para morsa ou projeto que exige a tela fixada na parede','caution'=>'Exige furação e fixação adequada à parede. Respeite VESA e carga de 2 a 9 kg.')
 ));
update_post_meta($id,'_bmd_aff_cfg',$cfg);
$q=array(
 array('question'=>'Qual suporte articulado comprar para monitor de 27 polegadas?','answer'=>'Entre estes três, o ELG F80N atende um monitor em mesa com borda adequada, o F80W oferece a opção de mesa em branco e o F410 é para fixação na parede. Antes da cor, confira o VESA, o peso sem a base e a estrutura.'),
 array('question'=>'Preciso furar a mesa para instalar o F80N ou o F80W?','answer'=>'Não necessariamente. A ELG informa instalação por morsa na borda ou por furação do tampo. A morsa só deve ser usada se a borda, a espessura e a resistência estrutural forem adequadas.'),
 array('question'=>'Como saber se meu monitor é VESA 75 × 75 ou 100 × 100?','answer'=>'Consulte o manual ou meça a distância entre os centros dos quatro furos traseiros. Furos a 75 mm em cada direção formam VESA 75 × 75; a 100 mm, VESA 100 × 100. Não confunda com o tamanho em polegadas.'),
 array('question'=>'O braço articulado aguenta monitor de 27 polegadas pesando 9 kg?','answer'=>'F80N, F80W e F410 declaram capacidade de 2 a 9 kg para a tela sem a base. Estar no limite exige atenção redobrada ao tampo, à parede e à instalação. Se houver dúvida, escolha outra estrutura ou consulte a fabricante.'),
 array('question'=>'Posso instalar suporte articulado em mesa de MDF ou vidro?','answer'=>'Não é possível garantir apenas pelo material. Avalie espessura, estrutura interna, borda e especificação do fabricante da mesa. Vidro ou tampos ocos e frágeis exigem cuidado; não instale sem confirmação de suporte estrutural adequado.'),
 array('question'=>'Monitor gamer curvo de 27 polegadas serve no F80N?','answer'=>'A ELG informa que F80N e F80W aceitam telas curvas e planas, desde que o peso e o padrão VESA estejam na faixa declarada. Confira recuos na traseira, posição dos conectores e o espaço necessário para movimentar a tela.'),
 array('question'=>'Por que o pistão a gás do suporte não segura o monitor?','answer'=>'Pode haver peso fora da faixa permitida ou tensão desregulada. Confira peso sem base, montagem e o ajuste de tensão descrito no manual do modelo exato. Não abra o cilindro pressurizado e não solte um monitor instável.'),
 array('question'=>'Qual é a diferença entre suporte F80N e F410?','answer'=>'O F80N é um braço de mesa com morsa ou furação. O F410 é um braço para parede e exige fixação adequada nessa superfície. Ambos usam VESA 75 × 75/100 × 100 e faixa de carga declarada de 2 a 9 kg, mas não são intercambiáveis na instalação.')
);
update_post_meta($id,'_bmd_aff_related_questions_v1',$q);
update_post_meta($id,'_bmd_aff_search_plan_v1',array(
 'main'=>'suporte articulado monitor 27 polegadas',
 'related'=>"melhor suporte para monitor 27 polegadas\nsuporte articulado 27 sem furar mesa\nELG F80N ou F80W\nsuporte monitor 27 VESA 75x75 100x100\nbraço articulado aguenta monitor pesado\nsuporte pistão a gás descendo sozinho\nsuporte monitor 27 parede",
 'sources'=>"https://elg.com.br/produto/suporte-multiarticulado-com-pistao-a-gas-para-monitores-de-17-a-35-f80n/\nhttps://elg.com.br/produto/suporte-multiarticulado-com-pistao-a-gas-para-monitores-de-17-a-35/\nhttps://elg.com.br/img/original/produtos/05b82ae8-75b8-4c41-8ca0-9e603b072c08/manuais_br/Manual-F410.pdf"
));
update_post_meta($id,'rank_math_title','Suporte Articulado para Monitor 27: F80N, F80W ou F410?');
update_post_meta($id,'rank_math_description','Suporte articulado para monitor 27 polegadas: compare F80N, F80W e F410, confira VESA, peso e fixação e escolha com verificador interativo.');
update_post_meta($id,'rank_math_focus_keyword','suporte articulado monitor 27 polegadas');
$result=wp_update_post(array('ID'=>$id,'post_status'=>'publish'),true);
if(is_wp_error($result)){echo "ERROR:PUBLISH\n";exit(9);}
clean_post_cache($id);
if(has_action('litespeed_purge_post'))do_action('litespeed_purge_post',$id);
echo "PUBLISHED:".$id.":".get_permalink($id)."\n";
}finally{$wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)',$lock));}
