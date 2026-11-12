<?php
if(!defined('ABSPATH'))exit;
add_action('wp_footer',static function(){if(!function_exists('bmdkit26_on_page')||!bmdkit26_on_page())return;echo '<script id="bmdkit26-js">'.$GLOBALS['bmdkit26js'].'</script>';},110);
$GLOBALS['bmdkit26js']=<<<'JS'
(function(){
'use strict';
var root=document.getElementById('bmdkit26'),form=document.getElementById('bk-form');
if(!root||!form)return;
var TAG='supercompra0d-20';
var amazonSearch=function(q){return 'https://www.amazon.com.br/s?k='+encodeURIComponent(q).replace(/%20/g,'+')+'&tag='+TAG;};
var items={
 basicTripod:{name:'Tripé Tomate MTG-3011',kind:'TRIPÉ',detail:'Tripé com cabeça de três movimentos e altura indicada de até 1,10 m. Confira se o anúncio inclui suporte para seu celular.',url:'https://www.amazon.com.br/dp/B07H518JFK?tag='+TAG,action:'Ver produto'},
 betterTripod:{name:'Tripé Ulanzi MT-44',kind:'TRIPÉ',detail:'Bastão e tripé extensível para celular, com cabeça esférica e rosca de 1/4”. Confira altura e suporte incluído.',url:'https://www.amazon.com.br/dp/B09T92NK4J?tag='+TAG,action:'Ver produto'},
 ring:{name:'Ring light com suporte para celular',kind:'ILUMINAÇÃO',detail:'Compare opções com intensidade ajustável, altura adequada e alimentação compatível. Abre resultados de busca, não um modelo específico.',url:amazonSearch('ring light 10 polegadas com tripe celular'),action:'Pesquisar opções'},
 panel:{name:'Painel LED regulável para gravação',kind:'ILUMINAÇÃO',detail:'Procure luz regulável e difusa para posicionar lateralmente. Confira alimentação, encaixe e acessórios. Abre uma busca na Amazon.',url:amazonSearch('painel led regulavel video gravação celular'),action:'Pesquisar opções'},
 nativeAudio:{name:'Áudio do próprio celular',kind:'ÁUDIO · SEM COMPRA',detail:'Comece gravando próximo da boca, em ambiente silencioso. Teste o som antes de investir em microfone.',url:null,action:''},
 usbEntry:{name:'Microfone de entrada para USB-C',kind:'ÁUDIO · PESQUISA',detail:'Pesquise lapelas USB-C e confirme compatibilidade, avaliações e se o kit vem com um ou dois transmissores.',url:amazonSearch('microfone lapela sem fio usb c celular'),action:'Pesquisar opções'},
 lightningEntry:{name:'Microfone de entrada para Lightning',kind:'ÁUDIO · PESQUISA',detail:'Procure receptor Lightning específico para seu iPhone. Confira modelo e compatibilidade antes de pagar.',url:amazonSearch('microfone lapela lightning iphone'),action:'Pesquisar opções'},
 duoUsb:{name:'Microfone com dois transmissores USB-C',kind:'ÁUDIO · ENTREVISTAS',detail:'Para duas pessoas, procure kit 2TX com receptor USB-C. Confira se realmente contém os dois transmissores.',url:amazonSearch('microfone lapela duplo 2 transmissores usb c'),action:'Pesquisar opções'},
 duoLightning:{name:'Microfone com dois transmissores Lightning',kind:'ÁUDIO · ENTREVISTAS',detail:'Procure kit duplo com receptor Lightning para iPhone. Não compre a variante USB-C por engano.',url:amazonSearch('microfone lapela duplo 2 transmissores lightning'),action:'Pesquisar opções'},
 premiumUsb:{name:'Hollyland Lark M2 USB-C',kind:'ÁUDIO · UPGRADE',detail:'Dois transmissores e receptor USB-C no kit consultado. A oferta principal não estava destacada na pesquisa; confira vendedores e estoque.',url:'https://www.amazon.com.br/dp/B0H97WGKS2?tag='+TAG,action:'Ver produto'},
 premiumCombo:{name:'Hollyland Lark M2 Combo',kind:'ÁUDIO · UPGRADE',detail:'Inclui receptores USB-C, Lightning e câmera no anúncio consultado. É um upgrade que pode ultrapassar o orçamento definido.',url:'https://www.amazon.com.br/dp/B0CP7QXWPN?tag='+TAG,action:'Ver produto'}
};
function getValue(name){var el=form.querySelector('input[name="'+name+'"]:checked');return el?el.value:null;}
function selection(){
 var budget=getValue('budget')||'400',device=getValue('device')||'usbc',usage=getValue('usage')||'solo';
 var kit=[],title='',desc='',note='';
 if(budget==='200'){
  title='Kit para começar com o essencial';
  desc='Primeiro estabilize o celular e aproveite uma luz adequada. O áudio interno pode ser suficiente em ambiente silencioso.';
  kit=[items.basicTripod,items.ring,items.nativeAudio];
  note='Nesta faixa, evite comprar um microfone de baixa qualidade só para completar o kit. Confira se tripé + luz cabem no teto de R$ 200.';
  if(usage==='duo')note+=' Para entrevistas, aproxime o aparelho ou adie a compra até poder adquirir um conjunto de dois transmissores.';
 }else if(budget==='400'){
  title='Kit intermediário para gravar sozinho';
  desc='Priorize tripé confiável e iluminação. O microfone é um próximo passo, dependendo do preço e da compatibilidade.';
  kit=[items.betterTripod,items.ring,items.nativeAudio];
  if(usage==='duo'){
    title='Kit intermediário para entrevistas';
    kit=[items.basicTripod,items.ring,device==='usbc'?items.duoUsb:items.duoLightning];
    desc='Reserve parte do orçamento para dois transmissores. O link do microfone abre opções para comparar, não uma oferta validada.';
  }else if(device==='lightning'){
    kit=[items.betterTripod,items.ring,items.lightningEntry];
    desc='Priorize estabilidade e luz. Um microfone Lightning pode ser um upgrade, se o total da compra permitir.';
  }else{
    kit=[items.betterTripod,items.ring,items.usbEntry];
  }
  note='As opções de microfone são pesquisas para você filtrar marcas, compatibilidade e preço. Se a soma ultrapassar R$ 400, comece sem microfone externo.';
 }else{
  title='Kit com upgrade de áudio e iluminação';
  desc='Inclui um tripé versátil, uma luz regulável e uma sugestão de microfone sem fio com receptor compatível.';
  kit=[items.betterTripod,items.panel,device==='usbc'?items.premiumUsb:items.premiumCombo];
  note='Atenção: os microfones deste nível podem consumir grande parte ou ultrapassar R$ 1.000 sozinhos. Se o total exceder o teto, priorize tripé + iluminação e deixe o microfone para uma segunda etapa.';
  if(usage==='duo')desc+=' O kit de áudio sugerido contempla dois transmissores no anúncio consultado.';
 }
 if(device==='lightning'&&budget==='200')note+=' Seu iPhone Lightning usa o microfone interno neste kit inicial.';
 return {budget:budget,device:device,usage:usage,kit:kit,title:title,desc:desc,note:note};
}
var productsEl=document.getElementById('bk-products');
function el(tag,cls,content){var node=document.createElement(tag);if(cls)node.className=cls;if(content!==undefined)node.textContent=content;return node;}
function render(){
 var s=selection();
 document.getElementById('bk-result-title').textContent=s.title;
 document.getElementById('bk-result-subtitle').textContent=s.desc;
 document.getElementById('bk-budget-badge').textContent='Teto planejado: R$ '+s.budget;
 document.getElementById('bk-device-badge').textContent=s.device==='usbc'?'Celular USB-C':'iPhone Lightning';
 document.getElementById('bk-use-badge').textContent=s.usage==='solo'?'Individual':'Duas pessoas';
 document.getElementById('bk-caveat').textContent=s.note;
 productsEl.replaceChildren();
 s.kit.forEach(function(p,i){
  var box=el('div','bk-product'),num=el('span','bk-product-n',String(i+1).padStart(2,'0')),container=el('div');
  var type=el('span','bk-item-kind',p.kind);type.style.cssText='font-size:9px;letter-spacing:.09em;color:#f9adb9;font-weight:800;display:block;margin-bottom:5px';
  container.appendChild(type);container.appendChild(el('strong','',p.name));container.appendChild(el('p','',p.detail));
  if(p.url){
   var a=el('a','bk-product-link',p.action+' ↗');a.href=p.url;a.target='_blank';a.rel='nofollow sponsored noopener';a.dataset.kind=p.kind;container.appendChild(a);
  }else container.appendChild(el('span','bk-no-link','Sem compra necessária'));
  box.appendChild(num);box.appendChild(container);productsEl.appendChild(box);
 });
 var state=JSON.stringify({budget:s.budget,device:s.device,usage:s.usage});
 root.dataset.selection=state;
 document.getElementById('bk-feedback').textContent='';
}
form.addEventListener('change',render);
document.getElementById('bk-copy').addEventListener('click',function(){
 var s=selection();
 var content=['Kit para gravar Reels','Teto planejado: R$ '+s.budget,'Celular: '+(s.device==='usbc'?'USB-C':'Lightning'),'Gravação: '+(s.usage==='solo'?'Individual':'Entrevista'),'',...s.kit.map(function(p,i){return (i+1)+'. '+p.name+(p.url?' — '+p.url:'');}),'','Atenção: '+s.note].join('\n');
 function feedback(message){document.getElementById('bk-feedback').textContent=message;}
 if(navigator.clipboard&&window.isSecureContext)navigator.clipboard.writeText(content).then(function(){feedback('Lista copiada.');}).catch(function(){fallback();});
 else fallback();
 function fallback(){var t=document.createElement('textarea');t.value=content;t.style.position='fixed';t.style.opacity='0';document.body.appendChild(t);t.select();try{feedback(document.execCommand('copy')?'Lista copiada.':'Não foi possível copiar automaticamente.');}catch(e){feedback('Copie os itens diretamente da tela.');}t.remove();}
});
root.addEventListener('click',function(ev){var a=ev.target.closest('a[href*="amazon.com.br"]');if(!a)return;try{if(window.dataLayer&&Array.isArray(window.dataLayer)){window.dataLayer.push({event:'bmd_affiliate_click',page:'kit_reels',category:a.dataset.kind||'related'});}}catch(e){}});
render();
})();
JS;
