<?php
/**
 * BMD | one-off publication for two researched guides, 2026-11-10.
 * CLI only; refuses to overwrite existing posts; serialised with database lock.
 */
if (PHP_SAPI !== 'cli') { http_response_code(403); exit; }
$root='/home/u783725167/domains/blogdemarketingdigital.com.br/public_html';
require $root.'/wp-load.php';
global $wpdb;
$mutex='bmd_20261110_guides_publisher';
if ((int)$wpdb->get_var($wpdb->prepare('SELECT GET_LOCK(%s, 5)', $mutex)) !== 1) {
    fwrite(STDERR,"LOCK: busy or unavailable\n");exit(1);
}
try{
 $items=array(
  array(
    'slug'=>'avaliacoes-google-sumiram-nao-aparecem',
    'file'=>'/home/u783725167/bmd-google-reviews-20261110.html',
    'title'=>'Avaliações do Google sumiram ou não aparecem? Veja o que fazer',
    'seo'=>'Avaliações do Google Sumiram? Como Resolver em 2026',
    'description'=>'Avaliações do Google sumiram ou não aparecem? Descubra causas, faça 6 verificações e veja como pedir análise pelo suporte oficial.',
    'keyword'=>'avaliações do google sumiram',
    'excerpt'=>'O que fazer quando avaliações desaparecem do Google Maps: diferenças entre atraso e remoção, seis testes e modelo de solicitação ao suporte.',
    'sentinel'=>'id="google-suporte"'
  ),
  array(
    'slug'=>'nome-de-usuario-whatsapp-nao-aparece-como-reservar',
    'file'=>'/home/u783725167/bmd-whatsapp-username-20261110.html',
    'title'=>'Nome de usuário do WhatsApp não aparece? Como reservar o @',
    'seo'=>'Nome de Usuário do WhatsApp Não Aparece? Como Reservar',
    'description'=>'Saiba como reservar o @ no WhatsApp em 2026, por que a opção não aparece e como reivindicar seu nome do Instagram ou Facebook.',
    'keyword'=>'nome de usuário whatsapp não aparece',
    'excerpt'=>'Tutorial atualizado do @ no WhatsApp: como reservar, o que fazer se a função não aparece e como usar seu nome do Instagram.',
    'sentinel'=>'id="wa-instagram"'
  )
 );
 $category=get_category_by_slug('marketing-digital');
 $categories=$category && !is_wp_error($category)?array((int)$category->term_id):array((int)get_option('default_category'));
 foreach($items as $a){
  $html=@file_get_contents($a['file']);
  if(!is_string($html)||strlen($html)<8500||
      strpos($html,'class="bmd26-article')===false||
      strpos($html,$a['sentinel'])===false||
      strpos($html,'10 de novembro de 2026')===false) {
       fwrite(STDERR,"GUARD:invalid-html ".$a['slug']."\n");exit(2);
  }
  $row=$wpdb->get_row($wpdb->prepare(
   "SELECT ID,post_status,post_title FROM {$wpdb->posts} WHERE post_name=%s AND post_type='post' AND post_status!='trash' LIMIT 1",
    $a['slug']
  ));
  if($row){
    if($row->post_status!=='publish') {fwrite(STDERR,"GUARD:existing-not-published ".$a['slug']."\n");exit(3);}
    echo "EXISTS:".$row->ID.":".get_permalink((int)$row->ID)."\n";
    continue;
  }
  $id=wp_insert_post(array(
    'post_type'=>'post','post_status'=>'publish','post_author'=>1,'post_name'=>$a['slug'],
    'post_title'=>$a['title'],'post_content'=>$html,'post_excerpt'=>$a['excerpt'],
    'post_category'=>$categories,'comment_status'=>'closed',
    'ping_status'=>'closed'
  ),true);
  if(is_wp_error($id)||!$id) {
    fwrite(STDERR,"ERROR:publish:".$a['slug'].":".(is_wp_error($id)?$id->get_error_message():'no-id')."\n");exit(4);
  }
  $post=get_post((int)$id);
  if(!$post||$post->post_status!=='publish'||$post->post_name!==$a['slug']){
    fwrite(STDERR,"GUARD:published-slug-mismatch:".$a['slug'].":".$id."\n");exit(5);
  }
  update_post_meta((int)$id,'rank_math_title',$a['seo']);
  update_post_meta((int)$id,'rank_math_description',$a['description']);
  update_post_meta((int)$id,'rank_math_focus_keyword',$a['keyword']);
  echo "PUBLISHED:".$id.":".get_permalink((int)$id)."\n";
 }
 update_option('bmd_seo_two_guides_20261110_done',date('c'),false);
}finally{
 $wpdb->get_var($wpdb->prepare('SELECT RELEASE_LOCK(%s)', $mutex));
}
