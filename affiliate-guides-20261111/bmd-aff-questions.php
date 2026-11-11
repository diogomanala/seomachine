<?php
/**
 * Plugin Name: BMD Afiliados - Dúvidas de Compra
 * Description: FAQs editoriais por intenção de busca, critérios de revisão e padrão para próximos comparativos.
 * Version: 1.0.0
 */
if (!defined('ABSPATH')) exit;
const BMD_AFF_FAQ_META='_bmd_aff_related_questions_v1';
const BMD_AFF_PLAN_META='_bmd_aff_search_plan_v1';
function bmd_aff_qna_get($post_id) {
  $v=get_post_meta((int)$post_id,BMD_AFF_FAQ_META,true);
  return is_array($v)?$v:array();
}
function bmd_aff_qna_post_enabled($post_id) {
  $post=get_post((int)$post_id);
  if (!$post||$post->post_type!=='post')return false;
  if(strpos($post->post_content,'[bmd_aff_perguntas')!==false)return true;
  if(strpos($post->post_content,'[bmd_comparativo')!==false)return true;
  return metadata_exists('post',(int)$post_id,'_bmd_aff_cfg');
}
add_action('add_meta_boxes_post',function($post){
  if(!bmd_aff_qna_post_enabled($post->ID))return;
  add_meta_box('bmd-aff-faq-standard','BMD SEO | Buscas relacionadas, fontes e perguntas','bmd_aff_qna_metabox','post','normal','default');
});
function bmd_aff_qna_metabox($post) {
  wp_nonce_field('bmd_aff_qna_save','bmd_aff_qna_nonce');
  $items=bmd_aff_qna_get($post->ID);
  $plan=get_post_meta($post->ID,BMD_AFF_PLAN_META,true);
  $plan=is_array($plan)?$plan:array();
  echo '<p>Preencha até oito perguntas reais do contexto da compra. Mostre a resposta no artigo com <code>[bmd_aff_perguntas]</code>. Não copie respostas de concorrentes nem invente números de produto.</p>';
  foreach(array('main'=>'Busca principal','related'=>'Buscas secundárias (uma por linha)','sources'=>'Fontes verificadas (URL por linha)') as $k=>$label){
    echo '<p><label for="bmd-aff-plan-'.esc_attr($k).'"><strong>'.esc_html($label).'</strong></label><br>';
    echo '<textarea style="width:100%" rows="'.($k==='main'?2:3).'" id="bmd-aff-plan-'.esc_attr($k).'" name="bmd_aff_plan['.esc_attr($k).']">'.esc_textarea($plan[$k]??'').'</textarea></p>';
  }
  for($i=0;$i<8;$i++){
    $pair=$items[$i]??array();
    echo '<div style="border:1px solid #dcdcde;padding:11px;margin:10px 0"><strong>Busca / pergunta '.($i+1).'</strong>';
    echo '<label style="display:block;margin:7px 0 3px" for="bmd-aff-question-'.$i.'">Pergunta clara</label>';
    echo '<input style="width:100%" type="text" id="bmd-aff-question-'.$i.'" name="bmd_aff_questions['.$i.'][question]" value="'.esc_attr($pair['question']??'').'">';
    echo '<label style="display:block;margin:7px 0 3px" for="bmd-aff-answer-'.$i.'">Resposta objetiva e verificável</label>';
    echo '<textarea style="width:100%" rows="3" id="bmd-aff-answer-'.$i.'" name="bmd_aff_questions['.$i.'][answer]">'.esc_textarea($pair['answer']??'').'</textarea></div>';
  }
  $img=get_post_thumbnail_id($post->ID)>0;
  $cfg=get_post_meta($post->ID,'_bmd_aff_cfg',true);
  $count=is_array($cfg)?count($cfg['products']??array()):0;
  echo '<div style="padding:10px;background:#f3f5f6;border-radius:6px"><strong>Conferência editorial:</strong><ul>';
  echo '<li>'.($img?'✓':'Pendente:').' imagem destacada e alt conferidos</li>';
  echo '<li>'.($count>=3?'✓':'Pendente:').' ao menos 3 produtos com ASINs verificados</li>';
  echo '<li>'.(count($items)>=5?'✓':'Pendente:').' 5 ou mais respostas reais</li>';
  echo '<li>'.(!empty($plan['sources'])?'✓':'Pendente:').' fontes listadas e conferidas</li>';
  echo '<li>'.(strpos($post->post_content,'[bmd_aff_perguntas]')!==false?'✓':'Pendente:').' shortcode das perguntas inserido no artigo</li>';
  echo '</ul><p>Antes de publicar: conferir em celular, canonical, produtos, link de afiliado, aviso de comissão, ausência de preços desatualizados e revisão em 45 dias.</p></div>';
}
add_action('save_post_post',function($post_id,$post,$update){
  if(wp_is_post_revision($post_id)||(defined('DOING_AUTOSAVE')&&DOING_AUTOSAVE))return;
  if(!current_user_can('edit_post',$post_id)||empty($_POST['bmd_aff_qna_nonce']))return;
  $nonce=sanitize_text_field(wp_unslash($_POST['bmd_aff_qna_nonce']));
  if(!wp_verify_nonce($nonce,'bmd_aff_qna_save'))return;
  $raw=isset($_POST['bmd_aff_questions'])&&is_array($_POST['bmd_aff_questions'])?wp_unslash($_POST['bmd_aff_questions']):array();
  $pairs=array();
  foreach(array_slice($raw,0,8) as $item) {
    if(!is_array($item))continue;
    $q=sanitize_text_field($item['question']??'');
    $a=sanitize_textarea_field($item['answer']??'');
    if($q!==''&&$a!=='')$pairs[]=array('question'=>mb_substr($q,0,220),'answer'=>mb_substr($a,0,1300));
  }
  if($pairs)update_post_meta($post_id,BMD_AFF_FAQ_META,$pairs);
  else delete_post_meta($post_id,BMD_AFF_FAQ_META);
  $p=isset($_POST['bmd_aff_plan'])&&is_array($_POST['bmd_aff_plan'])?wp_unslash($_POST['bmd_aff_plan']):array();
  $plan=array(
    'main'=>sanitize_text_field($p['main']??''),
    'related'=>sanitize_textarea_field($p['related']??''),
    'sources'=>sanitize_textarea_field($p['sources']??'')
  );
  update_post_meta($post_id,BMD_AFF_PLAN_META,$plan);
},15,3);
add_shortcode('bmd_aff_perguntas',function(){
  if(!is_singular('post'))return '';
  $post_id=get_the_ID();
  $items=bmd_aff_qna_get($post_id);
  if(!$items)return '';
  ob_start();
  ?>
  <section class="bmd-aff-qna" id="bmd-aff-related" aria-labelledby="bmd-aff-related-title">
    <div class="bmd-aff-qna-heading">
      <span>DÚVIDAS QUE FAZEM DIFERENÇA NA COMPRA</span>
      <h2 id="bmd-aff-related-title">Perguntas relacionadas à sua escolha</h2>
      <p>Respostas curtas para conferir compatibilidade e evitar comprar o kit errado.</p>
    </div>
    <div class="bmd-aff-qna-list">
      <?php foreach($items as $i=>$pair): ?>
      <details class="bmd-aff-qna-item" <?php if($i===0)echo 'open'; ?>>
        <summary><?php echo esc_html($pair['question']); ?><span aria-hidden="true"></span></summary>
        <div class="bmd-aff-qna-answer"><p><?php echo nl2br(esc_html($pair['answer'])); ?></p></div>
      </details>
      <?php endforeach; ?>
    </div>
  </section>
  <?php
  return ob_get_clean();
});
add_action('wp_enqueue_scripts',function(){
 if(!is_singular('post')||!bmd_aff_qna_post_enabled(get_queried_object_id()))return;
 $style=content_url('mu-plugins/bmd-aff-questions.css');
 wp_enqueue_style('bmd-aff-questions',$style,array(),'20261111a');
},99);
