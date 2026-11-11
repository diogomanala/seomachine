<?php
/**
 * BMD Premium Affiliate Guide v1.0
 * Scoped to one editorial WordPress post. No prices, scraped images, reviews or fake ratings.
 */
if(!defined('ABSPATH'))exit;
function bmd_aff_premium_is_post(){
 if(!is_singular('post'))return false;
 $p=get_queried_object();
 return $p instanceof WP_Post && $p->post_name==='melhores-microfones-lapela-sem-fio-para-gravar-reels';
}
add_action('wp_head',function(){
 if(!bmd_aff_premium_is_post())return;
 echo <<<'STYLE'
<style id="bmd-aff-premium-v1">
.bmd-aff-premium{--ink:#202830;--muted:#5e6972;--accent:#af344b;--edge:#e3e7ea;font-family:inherit;color:var(--ink);line-height:1.65}
.bmd-aff-premium *{box-sizing:border-box}
.bmd-aff-premium .affp-top{background:linear-gradient(130deg,#21272f,#34313a);color:#fff;border-radius:20px;padding:clamp(22px,5vw,38px);margin:20px 0 18px;position:relative;overflow:hidden}
.bmd-aff-premium .affp-top:after{content:"";position:absolute;right:-90px;top:-90px;width:270px;height:270px;border:48px solid rgba(239,112,130,.10);border-radius:50%;pointer-events:none}
.bmd-aff-premium .affp-eyebrow{font-size:11px;letter-spacing:.11em;text-transform:uppercase;color:#f3acb7;font-weight:800}
.bmd-aff-premium .affp-top h2{font-size:clamp(25px,4vw,38px)!important;color:#fff!important;line-height:1.17!important;letter-spacing:-.03em!important;margin:12px 0!important}
.bmd-aff-premium .affp-top p{color:#e4e6e8!important;font-size:15px!important;max-width:760px}
.bmd-aff-premium .affp-summary{border:1px solid rgba(255,255,255,.19);background:rgba(255,255,255,.08);padding:16px 18px;border-radius:12px;font-size:13px;color:#fff}
.bmd-aff-premium .affp-summary strong{color:#fff}
.bmd-aff-premium .affp-bar{display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin:15px 0 22px}
.bmd-aff-premium .affp-bar a{font-size:12px;color:#9d2b41!important;border:1px solid #ead6da;border-radius:999px;text-decoration:none!important;padding:8px 12px;background:#fff}
.bmd-aff-premium .affp-section{margin:26px 0}
.bmd-aff-premium .affp-section h2{font-size:clamp(22px,3vw,29px)!important;color:#222b34!important;margin:0 0 10px!important;line-height:1.3!important}
.bmd-aff-premium .affp-sub{font-size:13px!important;color:#62717b;margin:0 0 16px!important}
.bmd-aff-premium .affp-choice{border:1px solid var(--edge);border-radius:17px;background:#fff;padding:22px}
.bmd-aff-premium .affp-choice fieldset{border:0;margin:0;padding:0}
.bmd-aff-premium .affp-choice legend{font-size:15px;font-weight:800;margin-bottom:14px}
.bmd-aff-premium .affp-options{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px}
.bmd-aff-premium .affp-options label{display:flex;gap:9px;align-items:flex-start;border:1px solid #e4e7ea;border-radius:12px;padding:15px 13px;cursor:pointer;font-size:13px;line-height:1.45;background:#fafbfb}
.bmd-aff-premium .affp-options label:has(input:checked){background:#fff3f5;border-color:#b54458}
.bmd-aff-premium .affp-options input{accent-color:#a83249;margin-top:3px}
.bmd-aff-premium .affp-answer{background:#f6f7f8;border-left:3px solid #b0364b;border-radius:0 12px 12px 0;padding:17px 19px;margin-top:14px}
.bmd-aff-premium .affp-answer strong{display:block;font-size:17px;margin-bottom:5px}
.bmd-aff-premium .affp-answer p{font-size:13px!important;margin:0!important}
.bmd-aff-premium .affp-answer a{display:inline-block;margin-top:12px;padding:10px 15px;border-radius:9px;background:#ab3349;color:#fff!important;font-weight:700;font-size:12px;text-decoration:none!important}
.bmd-aff-premium .affp-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
.bmd-aff-premium .affp-card{border:1px solid #e4e8eb;background:#fff;border-radius:15px;padding:19px;min-width:0}
.bmd-aff-premium .affp-card small{font-size:10px;font-weight:800;letter-spacing:.09em;color:#a93448}
.bmd-aff-premium .affp-card h3{font-size:16px!important;line-height:1.3!important;margin:10px 0!important;color:#24313a!important}
.bmd-aff-premium .affp-card p{font-size:12px!important;color:#5c6973;margin:8px 0!important}
.bmd-aff-premium .affp-card a{font-size:12px!important;color:#a22c42!important;font-weight:700}
.bmd-aff-premium .affp-table{overflow-x:auto;border:1px solid #e3e8ea;border-radius:13px}
.bmd-aff-premium .affp-table table{width:100%;min-width:560px;border-collapse:collapse!important;margin:0!important}
.bmd-aff-premium .affp-table th{background:#26313a!important;color:white!important;font-size:12px!important;text-align:left!important;padding:14px!important;border:0!important}
.bmd-aff-premium .affp-table td{font-size:12px!important;padding:14px!important;border:0!important;border-top:1px solid #e5e9ec!important;vertical-align:top!important}
.bmd-aff-premium .affp-table tr:nth-child(even) td{background:#f8f9fa!important}
.bmd-aff-premium .affp-method{border:1px solid #e4e8eb;border-radius:15px;padding:20px;background:#f9fafb}
.bmd-aff-premium .affp-method p,.bmd-aff-premium .affp-method li{font-size:13px!important;color:#52616b}
.bmd-aff-premium .affp-note{font-size:12px!important;color:#66737d;margin:14px 0}
.bmd-aff-premium a:focus-visible,.bmd-aff-premium input:focus-visible{outline:3px solid #b03c52;outline-offset:3px}
@media(max-width:720px){.bmd-aff-premium .affp-options,.bmd-aff-premium .affp-grid{grid-template-columns:1fr}.bmd-aff-premium .affp-card{padding:15px}.bmd-aff-premium .affp-choice{padding:15px}}
@media(prefers-reduced-motion:reduce){.bmd-aff-premium *{scroll-behavior:auto!important}}
</style>
STYLE;
},115);
add_filter('the_content',function($content){
 if(is_admin()||!is_main_query()||!in_the_loop()||!bmd_aff_premium_is_post())return $content;
 if(strpos($content,'id="bmd-aff-premium"')!==false)return $content;
 $tag='supercompra0d-20';
 $products=array(
  'combo'=>array('Hollyland Lark M2 Combo','B0CP7QXWPN','Celular + câmera','USB-C, Lightning e câmera, conforme a variante'),
  'usb'=>array('Hollyland Lark M2 USB-C','B0H97WGKS2','Celular USB-C','USB-C; não substitui um receptor de câmera'),
  'c1'=>array('Hollyland Lark C1','B0BHWF94KR','Alternativa móvel','Confirme conector, versão e estoque')
 );
 $urls=array();foreach($products as $k=>$p){$urls[$k]='https://www.amazon.com.br/dp/'.$p[1].'?tag='.$tag;}
 $pfx='<div class="bmd-aff-premium" id="bmd-aff-premium">
 <header class="affp-top"><div class="affp-eyebrow">GUIA DE COMPRA · EDIÇÃO 2026</div>
 <h2>Descubra qual microfone combina com a sua gravação</h2>
 <p>Compare pelo celular que você usa, pela quantidade de pessoas e pela necessidade de conectar uma câmera. Sem nota inventada, preço desatualizado ou promessa de áudio profissional.</p>
 <div class="affp-summary"><strong>Escolha rápida:</strong> grava só no celular USB-C? Comece pelo Lark M2 USB-C. Alterna entre celular e câmera? Compare o Lark M2 Combo. Quer considerar outra linha? Verifique a versão do Lark C1.</div></header>
 <nav class="affp-bar" aria-label="Navegação rápida do guia"><a href="#affp-escolher">Qual comprar?</a><a href="#affp-modelos">Comparar modelos</a><a href="#affp-compat">Compatibilidade</a><a href="#affp-metodo">Como avaliamos</a><a href="#affp-guia">Guia completo</a></nav>
 <section class="affp-section" id="affp-escolher"><h2>Encontre sua opção em 10 segundos</h2>
 <p class="affp-sub">Selecione seu cenário. A recomendação é editorial e considera apenas a compatibilidade e a finalidade informadas.</p>
 <div class="affp-choice"><fieldset><legend>Onde você vai gravar?</legend><div class="affp-options">
 <label><input type="radio" name="bmd-aff-use" value="usb" checked> Só no celular USB-C</label>
 <label><input type="radio" name="bmd-aff-use" value="combo"> No celular e também em câmera</label>
 <label><input type="radio" name="bmd-aff-use" value="c1"> Quero comparar uma alternativa</label>
 </div></fieldset>
 <div class="affp-answer" aria-live="polite"><strong id="affp-name">Hollyland Lark M2 USB-C</strong><p id="affp-reason">É a opção mais direta desta seleção para quem grava com um smartphone USB-C. Confirme a compatibilidade com seu aparelho e aplicativo.</p><a id="affp-action" href="'.esc_url($urls['usb']).'" rel="nofollow sponsored noopener" target="_blank">Conferir na Amazon</a></div></div></section>
 <section class="affp-section" id="affp-modelos"><h2>Três opções, três necessidades</h2><p class="affp-sub">A melhor compra depende mais da conexão correta do que do nome do modelo.</p><div class="affp-grid">';
 foreach($products as $k=>$p){
  $pfx.='<article class="affp-card"><small>'.esc_html($p[2]).'</small><h3>'.esc_html($p[0]).'</h3><p>'.esc_html($p[3]).'</p><a href="'.esc_url($urls[$k]).'" target="_blank" rel="nofollow sponsored noopener">Consultar produto na Amazon</a></article>';
 }
 $pfx.='</div></section>
 <section class="affp-section" id="affp-compat"><h2>Compatibilidade antes do preço</h2><div class="affp-table"><table><thead><tr><th>Seu equipamento</th><th>O que procurar</th><th>Atenção</th></tr></thead><tbody>
 <tr><td>Android com USB-C</td><td>Receptor USB-C compatível</td><td>Confirme suporte a microfone externo no aplicativo</td></tr>
 <tr><td>iPhone com Lightning</td><td>Receptor Lightning ou kit compatível</td><td>Não presuma que USB-C funcione com adaptador</td></tr>
 <tr><td>iPhone com USB-C</td><td>Receptor USB-C compatível</td><td>Verifique modelo, iOS e aplicativo</td></tr>
 <tr><td>Câmera com entrada de áudio</td><td>Receptor e cabo apropriados</td><td>Kit só para smartphone pode não atender</td></tr>
 <tr><td>Entrevista com duas pessoas</td><td>Dois transmissores</td><td>Confira exatamente o conteúdo da embalagem</td></tr>
 </tbody></table></div></section>
 <section class="affp-section" id="affp-metodo"><h2>Como este comparativo foi feito</h2><div class="affp-method"><p><strong>Critérios:</strong> compatibilidade de conexão, versatilidade do kit, número de transmissores, acessórios e adequação ao cenário de gravação.</p>
 <p><strong>Limites:</strong> não realizamos testes de bancada nem medimos alcance, ruído, latência ou bateria. Não exibimos estrelas, número de avaliações, preços ou disponibilidade da Amazon sem uma integração autorizada que forneça esses dados.</p>
 <p><strong>Atualização:</strong> revisão editorial em 11/11/2026. Verifique as especificações e a variante no anúncio antes de comprar. Os botões contêm links de afiliado.</p></div></section>
 <div id="affp-guia"></div></div>';
 $script='<script id="bmd-aff-premium-js">(function(){var r=document.querySelector("#bmd-aff-premium");if(!r)return;var data='.wp_json_encode(array(
 'usb'=>array('name'=>'Hollyland Lark M2 USB-C','reason'=>'É a opção mais direta desta seleção para smartphone USB-C. Confirme compatibilidade com seu aparelho e aplicativo.','url'=>$urls['usb']),
 'combo'=>array('name'=>'Hollyland Lark M2 Combo','reason'=>'O kit Combo é o mais versátil desta seleção para alternar entre celular e câmera. Confira o que vem na embalagem.','url'=>$urls['combo']),
 'c1'=>array('name'=>'Hollyland Lark C1','reason'=>'Uma alternativa para gravações móveis. Confira a variante e a disponibilidade atual no anúncio.','url'=>$urls['c1'])
 ),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE).';r.querySelectorAll("input[name=bmd-aff-use]").forEach(function(el){el.addEventListener("change",function(){if(!el.checked)return;var p=data[el.value];if(!p)return;r.querySelector("#affp-name").textContent=p.name;r.querySelector("#affp-reason").textContent=p.reason;r.querySelector("#affp-action").href=p.url;});});})();</script>';
 return $pfx.$content.$script;
},12);
add_action('wp_head',function(){
 if(!bmd_aff_premium_is_post())return;
 $p=get_queried_object();
 $schema=array(
 '@context'=>'https://schema.org',
 '@graph'=>array(
  array('@type'=>'Article','@id'=>get_permalink($p).'#article','headline'=>get_the_title($p),'mainEntityOfPage'=>get_permalink($p),'inLanguage'=>'pt-BR','datePublished'=>get_the_date('c',$p),'dateModified'=>get_the_modified_date('c',$p),'author'=>array('@type'=>'Organization','name'=>get_bloginfo('name')),'about'=>array('@type'=>'Thing','name'=>'Microfones de lapela sem fio para criação de vídeos')),
  array('@type'=>'ItemList','@id'=>get_permalink($p).'#comparison','name'=>'Microfones de lapela sem fio para gravar Reels','itemListOrder'=>'https://schema.org/ItemListOrderUnordered','numberOfItems'=>3,'itemListElement'=>array(
   array('@type'=>'ListItem','position'=>1,'name'=>'Hollyland Lark M2 Combo','url'=>'https://www.amazon.com.br/dp/B0CP7QXWPN?tag=supercompra0d-20'),
   array('@type'=>'ListItem','position'=>2,'name'=>'Hollyland Lark M2 USB-C','url'=>'https://www.amazon.com.br/dp/B0H97WGKS2?tag=supercompra0d-20'),
   array('@type'=>'ListItem','position'=>3,'name'=>'Hollyland Lark C1','url'=>'https://www.amazon.com.br/dp/B0BHWF94KR?tag=supercompra0d-20')
  ))
 )
 );
 echo '<script type="application/ld+json" id="bmd-aff-premium-schema">'.wp_json_encode($schema,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP).'</script>';
},119);
