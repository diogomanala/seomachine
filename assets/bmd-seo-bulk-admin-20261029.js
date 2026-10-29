/* BMD SEO Mass Repair v1 — administrator-only, incremental safe run. */
(function(){
 'use strict';
 function ready(){
  var root=document.querySelector('.bmdseo-bulk-admin');
  var cfg=window.BmdSeoBulk;
  var btn=document.getElementById('bmdseo-bulk-start');
  var feedback=document.getElementById('bmdseo-bulk-feedback');
  var phase=document.getElementById('bmdseo-bulk-phase');
  if(!root||!cfg||!btn||!feedback||!phase)return;
  var running=false;
  function num(name,value){
   var el=root.querySelector('[data-bmd="'+name+'"]');
   if(el)el.textContent=Number(value||0).toLocaleString('pt-BR');
  }
  function counts(data){
   if(!data||!data.issue_counts)return;
   var el=document.getElementById('bmdseo-bulk-issue-counts');
   if(!el)return;
   el.textContent='';
   var names=Object.keys(data.issue_counts).sort(function(a,b){
    return Number(data.issue_counts[b])-Number(data.issue_counts[a]);
   });
   names.forEach(function(name){
    var tr=document.createElement('tr');
    var td=document.createElement('td');
    var count=document.createElement('td');
    td.textContent=name.replace(/_/g,' ');
    count.textContent=Number(data.issue_counts[name]).toLocaleString('pt-BR');
    tr.appendChild(td);tr.appendChild(count);el.appendChild(tr);
   });
  }
  function redraw(data){
   phase.textContent=data.status||'processing';
   ['scanned','repaired_posts','repaired_links','issues'].forEach(function(n){num(n,data[n]);});
   counts(data);
  }
  function step(){
   var body=new URLSearchParams();
   body.append('action','bmdseo_bulk_step');
   body.append('nonce',cfg.nonce);
   return fetch(cfg.ajax,{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/x-www-form-urlencoded; charset=UTF-8'},body:body.toString(),cache:'no-store'})
    .then(function(r){if(!r.ok)throw Error('HTTP '+r.status);return r.json();})
    .then(function(json){if(!json.success)throw Error(json.data||'Erro na auditoria');return json.data;});
  }
  async function run(){
   if(running)return;
   running=true;btn.disabled=true;feedback.textContent='Verificando artigos e corrigindo apenas links confirmados…';
   try{
    var data,last=-1,busy=0;
    for(var n=0;n<100;n++){
     data=await step();redraw(data);
     if(data.status==='complete'){
      feedback.textContent='Varredura concluída. Baixe o CSV para revisar os casos não automáticos.';
      btn.textContent='Auditoria concluída';
      running=false;return;
     }
     if(data.status==='error')throw Error('Processamento interrompido. Consulte o log PHP.');
     if(data.status==='busy'){
      busy++;if(busy>8)throw Error('Outro processo já está analisando o site. Atualize a página para ver o resultado.');
      await new Promise(function(resolve){setTimeout(resolve,1100);});continue;
     }
     busy=0;
     feedback.textContent=(data.scanned||0).toLocaleString('pt-BR')+' publicações verificadas; '+(data.repaired_links||0)+' links normalizados.';
     if(data.scanned===last)throw Error('A varredura não avançou. Recarregue a página para continuar.');
     last=data.scanned;
    }
    feedback.textContent='Limite de lotes desta sessão atingido; clique novamente para continuar.';
    btn.disabled=false;
   }catch(e){
    feedback.textContent='O processo parou: '+String(e.message||e)+' Os lotes concluídos foram preservados.';
    btn.disabled=false;
   }
   running=false;
  }
  btn.addEventListener('click',run);
 }
 if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',ready,{once:true});else ready();
})();