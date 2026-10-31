(function(){
'use strict';
var BRL=new Intl.NumberFormat('pt-BR',{style:'currency',currency:'BRL'});
var NUM=new Intl.NumberFormat('pt-BR',{maximumFractionDigits:1});
var DEC=new Intl.NumberFormat('pt-BR',{maximumFractionDigits:2});
function start(){
document.querySelectorAll('.bmd-ads-profit').forEach(function(root){
 var fields=['daily','cpc','land','close','ticket','margin','fee'];
 var error=root.querySelector('[data-err]');
 var result=root.querySelector('[data-results]');
 function get(k){var el=root.querySelector('[data-input="'+k+'"]');return el?Number(el.value):NaN;}
 function out(k,v){var el=root.querySelector('[data-out="'+k+'"]');if(el)el.textContent=String(v);}
 function money(v){return BRL.format(Math.abs(v)<.005?0:v);}
 function calc(){
  var vals={};fields.forEach(function(k){vals[k]=get(k);});
  var errs=fields.filter(function(k){return !Number.isFinite(vals[k])||vals[k]<0||(k==='land'||k==='close'||k==='margin'?vals[k]>100:false)||vals[k]>1e7;});
  if(vals.cpc<=0)errs.push('cpc');
  if(vals.daily<=0)errs.push('daily');
  if(errs.length){error.hidden=false;error.textContent='Confira os valores: orçamento e CPC precisam ser positivos; taxas entre 0% e 100%.';result.hidden=true;return;}
  error.hidden=true;result.hidden=false;
  var spend=vals.daily*30.4;
  var clicks=spend/vals.cpc,leadRate=vals.land/100,saleRate=vals.close/100;
  var leads=clicks*leadRate,sales=leads*saleRate;
  var unitMargin=vals.ticket*vals.margin/100;
  var revenue=sales*vals.ticket,contribution=sales*unitMargin;
  var profit=contribution-spend-vals.fee;
  var maxCPC=(spend+vals.fee)>0?unitMargin*leadRate*saleRate*spend/(spend+vals.fee):0;
  var costPerSale=sales>0?(spend+vals.fee)/sales:null;
  var maxCPA=unitMargin;
  out('spend',money(spend));
  out('clicks',NUM.format(clicks));
  out('leads',DEC.format(leads));
  out('sales',DEC.format(sales));
  out('max-cpc',money(maxCPC));
  out('margin-sale',money(unitMargin));
  out('cpa',costPerSale===null?'Não calculável':money(costPerSale));
  out('max-cpa',money(maxCPA));
  out('profit',money(profit));
  out('revenue',money(revenue));
  out('contribution',money(contribution));
  var state=root.querySelector('[data-profit-state]');
  if(state){
   state.textContent=profit>=0?'Contribuição positiva na simulação':'Resultado negativo na simulação';
   state.className='bmd-ads-profit-status '+(profit>=0?'positive':'negative');
  }
  var verdict=root.querySelector('[data-verdict]');
  if(verdict){
   verdict.textContent=leadRate===0||saleRate===0||unitMargin===0?
    'Sem conversões ou sem margem de contribuição, nenhum CPC positivo se paga neste cenário.':
    vals.cpc<=maxCPC?'Neste cenário, o CPC informado fica dentro do limite de equilíbrio calculado.':
    'Neste cenário, o CPC informado supera o limite de equilíbrio. Ajuste o funil, a margem ou o custo.';
  }
  var tbody=root.querySelector('[data-scenarios]');
  if(tbody){
   tbody.replaceChildren();
   [.75,1,1.5].forEach(function(k){
    var simulatedCPC=vals.cpc*k;
    var scenariosProfit=(spend/simulatedCPC)*leadRate*saleRate*unitMargin-spend-vals.fee;
    var tr=document.createElement('tr');
    [money(simulatedCPC),money(scenariosProfit)].forEach(function(t){var td=document.createElement('td');td.textContent=t;tr.appendChild(td);});
    if(scenariosProfit<0)tr.className='loss';
    tbody.appendChild(tr);
   });
  }
 }
 fields.forEach(function(k){var el=root.querySelector('[data-input="'+k+'"]');if(el)el.addEventListener('input',calc);});
 root.querySelectorAll('[data-daily-preset]').forEach(function(b){
  b.addEventListener('click',function(){
   var daily=root.querySelector('[data-input="daily"]');
   if(daily)daily.value=b.getAttribute('data-daily-preset');
   root.querySelectorAll('[data-daily-preset]').forEach(function(x){x.setAttribute('aria-pressed',x===b?'true':'false');});
   calc();
  });
 });
 calc();
});
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',start);else start();
})();