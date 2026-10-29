/* BMD original SEO worklist: progress and pending action plan, browser-only. */
(function(){
 'use strict';
 function boot(){
  var root=document.getElementById('bmd-seo-worklist');if(!root)return;
  var checks=Array.prototype.slice.call(root.querySelectorAll('[data-bmd-seo-check]'));
  var status=root.querySelector('#bmd-seo-worklist-status');
  var meter=root.querySelector('.bmd-seo-worklist-meter');
  var bar=meter?meter.querySelector('span'):null;
  var copy=root.querySelector('#bmd-seo-worklist-copy');
  var feedback=root.querySelector('#bmd-seo-worklist-feedback');
  if(checks.length!==5||!status||!meter||!bar||!copy||!feedback)return;
  function refresh(){
    var done=checks.filter(function(c){return c.checked;}).length;
    status.textContent=done+' de 5 verificações concluídas';
    meter.setAttribute('aria-valuenow',String(done));
    bar.style.width=String(done*20)+'%';
    copy.textContent=done===5?'Copiar resumo concluído':'Copiar tarefas pendentes';
  }
  checks.forEach(function(c){c.addEventListener('change',function(){
    feedback.textContent='';
    refresh();
  });});
  copy.addEventListener('click',function(){
    var pending=checks.filter(function(c){return !c.checked;});
    var text='Auditoria SEO manual — Blog de Marketing Digital\n';
    if(!pending.length){
      text+='\nTodas as cinco verificações foram marcadas como concluídas. Confira as evidências no Search Console, PageSpeed Insights e na revisão de conteúdo antes de encerrar.';
    }else{
      text+='\nTarefas pendentes (confira e registre as evidências):\n';
      pending.forEach(function(c,index){
        var step=c.closest('.bmd-seo-worklist-step');
        var title=step?step.querySelector('.bmd-seo-worklist-step-title strong'):null;
        var detail=step?step.querySelector('p'):null;
        var link=step?step.querySelector('a[href]'):null;
        text+=(index+1)+'. '+(title?title.textContent.trim():'Revisão SEO')+'\n';
        if(detail)text+='   '+detail.textContent.trim()+'\n';
        if(link)text+='   '+link.href+'\n';
      });
    }
    text+='\nChecklist informativo, não uma análise automatizada nem garantia de classificação.';
    function fallback(){
      var node=document.createElement('textarea');
      node.value=text;node.style.position='fixed';node.style.left='-9999px';
      document.body.appendChild(node);node.focus();node.select();
      try{
        if(document.execCommand && document.execCommand('copy')){
          node.remove();feedback.textContent='Checklist copiado.';return;
        }
      }catch(e){}
      feedback.textContent='Não foi possível copiar automaticamente. Selecione as etapas e copie manualmente.';
      node.remove();return;
    }
    if(navigator.clipboard&&navigator.clipboard.writeText){
      navigator.clipboard.writeText(text).then(function(){feedback.textContent='Checklist copiado.';})
       .catch(fallback);
    }else fallback();
  });
  refresh();
 }
 if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',boot,{once:true});else boot();
})();