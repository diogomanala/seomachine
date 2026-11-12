/* BMD Reels creator configurator: 18 combinations, no cookies or remote API calls. */
(function(){
 'use strict';
 function init(){
  var root=document.getElementById('bmd-kit-reels');
  if(!root || root.dataset.initialized==='1')return;
  root.dataset.initialized='1';
  var form=root.querySelector('#bkr-form'), title=root.querySelector('#bkr-result-title'),
  intro=root.querySelector('#bkr-result-intro'), grid=root.querySelector('#bkr-result-grid'),
  note=root.querySelector('#bkr-budget-note'), copy=root.querySelector('#bkr-copy');
  if(!form||!grid||!title||!intro||!note)return;
  var tag='supercompra0d-20';
  var products={
   v10:{name:'BOYA BY-V10 USB-C',kind:'ÁUDIO',desc:'Microfone sem fio para gravar sozinho em aparelho USB-C. Confira o aplicativo de câmera.',asin:'B0D69WQFYQ'},
   v20:{name:'BOYA BY-V20 USB-C',kind:'ÁUDIO',desc:'Dois transmissores para entrevistas com celular USB-C. Verifique a composição do kit.',asin:'B0DJS8HCGJ'},
   v2:{name:'BOYA BY-V2 Lightning',kind:'ÁUDIO',desc:'Conector próprio para iPhones Lightning; confirme a variante e o número de transmissores.',asin:'B0BR3S4KPD'},
   combo:{name:'Hollyland Lark M2 Combo',kind:'ÁUDIO AVANÇADO',desc:'Se também grava com câmera, considere este kit e confira os receptores incluídos.',asin:'B0CP7QXWPN'},
   tripod:{name:'Ulanzi MT-44',kind:'SUPORTE',desc:'Tripé extensível para estabilizar e enquadrar o celular. Verifique estabilidade e suporte.',asin:'B09T92NK4J'},
   light:{name:'Ulanzi VL49 RGB',kind:'ILUMINAÇÃO',desc:'Luz compacta de preenchimento ou fundo. Para luz principal, use uma janela ou fonte suave.',asin:'B08MDQ3CJY'}
  };
  var free={
   window:{name:'Luz natural da janela',kind:'SEM COMPRA',desc:'Coloque a janela na frente ou lateral do rosto para obter iluminação mais uniforme.'},
   phone:{name:'Comece com o microfone do celular',kind:'SEM COMPRA',desc:'Grave perto do aparelho, com pouco eco e ventiladores desligados; teste dez segundos.'},
   support:{name:'Apoio firme que você já tem',kind:'SEM COMPRA',desc:'Use um apoio estável à altura dos olhos enquanto avalia a compra de um tripé.'},
   sound:{name:'Escolha um ambiente silencioso',kind:'SEM COMPRA',desc:'Para entrevistas, aproxime o telefone das duas pessoas e monitore ruídos.'}
  };
  function selected(name){var c=form.querySelector('input[name="'+name+'"]:checked');return c?c.value:null;}
  function data(){
   return {budget:Number(selected('bkr-budget')||400),device:selected('bkr-device')||'usb',use:selected('bkr-use')||'solo'};
  }
  function getKit(q){
   var a=[],audio=q.device==='lightning'?'v2':q.use==='interview'?'v20':'v10';
   if(q.budget===200){
    if(q.use==='product'){a=[free.window,free.support,products.tripod];}
    else if(q.use==='interview'){a=[free.sound,free.phone,products.tripod];}
    else a=[free.window,free.phone,products.tripod];
   }else if(q.budget===400){
    if(q.use==='product')a=[products.tripod,free.window,products.light];
    else a=[products[audio],free.window,products.tripod];
   }else{
    if(q.use==='product')a=[products.tripod,products.light,products[q.device==='lightning'?'v2':'v10']];
    else if(q.use==='interview')a=[products[q.device==='lightning'?'v2':'v20'],products.tripod,products.light];
    else a=[products[q.device==='lightning'?'v2':'v20'],products.tripod,products.light];
   }
   return a;
  }
  var labels={solo:'Reels sozinho',interview:'entrevistas com duas pessoas',product:'vídeos de produtos e tutoriais'};
  function explain(q){
   if(q.budget===200)return 'Nesta faixa, a recomendação é usar o que você já tem e decidir qual acessório comprar primeiro. Não há promessa de um kit completo por R$ 200.';
   if(q.use==='product')return 'Em demonstrações e vídeos de bancada, priorize um enquadramento estável e a iluminação do objeto. O microfone pode vir depois.';
   if(q.use==='interview')return 'Para duas pessoas, prefira um conjunto com dois transmissores e receptor compatível. Veja o que é essencial agora e o que pode ficar para depois.';
   return 'Para vídeos falando para a câmera, comece pela compatibilidade do microfone e use luz natural sempre que possível. O tripé é um segundo passo.';
  }
  function makeElement(tagName,cls,text){
   var el=document.createElement(tagName);if(cls)el.className=cls;if(typeof text==='string')el.textContent=text;return el;
  }
  function render(){
   var q=data(),a=getKit(q);grid.replaceChildren();
   title.textContent='Seu plano para '+labels[q.use];
   intro.textContent=explain(q);
   a.forEach(function(p,i){
    var card=makeElement('div','bkr-result-item'+(!p.asin?' bkr-free':''));
    var number=makeElement('span','bkr-index',String(i+1).padStart(2,'0'));
    var right=makeElement('div','');
    right.appendChild(makeElement('small','',p.kind));
    right.appendChild(makeElement('h3','',p.name));
    right.appendChild(makeElement('p','',p.desc));
    if(p.asin){
     var a=makeElement('a','','Consultar na Amazon ↗');
     a.href='https://www.amazon.com.br/dp/'+p.asin+'?tag='+tag;
     a.target='_blank';a.rel='nofollow sponsored noopener';a.dataset.asin=p.asin;
     right.appendChild(a);
    }
    card.appendChild(number);card.appendChild(right);grid.appendChild(card);
   });
   note.textContent='';
   var bold=makeElement('strong','','Sobre o orçamento: ');
   note.appendChild(bold);
   note.appendChild(document.createTextNode('essa faixa organiza prioridades de compra, não calcula preços em tempo real. A soma dos itens pode ultrapassar R$ '+q.budget.toLocaleString('pt-BR')+'. Confira preço, frete, compatibilidade e estoque na Amazon.'));
  }
  function copyList(){
   var q=data(),names=getKit(q).map(function(p,i){return (i+1)+'. '+p.name+(p.asin?' | https://www.amazon.com.br/dp/'+p.asin+'?tag='+tag:'');});
   var text='Kit para '+labels[q.use]+' | Orçamento de referência até R$ '+q.budget+'\n'+names.join('\n')+'\nPreços não calculados em tempo real. Confira na Amazon.';
   if(navigator.clipboard&&navigator.clipboard.writeText){
    navigator.clipboard.writeText(text).then(function(){copy.textContent='Lista copiada ✓';setTimeout(function(){copy.textContent='Copiar lista ⧉';},1900);}).catch(function(){copyFallback(text);});
   }else copyFallback(text);
  }
  function copyFallback(text){
   var ta=document.createElement('textarea');ta.value=text;ta.setAttribute('readonly','');ta.style.position='fixed';ta.style.opacity='0';
   document.body.appendChild(ta);ta.select();var ok=false;try{ok=document.execCommand('copy');}catch(e){}
   ta.remove();copy.textContent=ok?'Lista copiada ✓':'Selecione para copiar';
   if(!ok)window.prompt('Copie sua lista:',text);
   setTimeout(function(){copy.textContent='Copiar lista ⧉';},1900);
  }
  form.addEventListener('change',function(e){if(e.target&&e.target.matches('input[type=radio]'))render();});
  if(copy)copy.addEventListener('click',copyList);
  root.addEventListener('click',function(e){
   var target=e.target.closest('a[href*="amazon.com.br/dp/"]');
   if(!target)return;
   var href=target.getAttribute('href')||'';
   var asin=target.dataset.asin||(href.match(/\/dp\/([A-Z0-9]+)/)||[])[1]||'';
   if(typeof window.gtag==='function')window.gtag('event','affiliate_click',{event_category:'amazon_kit_reels',item_id:asin,link_url:href});
  });
  render();
 }
 if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init,{once:true});else init();
})();