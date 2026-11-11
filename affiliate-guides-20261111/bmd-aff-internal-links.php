<?php
/**
 * BMD related internal links for two existing creator guides.
 * No database rewrites; only appends a context-relevant reading suggestion.
 */
if(!defined('ABSPATH'))exit;
function bmd_aff_source_link_target(){
 if(is_admin()||!is_singular('post'))return 0;
 $id=(int)get_queried_object_id();
 return in_array($id,array(8243,8039),true)?$id:0;
}
add_filter('the_content',static function($content){
 if(is_admin()||!is_singular('post')||!is_main_query()||!in_the_loop())return $content;
 $id=bmd_aff_source_link_target();
 if(!$id)return $content;
 $href=home_url('/melhores-microfones-lapela-sem-fio-para-gravar-reels/');
 if(strpos($content,$href)!==false)return $content;
 $title=$id===8243?'Vai colocar o roteiro de Reels em prática?':'Vai produzir vídeos UGC para clientes?';
 $detail=$id===8243?'Antes de gravar, confira qual microfone de lapela funciona no seu celular e como testar o áudio.':'Veja o comparativo de três microfones sem fio para gravações com smartphone, incluindo compatibilidade e limitações.';
 return $content.'<aside class="bmd-aff-intlink" aria-label="Leitura complementar"><span>LEITURA COMPLEMENTAR</span><strong>'.esc_html($title).'</strong><p>'.esc_html($detail).'</p><a href="'.esc_url($href).'">Ver guia de microfones para Reels</a></aside>';
},120);
add_action('wp_head',static function(){
 if(!bmd_aff_source_link_target())return;
 echo <<<'CSS'
<style id="bmd-aff-internal-link-css">
.bmd-aff-intlink{border:1px solid #e0e6ea;border-left:4px solid #a82d45;border-radius:0 13px 13px 0;background:#f8fafb;color:#263744;margin:30px 0;padding:18px 20px}
.bmd-aff-intlink span{display:block;color:#952a42;font-size:10px;letter-spacing:.09em;font-weight:800;margin:0 0 8px}
.bmd-aff-intlink strong{display:block;color:#1f303e;font-size:16px;line-height:1.4}
.bmd-aff-intlink p{color:#526370;font-size:13px;line-height:1.65;margin:7px 0 10px}
.bmd-aff-intlink a{color:#9e2941;font-weight:800;font-size:13px}
</style>
CSS;
});
