<?php
/**
 * Plugin Name: BMD Comparativos
 * Description: Reusable lightweight affiliate buyer guides, editor, and stale-content review reminders.
 * Version: 1.0.0
 */
if (!defined('ABSPATH')) exit;
const BMD_AFF_TAG = 'supercompra0d-20';
function bmd_aff_get_cfg($post_id) {
  $v=get_post_meta($post_id,'_bmd_aff_cfg',true);
  return is_array($v)?$v:array();
}
function bmd_aff_link($asin) {
  if(!preg_match('/^[A-Z0-9]{10}$/D',(string)$asin))return '';
  return 'https://www.amazon.com.br/dp/'.$asin.'?tag='.BMD_AFF_TAG;
}
function bmd_aff_meta_field($name,$val,$label,$rows=0) {
  $id='bmd_aff_'.sanitize_key($name);
  echo '<label for="'.esc_attr($id).'" style="display:block;font-weight:600;margin:10px 0 4px">'.esc_html($label).'</label>';
  if($rows)echo '<textarea name="bmd_aff['.esc_attr($name).']" id="'.esc_attr($id).'" rows="'.(int)$rows.'" style="width:100%">'.esc_textarea($val).'</textarea>';
  else echo '<input type="text" name="bmd_aff['.esc_attr($name).']" id="'.esc_attr($id).'" value="'.esc_attr($val).'" style="width:100%"/>';
}
add_action('add_meta_boxes_post',function($post){
  add_meta_box('bmd-aff-editor','BMD Comparativo | Produtos Amazon','bmd_aff_editor_box','post','normal','default');
});
function bmd_aff_editor_box($post) {
  $cfg=bmd_aff_get_cfg($post->ID);
  wp_nonce_field('bmd_aff_save','bmd_aff_nonce');
  echo '<p>Use <code>[bmd_comparativo]</code> no artigo. Preços, avaliações e imagens de produtos não são exibidos automaticamente. Links gerados pelo ASIN + tag da conta.</p>';
  bmd_aff_meta_field('headline',$cfg['headline']??'','Título do bloco');
  bmd_aff_meta_field('answer',$cfg['answer']??'','Resposta direta para o leitor',3);
  bmd_aff_meta_field('reviewed',$cfg['reviewed']??'','Revisão editorial (AAAA-MM-DD)');
  echo '<label for="bmd_aff_cat" style="display:block;font-weight:600;margin:10px 0 4px">Categoria do comparativo</label><select name="bmd_aff[category]" id="bmd_aff_cat"><option value="audio" '.selected(($cfg['category']??'audio'),'audio',false).'>Áudio / criadores</option><option value="office" '.selected(($cfg['category']??''),'office',false).'>Home office</option><option value="tools" '.selected(($cfg['category']??''),'tools',false).'>Ferramentas</option></select>';
  echo '<p style="margin-top:12px"><strong>Produtos (até 6).</strong> Não adicione preço ou nota sem fonte autorizada. O botão é composto do ASIN e da tag de afiliado.</p>';
  for($i=0;$i<6;$i++){
    $p=$cfg['products'][$i]??array();
    echo '<fieldset style="border:1px solid #dcdcde;padding:10px;margin-bottom:10px"><legend style="font-weight:700">Produto '.($i+1).'</legend>';
    foreach(array('asin'=>'ASIN Amazon (10 caracteres)','name'=>'Nome do produto','badge'=>'Perfil recomendado (curto)','best'=>'Para quem serve','caution'=>'Atenção / limitação') as $k=>$label){
      $v=$p[$k]??'';$id='bmd_aff_'.$i.'_'.$k;
      echo '<label for="'.esc_attr($id).'" style="display:block;margin:6px 0 3px">'.esc_html($label).'</label><input id="'.esc_attr($id).'" type="text" name="bmd_aff_products['.$i.']['.esc_attr($k).']" value="'.esc_attr($v).'" style="width:100%">';
    }
    echo '</fieldset>';
  }
  $updated=$cfg['reviewed']??'';
  if($updated && strtotime($updated)<strtotime('-45 days'))echo '<p style="color:#a32536;font-weight:bold">Revisão vencida: confira ASINs, variantes, disponibilidade e links antes de ampliar tráfego.</p>';
}
add_action('save_post_post',function($post_id,$post,$update){
 if(!isset($_POST['bmd_aff_nonce'])||!wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['bmd_aff_nonce'])),'bmd_aff_save'))return;
 if(!current_user_can('edit_post',$post_id)||wp_is_post_revision($post_id)||(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE))return;
 $in=isset($_POST['bmd_aff'])&&is_array($_POST['bmd_aff'])?wp_unslash($_POST['bmd_aff']):array();
 $raw=isset($_POST['bmd_aff_products'])&&is_array($_POST['bmd_aff_products'])?wp_unslash($_POST['bmd_aff_products']):array();
 $cfg=array();
 $cfg['headline']=sanitize_text_field($in['headline']??'');
 $cfg['answer']=sanitize_textarea_field($in['answer']??'');
 $cfg['category']=in_array($in['category']??'audio',array('audio','office','tools'),true)?$in['category']:'audio';
 $review=(string)($in['reviewed']??'');
 $cfg['reviewed']=preg_match('/^\d{4}-\d{2}-\d{2}$/D',$review)&&strtotime($review)?$review:'';
 $cfg['products']=array();
 foreach(array_slice($raw,0,6) as $p){
   if(!is_array($p))continue;
   $asin=strtoupper(preg_replace('/[^A-Za-z0-9]/','',(string)($p['asin']??'')));
   if(!preg_match('/^[A-Z0-9]{10}$/D',$asin))continue;
   $cfg['products'][]=array(
    'asin'=>$asin,'name'=>sanitize_text_field($p['name']??''),
    'badge'=>sanitize_text_field($p['badge']??''),
    'best'=>sanitize_text_field($p['best']??''),
    'caution'=>sanitize_text_field($p['caution']??'')
   );
 }
 if(count($cfg['products']))update_post_meta($post_id,'_bmd_aff_cfg',$cfg);
},10,3);
function bmd_aff_is_page(){
 if(!is_singular('post'))return false;
 $post=get_queried_object();
 return $post instanceof WP_Post && !empty(bmd_aff_get_cfg($post->ID)['products']);
}
function bmd_aff_criteria($category) {
 $sets=array(
 'audio'=>array('Conexão certa para celular ou câmera','Número de transmissores necessários','Proteção contra vento e ambiente da gravação','Acessórios, estojo e garantia'),
 'office'=>array('Dimensões e compatibilidade','Capacidade de peso e fixação','Ergonomia real no espaço disponível','Garantia e reposição de peças'),
 'tools'=>array('Compatibilidade com equipamentos','Material, precisão e variedade de pontas','Segurança, armazenamento e reposição','Garantia e peças incluídas'));
 return $sets[$category]??$sets['audio'];
}
add_shortcode('bmd_comparativo',function($atts=array()){
 if(!is_singular('post'))return '';
 $cfg=bmd_aff_get_cfg(get_the_ID());
 $items=array_values(array_filter($cfg['products']??array(),function($p){return !empty($p['name']) && bmd_aff_link($p['asin']??'');}));
 if(!$items)return '';
 $heading=$cfg['headline']??'Compare os modelos';
 $answer=$cfg['answer']??'Confira a compatibilidade, o uso e as limitações antes de comprar.';
 $reviewed=$cfg['reviewed']??'';
 ob_start(); ?>
 <div id="bmd-aff-v2" class="bmd-aff">
 <div class="bmd-aff-disclosure"><strong>Transparência:</strong> este artigo contém links de afiliado. Como participante do Programa de Associados da Amazon, podemos receber uma comissão por compras qualificadas, sem custo adicional para você. Não afirmamos ter testado os produtos pessoalmente.</div>
 <section class="bmd-aff-hero" aria-labelledby="bmd-aff-title">
 <div class="bmd-aff-hero-copy">
 <span class="bmd-aff-kicker">GUIA DE COMPRA • COMPARAÇÃO EDITORIAL</span>
 <h2 id="bmd-aff-title"><?php echo esc_html($heading); ?></h2>
 <p><?php echo esc_html($answer); ?></p>
 <div class="bmd-aff-hero-meta"><span><span aria-hidden="true">✓</span> <?php echo count($items); ?> modelos comparados</span><span>Sem preços desatualizados</span></div>
 </div>
 <?php if(has_post_thumbnail()): ?>
 <figure class="bmd-aff-cover"><?php echo get_the_post_thumbnail(get_the_ID(),'large',array('loading'=>'eager','decoding'=>'async','fetchpriority'=>'high')); ?><figcaption>Imagem editorial ilustrativa. Confira o produto real no anúncio.</figcaption></figure>
 <?php endif;?>
 </section>
 <nav class="bmd-aff-jump" aria-label="Navegação do comparativo"><a href="#bmd-aff-choose">Escolha rápida</a><a href="#bmd-aff-list">Modelos</a><a href="#bmd-aff-table">Tabela</a><a href="#bmd-aff-criteria">Critérios</a><a href="#bmd-aff-more">Guia de compra</a></nav>
 <section id="bmd-aff-choose" class="bmd-aff-choose" aria-labelledby="bmd-aff-choose-title">
 <div class="bmd-aff-sectionhead"><span class="bmd-aff-smallcap">ESCOLHA GUIADA</span><h2 id="bmd-aff-choose-title">Qual opção combina com você?</h2><p>Selecione como pretende usar. A indicação considera os critérios explicados no artigo.</p></div>
 <fieldset><legend>Meu tipo de uso</legend>
 <div class="bmd-aff-options">
 <?php foreach($items as $i=>$p): ?>
 <label><input type="radio" name="bmd-aff-use" value="<?php echo (int)$i; ?>" <?php checked($i,0); ?>><span class="bmd-aff-radio-title"><?php echo esc_html($p['badge']?:('Opção '.($i+1))); ?></span><small><?php echo esc_html($p['best']); ?></small></label>
 <?php endforeach; ?>
 </div>
 </fieldset>
 <div class="bmd-aff-result" aria-live="polite">
 <div><small>INDICAÇÃO PARA ESSE USO</small><h3 id="bmd-aff-choice-name"><?php echo esc_html($items[0]['name']); ?></h3><p id="bmd-aff-choice-text"><?php echo esc_html($items[0]['caution']); ?></p></div>
 <a id="bmd-aff-choice-link" href="<?php echo esc_url(bmd_aff_link($items[0]['asin'])); ?>" rel="nofollow sponsored noopener" target="_blank">Ver disponibilidade <span aria-hidden="true">↗</span></a>
 </div>
 </section>
 <section class="bmd-aff-section" id="bmd-aff-list" aria-labelledby="bmd-aff-list-title">
 <div class="bmd-aff-sectionhead"><span class="bmd-aff-smallcap">MODELOS SELECIONADOS</span><h2 id="bmd-aff-list-title">Comparação por necessidade</h2><p>Confira a configuração exata do kit antes de finalizar uma compra.</p></div>
 <div class="bmd-aff-cards">
 <?php foreach($items as $i=>$p): ?>
 <article class="bmd-aff-card" id="bmd-aff-item-<?php echo (int)($i+1); ?>">
 <div class="bmd-aff-card-top"><span>0<?php echo (int)($i+1); ?></span><small><?php echo esc_html($p['badge']); ?></small></div>
 <h3><?php echo esc_html($p['name']); ?></h3>
 <div class="bmd-aff-card-body"><div><span class="bmd-aff-card-label">IDEAL PARA</span><p><?php echo esc_html($p['best']); ?></p></div><div><span class="bmd-aff-card-label">ANTES DE COMPRAR</span><p><?php echo esc_html($p['caution']); ?></p></div></div>
 <a class="bmd-aff-card-cta" href="<?php echo esc_url(bmd_aff_link($p['asin'])); ?>" rel="nofollow sponsored noopener" target="_blank" data-asin="<?php echo esc_attr($p['asin']); ?>">Consultar na Amazon <span aria-hidden="true">↗</span></a>
 <p class="bmd-aff-card-foot">ASIN: <?php echo esc_html($p['asin']); ?> · Confira vendedor e estoque</p>
 </article>
 <?php endforeach; ?>
 </div>
 </section>
 <section class="bmd-aff-section" id="bmd-aff-table" aria-labelledby="bmd-aff-table-title"><div class="bmd-aff-sectionhead"><span class="bmd-aff-smallcap">COMPARAÇÃO LADO A LADO</span><h2 id="bmd-aff-table-title">O que muda de um para outro?</h2></div>
 <div class="bmd-aff-tablewrap" role="region" aria-label="Tabela comparativa com rolagem horizontal" tabindex="0">
 <table><thead><tr><th scope="col">Modelo</th><th scope="col">Tipo de uso</th><th scope="col">Atenção</th><th scope="col">Link</th></tr></thead><tbody>
 <?php foreach($items as $p): ?><tr><th scope="row"><?php echo esc_html($p['name']); ?></th><td><?php echo esc_html($p['best']); ?></td><td><?php echo esc_html($p['caution']); ?></td><td><a href="<?php echo esc_url(bmd_aff_link($p['asin'])); ?>" target="_blank" rel="nofollow sponsored noopener">Ver oferta ↗</a></td></tr><?php endforeach; ?>
 </tbody></table></div></section>
 <section class="bmd-aff-section" id="bmd-aff-criteria"><div class="bmd-aff-sectionhead"><span class="bmd-aff-smallcap">CHECKLIST DE COMPRA</span><h2>O que avaliar antes de escolher</h2></div>
 <div class="bmd-aff-criteria"><?php foreach(bmd_aff_criteria($cfg['category']??'audio') as $i=>$crit): ?><div><b><?php echo '0'.(int)($i+1); ?></b><span><?php echo esc_html($crit); ?></span></div><?php endforeach; ?></div></section>
 <div class="bmd-aff-editorial-note">Revisão editorial: <strong><?php echo $reviewed?esc_html(date_i18n('d/m/Y',strtotime($reviewed))):'data não informada'; ?></strong>. Ofertas e variantes podem mudar. Este comparativo não utiliza preços ou avaliações capturados automaticamente.</div>
 </div>
 <script id="bmd-aff-chooser-js">(function(){const el=document.getElementById('bmd-aff-v2');if(!el)return;const data=<?php echo wp_json_encode(array_map(function($p){return array('name'=>$p['name'],'text'=>$p['caution'],'url'=>bmd_aff_link($p['asin']));},$items),JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_TAG|JSON_HEX_AMP); ?>;el.querySelectorAll('input[name="bmd-aff-use"]').forEach(input=>input.addEventListener('change',()=>{const p=data[Number(input.value)];if(!p||!input.checked)return;el.querySelector('#bmd-aff-choice-name').textContent=p.name;el.querySelector('#bmd-aff-choice-text').textContent=p.text;el.querySelector('#bmd-aff-choice-link').href=p.url;}));})();</script>
 <?php return ob_get_clean();
});

add_action('wp_enqueue_scripts',function(){
 if(!bmd_aff_is_page())return;
 wp_enqueue_style('bmd-affiliates-v2',content_url('mu-plugins/bmd-affiliates-style.css'),array(),'20261111c');
},95);
add_shortcode('bmd_aff_imagem',function(){
 $post_id=get_the_ID();
 $id=(int)get_post_meta($post_id,'_bmd_aff_secondary_id',true);
 if(!$id || !get_post($id))return '';
 return '<figure class="bmd-aff-figure">'.wp_get_attachment_image($id,'large',false,array('loading'=>'lazy','decoding'=>'async','sizes'=>'(max-width: 800px) 100vw, 800px')).'<figcaption>Ilustração editorial de uma gravação com microfone de lapela. Não representa um teste do modelo citado.</figcaption></figure>';
});

add_action('wp_head',function(){
 if(!bmd_aff_is_page())return;
 $cfg=bmd_aff_get_cfg(get_queried_object_id());
 $items=array_values(array_filter($cfg['products']??array(),function($p){return !empty($p['name'])&&bmd_aff_link($p['asin']??'');}));
 if(!$items)return;
 $graph=array('@context'=>'https://schema.org','@type'=>'ItemList','name'=>'Comparativo de equipamentos','itemListOrder'=>'https://schema.org/ItemListOrderUnordered','numberOfItems'=>count($items),'itemListElement'=>array());
 foreach($items as $i=>$p)$graph['itemListElement'][]=array('@type'=>'ListItem','position'=>$i+1,'name'=>$p['name'],'url'=>get_permalink().'#bmd-aff-item-'.($i+1));
 echo '<script type="application/ld+json" id="bmd-aff-itemlist">'.wp_json_encode($graph,JSON_HEX_TAG|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES).'</script>';
},124);
add_action('wp_dashboard_setup',function(){
 wp_add_dashboard_widget('bmd-aff-review','BMD Afiliados | Revisões pendentes',function(){
  $q=new WP_Query(array('post_type'=>'post','post_status'=>'publish','posts_per_page'=>100,'meta_key'=>'_bmd_aff_cfg','fields'=>'ids','no_found_rows'=>true));
  $num=0;$now=time();
  foreach($q->posts as $id){
   $cfg=bmd_aff_get_cfg($id);$at=$cfg['reviewed']??'';
   if($at&&strtotime($at)>strtotime('-45 days'))continue;
   $num++;
   echo '<p><a href="'.esc_url(get_edit_post_link($id)).'">'.esc_html(get_the_title($id)).'</a> <span style="color:#9b233c">(revisar)</span></p>';
  }
  if(!$num)echo '<p>Nenhum comparativo com revisão vencida.</p>';
  echo '<p style="font-size:12px;color:#646970">Revise ASINs, variantes, disponibilidade e links a cada 45 dias. Nenhuma compra de serviço ou consulta automática é feita.</p>';
 });
});
