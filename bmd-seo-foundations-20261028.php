<?php
/**
 * Blog de Marketing Digital — GSC-driven search intent improvement.
 * SEO foundations refresh + Google Ads budget simulator, 28 Oct 2026.
 * No outside scripts, paid services, or ranking promises.
 */
if (!defined('ABSPATH')) { exit; }

add_action('init', function() {
    $done = 'bmd_seo_foundations_20261028_v1';
    if (get_option($done) === 'applied') { return; }
    $slug = 'significado-o-que-e-seo-e-para-que-serve';
    $post = get_page_by_path($slug, OBJECT, 'post');
    if (!$post || $post->post_status !== 'publish') { return; }
    $backup_key = '_bmd_seo_foundations_original_20261028';
    if (metadata_exists('post', $post->ID, $backup_key)) { update_option($done, 'applied', false); return; }
    $previous = (string)$post->post_content;
    $plain = wp_strip_all_tags($previous);
    if (strlen($previous) < 2500 || strlen($previous) > 60000 ||
        stripos($plain, 'search engine optimization') === false ||
        stripos($plain, 'palavra-chave') === false) {
        update_option($done, 'original-mismatch', false);
        return;
    }
    if (!add_post_meta($post->ID, $backup_key, $previous, true)) { return; }
    $html = <<<'BMD_SEO'
<p><strong>SEO significa Search Engine Optimization, ou otimização para mecanismos de busca.</strong> É o conjunto de práticas que facilita a descoberta, compreensão e apresentação das páginas de um site nos resultados orgânicos do Google e de outros buscadores. SEO envolve conteúdo útil, tecnologia, arquitetura do site e reputação — e não significa comprar anúncios ou garantir uma posição.</p>
<p><strong>Guia revisado em outubro de 2026.</strong> Aqui você encontra uma explicação para iniciantes, um exemplo prático, as ferramentas gratuitas mais úteis e um checklist para começar sem depender de uma assinatura de SEO.</p>

<h2>Para que serve o SEO na prática?</h2>
<p>SEO ajuda uma pessoa a encontrar uma resposta, serviço ou produto no momento em que faz uma pesquisa. Para um blog, isso pode significar leitores que procuram uma orientação. Para uma loja, compradores pesquisando categorias. Para uma empresa, potenciais clientes que querem entender um problema antes de contratar.</p>
<p>O objetivo principal deve ser <strong>tráfego orgânico qualificado e uma boa experiência</strong>, não visualizações descontextualizadas. O acesso vindo do buscador não é cobrado por clique, mas produzir conteúdo, corrigir problemas técnicos e manter o site tem custos de trabalho e infraestrutura.</p>

<h2>Como o Google encontra e mostra uma página?</h2>
<p>Para entender SEO, separe três processos diferentes. Não basta publicar o conteúdo e presumir que ele aparecerá na primeira página.</p>
<div class="bmd-geo-table" role="region" aria-label="Três etapas da Pesquisa Google" tabindex="0"><table><thead><tr><th>Etapa</th><th>O que acontece</th><th>Como ajudar</th></tr></thead><tbody>
<tr><td><strong>1. Rastreamento</strong></td><td>Sistemas automatizados descobrem URLs e acessam páginas disponíveis</td><td>Crie links internos úteis, evite bloquear páginas importantes e mantenha o site acessível</td></tr>
<tr><td><strong>2. Indexação</strong></td><td>O Google analisa o conteúdo e pode incluí-lo em seu índice</td><td>Ofereça conteúdo próprio e acessível, canônicas coerentes e páginas sem noindex indevido</td></tr>
<tr><td><strong>3. Veiculação</strong></td><td>O buscador escolhe os resultados relevantes para a consulta e seu contexto</td><td>Responda à intenção de busca com clareza, qualidade e informações verificáveis</td></tr>
</tbody></table></div>
<p><strong>Importante:</strong> um sitemap não garante indexação; e uma página indexada não tem posição assegurada. Essas etapas são explicadas no <a href="https://developers.google.com/search/docs/fundamentals/how-search-works?hl=pt-BR" rel="noopener noreferrer" target="_blank">guia oficial do funcionamento da Pesquisa Google</a>.</p>

<h2>SEO, SEM e Google Ads: qual é a diferença?</h2>
<p>O <strong>SEO</strong> trabalha para tornar páginas relevantes e acessíveis nos resultados orgânicos. <strong>Google Ads</strong> é a plataforma de anúncios que pode cobrar por interações conforme a campanha. <strong>SEM</strong> é uma expressão usada com significados variados: às vezes designa todo o marketing de busca; em outros contextos, apenas anúncios de pesquisa. Ao comparar relatórios, esclareça qual definição está sendo utilizada.</p>
<p>Se a dúvida for financeira, veja nosso guia <a href="/quanto-custa-google-ads/">quanto custa anunciar no Google Ads</a>, com um simulador de cenários de orçamento e CPC. Uma campanha paga não compra posições orgânicas.</p>

<h2>Quais são os pilares de uma estratégia de SEO?</h2>
<h3>1. Conteúdo original que atende à intenção de busca</h3>
<p>O ponto de partida é compreender o que a pessoa deseja resolver. Uma busca por <em>“o que é SEO”</em> exige definição e exemplos. Já <em>“como fazer análise SEO gratuita”</em> exige um roteiro, checagens e ferramentas utilizáveis. Misturar temas sem uma resposta clara produz páginas longas, porém pouco úteis.</p>
<p>Prefira explicar conceitos com exemplos próprios, fontes confiáveis e limitações explícitas. Não copie o texto do primeiro concorrente, não crie números de testes inexistentes e evite publicar várias páginas que respondem à mesma pergunta sem diferenciação.</p>
<h3>2. SEO técnico e experiência no celular</h3>
<p>Verifique status HTTP, indexação, canonical, títulos, estrutura de links e se a página abre sem bloquear usuários ou robôs legítimos. Depois avalie performance real, estabilidade visual e interatividade. O Google documenta <strong>LCP, INP e CLS</strong> como métricas de experiência, mas não existe um tempo mágico que, sozinho, determine classificação.</p>
<p>Uma página pode ter um ótimo teste de velocidade e continuar irrelevante para a pesquisa. Por isso, corrija primeiro os erros que impedem uso, compreensão e descoberta do conteúdo.</p>
<h3>3. Organização de páginas e links internos</h3>
<p>Construa caminhos naturais entre um guia inicial, conteúdos aprofundados e recursos práticos. Este artigo explica o conceito de SEO; o <a href="/estrategia-de-seo-seu-potencial/">guia de estratégia de SEO</a> detalha o planejamento, e nossa <a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/">auditoria SEO gratuita com 23 ferramentas</a> mostra onde identificar problemas.</p>
<p>Evite links só para preencher uma quantidade fixa e menus que escondem o conteúdo principal.</p>
<h3>4. Referências, confiabilidade e reputação</h3>
<p>Deixe claro quem é responsável pelo conteúdo, quando a informação foi revisada e como as afirmações importantes podem ser verificadas. Links recebidos naturalmente de outros sites podem ajudar a descoberta e a reputação; comprar links artificiais para manipular rankings viola as políticas contra spam do Google.</p>
<p>“Autoridade de Domínio” de algumas ferramentas é uma métrica de terceiros, não uma nota oficial do Google que você possa aumentar para garantir posições.</p>

<h2>Como escolher palavras-chave sem gastar em ferramentas?</h2>
<ol>
<li><strong>Comece por problemas reais:</strong> perguntas de clientes, dúvidas que chegam no atendimento e tarefas que seu produto resolve.</li>
<li><strong>Veja o Search Console:</strong> analise consultas e páginas que já recebem impressões, mesmo sem gerar muitos cliques.</li>
<li><strong>Examine os resultados atuais:</strong> descubra se a intenção é definição, tutorial, ferramenta, comparativo ou contratação.</li>
<li><strong>Crie um artigo realmente diferente:</strong> acrescente um exemplo, demonstração, checklist ou dado próprio verificável, em vez de apenas ampliar o número de palavras.</li>
<li><strong>Meça depois de publicar:</strong> registre a data e compare consultas e páginas em períodos equivalentes.</li>
</ol>
<p>Termos genéricos, como “marketing” ou “SEO”, costumam reunir muitas intenções distintas. Expressões específicas, como “como saber se meu site é WordPress”, podem ser mais adequadas para uma resposta objetiva. O volume de busca, isoladamente, não mede qualidade nem oportunidade.</p>

<h2>Exemplo: como o SEO ajuda um pequeno negócio?</h2>
<p>Imagine uma empresa que desenvolve sites. Um potencial cliente procura <em>“como descobrir em que plataforma um site foi feito”</em>. A empresa pode responder à pergunta com um tutorial mostrando os sinais de WordPress, explicar limitações das ferramentas e oferecer um próximo passo relevante.</p>
<p>O artigo pode direcionar ao <a href="/como-descobrir-plataforma-do-site/">guia para identificar a tecnologia de um site</a>. Se a pessoa desejar contratar uma reformulação, a empresa deverá explicar seu serviço com informações concretas. Não é preciso forçar uma venda em cada parágrafo do tutorial.</p>
<p>O que determina a utilidade desse caminho é se a resposta atende à dúvida original. O resultado financeiro só poderá ser avaliado quando visitas e contatos forem medidos corretamente.</p>

<h2>Como fazer uma primeira análise SEO gratuita em 20 minutos?</h2>
<div class="bmd-geo-table" role="region" aria-label="Auditoria SEO inicial" tabindex="0"><table><thead><tr><th>Tempo sugerido</th><th>Verificação</th><th>Ferramenta</th></tr></thead><tbody>
<tr><td>5 minutos</td><td>Confira a página mais importante e se o Google a reconhece no índice</td><td><a href="https://search.google.com/search-console/about" target="_blank" rel="noopener noreferrer">Google Search Console</a></td></tr>
<tr><td>5 minutos</td><td>Veja cliques, impressões, consultas e a página que recebe a busca</td><td>Relatório de desempenho do Search Console</td></tr>
<tr><td>5 minutos</td><td>Confira carregamento, layout e experiência em celular</td><td><a href="https://pagespeed.web.dev/" target="_blank" rel="noopener noreferrer">PageSpeed Insights</a> + teste real</td></tr>
<tr><td>5 minutos</td><td>Leia a abertura, confira título, links e se o conteúdo resolve a pergunta</td><td>Revisão editorial manual</td></tr>
</tbody></table></div>
<p>Esses tempos são apenas uma sugestão de organização, não uma promessa de auditoria completa. Sites grandes exigem mais verificações, incluindo rastreamento por amostragem, logs, templates e estrutura técnica.</p>

<h2>O que é GEO e como SEO se relaciona com respostas de IA?</h2>
<p>GEO é um termo usado para falar de visibilidade e referências em respostas geradas por inteligência artificial. No caso do Google, a documentação oficial afirma que <strong>as boas práticas fundamentais de SEO também se aplicam às Visões gerais criadas por IA e ao Modo IA</strong>. Não existe uma marcação especial, arquivo obrigatório ou fórmula que garanta ser citado.</p>
<p>O melhor caminho continua sendo oferecer páginas públicas e indexáveis, respostas claras, informações próprias ou verificáveis, links internos pertinentes e dados estruturados que correspondam ao conteúdo visível. Saiba mais nas <a href="https://developers.google.com/search/docs/appearance/ai-features?hl=pt-BR" target="_blank" rel="noopener noreferrer">orientações oficiais do Google sobre recursos de IA</a>.</p>
<p>Para observar resultados, não confunda uma citação em uma resposta de IA com clique orgânico ou venda. Cada plataforma apresenta métricas e limitações diferentes.</p>

<h2>Erros de SEO que vale evitar</h2>
<ul>
<li><strong>Comprar posição:</strong> não existem pagamentos ao Google que garantam uma classificação orgânica.</li>
<li><strong>Copiar concorrentes:</strong> replicar textos e estruturas sem valor novo não resolve a intenção do usuário.</li>
<li><strong>Inflar palavras-chave:</strong> repetições artificiais prejudicam a leitura e podem se enquadrar em práticas de spam.</li>
<li><strong>Desconsiderar indexação:</strong> melhorar metadados não corrige uma página bloqueada.</li>
<li><strong>Tratar métricas externas como regra do Google:</strong> notas de ferramentas comerciais são diagnósticos próprios.</li>
<li><strong>Prometer primeira página:</strong> a classificação varia por consulta e contexto; nem indexação é garantida.</li>
</ul>

<h2>Perguntas frequentes sobre SEO</h2>
<h3>O SEO é gratuito?</h3>
<p>O clique orgânico não exige pagamento por anúncio, mas produção de conteúdo, hospedagem e melhorias do site podem ter custos. Você consegue começar com ferramentas gratuitas, especialmente Search Console.</p>
<h3>Em quanto tempo o SEO dá resultado?</h3>
<p>Depende da mudança, da concorrência, da indexação e do estado inicial do site. O Google informa que alguns efeitos aparecem em horas e outros levam vários meses. Avalie tendências com paciência e períodos comparáveis, sem esperar uma posição específica.</p>
<h3>SEO e tráfego pago podem funcionar juntos?</h3>
<p>Sim. Um canal pode responder dúvidas e outro divulgar uma oferta. Meça os dois separadamente e use sua própria estrutura de custos e conversões para comparar investimentos.</p>
<h3>Vale a pena usar IA para escrever textos de SEO?</h3>
<p>IA pode ajudar na pesquisa, organização e revisão, mas o resultado deve ser checado, enriquecido com experiência real e ajustado ao usuário. Criar páginas em escala apenas para manipular classificações pode contrariar as políticas de spam.</p>
<p><strong>Próximo passo:</strong> avance para nosso <a href="/estrategia-de-seo-seu-potencial/">plano prático de estratégia SEO</a> ou siga o <a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/">checklist de auditoria gratuita</a>. Ambos complementam este guia sem exigir assinaturas.</p>
<p><small><strong>Fontes técnicas:</strong> <a href="https://developers.google.com/search/docs/fundamentals/seo-starter-guide?hl=pt-BR" target="_blank" rel="noopener noreferrer">Guia de SEO do Google</a>, <a href="https://developers.google.com/search/docs/fundamentals/creating-helpful-content?hl=pt-BR" target="_blank" rel="noopener noreferrer">conteúdo útil e confiável</a> e <a href="https://developers.google.com/search/docs/appearance/ai-features?hl=pt-BR" target="_blank" rel="noopener noreferrer">recursos de IA na Pesquisa</a>.</small></p>
BMD_SEO;
    $updated = wp_update_post(array(
        'ID' => $post->ID,
        'post_title' => 'O Que É SEO e Para Que Serve? Guia Prático para Iniciantes',
        'post_content' => $html
    ), true);
    if (is_wp_error($updated) || !$updated) {
        delete_post_meta($post->ID, $backup_key);
        return;
    }
    update_post_meta($post->ID, 'rank_math_title', 'O Que É SEO? Como Funciona e Para Que Serve | BMD');
    update_post_meta($post->ID, 'rank_math_description',
        'Entenda o que é SEO, como o Google rastreia e indexa páginas e como começar uma estratégia gratuita de busca e visibilidade em IA.');
    clean_post_cache($post->ID);
    update_option($done, 'applied', false);
}, 33);

/** The revised SEO page has an independent on-page title; do not overwrite other articles. */
add_filter('rank_math/frontend/title', function($title) {
    if (!is_admin() && is_singular('post') &&
        get_post_field('post_name', get_queried_object_id()) === 'significado-o-que-e-seo-e-para-que-serve' &&
        metadata_exists('post', get_queried_object_id(), '_bmd_seo_foundations_original_20261028')) {
        return 'O Que É SEO? Como Funciona e Para Que Serve | BMD';
    }
    return $title;
}, 85);
add_filter('rank_math/frontend/description', function($desc) {
    if (!is_admin() && is_singular('post') &&
        get_post_field('post_name', get_queried_object_id()) === 'significado-o-que-e-seo-e-para-que-serve' &&
        metadata_exists('post', get_queried_object_id(), '_bmd_seo_foundations_original_20261028')) {
        return 'Entenda o que é SEO, como o Google rastreia e indexa páginas e como começar uma estratégia gratuita de busca e visibilidade em IA.';
    }
    return $desc;
}, 85);

/**
 * Interactive scenario calculator for the already useful /quanto-custa-google-ads/ article.
 * All calculations happen locally; no claims about typical CPCs.
 */
add_shortcode('bmd_google_ads_budget_simulator', function() {
    return <<<'BMD_CALCULATOR'
<section class="bmd-ads-budget" aria-labelledby="bmd-ads-budget-heading">
 <div class="bmd-ads-head"><span>SIMULADOR GRÁTIS · SEM CADASTRO</span><h2 id="bmd-ads-budget-heading">Simule um orçamento de Google Ads</h2><p>Use seus próprios números para estimar gasto mensal, cliques e leads. Não consultamos preços do Google Ads, e o cálculo não prevê resultados reais.</p></div>
 <form id="bmd-ads-budget-form">
  <div class="bmd-ads-inputs">
   <div><label for="bmd-ads-daily">Orçamento diário médio (R$)</label><input id="bmd-ads-daily" type="number" min=".01" max="100000" step=".01" required value="30"></div>
   <div><label for="bmd-ads-cpc">CPC estimado (R$)</label><input id="bmd-ads-cpc" type="number" min=".01" max="10000" step=".01" required value="5"></div>
   <div><label for="bmd-ads-conversion">Taxa de conversão estimada (%)</label><input id="bmd-ads-conversion" type="number" min="0" max="100" step=".1" required value="3"></div>
  </div>
  <p class="bmd-ads-note">Os valores preenchidos são exemplos hipotéticos. Consulte o Planejador de Palavras-chave e seu próprio histórico antes de investir.</p>
  <button type="submit">Calcular cenário <span aria-hidden="true">↗</span></button>
 </form>
 <p id="bmd-ads-budget-error" role="alert" hidden></p>
 <div class="bmd-ads-results" id="bmd-ads-budget-results" role="status" aria-live="polite">
  <div><span>Orçamento mensal de referência</span><strong id="bmd-ads-monthly">—</strong></div>
  <div><span>Cliques hipotéticos</span><strong id="bmd-ads-clicks">—</strong></div>
  <div><span>Leads esperados (estimativa)</span><strong id="bmd-ads-leads">—</strong></div>
  <div><span>Custo estimado por lead</span><strong id="bmd-ads-cpl">—</strong></div>
 </div>
 <p class="bmd-ads-caveat"><strong>Como é calculado:</strong> orçamento mensal de referência = orçamento diário × 30,4; cliques = orçamento mensal ÷ CPC; leads esperados = cliques × taxa de conversão; custo por lead = orçamento mensal ÷ leads esperados. O Google pode distribuir os gastos de forma desigual, e os resultados não são garantidos. Uma taxa de 0% não produz custo por lead definido. Não inclui gestão, impostos, produção ou margem.</p>
 <p class="bmd-ads-next">Para verificar a rentabilidade depois das vendas, utilize a nossa <a href="/calculadora-roas-cac-lucro/">calculadora de ROAS, CAC e lucro</a>. O <a href="https://support.google.com/google-ads/answer/10486637?hl=pt-BR" target="_blank" rel="noopener noreferrer">Google explica os limites de gasto</a> na documentação oficial.</p>
</section>
<style>
.bmd-ads-budget{max-width:920px;margin:24px 0 32px;border:1px solid #dfe5ee;border-radius:19px;background:#fff;overflow:hidden;box-shadow:0 10px 30px rgba(16,30,50,.045);color:#19263b}
.bmd-ads-budget *{box-sizing:border-box}
.bmd-ads-head{background:linear-gradient(125deg,#fff8f1,#f7faff);padding:27px 28px 19px}
.bmd-ads-head span{font-size:11px;font-weight:800;letter-spacing:.1em;color:#b65b3f}
.bmd-ads-head h2{font-size:clamp(24px,3.4vw,34px)!important;line-height:1.2!important;letter-spacing:-.035em;margin:11px 0!important;color:#1b2c44}
.bmd-ads-head p{margin:0!important;line-height:1.6;color:#57647a;font-size:14px}
.bmd-ads-budget form{padding:23px 28px 16px;margin:0}
.bmd-ads-inputs{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:13px}
.bmd-ads-budget label{display:block;margin:0 0 8px;font-size:13px;font-weight:750;line-height:1.4}
.bmd-ads-budget input{display:block!important;width:100%!important;min-width:0;min-height:48px;background:#fff!important;border:1px solid #bec9d8!important;border-radius:10px!important;font-size:16px!important;padding:10px 12px!important;color:#19283c!important;margin:0!important;box-shadow:none!important}
.bmd-ads-budget input:focus{outline:2px solid #d49272;outline-offset:2px}
.bmd-ads-note{font-size:12px;line-height:1.6;color:#657389;margin:14px 0!important}
.bmd-ads-budget button{background:#192b45!important;color:#fff!important;border:0!important;border-radius:10px!important;padding:13px 18px!important;min-height:47px;font-size:14px;font-weight:750;cursor:pointer}
.bmd-ads-results{padding:0 28px 22px;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}
.bmd-ads-results>div{background:#f7f9fc;border:1px solid #e7ebf1;border-radius:11px;padding:16px;min-width:0}
.bmd-ads-results span{display:block;font-size:12px;color:#68758a;margin-bottom:9px}
.bmd-ads-results strong{font-size:clamp(20px,3vw,26px);color:#1c2b3e;font-variant-numeric:tabular-nums;overflow-wrap:anywhere}
.bmd-ads-caveat{background:#f9fafc;border-top:1px solid #edf0f4;padding:16px 28px;margin:0!important;font-size:12px;color:#5a697d;line-height:1.65}
.bmd-ads-next{padding:14px 28px;margin:0!important;border-top:1px solid #eef0f4;font-size:13px;line-height:1.7}
#bmd-ads-budget-error{margin:0 28px 16px;border:1px solid #eccbc1;border-radius:8px;padding:10px;color:#823925}
@media(max-width:680px){.bmd-ads-head,.bmd-ads-budget form{padding:20px}.bmd-ads-inputs,.bmd-ads-results{grid-template-columns:1fr}.bmd-ads-results{padding:0 20px 20px}.bmd-ads-caveat,.bmd-ads-next{padding:15px 20px}}
</style>
<script>
(function(){
var form=document.getElementById('bmd-ads-budget-form');
if(!form)return;
var err=document.getElementById('bmd-ads-budget-error'),fmt=new Intl.NumberFormat('pt-BR',{style:'currency',currency:'BRL'}),num=new Intl.NumberFormat('pt-BR',{maximumFractionDigits:1});
function update(){
 var daily=Number(document.getElementById('bmd-ads-daily').value),cpc=Number(document.getElementById('bmd-ads-cpc').value),conv=Number(document.getElementById('bmd-ads-conversion').value);
 if(![daily,cpc,conv].every(Number.isFinite)||daily<=0||daily>100000||cpc<=0||cpc>10000||conv<0||conv>100){err.hidden=false;err.textContent='Revise os números. O orçamento e o CPC devem ser positivos, e a conversão deve estar entre 0% e 100%.';return;}
 err.hidden=true;
 var monthly=daily*30.4, clicks=monthly/cpc, leads=clicks*conv/100;
 document.getElementById('bmd-ads-monthly').textContent=fmt.format(monthly);
 document.getElementById('bmd-ads-clicks').textContent=num.format(clicks);
 document.getElementById('bmd-ads-leads').textContent=num.format(leads);
 document.getElementById('bmd-ads-cpl').textContent=leads>0?fmt.format(monthly/leads):'Não aplicável';
}
form.addEventListener('submit',function(e){e.preventDefault();update();});update();
})();
</script>
BMD_CALCULATOR;
});

add_filter('the_content', function($content) {
    if (is_admin() || !is_main_query() || !in_the_loop() || is_preview() || !is_singular('post')) {
        return $content;
    }
    if ((string)get_post_field('post_name', get_the_ID()) !== 'quanto-custa-google-ads') { return $content; }
    if (strpos($content, 'bmd-ads-budget') !== false ||
        strpos($content, '<h2') === false || strlen($content) < 3000) {
        return $content;
    }
    $pos = strpos($content, '<h2');
    if ($pos === false || $pos > 10000) { return $content; }
    return substr($content, 0, $pos) . do_shortcode('[bmd_google_ads_budget_simulator]') .
        substr($content, $pos);
}, 68);

/** Make tables for the new SEO guide usable on narrow screens. */
add_action('wp_head',function(){
    if (is_admin() || !is_singular('post') ||
        get_post_field('post_name',get_queried_object_id()) !== 'significado-o-que-e-seo-e-para-que-serve') { return; }
    echo '<style>.entry-content .bmd-geo-table{max-width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;border:1px solid #e0e6ee;border-radius:11px;margin:18px 0}.entry-content .bmd-geo-table table{min-width:530px;width:100%;border-collapse:collapse;margin:0}.entry-content .bmd-geo-table th,.entry-content .bmd-geo-table td{padding:12px 14px;text-align:left;vertical-align:top;border-bottom:1px solid #e6edf3;font-size:14px;line-height:1.55}.entry-content .bmd-geo-table th{background:#f6f8fb;color:#253249;font-weight:750}.entry-content code{overflow-wrap:anywhere}</style>';
},79);
