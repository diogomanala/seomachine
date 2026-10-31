(function () {
'use strict';
function n(s){return String(s||'').normalize('NFD').replace(/[\u0300-\u036f]/g,'').trim().toLowerCase();}
function asNumber(s,integer){
 s=String(s===null||s===undefined?'':s).trim().replace(/\s/g,'').replace(/[R$€%]/g,'');
 if(!s)return 0;
 if(s.indexOf(',')!==-1&&s.indexOf('.')!==-1){
   s=s.lastIndexOf(',')>s.lastIndexOf('.')?s.replace(/\./g,'').replace(',','.'):s.replace(/,/g,'');
 }else if(s.indexOf(',')!==-1){
   s=integer?s.replace(/,/g,''):s.replace(',','.');
 }else if(integer&&/^\d{1,3}(\.\d{3})+$/.test(s)){s=s.replace(/\./g,'');}
 var v=Number(s);return Number.isFinite(v)?v:0;
}
function readCSV(raw,limit){
 var lines=String(raw||'').replace(/^\uFEFF/,'').slice(0,500000).replace(/\r\n/g,'\n');
 var head=lines.split('\n',1)[0];
 var delim=head.indexOf('\t')!==-1?'\t':head.indexOf(';')!==-1?';':',';
 var rows=[],row=[],part='',quote=false;
 for(var i=0;i<lines.length;i++){
   var c=lines[i],next=lines[i+1];
   if(c==='"'){if(quote&&next==='"'){part+='"';i++;}else quote=!quote;continue;}
   if(!quote&&(c===delim||c==='\n')){
     row.push(part);part='';
     if(c==='\n'){if(row.some(function(x){return x.trim()!=='';}))rows.push(row);row=[];if(rows.length>=limit)break;}
   }else part+=c;
 }
 if(part||row.length){row.push(part);if(row.some(function(x){return x.trim()!=='';}))rows.push(row);}
 return rows;
}
function discover(rows){
 for(var i=0;i<Math.min(8,rows.length);i++){
  var header=rows[i].map(n);
  function idx(words){for(var k=0;k<header.length;k++)if(words.some(function(w){return header[k]===w||header[k].indexOf(w)!==-1;}))return k;return -1;}
  var query=idx(['consultas mais frequentes','consultas','consulta','query','search term','top queries','palavra-chave']);
  var impressions=idx(['impressions','impressoes','impressao']);
  var pos=idx(['position','posicao','pos. media']);
  var clicks=idx(['clicks','cliques']);
  var ctr=idx(['ctr']);
  if(query>=0&&impressions>=0&&pos>=0)return {row:i,query:query,impressions:impressions,pos:pos,clicks:clicks,ctr:ctr};
 }
 return null;
}
var commercial=/preco|precos|quanto custa|contratar|planos|crm|software|vps|n8n|hospedagem|ferramenta|plataforma|automacao|cloud|google ads|whatsapp api|melhor/i;
function action(p,ctr,clicks){
 if(p<=5&&ctr<2)return 'Confira título, resposta inicial e intenção de busca';
 if(p<=12)return 'Reforce a resposta e os links internos';
 if(p<=25)return 'Complete subtópicos e compare a página com os resultados';
 if(p<=50)return 'Aprofunde o conteúdo e avalie termos mais específicos';
 return 'Monitore antes de priorizar';
}
function rank(raw){
 var rows=readCSV(raw,6000);
 var h=discover(rows);
 if(!h)throw Error('Não encontrei as colunas de consulta, impressões e posição. Exporte a tabela de Consultas do relatório Desempenho do Search Console em CSV ou cole os dados com o cabeçalho.');
 var data=[],seen=new Set();
 for(var i=h.row+1;i<rows.length;i++){
  var row=rows[i],query=String(row[h.query]||'').trim();
  var imp=asNumber(row[h.impressions],true);
  var pos=asNumber(row[h.pos],false);
  if(!query||!Number.isFinite(pos)||pos<=0||imp<=0)continue;
  var clicks=h.clicks>=0?asNumber(row[h.clicks],true):0;
  var ctr=h.ctr>=0?asNumber(row[h.ctr],false):100*clicks/imp;
  if(h.ctr>=0&&!String(row[h.ctr]).includes('%')&&ctr>0&&ctr<=1)ctr*=100;
  var key=query.toLowerCase();
  if(seen.has(key))continue;
  seen.add(key);
  var com=commercial.test(n(query));
  var weight=pos<=5?(ctr<2?1.0:0.45):pos<=12?1.35:pos<=25?1:pos<=50?.48:.10;
  var priority=imp*weight*(clicks===0?1.15:1)*(com?1.12:1);
  data.push({query:query,imp:imp,pos:pos,clicks:clicks,ctr:ctr,commercial:com,priority:priority,recommendation:action(pos,ctr,clicks)});
 }
 if(!data.length)throw Error('Nenhuma consulta com posição e impressões válidas foi encontrada.');
 data.sort(function(a,b){return b.priority-a.priority;});
 return data;
}
function init(){
 document.querySelectorAll('.bmd-gsc-audit').forEach(function(root){
  var area=root.querySelector('[data-audit-input]'),file=root.querySelector('[data-audit-file]'),go=root.querySelector('[data-audit-run]'),sample=root.querySelector('[data-audit-demo]'),message=root.querySelector('[data-audit-message]'),result=root.querySelector('[data-audit-results]'),table=root.querySelector('[data-audit-tbody]'),summary=root.querySelector('[data-audit-summary]');
  var fmt=new Intl.NumberFormat('pt-BR'),decimal=new Intl.NumberFormat('pt-BR',{maximumFractionDigits:1});
  function setMessage(s,err){message.textContent=s;message.classList.toggle('bmd-gsc-error',!!err);}
  function createCell(tr,v){var td=document.createElement('td');td.textContent=String(v);tr.appendChild(td);return td;}
  function render(){
   try{
    var all=rank(area.value);
    var ranked=all.filter(function(x){return x.imp>=5&&x.pos<=50;});
    if(!ranked.length)ranked=all;
    table.replaceChildren();
    ranked.slice(0,10).forEach(function(v,index){
      var row=document.createElement('tr');
      createCell(row,String(index+1).padStart(2,'0'));
      var title=createCell(row,v.query);
      if(v.commercial){var mark=document.createElement('small');mark.className='bmd-gsc-commercial';mark.textContent='Sinal comercial';title.appendChild(mark);}
      createCell(row,fmt.format(v.imp));
      createCell(row,fmt.format(v.clicks));
      createCell(row,decimal.format(v.pos));
      createCell(row,v.recommendation);
      table.appendChild(row);
    });
    result.hidden=false;
    summary.textContent='Foram lidas '+fmt.format(all.length)+' consultas. Exibindo até 10 prioridades, combinando impressões, posição média e cliques. A marcação comercial identifica apenas palavras do texto; não mede CPC nem RPM.';
    setMessage('Análise local concluída. Confira as sugestões no quadro abaixo.',false);
   }catch(e){result.hidden=true;setMessage(e.message||'Não foi possível analisar esta tabela.',true);}
  }
  go.addEventListener('click',render);
  sample.addEventListener('click',function(){
    area.value='Consulta;Cliques;Impressões;CTR;Posição\nanalise seo gratuita;0;199;0%;29,2\nquanto custa crm;1;84;1,19%;11,7\ncomo clonar site completo;0;47;0%;19,5\npreço de hospedagem vps;0;31;0%;34,2\nn8n cloud vs vps;2;45;4,44%;8,3';
    setMessage('Exemplo fictício carregado. Clique em Analisar para conhecer o resultado.',false);
  });
  file.addEventListener('change',function(){
   if(!file.files||!file.files[0])return;
   var f=file.files[0];
   if(f.size>1500000){setMessage('O CSV precisa ter até 1,5 MB. Exporte apenas as consultas do período desejado.',true);return;}
   var reader=new FileReader();
   reader.onload=function(){area.value=String(reader.result||'');setMessage('CSV carregado apenas no seu navegador. Clique em Analisar.',false);};
   reader.onerror=function(){setMessage('Não consegui ler o arquivo. Você pode colar as linhas no campo.',true);};
   reader.readAsText(f,'UTF-8');
  });
 });
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init);else init();
})();