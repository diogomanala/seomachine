(function(){
"use strict";
const RULES=[
{id:"wordpress",name:"WordPress",type:"CMS",checks:[
{re:/\/wp-content\/(?:themes|plugins|uploads)\//ig,label:"Arquivos públicos em /wp-content/",strong:true},
{re:/\/wp-includes\//ig,label:"Recursos do núcleo /wp-includes/",strong:true},
{re:/<meta[^>]+name=["']generator["'][^>]+content=["']WordPress\b/ig,label:"Metatag de geração WordPress",strong:true},
{re:/<link[^>]+href=["'][^"']*\/wp-json\//ig,label:"Referência à REST API do WordPress",strong:false}
]},
{id:"elementor",name:"Elementor",type:"Editor visual",checks:[
{re:/\/wp-content\/plugins\/elementor\//ig,label:"Arquivos públicos do plugin Elementor",strong:true},
{re:/\belementor-(?:widget|section|container|element|page)\b/ig,label:"Classes do Elementor no HTML",strong:true},
{re:/\belementorFrontendConfig\b/g,label:"Configuração JavaScript do Elementor",strong:true}
]},
{id:"shopify",name:"Shopify",type:"Comércio eletrônico",checks:[
{re:/cdn\.shopify\.com/ig,label:"Arquivos servidos pelo CDN Shopify",strong:true},
{re:/\/cdn\/shop\/(?:files|products)\//ig,label:"Estrutura de arquivos de loja Shopify",strong:true},
{re:/\b(?:Shopify\.theme|window\.Shopify)\b/g,label:"Objeto JavaScript do Shopify",strong:true},
{re:/\.myshopify\.com/ig,label:"Domínio técnico myshopify.com",strong:true}
]},
{id:"webflow",name:"Webflow",type:"Construtor visual",checks:[
{re:/data-wf-(?:site|page)=/ig,label:"Atributos de publicação do Webflow",strong:true},
{re:/\/webflow\.(?:js|css)(?:[?"'])/ig,label:"Arquivos Webflow",strong:true},
{re:/uploads-ssl\.webflow\.com|assets\.website-files\.com/ig,label:"Hospedagem de recursos do Webflow",strong:false}
]},
{id:"framer",name:"Framer",type:"Construtor visual",checks:[
{re:/framerusercontent\.com/ig,label:"Recursos Framer",strong:true},
{re:/data-framer-(?:name|component-type|appear-id)/ig,label:"Atributos do Framer",strong:true},
{re:/framer\.com\/m\//ig,label:"Módulos publicados pelo Framer",strong:true}
]},
{id:"wix",name:"Wix",type:"Construtor visual",checks:[
{re:/static\.wixstatic\.com/ig,label:"CDN de imagens Wix",strong:true},
{re:/\bwixsite\.com\b/ig,label:"Domínio Wix",strong:true},
{re:/\bwix-image\b/ig,label:"Componente visual Wix",strong:true}
]},
{id:"nextjs",name:"Next.js",type:"Framework web",checks:[
{re:/id=["']__NEXT_DATA__["']/ig,label:"Dados de hidratação do Next.js",strong:true},
{re:/\/_next\/static\//ig,label:"Arquivos de build do Next.js",strong:true},
{re:/\bself\.__next_f\b/g,label:"Marcação de streaming Next.js",strong:true}
]},
{id:"nuxt",name:"Nuxt",type:"Framework web",checks:[
{re:/\b__NUXT__\b/g,label:"Estrutura de dados Nuxt",strong:true},
{re:/\/_nuxt\//ig,label:"Arquivos estáticos Nuxt",strong:true}
]},
{id:"astro",name:"Astro",type:"Framework web",checks:[
{re:/<astro-island\b/ig,label:"Componentes Astro Island",strong:true},
{re:/\bdata-astro-cid=/ig,label:"Atributos gerados pelo Astro",strong:true}
]}
];
function analyze(content){
const results=[];
for(const rule of RULES){
let found=[];
for(const sig of rule.checks){
sig.re.lastIndex=0;
if(sig.re.test(content))found.push(sig);
}
if(found.length)results.push({rule,found,confidence:found.some(s=>s.strong)?"forte":"limitado"});
}
return results.sort((a,b)=>b.found.length-a.found.length);
}
function init(root){
const entry=root.querySelector("[data-technology-html]");
const file=root.querySelector("[data-technology-file]");
const trigger=root.querySelector("[data-technology-submit]");
const example=root.querySelector("[data-technology-demo]");
const clear=root.querySelector("[data-technology-clear]");
const summary=root.querySelector("[data-technology-status]");
const results=root.querySelector("[data-technology-results]");
const list=root.querySelector("[data-technology-list]");
if(!entry||!file||!trigger||!example||!clear||!summary||!results||!list)return;
function status(message,error){summary.textContent=message;summary.dataset.error=error?"true":"false";}
function run(){
const raw=entry.value.trim();
if(!raw){results.hidden=true;status("Cole o HTML da página ou escolha um arquivo .html para analisar.",true);return;}
if(raw.length>1250000){results.hidden=true;status("O código excede 1,25 milhão de caracteres. Cole somente o HTML principal ou escolha um arquivo menor.",true);return;}
const matches=analyze(raw);
list.replaceChildren();
results.hidden=false;
if(!matches.length){status("Nenhuma assinatura reconhecida neste HTML.",false);
const p=document.createElement("p");p.textContent="Isso não significa que o site não usa WordPress ou outro framework. Alguns recursos são carregados depois, outros ficam atrás de CDN ou são ocultados. Use Wappalyzer e a guia Network para complementar.";p.className="bmd-tech-empty";list.appendChild(p);return;}
status(matches.length+" tecnologia"+(matches.length>1?"s":"")+" com sinais encontrados. Uma página pode combinar CMS, editor e framework.",false);
matches.forEach((m)=>{
const article=document.createElement("article");article.className="bmd-tech-result";
const header=document.createElement("div");header.className="bmd-tech-result-head";
const title=document.createElement("strong");title.textContent=m.rule.name;
const label=document.createElement("span");label.className="bmd-tech-type";label.textContent=m.rule.type+" • "+(m.confidence==="forte"?"indício consistente":"sinal limitado");header.append(title,label);
const ul=document.createElement("ul");m.found.forEach((c)=>{const li=document.createElement("li");li.textContent=c.label;ul.appendChild(li);});
article.append(header,ul);list.appendChild(article);
});
}
trigger.addEventListener("click",run);
example.addEventListener("click",()=>{
entry.value='<!doctype html><html><head><link rel="stylesheet" href="https://exemplo.test/wp-content/themes/tema/style.css"><script src="https://exemplo.test/wp-includes/js/jquery/jquery.min.js"></script></head><body><main class="elementor elementor-123"><div class="elementor-widget elementor-widget-heading">Exemplo</div></main></body></html>';
file.value="";status("Exemplo de código WordPress com Elementor carregado. Clique em Analisar.",false);results.hidden=true;});
clear.addEventListener("click",()=>{entry.value="";file.value="";list.replaceChildren();results.hidden=true;status("O código é processado somente no seu navegador.",false);});
file.addEventListener("change",()=>{
const f=file.files&&file.files[0];if(!f)return;
if(f.size>1200000){status("Arquivo muito grande. O limite para análise local é 1,2 MB.",true);return;}
const reader=new FileReader();reader.onload=()=>{entry.value=String(reader.result||"");results.hidden=true;status("Arquivo carregado localmente. Clique em Analisar.",false);};
reader.onerror=()=>status("O arquivo não pôde ser lido. Cole o código na caixa de texto.",true);reader.readAsText(f,"UTF-8");
});
}
function mount(){document.querySelectorAll(".bmd-technology-detector").forEach(init);}
if(document.readyState==="loading")document.addEventListener("DOMContentLoaded",mount);else mount();
})();