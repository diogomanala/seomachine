(function(){
"use strict";
function create(tag, cls, txt){
 const e=document.createElement(tag);if(cls)e.className=cls;if(txt!==undefined)e.textContent=String(txt);return e;
}
function addLink(parent,label,url){
 try{const u=new URL(url);if(u.protocol!=="https:")return;const a=create("a",null,label);a.href=u.href;a.rel="nofollow noopener noreferrer";a.target="_blank";parent.appendChild(a);}catch(e){}
}
function explainRange(r){
 if(!r||typeof r!=="object")return "Faixa não identificada";
 const ops={lt:"<",lte:"≤",le:"≤",gt:">",gte:"≥",ge:"≥",eq:"="};
 let bits=[];
 if(r.min&&ops[r.min_op])bits.push(ops[r.min_op]+" "+r.min);
 if(r.max&&ops[r.max_op])bits.push(ops[r.max_op]+" "+r.max);
 return bits.length?"Versões "+bits.join(" e "):"Faixa não identificada";
}
function showEntry(record){
 const card=create("article","bmd-radar-advisory");
 const h=create("h4",null,record.name||"Aviso de segurança");card.appendChild(h);
 const bits=create("div","bmd-radar-tags");bits.appendChild(create("span","bmd-radar-range",explainRange(record.range)));
 if(record.cvss!==null&&record.cvss!==undefined)bits.appendChild(create("span","bmd-radar-score","CVSS "+Number(record.cvss).toFixed(1)));
 if(record.range&&record.range.unfixed)bits.appendChild(create("span","bmd-radar-unfixed","Fonte não informa correção"));
 card.appendChild(bits);
 if(Array.isArray(record.sources)&&record.sources.length){
  const wrap=create("div","bmd-radar-sources");wrap.appendChild(create("span",null,"Referências: "));
  record.sources.forEach((source,i)=>{if(i)wrap.appendChild(document.createTextNode(" · "));addLink(wrap,source.label||"Fonte",source.url);});
  card.appendChild(wrap);
 }
 return card;
}
function setup(root){
 const form=root.querySelector("[data-form]");
 const choice=root.querySelector("[data-plugin]");
 const wrap=root.querySelector("[data-custom-wrap]");
 const custom=root.querySelector("[data-custom]");
 const version=root.querySelector("[data-version]");
 const button=root.querySelector("[data-submit]");
 const message=root.querySelector("[data-message]");
 const results=root.querySelector("[data-results]");
 const summary=root.querySelector("[data-summary]");
 const counts=root.querySelector("[data-counts]");
 const advisories=root.querySelector("[data-advisories]");
 const meta=root.querySelector("[data-meta]");
 if(!form||!choice||!wrap||!custom||!version||!button||!message||!results||!summary||!counts||!advisories||!meta)return;
 let current=null;
 function notify(t,isError){message.textContent=t;message.className="bmd-radar-message"+(isError?" error":"");}
 function change(){const other=choice.value==="outro";wrap.hidden=!other;custom.required=other;custom.disabled=!other;}
 choice.addEventListener("change",change);change();
 form.addEventListener("submit",async function(ev){
 ev.preventDefault();
 const slug=(choice.value==="outro"?custom.value:choice.value).toLowerCase().trim();
 const ver=version.value.trim();
 if(!/^[a-z0-9][a-z0-9-]{1,78}[a-z0-9]$/.test(slug)){notify("Selecione um plugin ou informe um slug válido, com letras, números e hífens.",true);return;}
 if(!/^[0-9]{1,5}(?:\.[0-9]{1,5}){0,6}(?:[-._+]?[a-zA-Z0-9.-]{1,20})?$/.test(ver)){notify("Informe a versão exata, por exemplo: 3.21.4 ou 5.9.4.",true);return;}
 if(current)current.abort();current=new AbortController();
 const abort=current;button.disabled=true;button.textContent="Consultando avisos…";results.hidden=true;notify("Consultando avisos públicos e comparando a versão informada…",false);
 try{
  const url=new URL(root.dataset.endpoint,document.location.origin);
  url.searchParams.set("slug",slug);url.searchParams.set("version",ver);
  const response=await fetch(url.href,{signal:abort.signal,credentials:"same-origin",headers:{Accept:"application/json"}});
  const data=await response.json();
  if(!response.ok)throw new Error(data.message||"Não foi possível consultar a base agora.");
  if(!data||!data.counts)throw new Error("A fonte retornou dados incompletos.");
  summary.replaceChildren();counts.replaceChildren();advisories.replaceChildren();meta.replaceChildren();
  const header=create("div","bmd-radar-result-header");
  header.appendChild(create("span","bmd-radar-result-label",data.plugin+" · versão "+data.version));
  let title="",desc="",kind="";
  if(data.risk==="affected"){
   title="Atenção: "+data.counts.affected+" aviso"+(data.counts.affected!==1?"s":"")+" aplicável"+(data.counts.affected!==1?"eis":"")+" à versão";
   desc="A versão informada aparece nas faixas afetadas em registros públicos. Confira cada referência antes de atualizar ou desativar funcionalidades.";
   kind="warning";
  }else if(data.risk==="inconclusive"){
   title="Resultado inconclusivo";
   desc="Não foi identificada correspondência nas faixas reconhecíveis, mas há avisos sem dados suficientes para comparar versões. A ausência de confirmação não significa segurança.";
   kind="neutral";
  }else{
   title="Nenhum aviso aplicável encontrado";
   desc="Os registros conhecidos consultados não apontaram essa versão nas faixas afetadas. Isso não garante que o plugin esteja seguro ou atualizado.";
   kind="clear";
  }
  header.appendChild(create("h3","bmd-radar-result-title",title));
  header.appendChild(create("p","bmd-radar-result-description",desc));
  summary.className="bmd-radar-summary "+kind;summary.appendChild(header);
  const cells=[
   ["Avisos aplicáveis",data.counts.affected],
   ["Outras versões",data.counts.not_affected],
   ["Sem faixa conclusiva",data.counts.inconclusive]
  ];
  cells.forEach(([label,value])=>{const box=create("div","bmd-radar-number");box.appendChild(create("strong",null,value));box.appendChild(create("span",null,label));counts.appendChild(box);});
  if(data.latest){const latest=create("p","bmd-radar-current","Versão mais recente informada pela fonte: "+data.latest+". Confira a versão de correção e a compatibilidade no site oficial do plugin.");advisories.appendChild(latest);}
  if(data.affected&&data.affected.length){
   const h=create("h3",null,"Avisos que correspondem à versão");advisories.appendChild(h);
   data.affected.forEach(x=>advisories.appendChild(showEntry(x)));
   if(data.counts.affected>data.affected.length)advisories.appendChild(create("p","bmd-radar-extra","Mostrando "+data.affected.length+" de "+data.counts.affected+" avisos aplicáveis. Consulte a fonte para a lista completa."));
  }
  if(data.inconclusive&&data.inconclusive.length){
   advisories.appendChild(create("h3",null,"Avisos que exigem conferência manual"));
   data.inconclusive.forEach(x=>advisories.appendChild(showEntry(x)));
   if(data.counts.inconclusive>data.inconclusive.length)advisories.appendChild(create("p","bmd-radar-extra","Outros registros inconclusivos estão disponíveis na fonte original."));
  }
  if(data.last_updated){
   const dt=new Date(data.last_updated);
   if(!Number.isNaN(dt.getTime()))meta.appendChild(create("span",null,"Base atualizada em "+new Intl.DateTimeFormat("pt-BR",{timeZone:"America/Sao_Paulo",day:"2-digit",month:"2-digit",year:"numeric",hour:"2-digit",minute:"2-digit"}).format(dt)+" (Brasília)"));
  }
  addLink(meta,"Consultar a base original e fontes",data.source_url);
  results.hidden=false;
  notify("Consulta concluída. Verifique os avisos e suas fontes abaixo.",false);
 }catch(err){
  if(err&&err.name==="AbortError")return;
  notify((err&&err.message)||"Não foi possível consultar os dados. Tente novamente.",true);
 }finally{
  if(abort===current){button.disabled=false;button.textContent="Verificar versão →";current=null;}
 }
 });
}
function init(){document.querySelectorAll(".bmd-radar").forEach(setup);}
if(document.readyState==="loading")document.addEventListener("DOMContentLoaded",init);else init();
})();