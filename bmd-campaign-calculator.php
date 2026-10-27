<?php
/**
 * BMD Campaign Calculator (ROAS, CAC & campaign profit).
 * Loaded by BMD Performance Core. No libraries, external APIs or storage.
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

add_shortcode( 'bmd_campaign_calculator', function () {
    return <<<'BMD_CALC_HTML'
<div id="bmd-calc-2026" class="bmd-calc">
<style>
.bmd-calc{--bmd-ink:#162235;--bmd-muted:#56657a;--bmd-orange:#cb4b22;--bmd-line:#dce3ec;--bmd-soft:#f7f9fc;color:var(--bmd-ink);margin:22px 0 30px;max-width:1100px;font-family:inherit;font-size:16px}
.bmd-calc *{box-sizing:border-box}
.bmd-calc .bmd-calc-panel{border:1px solid var(--bmd-line);border-radius:14px;padding:22px;margin:0 0 18px;background:#fff}
.bmd-calc h2{font-size:1.28em;margin:0 0 8px;color:var(--bmd-ink);line-height:1.35}
.bmd-calc .bmd-calc-note{margin:0 0 18px;color:var(--bmd-muted);line-height:1.55;font-size:.92em}
.bmd-calc .bmd-calc-fields{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:15px}
.bmd-calc label{display:block;font-size:.91em;font-weight:650;margin:0 0 5px;color:var(--bmd-ink)}
.bmd-calc input{width:100%;display:block;border:1px solid #bac7d6;background:#fff;border-radius:8px;min-height:46px;font-size:16px;line-height:1.4;padding:10px 12px;color:var(--bmd-ink);font-variant-numeric:tabular-nums}
.bmd-calc input:focus-visible,.bmd-calc button:focus-visible{outline:3px solid #f0c2b1;outline-offset:2px}
.bmd-calc .bmd-help{font-size:12px;color:#627288;display:block;margin-top:4px;line-height:1.5}
.bmd-calc .bmd-calc-result-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
.bmd-calc .bmd-metric{border:1px solid var(--bmd-line);border-radius:11px;background:#f8fafc;padding:15px 14px;min-height:98px}
.bmd-calc .bmd-metric-label{display:block;color:var(--bmd-muted);font-size:13px;margin-bottom:7px;font-weight:600}
.bmd-calc .bmd-metric-val{display:block;font-weight:750;font-size:clamp(21px,2.5vw,29px);letter-spacing:-.6px;line-height:1.28;overflow-wrap:anywhere;font-variant-numeric:tabular-nums;color:#162235}
.bmd-calc .bmd-metric-val.bmd-loss{color:#bc352c}
.bmd-calc .bmd-metric-val.bmd-profit{color:#177353}
.bmd-calc .bmd-calc-detail{border-top:1px solid var(--bmd-line);margin-top:17px;padding-top:14px}
.bmd-calc .bmd-calc-detail-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px 16px}
.bmd-calc .bmd-calc-detail-grid p{margin:0;font-size:.91em;line-height:1.65}
.bmd-calc .bmd-calc-scenarios{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
.bmd-calc .bmd-scenario{border:1px solid var(--bmd-line);border-radius:9px;padding:14px;background:var(--bmd-soft)}
.bmd-calc .bmd-scenario strong{display:block;margin-bottom:8px;font-size:.92em}
.bmd-calc .bmd-scenario output{font-weight:750;font-size:1.28em;line-height:1.25;display:block;overflow-wrap:anywhere;font-variant-numeric:tabular-nums}
.bmd-calc .bmd-scenario span{display:block;font-size:.85em;color:var(--bmd-muted);margin-top:5px}
.bmd-calc .bmd-calc-btn{border:0;border-radius:8px;background:var(--bmd-orange);color:#fff;font-weight:700;cursor:pointer;padding:10px 16px;min-height:44px}
.bmd-calc .bmd-calc-btn:hover{background:#a83b1a}
.bmd-calc .bmd-calc-btn.bmd-secondary{background:#edf0f5;color:#20324b}
.bmd-calc .bmd-actions{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-top:15px}
.bmd-calc #bmd-calc-message{font-size:.86em;color:#43647e}
.bmd-calc .bmd-calc-alert{margin:0 0 12px;padding:10px 12px;background:#fff4ed;border:1px solid #f3c5a6;border-radius:8px;font-size:.9em}
.bmd-calc .bmd-calc-alert[hidden]{display:none}
.bmd-calc .bmd-neutral{color:var(--bmd-muted);font-size:.88em;margin-top:11px;line-height:1.6}
@media(max-width:780px){.bmd-calc .bmd-calc-panel{padding:17px}.bmd-calc .bmd-calc-result-grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
@media(max-width:520px){.bmd-calc .bmd-calc-fields,.bmd-calc .bmd-calc-scenarios,.bmd-calc .bmd-calc-detail-grid{grid-template-columns:1fr}.bmd-calc .bmd-metric{min-height:87px;padding:12px}.bmd-calc .bmd-metric-val{font-size:22px}}
@media print{.bmd-calc .bmd-actions{display:none}}
</style>
<div class="bmd-calc-panel">
<h2>1. Informe os números da campanha</h2>
<p class="bmd-calc-note">Use valores totais do mesmo período. Experimente os valores de exemplo ou substitua pelos números reais. Cálculo automático, sem cadastro.</p>
<div id="bmd-calc-alert" class="bmd-calc-alert" role="alert" hidden></div>
<form id="bmd-calc-form" autocomplete="off" novalidate>
<div class="bmd-calc-fields">
<div><label for="bmd-invest">Investimento em anúncios (R$)</label><input id="bmd-invest" name="adspend" type="text" inputmode="decimal" value="1000" aria-describedby="bmd-invest-help"><small id="bmd-invest-help" class="bmd-help">Somente mídia paga: Meta Ads, Google Ads etc.</small></div>
<div><label for="bmd-revenue">Receita atribuída à campanha (R$)</label><input id="bmd-revenue" name="revenue" type="text" inputmode="decimal" value="4000"><small class="bmd-help">Faturamento, não lucro.</small></div>
<div><label for="bmd-orders">Pedidos / vendas</label><input id="bmd-orders" name="orders" type="text" inputmode="numeric" value="40"><small class="bmd-help">Vendas geradas no período analisado.</small></div>
<div><label for="bmd-customers">Clientes novos adquiridos</label><input id="bmd-customers" name="customers" type="text" inputmode="numeric" value="30"><small class="bmd-help">Use somente novos clientes para o CAC.</small></div>
<div><label for="bmd-cogs">Custo dos produtos ou serviços vendidos (R$)</label><input id="bmd-cogs" name="cogs" type="text" inputmode="decimal" value="1400"><small class="bmd-help">Total de produção, entrega ou prestação do serviço.</small></div>
<div><label for="bmd-acq">Outros custos de aquisição (R$)</label><input id="bmd-acq" name="acq" type="text" inputmode="decimal" value="100"><small class="bmd-help">Comissões de captação, agência e outros custos atribuídos à aquisição.</small></div>
<div><label for="bmd-oper">Outras despesas operacionais (R$)</label><input id="bmd-oper" name="oper" type="text" inputmode="decimal" value="300"><small class="bmd-help">Despesas da operação atribuídas à campanha, sem duplicar custos anteriores.</small></div>
<div><label for="bmd-fee">Taxa variável de pagamento (%)</label><input id="bmd-fee" name="fee" type="text" inputmode="decimal" value="3,9"><small class="bmd-help">Percentual sobre a receita recebida.</small></div>
<div><label for="bmd-fixed">Taxa fixa por pedido (R$)</label><input id="bmd-fixed" name="fixed" type="text" inputmode="decimal" value="0"><small class="bmd-help">Deixe 0 se não existir.</small></div>
</div>
</form>
</div>
<div class="bmd-calc-panel">
<h2>2. Veja seu resultado</h2>
<p class="bmd-calc-note">ROAS não é lucro. CAC considera clientes novos; o resultado líquido estimado considera os custos informados.</p>
<div class="bmd-calc-result-grid" aria-live="polite" aria-atomic="false">
<div class="bmd-metric"><span class="bmd-metric-label">ROAS</span><output class="bmd-metric-val" id="bmd-out-roas">—</output></div>
<div class="bmd-metric"><span class="bmd-metric-label">CAC (por novo cliente)</span><output class="bmd-metric-val" id="bmd-out-cac">—</output></div>
<div class="bmd-metric"><span class="bmd-metric-label">Resultado líquido estimado</span><output class="bmd-metric-val" id="bmd-out-profit">—</output></div>
<div class="bmd-metric"><span class="bmd-metric-label">Margem líquida</span><output class="bmd-metric-val" id="bmd-out-margin">—</output></div>
<div class="bmd-metric"><span class="bmd-metric-label">CPA de mídia (por venda)</span><output class="bmd-metric-val" id="bmd-out-cpa">—</output></div>
<div class="bmd-metric"><span class="bmd-metric-label">Ticket médio</span><output class="bmd-metric-val" id="bmd-out-aov">—</output></div>
</div>
<div class="bmd-calc-detail">
<div class="bmd-calc-detail-grid">
<p><strong>Custo total informado:</strong> <output id="bmd-out-total">—</output></p>
<p><strong>Taxas de pagamento:</strong> <output id="bmd-out-fees">—</output></p>
<p><strong>Vendas para empatar:</strong> <output id="bmd-out-breakeven">—</output></p>
<p><strong>Receita no ponto de equilíbrio:</strong> <output id="bmd-out-breakrev">—</output></p>
</div>
<p class="bmd-neutral" id="bmd-calc-breaknote">O ponto de equilíbrio só é calculado quando há vendas e contribuição positiva por venda; pressupõe ticket e custos variáveis unitários constantes.</p>
</div>
</div>
<div class="bmd-calc-panel">
<h2>3. Compare cenários</h2>
<p class="bmd-calc-note">Receita e número de pedidos 20% menores ou maiores, mantendo o investimento, os custos fixos e o ticket/custo variável médio. São simulações matemáticas, não previsões.</p>
<div class="bmd-calc-scenarios" aria-live="polite">
<div class="bmd-scenario"><strong>Conservador (−20%)</strong><output id="bmd-scenario-low">—</output><span id="bmd-scenario-low-roas">ROAS: —</span></div>
<div class="bmd-scenario"><strong>Atual (base)</strong><output id="bmd-scenario-base">—</output><span id="bmd-scenario-base-roas">ROAS: —</span></div>
<div class="bmd-scenario"><strong>Otimista (+20%)</strong><output id="bmd-scenario-high">—</output><span id="bmd-scenario-high-roas">ROAS: —</span></div>
</div>
<div class="bmd-actions"><button type="button" class="bmd-calc-btn" id="bmd-calc-copy">Copiar resumo</button><button type="button" class="bmd-calc-btn bmd-secondary" id="bmd-calc-reset">Restaurar exemplo</button><span id="bmd-calc-message" role="status" aria-live="polite"></span></div>
</div>
<p class="bmd-neutral">Privacidade: as informações são calculadas no seu navegador; não são enviadas ao nosso servidor. Não inclui tributos sobre a renda, impostos variáveis não informados, devoluções, efeitos de atribuição ou custos não preenchidos. ROAS alto não garante lucro.</p>
<script>
(function(){
'use strict';
var root=document.getElementById('bmd-calc-2026');if(!root||root.dataset.initialized==='1')return;root.dataset.initialized='1';
var fields=['invest','revenue','orders','customers','cogs','acq','oper','fee','fixed'];
var defaults={invest:'1000',revenue:'4000',orders:'40',customers:'30',cogs:'1400',acq:'100',oper:'300',fee:'3,9',fixed:'0'};
var formatter=new Intl.NumberFormat('pt-BR',{style:'currency',currency:'BRL',maximumFractionDigits:2});
var decimal=new Intl.NumberFormat('pt-BR',{maximumFractionDigits:2,minimumFractionDigits:2});
var percent=new Intl.NumberFormat('pt-BR',{style:'percent',minimumFractionDigits:1,maximumFractionDigits:1});
function el(id){return root.querySelector('#bmd-'+id);}
function show(id,value){var e=el(id);if(e)e.textContent=value;}
function parseBR(s){
var v=String(s).trim().replace(/\s|\u00a0|R\$|%/gi,'');
if(!v)return NaN;
if(!/^[+]?[0-9.,]+$/.test(v))return NaN;
v=v.replace(/^\+/,'');
var comma=v.lastIndexOf(','),dot=v.lastIndexOf('.');
if(comma>=0&&dot>=0){if(comma>dot)v=v.replace(/\./g,'').replace(',','.');else v=v.replace(/,/g,'');}
else if(comma>=0){if((v.match(/,/g)||[]).length>1)return NaN;v=v.replace(',','.');}
else if(dot>=0){if((v.match(/\./g)||[]).length>1 || /^\d{1,3}(?:\.\d{3})+$/.test(v))v=v.replace(/\./g,'');}
var n=Number(v);return Number.isFinite(n)?n:NaN;
}
function fmt(v){return Number.isFinite(v)?formatter.format(v):'—';}
function mult(v){return Number.isFinite(v)?decimal.format(v)+'×':'—';}
function get(){
var d={};for(var i=0;i<fields.length;i++){var k=fields[i];d[k]=parseBR(el(k).value);if(!Number.isFinite(d[k])||d[k]<0||d[k]>1e12)return {error:'Revise o campo "'+el(k).previousElementSibling.textContent+'". Use números positivos ou zero.'};}
if(!Number.isInteger(d.orders)||!Number.isInteger(d.customers))return{error:'Pedidos e clientes novos devem ser números inteiros.'};
if(d.fee>100)return{error:'A taxa variável de pagamento não pode ultrapassar 100%.'};
if(d.customers>d.orders&&d.orders>0)return{error:'Clientes novos não podem superar o número de pedidos informado.'};
return {data:d};
}
function scenario(d,factor){
var rev=d.revenue*factor,orders=d.orders*factor,goods=d.cogs*factor;
var fees=rev*d.fee/100 + orders*d.fixed;
var profit=rev-(d.invest+d.acq+d.oper+goods+fees);
return{rev:rev,profit:profit,roas:d.invest>0?rev/d.invest:NaN};
}
function render(){
var reading=get(),warn=el('calc-alert');var v;
if(reading.error){warn.hidden=false;warn.textContent=reading.error;root.querySelectorAll('output').forEach(function(o){o.textContent='—';});return;}
warn.hidden=true;warn.textContent='';
var d=reading.data,fees=d.revenue*d.fee/100+d.orders*d.fixed,total=d.invest+d.acq+d.oper+d.cogs+fees;
var profit=d.revenue-total,aov=d.orders>0?d.revenue/d.orders:NaN;
var roas=d.invest>0?d.revenue/d.invest:NaN,cac=d.customers>0?(d.invest+d.acq)/d.customers:NaN;
var cpa=d.orders>0?d.invest/d.orders:NaN;
show('out-roas',mult(roas));show('out-cac',fmt(cac));show('out-profit',fmt(profit));
show('out-margin',d.revenue>0?percent.format(profit/d.revenue):'—');
show('out-cpa',fmt(cpa));show('out-aov',fmt(aov));show('out-total',fmt(total));show('out-fees',fmt(fees));
el('out-profit').className='bmd-metric-val '+(profit>=0?'bmd-profit':'bmd-loss');
var contribution=d.orders>0?aov*(1-d.fee/100)-d.cogs/d.orders-d.fixed:NaN;
var fixedCosts=d.invest+d.acq+d.oper;
var breakOrders=contribution>0?Math.ceil(fixedCosts/contribution):NaN;
show('out-breakeven',Number.isFinite(breakOrders)?new Intl.NumberFormat('pt-BR').format(breakOrders)+' vendas':'Não calculável');
show('out-breakrev',fmt(breakOrders*aov));
['low','base','high'].forEach(function(key,i){var calc=scenario(d,[.8,1,1.2][i]);show('scenario-'+key,fmt(calc.profit));show('scenario-'+key+'-roas','ROAS: '+mult(calc.roas));el('scenario-'+key).style.color=calc.profit>=0?'#177353':'#bc352c';});
}
fields.forEach(function(k){el(k).addEventListener('input',function(){render();el('calc-message').textContent='';});});
el('calc-reset').addEventListener('click',function(){fields.forEach(function(k){el(k).value=defaults[k];});render();el('calc-message').textContent='Exemplo restaurado.';});
el('calc-copy').addEventListener('click',function(){
var z=get();if(z.error){el('calc-message').textContent='Corrija os campos antes de copiar.';return;}
var d=z.data,fees=d.revenue*d.fee/100+d.orders*d.fixed,profit=d.revenue-d.invest-d.acq-d.oper-d.cogs-fees;
var lines=['Resultado estimado da campanha','Investimento em mídia: '+fmt(d.invest),'Receita: '+fmt(d.revenue),
'ROAS: '+mult(d.invest>0?d.revenue/d.invest:NaN),'CAC: '+fmt(d.customers>0?(d.invest+d.acq)/d.customers:NaN),
'Lucro/prejuízo estimado: '+fmt(profit),'Margem líquida: '+(d.revenue>0?percent.format(profit/d.revenue):'—'),
'Calculado em: https://blogdemarketingdigital.com.br/calculadora-roas-cac-lucro/'];
var text=lines.join('\n'),status=el('calc-message');
function ok(){status.textContent='Resumo copiado!';}
function fallback(){var t=document.createElement('textarea');t.value=text;t.style.cssText='position:fixed;opacity:0';document.body.appendChild(t);t.select();var copied=false;try{copied=document.execCommand('copy')}catch(e){}t.remove();status.textContent=copied?'Resumo copiado!':'Seleção automática indisponível; copie os resultados acima.';}
if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(text).then(ok,fallback)}else fallback();
});
render();
})();
</script>
</div>
BMD_CALC_HTML;
} );

add_action( 'init', function () {
    if ( get_option( 'bmd_create_campaign_calculator_20261027', false ) ) { return; }
    $existing = get_page_by_path( 'calculadora-roas-cac-lucro', OBJECT, 'page' );
    if ( $existing ) {
        update_option( 'bmd_create_campaign_calculator_20261027', 'existing_page_' . (int) $existing->ID, false );
        return;
    }
    $content = <<<'BMD_CALC_PAGE'
<p>Seu anúncio teve ROAS alto, mas sobrou dinheiro no caixa? A <strong>calculadora de ROAS, CAC e lucro grátis</strong> ajuda a analisar campanhas de Google Ads, Meta Ads e outros canais considerando receita, mídia paga, custos dos produtos, aquisição, operação e taxas de pagamento. Não exige cadastro, planilha nem conexão com suas contas de anúncios.</p>
[bmd_campaign_calculator]
<h2>Como calcular o ROAS?</h2>
<p><strong>ROAS = receita atribuída aos anúncios ÷ investimento em anúncios.</strong> Por exemplo, R$ 4.000 em receita com R$ 1.000 investidos gera ROAS de 4,0x. Isso significa faturamento quatro vezes maior que a verba de anúncios, não lucro quatro vezes maior. ROAS não é a mesma coisa que ROI.</p>
<h2>Como calcular o CAC (custo de aquisição de clientes)?</h2>
<p><strong>CAC = custos de aquisição ÷ número de novos clientes.</strong> Aqui, os custos de aquisição incluem anúncios e outros gastos informados especificamente para adquirir clientes. Pedidos não são necessariamente clientes novos: quem compra de novo não deve ser tratado como novo cliente.</p>
<h2>Como descobrir o lucro de uma campanha?</h2>
<p><strong>Resultado estimado = receita − investimento em mídia − custos de aquisição adicionais − custos de produtos ou serviços − despesas operacionais atribuídas − taxas de pagamento.</strong> O resultado só é tão completo quanto os custos inseridos. Tributos e outras despesas não informados não são calculados automaticamente.</p>
<h2>Qual ROAS preciso para não ter prejuízo?</h2>
<p>Não existe um ROAS mínimo universal: ele depende da margem de contribuição, das taxas e dos demais custos. Nossa calculadora também estima o número de vendas para empatar, mantendo ticket e custos variáveis por venda iguais aos informados. Quando a margem de contribuição unitária não é positiva, não existe ponto de equilíbrio calculável nesse modelo.</p>
<h2>ROAS, CAC e CPA: qual a diferença?</h2>
<ul><li><strong>ROAS:</strong> receita atribuída dividida pelo investimento em anúncios.</li><li><strong>CAC:</strong> custo para conquistar um <em>novo cliente</em> (mídia e custos extras de aquisição).</li><li><strong>CPA de mídia:</strong> gasto em anúncios dividido por pedidos/vendas; nesta ferramenta, é custo por venda, não por lead.</li><li><strong>Lucro:</strong> receita menos os custos informados, inclusive taxas e custos de entrega ou prestação.</li><li><strong>Margem líquida estimada:</strong> resultado dividido pela receita, quando existe receita positiva.</li></ul>
<h2>Simulação de cenários para campanhas</h2>
<p>Os cenários conservador, base e otimista variam a receita e o volume em 20% para cima ou para baixo. O investimento em anúncios e os demais custos considerados fixos permanecem iguais. Custos variáveis, como taxa percentual e custo dos produtos por venda, acompanham o volume. Essa simplificação é útil para planejamento, mas não prevê o desempenho futuro de uma campanha.</p>
<h2>Perguntas frequentes</h2>
<h3>Esta calculadora serve para Google Ads e Meta Ads?</h3>
<p>Sim. Digite os valores de campanhas de qualquer canal, desde que receitas, clientes e custos se refiram ao mesmo período e critério de atribuição.</p>
<h3>ROAS 2 significa que uma campanha é lucrativa?</h3>
<p>Não necessariamente. O resultado depende da margem bruta, das taxas, do custo de aquisição e das outras despesas. É possível ter ROAS positivo e prejuízo.</p>
<h3>Posso calcular uma campanha que não teve vendas?</h3>
<p>Sim. Com receita e pedidos iguais a zero, o resultado considera os gastos preenchidos. Indicadores que precisariam dividir por zero aparecem como indisponíveis.</p>
<h3>Os valores são enviados a algum servidor?</h3>
<p>Não. Os cálculos são realizados no navegador do visitante, sem guardar os dados digitados em uma conta ou serviço de terceiros.</p>
<p>Para acompanhar a origem das suas campanhas, utilize também o <a href="/gerador-de-utm/">gerador de UTM gratuito</a>. Para aprofundar o planejamento, leia nosso guia sobre <a href="/quanto-custa-google-ads/">quanto custa anunciar no Google Ads</a>.</p>
BMD_CALC_PAGE;
    $id=wp_insert_post( array(
        'post_type'=>'page',
        'post_status'=>'publish',
        'post_title'=>'Calculadora de ROAS, CAC e Lucro Grátis para Google Ads e Meta Ads',
        'post_name'=>'calculadora-roas-cac-lucro',
        'post_content'=>wp_slash( $content ),
        'meta_input'=>array(
            'rank_math_title'=>'Calculadora de ROAS e CAC Grátis | Lucro de Campanhas',
            'rank_math_description'=>'Calcule ROAS, CAC, CPA, lucro, margem e ponto de equilíbrio de campanhas Meta Ads e Google Ads. Simulador gratuito com cenários e sem cadastro.',
            'rank_math_focus_keyword'=>'calculadora de roas',
        ),
    ), true );
    if ( ! is_wp_error( $id ) && $id ) {
        update_option( 'bmd_create_campaign_calculator_20261027', (int) $id, false );
    }
}, 30 );
