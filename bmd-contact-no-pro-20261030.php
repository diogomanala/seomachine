<?php
/**
 * BMD Contato & Parcerias, 30/10/2026.
 * Formulário nativo; sem Elementor Pro ou CAPTCHA de terceiros.
 * Propostas privadas e avisos por wp_mail quando houver destinatário real.
 */
if (!defined('ABSPATH')) exit;

function bmd_c_topics() {
 return array('publicidade'=>'Publicidade e patrocínio','projeto'=>'Projeto ou parceria','editorial'=>'Pauta ou colaboração editorial','correcao'=>'Correção sobre conteúdo','outro'=>'Outro assunto');
}
add_action('init',function(){
 $caps=array();
 foreach(array('edit_post','read_post','delete_post','edit_posts','edit_others_posts','publish_posts','read_private_posts','delete_posts','delete_private_posts','delete_others_posts','edit_private_posts','edit_published_posts','delete_published_posts') as $cap) $caps[$cap]='manage_options';
 $caps['create_posts']='do_not_allow';
 register_post_type('bmd_proposta',array(
 'labels'=>array('name'=>'Propostas recebidas','singular_name'=>'Proposta','menu_name'=>'Propostas recebidas','all_items'=>'Todas as propostas','edit_item'=>'Ver proposta','not_found'=>'Nenhuma proposta recebida'),
 'public'=>false,'publicly_queryable'=>false,'exclude_from_search'=>true,'show_ui'=>true,'show_in_rest'=>false,
 'show_in_menu'=>'tools.php','show_in_nav_menus'=>false,'rewrite'=>false,'query_var'=>false,
 'capabilities'=>$caps,'map_meta_cap'=>false,'supports'=>array('title','editor')));
});

function bmd_c_destination(){
 $email=sanitize_email((string)get_option('bmd_contact_notify_email',''));
 if(is_email($email)) return $email;
 $admin=sanitize_email((string)get_option('admin_email',''));
 if(is_email($admin) && !preg_match('/nonexisting|noreply|no-reply|example\.|invalid/i',$admin)) return $admin;
 return '';
}
add_action('admin_init',function(){
 register_setting('bmd_contact_settings','bmd_contact_notify_email',array('type'=>'string','sanitize_callback'=>'sanitize_email','default'=>''));
});
add_action('admin_menu',function(){
 add_options_page('Contato BMD','Contato BMD','manage_options','bmd-contact-settings',function(){
 if(!current_user_can('manage_options')) wp_die('Acesso negado');
 echo '<div class="wrap"><h1>Contato BMD: notificações</h1><p>As mensagens são salvas em <strong>Ferramentas → Propostas recebidas</strong>, mesmo quando o servidor não consegue enviar e-mails.</p>';
 if(!bmd_c_destination()) echo '<div class="notice notice-warning inline"><p><strong>Atenção:</strong> configure um e-mail real para receber avisos. As propostas já ficam registradas no painel.</p></div>';
 echo '<form method="post" action="options.php">';
 settings_fields('bmd_contact_settings');
 echo '<table class="form-table"><tr><th scope="row"><label for="bmd_contact_notify_email">E-mail de recebimento</label></th><td><input class="regular-text" type="email" name="bmd_contact_notify_email" id="bmd_contact_notify_email" value="'.esc_attr((string)get_option('bmd_contact_notify_email','')).'"/><p class="description">Use um endereço verificado. O WP Mail SMTP precisa estar configurado para o aviso chegar.</p></td></tr></table>';
 submit_button('Salvar endereço');echo '</form>';
 $destination=bmd_c_destination();
 echo '<hr style="margin:27px 0 19px"><h2>Verificação de recebimento</h2>';
 if($destination){
  echo '<p>Destino configurado: <strong>'.esc_html($destination).'</strong>.</p>';
  echo '<p>O teste cria uma proposta privada identificada como <strong>TESTE INTERNO</strong> e solicita ao WordPress o envio de um e-mail. Nenhum visitante recebe esse teste.</p>';
  echo '<form method="post" action="'.esc_url(admin_url('admin-post.php')).'">';
  echo '<input type="hidden" name="action" value="bmd_contact_test_flow">';
  wp_nonce_field('bmd_contact_test_flow_20261030');
  submit_button('Criar proposta de teste e testar notificação','secondary','submit',true);
  echo '</form>';
 } else {
  echo '<p><strong>Falta cadastrar um destinatário válido.</strong> Salve o endereço acima antes de iniciar o teste.</p>';
 }
 $last=get_option('bmd_contact_last_test_20261030',array());
 if(is_array($last)&&!empty($last['when'])){
  echo '<div class="notice notice-info inline"><p><strong>Último teste: </strong>'.esc_html((string)$last['when']).'. ';
  $saved=!empty($last['saved']);
  $sent=!empty($last['mail_accepted']);
  echo $saved?'Registro privado: criado. ':'Registro privado: falhou. ';
  echo $sent?'E-mail: aceito pelo sistema de envio. ':'E-mail: não foi aceito pelo sistema de envio. ';
  echo '<br>O aceite do servidor não confirma entrega na caixa de entrada. Confira também a pasta de spam.';
  if(!empty($last['id'])) echo ' <a href="'.esc_url(admin_url('post.php?post='.(int)$last['id'].'&action=edit')).'">Ver proposta de teste</a>.';
  echo '</p></div>';
 }
 echo '</div>';
 });
});
add_action('admin_post_bmd_contact_test_flow',function(){
 if(!current_user_can('manage_options')) wp_die('Acesso negado');
 check_admin_referer('bmd_contact_test_flow_20261030');
 $to=bmd_c_destination();
 if(!$to){wp_safe_redirect(admin_url('options-general.php?page=bmd-contact-settings'));exit;}
 if(get_transient('bmd_contact_test_guard_20261030')){
  wp_safe_redirect(admin_url('options-general.php?page=bmd-contact-settings'));exit;
 }
 set_transient('bmd_contact_test_guard_20261030',1,MINUTE_IN_SECONDS);
 $id=wp_insert_post(array(
  'post_type'=>'bmd_proposta','post_status'=>'private',
  'post_title'=>'[TESTE INTERNO] Verificação de contato BMD',
  'post_content'=>'Mensagem de teste gerada pelo administrador para verificar o cadastro privado e o aviso por e-mail. Não é proposta comercial.',
  'post_author'=>get_current_user_id(),'comment_status'=>'closed','ping_status'=>'closed'
 ),true);
 $saved=!is_wp_error($id)&&is_numeric($id)&&(int)$id>0;
 $sent=false;
 if($saved){
  update_post_meta($id,'_bmd_c_topic','outro');
  update_post_meta($id,'_bmd_c_name','Teste interno');
  update_post_meta($id,'_bmd_c_org','BMD');
  update_post_meta($id,'_bmd_c_email',$to);
  update_post_meta($id,'_bmd_c_test',1);
  $msg="Teste do formulário de contato do Blog de Marketing Digital.\n\nO registro privado de teste foi criado no WordPress sob o ID #".(int)$id.".\n\nSe esta mensagem chegou, o canal de notificação está operacional. Nenhuma proposta real foi enviada.";
  $sent=(bool)wp_mail($to,'[BMD] Teste interno de recebimento',$msg,array('Content-Type: text/plain; charset=UTF-8'));
  update_post_meta($id,'_bmd_c_mail',$sent?'sent':'failed');
 }
 update_option('bmd_contact_last_test_20261030',array(
  'when'=>current_time('mysql'),'saved'=>$saved,'mail_accepted'=>$sent,'id'=>$saved?(int)$id:0
 ),false);
 wp_safe_redirect(admin_url('options-general.php?page=bmd-contact-settings'));exit;
});
add_filter('manage_bmd_proposta_posts_columns',function(){
 return array('cb'=>'<input type="checkbox"/>','title'=>'Assunto / remetente','bmd_email'=>'E-mail','bmd_status'=>'Aviso por e-mail','date'=>'Recebido');
});
add_action('manage_bmd_proposta_posts_custom_column',function($column,$post_id){
 if($column==='bmd_email') echo esc_html((string)get_post_meta($post_id,'_bmd_c_email',true));
 if($column==='bmd_status'){
 $s=(string)get_post_meta($post_id,'_bmd_c_mail',true);
 echo esc_html($s==='sent'?'Solicitado ao servidor':($s==='no_recipient'?'Aguardando configuração':'Falhou'));
 }
},10,2);

add_action('template_redirect',function(){
 if(!is_page(1928)) return;
 if(!defined('DONOTCACHEPAGE')) define('DONOTCACHEPAGE',true);
 nocache_headers();
},0);
add_action('wp_enqueue_scripts',function(){
 if(!is_page(1928)) return;
 $p=__DIR__.'/assets/bmd-contact-20261030.css';
 if(is_file($p)) wp_enqueue_style('bmd-contact-v2',plugins_url('assets/bmd-contact-20261030.css',__FILE__),array(),(string)filemtime($p));
},80);

function bmd_c_html(){
 $status=isset($_GET['bmd_recebido'])?sanitize_key(wp_unslash($_GET['bmd_recebido'])):'';
 $time=time();$sig=hash_hmac('sha256',(string)$time,wp_salt('nonce'));
 ob_start(); ?>
 <div class="bmd-contact-v2" id="bmd-contacto">
  <header class="bmd-c-hero">
    <span class="bmd-c-eyebrow"><i aria-hidden="true"></i> CONTATO & COLABORAÇÕES</span>
    <h2>Tem uma proposta que faz sentido para o nosso público?</h2>
    <p>Anúncios, projetos, conteúdo, produtos, ideias ainda no papel. Conte o que está pensando e por que vale uma conversa. A gente lê antes de responder.</p>
  </header>
  <div class="bmd-c-offers" aria-label="Tipos de parceria">
   <article class="bmd-c-offer"><span>01 / VISIBILIDADE</span><h3>Publicidade com contexto</h3><p>Campanhas, patrocínios e divulgação para marcas ligadas a marketing, tecnologia e negócios digitais.</p></article>
   <article class="bmd-c-offer"><span>02 / CONSTRUÇÃO</span><h3>Projetos em conjunto</h3><p>Ferramentas, produtos e colaborações com uma ideia clara do que cada lado pode entregar.</p></article>
   <article class="bmd-c-offer"><span>03 / CONTEÚDO</span><h3>Pautas que merecem espaço</h3><p>Pesquisas, experiências práticas e histórias relevantes para quem vive de negócios digitais.</p></article>
  </div>
  <section class="bmd-c-boundaries" aria-labelledby="bmd-c-rules">
   <span class="bmd-c-check" aria-hidden="true">✓</span><div>
    <h3 id="bmd-c-rules">Uma regra importante, antes de escrever</h3>
    <p>Não fazemos <strong>troca de links</strong>, inserção de backlinks em artigos antigos nem publicações criadas apenas para influenciar rankings. Propostas comerciais são tratadas como publicidade, com identificação clara. Se a intenção é só conseguir um link, este não é o canal.</p>
   </div>
  </section>
  <section class="bmd-c-form-section" aria-labelledby="bmd-c-form-title">
   <div class="bmd-c-intro"><span class="bmd-c-eyebrow">VAMOS CONVERSAR?</span><h3 id="bmd-c-form-title">Conte sua ideia.</h3><p>Uma boa proposta já explica quem está por trás, o que quer construir ou divulgar e por que isso interessa aos nossos leitores.</p><p class="bmd-c-hint">Sem anexos ou apresentações intermináveis. Se precisarmos de outros detalhes, pediremos depois.</p></div>
   <div class="bmd-c-form-wrap">
   <?php if($status==='ok'): ?><div class="bmd-c-notice bmd-c-ok" role="status"><strong>Proposta recebida.</strong> A mensagem ficou registrada. Se fizer sentido continuar, responderemos pelo e-mail informado.</div><?php endif; ?>
   <?php if($status==='erro'): ?><div class="bmd-c-notice bmd-c-error" role="alert"><strong>Não conseguimos concluir o envio.</strong> Confira os campos e tente novamente.</div><?php endif; ?>
   <?php if($status==='limite'): ?><div class="bmd-c-notice bmd-c-error" role="alert"><strong>Muitas tentativas seguidas.</strong> Aguarde um pouco antes de tentar novamente.</div><?php endif; ?>
    <form class="bmd-c-form" method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>" accept-charset="UTF-8">
     <input type="hidden" name="action" value="bmd_contact_submit"/>
     <?php wp_nonce_field('bmd_contact_submit_20261030','bmd_contact_nonce'); ?>
     <input type="hidden" name="bmd_sent_at" value="<?php echo esc_attr((string)$time); ?>"/>
     <input type="hidden" name="bmd_sent_sig" value="<?php echo esc_attr($sig); ?>"/>
     <div class="bmd-c-trap" aria-hidden="true"><label for="bmd_fax_number">Deixe vazio</label><input type="text" id="bmd_fax_number" name="bmd_fax_number" tabindex="-1" autocomplete="off" value=""/></div>
     <div class="bmd-c-fields-2"><div class="bmd-c-field"><label for="bmd_c_name">Seu nome <em>*</em></label><input type="text" id="bmd_c_name" name="bmd_name" minlength="2" maxlength="90" placeholder="Como podemos chamar você?" autocomplete="name" required/></div>
      <div class="bmd-c-field"><label for="bmd_c_email">E-mail para resposta <em>*</em></label><input type="email" id="bmd_c_email" name="bmd_email" maxlength="190" placeholder="voce@empresa.com" autocomplete="email" required/></div></div>
     <div class="bmd-c-fields-2"><div class="bmd-c-field"><label for="bmd_c_org">Marca, empresa ou projeto</label><input type="text" id="bmd_c_org" name="bmd_org" maxlength="110" placeholder="Opcional"/></div>
      <div class="bmd-c-field"><label for="bmd_c_topic">Qual é o assunto? <em>*</em></label><select id="bmd_c_topic" name="bmd_topic" required><option value="">Selecione uma opção</option><?php foreach(bmd_c_topics() as $k=>$v): ?><option value="<?php echo esc_attr($k); ?>"><?php echo esc_html($v); ?></option><?php endforeach; ?></select></div></div>
     <div class="bmd-c-field"><label for="bmd_c_message">Sua proposta <em>*</em></label><textarea id="bmd_c_message" name="bmd_message" rows="6" minlength="35" maxlength="4500" required placeholder="O que você pretende anunciar ou construir? Para qual público? Já tem ideia de formato, orçamento ou prazo?"></textarea></div>
     <label class="bmd-c-consent"><input type="checkbox" name="bmd_consent" value="1" required/><span>Concordo em enviar estas informações para avaliação e resposta, conforme a <a href="<?php echo esc_url(home_url('/politica-de-privacidade/')); ?>">Política de Privacidade</a>. As propostas ficam registradas na área administrativa.</span></label>
     <button class="bmd-c-submit" type="submit">Enviar proposta <span aria-hidden="true">↗</span></button>
     <p class="bmd-c-footnote">Proteção antispam sem CAPTCHA de terceiros. Seus dados não aparecem publicamente.</p>
    </form>
   </div>
  </section>
 </div>
 <?php return ob_get_clean();
}
add_filter('the_content',function($content){
 if(is_admin()||is_feed()||is_preview()||!is_page(1928)||(int)get_the_ID()!==1928) return $content;
 $GLOBALS['bmd_c_rendered']=true;
 return bmd_c_html();
},999999);
add_action('wp_footer',function(){
 if(!is_page(1928)||!empty($GLOBALS['bmd_c_rendered'])) return;
 echo '<div class="bmd-c-fallback">'.bmd_c_html().'</div>';
 echo '<script>(function(){var e=document.querySelector(".bmd-c-fallback"),t=document.querySelector("#content .entry-content")||document.querySelector(".site-content");if(e&&t)t.appendChild(e);})();</script>';
},15);


function bmd_c_length($text){return function_exists('mb_strlen')?mb_strlen($text,'UTF-8'):strlen($text);}
add_filter('rank_math/frontend/title',function($title){
 return !is_admin()&&is_page(1928)?'Anúncios e Parcerias | Blog de Marketing Digital':$title;
},60);
add_filter('rank_math/frontend/description',function($description){
 return !is_admin()&&is_page(1928)?'Quer anunciar, propor uma pauta ou desenvolver uma parceria com o Blog de Marketing Digital? Envie sua proposta. Não fazemos troca de links ou venda de backlinks.':$description;
},60);
add_filter('rank_math/opengraph/facebook/og_description',function($description){
 return !is_admin()&&is_page(1928)?'Anúncios, patrocínios, projetos e colaborações editoriais. Envie sua proposta ao Blog de Marketing Digital. Não trabalhamos com troca de links.':$description;
},60);

function bmd_c_redirect($status){
 wp_safe_redirect(add_query_arg('bmd_recebido',$status,home_url('/contato/')),303);exit;
}
function bmd_c_submit(){
 if(strtoupper((string)($_SERVER['REQUEST_METHOD']??''))!=='POST') bmd_c_redirect('erro');
 if(!empty($_POST['bmd_fax_number'])) bmd_c_redirect('ok');
 $nonce=isset($_POST['bmd_contact_nonce'])?sanitize_text_field(wp_unslash($_POST['bmd_contact_nonce'])):'';
 if(!wp_verify_nonce($nonce,'bmd_contact_submit_20261030')) bmd_c_redirect('erro');
 $ts=isset($_POST['bmd_sent_at'])?absint($_POST['bmd_sent_at']):0;
 $sig=isset($_POST['bmd_sent_sig'])?(string)wp_unslash($_POST['bmd_sent_sig']):'';
 if(!$ts||!hash_equals(hash_hmac('sha256',(string)$ts,wp_salt('nonce')),$sig)||time()-$ts<4||time()-$ts>21600) bmd_c_redirect('erro');
 $identity=hash_hmac('sha256',(string)($_SERVER['REMOTE_ADDR']??'').'|'.(string)($_SERVER['HTTP_USER_AGENT']??''),wp_salt('auth'));
 $short='bmd_c_short_'.substr($identity,0,30);$hour='bmd_c_hour_'.substr($identity,0,30);
 if(get_transient($short)||(int)get_transient($hour)>=6) bmd_c_redirect('limite');
 $name=isset($_POST['bmd_name'])?sanitize_text_field(wp_unslash($_POST['bmd_name'])):'';
 $email=isset($_POST['bmd_email'])?sanitize_email(wp_unslash($_POST['bmd_email'])):'';
 $org=isset($_POST['bmd_org'])?sanitize_text_field(wp_unslash($_POST['bmd_org'])):'';
 $topic=isset($_POST['bmd_topic'])?sanitize_key(wp_unslash($_POST['bmd_topic'])):'';
 $message=isset($_POST['bmd_message'])?sanitize_textarea_field(wp_unslash($_POST['bmd_message'])):'';
 $consent=isset($_POST['bmd_consent'])?(string)wp_unslash($_POST['bmd_consent']):'';
 $topics=bmd_c_topics();
 if($consent!=='1'||!is_email($email)||!isset($topics[$topic])||bmd_c_length($name)<2||bmd_c_length($name)>90||bmd_c_length($org)>110||bmd_c_length($message)<35||bmd_c_length($message)>4500||preg_match_all('~https?://|www\.~i',$message)>2) bmd_c_redirect('erro');
 set_transient($hour,(int)get_transient($hour)+1,HOUR_IN_SECONDS);set_transient($short,1,45);
 $id=wp_insert_post(array('post_type'=>'bmd_proposta','post_status'=>'private','post_title'=>$topics[$topic].' | '.$name,'post_content'=>$message,'post_author'=>0,'comment_status'=>'closed','ping_status'=>'closed'),true);
 if(is_wp_error($id)||!$id){delete_transient($short);bmd_c_redirect('erro');}
 update_post_meta($id,'_bmd_c_email',$email);update_post_meta($id,'_bmd_c_name',$name);
 update_post_meta($id,'_bmd_c_org',$org);update_post_meta($id,'_bmd_c_topic',$topic);update_post_meta($id,'_bmd_c_consent_at',current_time('mysql'));
 $to=bmd_c_destination();$mail='no_recipient';
 if($to){
  $body="Nova proposta recebida no Blog de Marketing Digital.\n\nAssunto: ".$topics[$topic]."\nNome: ".$name."\nE-mail: ".$email."\nMarca/projeto: ".($org?:'Não informado')."\n\nMensagem:\n".$message."\n\nRegistro privado #".$id;
  $mail=wp_mail($to,'[BMD] Nova proposta: '.$topics[$topic],$body,array('Content-Type: text/plain; charset=UTF-8','Reply-To: '.$email))?'sent':'failed';
 }
 update_post_meta($id,'_bmd_c_mail',$mail);
 bmd_c_redirect('ok');
}
add_action('admin_post_nopriv_bmd_contact_submit','bmd_c_submit');
add_action('admin_post_bmd_contact_submit','bmd_c_submit');
