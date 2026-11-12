<?php
/**
 * Restore interactive form controls stripped by WordPress KSES.
 * Scoped to published post 9179 and only main post content.
 */
if(!defined('ABSPATH'))exit;
add_filter('the_content',static function($content){
 if(!function_exists('bmdkit26_on_page') || !bmdkit26_on_page() || !is_main_query() || !in_the_loop())return $content;
 if(strpos($content,'id="bk-form"')!==false && strpos($content,'name="budget"')!==false)return $content;
 $start=strpos($content,'<div class="bk-layout">');
 $aside=strpos($content,'<aside');
 if($start===false||$aside===false||$aside<$start ||
 strpos(substr($content,$aside,200),'id="bk-choice"')===false)return $content;
 $form=<<<'HTML'
<form id="bk-form" class="bk-form" aria-label="Configuração do kit para Reels">
<fieldset><legend><span>01</span> Quanto você pretende investir?</legend><div class="bk-choice-grid bk-choice-3">
<label class="bk-choice"><input type="radio" name="budget" value="200"><span><strong>Até R$ 200</strong><small>Começar pelo essencial</small></span></label>
<label class="bk-choice"><input type="radio" name="budget" value="400" checked><span><strong>Até R$ 400</strong><small>Kit básico mais flexível</small></span></label>
<label class="bk-choice"><input type="radio" name="budget" value="1000"><span><strong>Até R$ 1.000</strong><small>Planejar um upgrade</small></span></label>
</div></fieldset>
<fieldset><legend><span>02</span> Qual é a conexão do seu celular?</legend><div class="bk-choice-grid bk-choice-2">
<label class="bk-choice"><input type="radio" name="device" value="usbc" checked><span><strong>USB-C</strong><small>Android e iPhones mais recentes</small></span></label>
<label class="bk-choice"><input type="radio" name="device" value="lightning"><span><strong>Lightning</strong><small>iPhones com essa entrada</small></span></label>
</div></fieldset>
<fieldset><legend><span>03</span> O que você grava com mais frequência?</legend><div class="bk-choice-grid bk-choice-2">
<label class="bk-choice"><input type="radio" name="usage" value="solo" checked><span><strong>Reels falando sozinho</strong><small>Vídeos, aulas e stories</small></span></label>
<label class="bk-choice"><input type="radio" name="usage" value="duo"><span><strong>Entrevistas e duas pessoas</strong><small>Conversa e conteúdo com convidado</small></span></label>
</div></fieldset>
<div class="bk-privacy"><span aria-hidden="true">✓</span> As escolhas são processadas nesta página, sem cadastro e sem envio de dados pessoais.</div>
</form>
HTML;
 $endStart=$start+strlen('<div class="bk-layout">');
 return substr($content,0,$endStart).$form.substr($content,$aside);
},98);
