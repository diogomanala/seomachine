<?php
/**
 * BMD 28/10/2026 – improvements to verified editorial URLs only.
 * No bulk rewriting, external calls or new tracking libraries.
 * An original backup is kept for the only database post-content rewrite.
 */
if (!defined('ABSPATH')) { exit; }

add_shortcode('bmd_headline_workshop', function () {
    return <<<'BMD_HEADLINE'
<section class="bmd-hw" aria-label="Laboratório gratuito de headlines">
  <div class="bmd-hw-top"><span class="bmd-hw-tag">TESTE SEU TÍTULO · NO NAVEGADOR</span>
    <h2>Laboratório de headlines</h2>
    <p>Escreva uma versão, confira a clareza e compare alternativas. O contador é apenas uma referência editorial: não prevê cliques nem a exibição exata no Google.</p></div>
  <div class="bmd-hw-body"><label for="bmd-hw-input">Seu título</label>
    <textarea id="bmd-hw-input" rows="3" maxlength="240" placeholder="Ex.: Como calcular o ROAS de uma campanha sem usar planilhas">Como calcular o ROAS de uma campanha sem usar planilhas</textarea>
    <div class="bmd-hw-stats"><span><strong id="bmd-hw-chars">0</strong> caracteres</span><span><strong id="bmd-hw-words">0</strong> palavras</span></div>
    <div class="bmd-hw-preview"><span>PRÉVIA ILUSTRATIVA</span><strong id="bmd-hw-title"></strong><small>Seu título em uma visualização simplificada, não uma prévia oficial do Google.</small></div>
    <div class="bmd-hw-feedback" id="bmd-hw-feedback" role="status" aria-live="polite"></div>
    <button id="bmd-hw-copy" type="button">Copiar título</button>
  </div>
</section>
<style>
.bmd-hw{max-width:880px;margin:26px 0 34px;border:1px solid #e1e7ee;border-radius:18px;overflow:hidden;box-shadow:0 8px 25px rgba(20,34,55,.045);background:#fff;color:#18263a}
.bmd-hw *{box-sizing:border-box}
.bmd-hw-top{padding:26px 28px 20px;background:linear-gradient(135deg,#f9fbff,#fff5ee)}
.bmd-hw-tag{display:inline-block;font-size:11px;font-weight:800;letter-spacing:.1em;color:#b95532;margin-bottom:12px}
.bmd-hw-top h2{margin:0 0 10px!important;font-size:clamp(25px,4vw,34px)!important;line-height:1.18!important;letter-spacing:-.04em}
.bmd-hw-top p{margin:0!important;color:#516175;font-size:14px;line-height:1.7}
.bmd-hw-body{padding:24px 28px 28px}
.bmd-hw-body>label{display:block;font-weight:750;font-size:14px;margin-bottom:10px}
.bmd-hw textarea{width:100%!important;max-width:100%;display:block!important;resize:vertical;border:1px solid #cbd4de!important;border-radius:10px!important;padding:13px!important;background:white!important;color:#16283e!important;font-size:17px!important;line-height:1.5!important;min-height:95px}
.bmd-hw textarea:focus{outline:2px solid #df9c7c;outline-offset:2px}
.bmd-hw-stats{display:flex;gap:16px;flex-wrap:wrap;margin:10px 0 20px;color:#617084;font-size:12px;font-variant-numeric:tabular-nums}
.bmd-hw-preview{display:flex;flex-direction:column;gap:7px;padding:18px;background:#f7f9fc;border:1px solid #e7ecf2;border-radius:11px;overflow-wrap:anywhere}
.bmd-hw-preview span{font-size:10px;letter-spacing:.09em;font-weight:800;color:#6b7890}
.bmd-hw-preview strong{font-size:clamp(18px,3vw,22px);font-weight:700;line-height:1.35;color:#264a97}
.bmd-hw-preview small{font-size:12px;color:#6b7890}
.bmd-hw-feedback{font-size:13px;line-height:1.6;color:#465971;min-height:20px;margin:13px 0}
.bmd-hw button{border:0!important;border-radius:9px!important;background:#1b2b43!important;color:#fff!important;font-weight:750;min-height:44px;padding:10px 19px!important;cursor:pointer}
@media(max-width:520px){.bmd-hw-top,.bmd-hw-body{padding:20px}.bmd-hw-preview{padding:14px}}
</style>
<script>
(function(){
 var input=document.getElementById('bmd-hw-input'),title=document.getElementById('bmd-hw-title'),chars=document.getElementById('bmd-hw-chars'),words=document.getElementById('bmd-hw-words'),feedback=document.getElementById('bmd-hw-feedback'),copy=document.getElementById('bmd-hw-copy');
 if(!input||!title)return;
 function update(){var t=input.value.trim().replace(/\s+/g,' '),n=Array.from(t).length,w=t?t.split(/\s+/).length:0;chars.textContent=n;words.textContent=w;title.textContent=t||'Seu título aparece aqui';
 feedback.textContent= !t?'Digite uma proposta de título para começar.':n<25?'Tente deixar claro o assunto e o benefício concreto.':n>70?'É um título longo. Considere antecipar a informação principal.':'Agora confira se o título promete algo que o conteúdo realmente entrega.';
 }
 input.addEventListener('input',update);copy.addEventListener('click',function(){var t=input.value.trim();if(!t)return;if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(t).then(function(){copy.textContent='Copiado!';}).catch(function(){input.select();});}else{input.select();}});
 update();
})();
</script>
BMD_HEADLINE;
});

/** One guarded rewrite of an extremely thin 2022 tutorial; existing thumbnail remains. */
add_action('init', function() {
    $key='bmd_headline_guide_oct28_v1';
    if (get_option($key, false)) { return; }
    $slug='ferramenta-p-criar-e-analisar-as-melhores-headlines-titulos-100-gratis';
    $post=get_page_by_path($slug,OBJECT,'post');
    if (!$post || $post->post_status !== 'publish') { return; }
    $original=(string)$post->post_content;
    if (strlen($original)>8000 || strpos($original,'du2YYp7elbQ')===false ||
        strpos($original,'trends.taboola.com/titles')===false) {
        update_option($key,'skipped-original-mismatch',false);
        return;
    }
    $backup='_bmd_headline_original_oct28';
    if (!add_post_meta($post->ID,$backup,$original,true)) { return; }
    $updated=<<<'BMD_ARTICLE'
<p>Uma boa headline não precisa gritar, inventar números ou prometer resultados garantidos. Ela precisa mostrar <strong>para quem é o conteúdo, o que a pessoa vai encontrar e por que vale abrir</strong>. Neste guia, você vai escrever títulos para artigos, anúncios e Reels, usar uma ferramenta gratuita de revisão e comparar exemplos que podem ser adaptados ao seu negócio.</p>
<p><strong>Atualizado em outubro de 2026.</strong> O tutorial original foi reorganizado para incluir exemplos práticos e um laboratório de títulos que funciona no próprio navegador. Nenhum exemplo abaixo garante aumento de CTR: o resultado depende de público, oferta, imagem, canal e contexto.</p>

[bmd_headline_workshop]

<h2>Como criar uma headline que desperta interesse?</h2>
<p>Antes de escolher uma frase de impacto, responda a três perguntas: <strong>quem você quer alcançar?</strong>, <strong>qual problema essa pessoa tem?</strong> e <strong>que resposta concreta o conteúdo entrega?</strong> Quando falta uma delas, até uma frase bonita pode atrair pessoas erradas.</p>
<ol>
<li><strong>Comece pelo assunto:</strong> coloque a palavra ou problema central no início, especialmente em títulos de busca.</li>
<li><strong>Dê contexto:</strong> diga para quem serve, em qual situação ou qual resultado será explicado.</li>
<li><strong>Seja específico:</strong> prefira “como calcular o ROAS” a “o segredo que ninguém conta”.</li>
<li><strong>Não invente prova social:</strong> números de vendas, percentuais e depoimentos só entram se houver fonte verificável.</li>
<li><strong>Teste versões:</strong> compare duas ou três opções com público e criativo equivalentes, observando não só cliques, mas também engajamento e conversões.</li>
</ol>
<h2>15 exemplos de headlines para adaptar ao seu conteúdo</h2>
<p>Os exemplos são modelos editoriais, não títulos “comprovadamente vencedores”. Substitua os detalhes entre colchetes por informações verdadeiras.</p>
<h3>Para artigos e buscas no Google</h3>
<ol>
<li>Como calcular o ROAS de uma campanha: exemplo com números</li>
<li>Gerador de UTM: como identificar a origem das visitas</li>
<li>Quanto custa anunciar no Google Ads? O que entra na conta</li>
<li>[Ferramenta A] ou [Ferramenta B]: diferenças que importam para [perfil]</li>
<li>Como organizar [processo] em cinco etapas</li>
</ol>
<h3>Para anúncios e páginas de vendas</h3>
<ol start="6">
<li>Está perdendo tempo com [tarefa]? Veja uma alternativa prática</li>
<li>Conheça [recurso] e descubra quando ele faz sentido</li>
<li>De [situação inicial] a [resultado verificável]: veja o processo</li>
<li>O que está incluído em [produto] — e o que não está</li>
<li>[Benefício específico] para [público] sem [obstáculo legítimo]</li>
</ol>
<h3>Para Reels, carrosséis e newsletters</h3>
<ol start="11">
<li>O erro que faz sua campanha parecer lucrativa quando não é</li>
<li>Antes de publicar [conteúdo], confira estes três pontos</li>
<li>Você sabe de onde vêm os acessos do seu Instagram?</li>
<li>Testei [método real]: veja os passos e as limitações</li>
<li>O que eu mudaria hoje em [processo ou estratégia]</li>
</ol>
<h2>O que observar ao revisar uma headline?</h2>
<p><strong>Clareza:</strong> alguém que não conhece a marca entende o tema em poucos segundos? <strong>Coerência:</strong> a página realmente entrega o que o título sugere? <strong>Diferenciação:</strong> existe um detalhe concreto que a destaca de resultados semelhantes? <strong>Tom:</strong> a promessa combina com a marca e com o estágio de compra do público?</p>
<p>Para pesquisas no Google, use a intenção da busca como ponto de partida. Para redes sociais, experimente uma abertura mais narrativa, mas entregue a resposta rapidamente no conteúdo. Não existe uma contagem mágica de caracteres: os dispositivos e os formatos exibem títulos de maneira diferente.</p>
<h2>Como medir se o título ficou melhor?</h2>
<p>Compare a taxa de cliques com outras publicações do mesmo canal e período. Em campanhas pagas, acompanhe também custo por resultado, conversões e qualidade das visitas. Um título que atrai muitos cliques, mas gera visitas sem interesse, pode piorar o desempenho.</p>
<p>Se você anuncia, use nossa <a href="/calculadora-roas-cac-lucro/">calculadora gratuita de ROAS, CAC e lucro</a> para avaliar a campanha além do CTR. Para separar links de diferentes criativos, monte uma URL no <a href="/gerador-de-utm/">gerador de UTM</a>.</p>
<h2>Vídeo original: criação de headlines</h2>
<p>Este é o vídeo publicado originalmente com o artigo. Ele cita uma ferramenta externa da Taboola, cuja disponibilidade e funcionamento podem ter mudado desde a gravação.</p>
<div style="width:100%;aspect-ratio:16/9;max-width:880px"><iframe title="Tutorial original sobre headlines" src="https://www.youtube-nocookie.com/embed/du2YYp7elbQ" loading="lazy" allow="accelerometer; encrypted-media; gyroscope; picture-in-picture" allowfullscreen style="width:100%;height:100%;border:0;border-radius:12px"></iframe></div>
<p>Também vale conferir nosso <a href="/headlines-de-alta-conversao-para-seus-anuncios-e-paginas-de-vendas/">arquivo de modelos de headlines</a>, adaptando os exemplos com informações verdadeiras e evitando promessas absolutas.</p>
BMD_ARTICLE;
    $new_title='Como Criar Headlines Melhores: Exemplos e Ferramenta Grátis';
    $result=wp_update_post(array('ID'=>$post->ID,'post_content'=>$updated,'post_title'=>$new_title),true);
    if (is_wp_error($result) || !$result) {
        delete_post_meta($post->ID,$backup);
        return;
    }
    update_post_meta($post->ID,'rank_math_title','Como Criar Headlines: 15 Exemplos e Ferramenta Grátis | BMD');
    update_post_meta($post->ID,'rank_math_description','Aprenda a criar headlines para anúncios, artigos e Reels com 15 exemplos, checklist e laboratório gratuito para revisar seu título no navegador.');
    update_option($key,'applied',false);
    clean_post_cache($post->ID);
},29);

/** Honest, useful simulator: fee percentage and fixed fee are supplied by the visitor. */
add_shortcode('bmd_manual_fee_simulator', function(){
    return <<<'BMD_FEE'
<section class="bmd-fee" aria-label="Simulador de taxas de cartão">
<div class="bmd-fee-intro"><span class="bmd-fee-eyebrow">SIMULAÇÃO GRATUITA · SEM CADASTRO</span><h2>Quanto sobra depois das taxas do cartão?</h2>
<p>Digite os valores e as taxas do seu contrato para calcular quanto você receberia em vendas com cartão. Funciona com Mercado Pago e outros intermediadores, mas <strong>não consulta tarifas oficiais ou valores em tempo real</strong>.</p></div>
<form id="bmd-fee-form" class="bmd-fee-form">
<div class="bmd-fee-fields">
<div><label for="bmd-fee-price">Valor de cada venda (R$)</label><input id="bmd-fee-price" type="number" min="0.01" max="10000000" step="0.01" value="100" required></div>
<div><label for="bmd-fee-count">Quantidade de vendas</label><input id="bmd-fee-count" type="number" min="1" max="10000" step="1" value="1" required></div>
<div><label for="bmd-fee-percent">Taxa percentual (%)</label><input id="bmd-fee-percent" type="number" min="0" max="100" step="0.01" value="4" required></div>
<div><label for="bmd-fee-fixed">Taxa fixa por venda (R$)</label><input id="bmd-fee-fixed" type="number" min="0" max="100000" step="0.01" value="0" required></div>
</div>
<p class="bmd-fee-note">Os números preenchidos são apenas exemplos. Consulte sua tabela contratual de débito, crédito, parcelas e prazo de recebimento.</p>
<button type="submit">Calcular valor líquido</button>
</form>
<div id="bmd-fee-error" role="alert" hidden></div>
<div id="bmd-fee-results" class="bmd-fee-results" aria-live="polite">
<div><span>Total vendido</span><strong id="bmd-fee-gross">—</strong></div>
<div><span>Taxas estimadas</span><strong id="bmd-fee-total">—</strong></div>
<div class="bmd-fee-highlight"><span>Valor líquido estimado</span><strong id="bmd-fee-net">—</strong></div>
</div>
<p class="bmd-fee-explain">Fórmula: <strong>valor líquido = total vendido − (total vendido × percentual ÷ 100) − (taxa fixa × vendas)</strong>. Não inclui impostos, antecipação, juros, chargebacks ou custos do produto. Para calcular lucro de campanhas, use também a <a href="/calculadora-roas-cac-lucro/">calculadora de ROAS e CAC</a>.</p>
</section>
<section class="bmd-fee-guide">
<h2>Como calcular taxas do Mercado Pago ou de outra maquininha?</h2>
<ol><li>Consulte a taxa efetiva de seu contrato para a modalidade de pagamento e prazo de recebimento.</li><li>Digite o valor da venda e o número de transações.</li><li>Informe a porcentagem cobrada e, se existir, a tarifa fixa por transação.</li><li>Compare o valor líquido estimado com o extrato da plataforma. Despesas de antecipação e parcelamento precisam ser consideradas separadamente quando aplicáveis.</li></ol>
<h2>Por que o simulador não mostra uma taxa única?</h2><p>As condições variam por modalidade, prazo de recebimento, volume, antecipação, campanha e contrato. Para evitar resultados incorretos, esta ferramenta usa a taxa informada por você. Não representamos o Mercado Pago e não mostramos preços ou tarifas como se fossem cotações atualizadas.</p>
<h2>Taxa de cartão é a mesma coisa que lucro?</h2><p>Não. O valor líquido após a taxa de pagamento ainda pode precisar cobrir mercadoria, frete, mídia, equipe, impostos e outras despesas. Uma venda pode ter valor recebido positivo e ainda assim ser pouco lucrativa.</p></section>
<style>
.bmd-fee{font-family:inherit;max-width:960px;margin:10px auto 28px;border:1px solid #dfe5ed;border-radius:18px;background:#fff;overflow:hidden;color:#17253b;box-shadow:0 8px 32px rgba(20,35,56,.05)}
.bmd-fee *{box-sizing:border-box}
.bmd-fee-intro{padding:28px 28px 18px;background:linear-gradient(135deg,#fff9f5,#fafcff)}
.bmd-fee-eyebrow{font-size:11px;color:#bc5a34;font-weight:800;letter-spacing:.1em}
.bmd-fee h2{font-size:clamp(26px,4vw,36px)!important;letter-spacing:-.04em;line-height:1.17!important;margin:12px 0!important;color:#17253b}
.bmd-fee-intro p{font-size:15px;color:#506177;line-height:1.65;margin:0!important}
.bmd-fee-form{padding:8px 28px 24px}
.bmd-fee-fields{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px;margin-bottom:12px}
.bmd-fee label{font-size:13px;font-weight:700;display:block;margin:0 0 8px}
.bmd-fee input{width:100%;min-width:0;min-height:48px;border:1px solid #bac7d8;border-radius:10px;background:#fff;padding:11px;font-size:16px;color:#17253b}
.bmd-fee input:focus{outline:2px solid #d58f70;outline-offset:2px}
.bmd-fee-note{font-size:12px;color:#65758a;line-height:1.6;margin:8px 0 16px}
.bmd-fee button{border:0!important;background:#1a2b44!important;color:white!important;min-height:48px;border-radius:10px!important;padding:10px 18px!important;font-weight:750;cursor:pointer}
.bmd-fee-results{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;padding:0 28px 20px}
.bmd-fee-results>div{padding:17px;border:1px solid #e2e8f0;border-radius:12px;background:#f8fafc}
.bmd-fee-results span{display:block;font-size:12px;color:#627288;margin-bottom:8px}
.bmd-fee-results strong{display:block;font-size:clamp(18px,2.6vw,25px);font-variant-numeric:tabular-nums;overflow-wrap:anywhere}
.bmd-fee-results .bmd-fee-highlight{background:#edf8f2;border-color:#cfe6d8}
.bmd-fee-explain{padding:18px 28px;margin:0!important;border-top:1px solid #e8edf3;background:#f8fafc;font-size:13px;color:#5b6b80;line-height:1.7}
.bmd-fee-guide{max-width:820px;margin:25px 0 40px}
.bmd-fee-guide h2{font-size:clamp(23px,3vw,28px);margin:30px 0 14px}
.bmd-fee-guide li{margin-bottom:8px}
#bmd-fee-error{margin:0 28px 14px;padding:11px;border:1px solid #d9b8aa;background:#fff4ed;border-radius:8px;color:#91452a}
@media(max-width:650px){.bmd-fee-fields,.bmd-fee-results{grid-template-columns:1fr}.bmd-fee-intro,.bmd-fee-form{padding:20px}.bmd-fee-results{padding:0 20px 20px}.bmd-fee-explain{padding:18px 20px}}
</style>
<script>
(function(){
var form=document.getElementById('bmd-fee-form'),err=document.getElementById('bmd-fee-error');if(!form)return;
var fmt=new Intl.NumberFormat('pt-BR',{style:'currency',currency:'BRL'});
function compute(){
var p=Number(document.getElementById('bmd-fee-price').value),n=Number(document.getElementById('bmd-fee-count').value),
r=Number(document.getElementById('bmd-fee-percent').value),f=Number(document.getElementById('bmd-fee-fixed').value);
if(![p,n,r,f].every(Number.isFinite)||p<=0||n<1||n>10000||n!==Math.floor(n)||r<0||r>100||f<0){err.hidden=false;err.textContent='Confira os valores: venda positiva, quantidade inteira, taxa de 0 a 100% e taxa fixa não negativa.';return;}
err.hidden=true;var g=p*n,fees=g*r/100+f*n,net=g-fees;
document.getElementById('bmd-fee-gross').textContent=fmt.format(g);
document.getElementById('bmd-fee-total').textContent=fmt.format(fees);
document.getElementById('bmd-fee-net').textContent=fmt.format(net);
}
form.addEventListener('submit',function(e){e.preventDefault();compute();});compute();
})();
</script>
BMD_FEE;
});

/** Replace a visually empty, broken legacy calculator page at render time only. */
add_filter('the_content',function($content){
    if (is_admin() || !is_main_query() || !in_the_loop() || is_preview()) { return $content; }
    if (!is_page('simulador-de-vendas-no-cartao-calculo-de-taxas-mercado-pago')) { return $content; }
    return do_shortcode('[bmd_manual_fee_simulator]');
},55);

/** Correct stale / missing snippets without touching Rank Math global defaults. */
add_filter('rank_math/frontend/description',function($value){
    if (is_admin()) { return $value; }
    if (is_page('remover-metadados-ia')) {
        return 'Verifique e remova metadados EXIF, XMP, IPTC e C2PA de imagens no navegador. Limpeza de JPG, PNG e WebP, sem upload e sem cadastro.';
    }
    if (is_page('simulador-de-vendas-no-cartao-calculo-de-taxas-mercado-pago')) {
        return 'Simule taxas de cartão do Mercado Pago ou outras plataformas: informe o valor, percentual e tarifa fixa e calcule quanto recebe líquido.';
    }
    if (is_singular('post')) {
        $slug=(string)get_post_field('post_name',get_queried_object_id());
        if ($slug==='ferramenta-p-criar-e-analisar-as-melhores-headlines-titulos-100-gratis') {
            return 'Aprenda a criar headlines para anúncios, artigos e Reels com 15 exemplos, checklist e ferramenta gratuita para revisar títulos.';
        }
        if ($slug==='tendencias-de-marketing-digital-para-2023') {
            return 'Releia as tendências de marketing digital previstas para 2023: IA, vídeo, personalização e UX, com contexto editorial sobre o que mudou.';
        }
        if ($slug==='headlines-de-alta-conversao-para-seus-anuncios-e-paginas-de-vendas') {
            return 'Arquivo de 169 modelos de headlines para estudar estruturas de títulos. Adapte com dados reais e evite promessas sem comprovação.';
        }
    }
    return $value;
},55);

add_filter('rank_math/frontend/title',function($value){
    if (!is_admin() && is_page('simulador-de-vendas-no-cartao-calculo-de-taxas-mercado-pago')) {
        return 'Simulador de Taxas Mercado Pago: Calcule o Líquido | BMD';
    }
    if (!is_admin() && is_singular('post') &&
        get_post_field('post_name',get_queried_object_id())==='ferramenta-p-criar-e-analisar-as-melhores-headlines-titulos-100-gratis') {
        return 'Como Criar Headlines: 15 Exemplos e Ferramenta Grátis | BMD';
    }
    return $value;
},55);
add_filter('ocean_title',function($title){
    if (!is_admin() && is_page('simulador-de-vendas-no-cartao-calculo-de-taxas-mercado-pago')) {
        return 'Simulador de Taxas do Cartão: Mercado Pago e Outros';
    }
    return $title;
},40);

/** Fix a comparison teaser that still implied primary price verification. */
add_filter('the_content',function($content){
    if (is_admin() || !is_main_query() || !in_the_loop() || is_preview() || !is_singular('post')) { return $content; }
    $slug=(string)get_post_field('post_name',get_the_ID());
    if ($slug==='melhores-plataformas-de-automacao-de-marketing-em-2026-guia-completo-para-escolher-a-certa') {
        $content=str_replace('prós, contras e preços reais','prós, contras e preços divulgados publicamente',$content);
        $content=str_replace('\n<p><small><strong>Critérios editoriais','<p><small><strong>Critérios editoriais',$content);
    }
    if ($slug==='headlines-de-alta-conversao-para-seus-anuncios-e-paginas-de-vendas') {
        // The legacy boast had bold HTML around its figures, so a plain-text
        // replacement did not match. Replace only the exact audited paragraph.
        $old_claim='<p>Seleção de mais de <strong>169 títulos poderosos de alta conversão</strong> que já faturaram mais de <strong>1 milhão de reais em vendas</strong> esse ano.</p>';
        $new_intro='<p>Arquivo editorial com <strong>mais de 169 modelos de headlines</strong> para estudar estruturas de copywriting. As promessas e resultados dos exemplos históricos não foram verificados; adapte os modelos a informações comprováveis.</p>';
        $content=str_replace($old_claim,$new_intro,$content);
        $note='<p style="padding:17px 20px;border:1px solid #e3e9f0;border-radius:12px;background:#f8fafc"><strong>Como usar estes exemplos:</strong> trate-os como modelos históricos, não como provas de eficácia. Nosso <a href="'.esc_url(home_url('/ferramenta-p-criar-e-analisar-as-melhores-headlines-titulos-100-gratis/')).'">laboratório gratuito de headlines</a> ajuda a escrever versões claras sem inventar garantias de resultado.</p>';
        $content=$note.$content;
    }
    if ($slug==='tendencias-de-marketing-digital-para-2023') {
        $note='<div class="bmd-retro-note" style="padding:18px 20px;background:#f6f8fc;border:1px solid #dfe6f0;border-radius:12px;margin:0 0 24px"><strong>Arquivo editorial de 2023.</strong> Este texto registra previsões publicadas para aquele ano. Não é um guia atualizado de tendências para 2026. Para pesquisar soluções atuais, consulte também o <a href="'.esc_url(home_url('/melhores-plataformas-de-automacao-de-marketing-em-2026-guia-completo-para-escolher-a-certa/')).'">comparativo de plataformas de automação de 2026</a> e nosso <a href="'.esc_url(home_url('/gerador-de-utm/')).'">gerador de UTM</a>, útil para medir campanhas.</div>';
        $content=$note.$content;
    }
    return $content;
},47);

/** Only vetted thematic links, each on its specific article; avoid repeats. */
add_filter('the_content',function($content){
    if (is_admin() || !is_main_query() || !in_the_loop() || is_preview() || !is_singular('post')) { return $content; }
    $slug=(string)get_post_field('post_name',get_the_ID());
    $map=array(
        'como-criar-prompts-que-geram-ideias-virais-guia-do-criador-de-conteudo-em-2026'=>array('Como escrever headlines claras'=>'/ferramenta-p-criar-e-analisar-as-melhores-headlines-titulos-100-gratis/'),
        'como-definir-sua-estrategia-nas-midias-sociais'=>array('Gerador gratuito de links UTM'=>'/gerador-de-utm/'),
        'melhores-ferramentas-de-automacao-de-marketing-em-2026'=>array('Criar links rastreáveis com UTM'=>'/gerador-de-utm/'),
        'marketing-de-sucesso-linguagem-dos-resultados'=>array('Calculadora de ROAS, CAC e lucro'=>'/calculadora-roas-cac-lucro/'),
        'webdesign-projetando-trafego-conversoes'=>array('Medir campanhas e resultados de conversões'=>'/calculadora-roas-cac-lucro/'),
        'como-otimizar-seu-conteudo-para-o-google-sge-guia-completo-para-o-brasil'=>array('Como escrever títulos úteis para buscas'=>'/ferramenta-p-criar-e-analisar-as-melhores-headlines-titulos-100-gratis/'),
        'melhores-plataformas-de-automacao-de-marketing-em-2026-guia-completo-para-escolher-a-certa'=>array('Gerador de UTM para medir campanhas'=>'/gerador-de-utm/')
    );
    if (!isset($map[$slug])) { return $content; }
    $links=array();
    foreach($map[$slug] as $label=>$path){
        if (strpos($content,$path)===false) {
            $links[]='<a href="'.esc_url(home_url($path)).'">'.esc_html($label).'</a>';
        }
    }
    if (!$links) { return $content; }
    return $content.'<nav aria-label="Ferramenta complementar" style="border-top:1px solid #e4e8ef;margin:26px 0;padding:18px 0"><strong>Coloque em prática</strong><p style="margin:8px 0 0">'.implode(' · ',$links).'</p></nav>';
},49);
