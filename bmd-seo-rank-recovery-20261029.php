<?php
/**
 * BMD — data-informed evergreen repair, 29 October 2026.
 *
 * Exact legacy post slug only. One-time original backup, content-sentinel
 * and length safeguards, no new URL or publication on unrelated pages.
 *
 * Editorial role: 8 actions to improve rankings on an existing site.
 * Complementary to /23-ferramentas-gratuitas-de-seo-e-analise-de-websites/
 * (tool catalog & audit) and /estrategia-de-seo-seu-potencial/ (strategy).
 */
if (!defined('ABSPATH')) { exit; }

add_action('init', function() {
    $done = 'bmd_legacy_seo_rank_20261029_v1';
    if (get_option($done, false) !== false) { return; }
    $slug = 'dicas-melhorar-posicionamento-do-seu-site-no-google-seo';
    $post = get_page_by_path($slug, OBJECT, 'post');
    if (!$post || $post->post_status !== 'publish' || (string)$post->post_name !== $slug) {
        update_option($done, 'not-found', false);
        return;
    }
    $original = (string)$post->post_content;
    $plain = wp_strip_all_tags($original);
    if (strlen($original)<2000 || strlen($original)>100000 ||
        stripos($plain, 'As regras do jogo mudaram')===false ||
        stripos($plain, 'black hat')===false ||
        strpos($original, 'bmd-seo-recovery-20261029')!==false) {
        update_option($done, 'original-mismatch', false);
        return;
    }
    $backup = '_bmd_legacy_seo_rank_original_20261029';
    if (metadata_exists('post', $post->ID, $backup)) {
        update_option($done, 'backup-already-exists', false);
        return;
    }
    if (!add_post_meta($post->ID, $backup, $original, true)) { return; }

    $html = <<<'BMD_RANK_2026'
<p><strong>Melhorar o posicionamento no Google começa com um diagnóstico, não com repetir palavras-chave.</strong> Primeiro descubra por que a página não recebe visitas: ela não está indexada, aparece longe das primeiras posições, responde a outra intenção de busca ou tem dificuldades de uso no celular? Cada problema exige uma solução diferente.</p>
<p><strong>Atualizado em outubro de 2026.</strong> Este guia reúne oito ações práticas para recuperar e melhorar a visibilidade de páginas que já existem. Você pode executar as verificações com ferramentas gratuitas, sem contratar uma suíte de SEO. Não existe técnica que garanta a primeira posição.</p>
<div class="bmd-seo-recovery-20261029" role="region" aria-label="Diagnóstico inicial de SEO">
<h2>Por onde começar? Identifique o problema antes de mudar a página</h2>
<p>Abra o relatório <strong>Desempenho</strong> do <a href="https://search.google.com/search-console/about" target="_blank" rel="noopener noreferrer">Google Search Console</a>, filtre o endereço da página e compare dois períodos equivalentes. A posição média é um sinal aproximado: ela mistura consultas, países, dispositivos e posições diferentes.</p>
<div style="overflow-x:auto"><table><thead><tr><th>O que você observa</th><th>Hipótese inicial</th><th>O que conferir</th></tr></thead><tbody>
<tr><td>URL importante não aparece no Google</td><td>Descoberta, rastreamento ou indexação</td><td>Inspeção de URL, canonical, robots e noindex</td></tr>
<tr><td>Recebe impressões, mas nenhum clique</td><td>Posição distante ou resultado pouco relevante para a busca</td><td>Consultas, posição por termo, título e resposta oferecida</td></tr>
<tr><td>Cliques caíram, mas impressões continuam</td><td>Mudança de intenção, concorrência ou apresentação do resultado</td><td>Comparação da mesma URL e consultas, em período e país iguais</td></tr>
<tr><td>Boa posição, mas visitante sai sem avançar</td><td>Problema de conteúdo, experiência ou expectativa</td><td>Teste mobile, próximos passos, formulários e conteúdo efetivamente entregue</td></tr>
</tbody></table></div>
<p><strong>Quer transformar isso em tarefas?</strong> Use o <a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/#bmd-seo-worklist">checklist interativo de auditoria SEO gratuita</a> do blog. Ele não inventa uma nota: orienta o que verificar e permite copiar as pendências.</p>
</div>

<h2>1. Consulte as buscas e páginas que já têm impressões</h2>
<p>É comum publicar textos novos enquanto páginas antigas já estão perto de receber tráfego. No Search Console, selecione <strong>Desempenho → Resultados da pesquisa</strong>, compare 28 dias com os 28 anteriores e veja as abas de <strong>Páginas</strong> e <strong>Consultas</strong>. Selecione uma URL para descobrir quais perguntas a acionam.</p>
<p>Priorize páginas com impressões consistentes e intenção clara. Se uma página responde “como saber se o site usa WordPress”, por exemplo, a primeira parte deve ensinar esse diagnóstico; não começar com uma introdução longa sobre a história da internet. Nosso <a href="/como-descobrir-plataforma-do-site/">guia para identificar a tecnologia de um site</a> mostra essa abordagem.</p>
<p><strong>Evite:</strong> tratar toda queda como “penalização” ou somar posições médias de termos diferentes como se fossem uma classificação fixa.</p>

<h2>2. Corrija primeiro os problemas de indexação</h2>
<p>Conteúdo que o Google não consegue acessar ou escolhe não indexar não vai melhorar só com mais links internos. Inspecione a URL exata no Search Console. Confirme se ela retorna HTTP 200, está disponível para rastreamento, não traz <code>noindex</code> indevido e declara uma URL canônica coerente.</p>
<p>Sitemaps ajudam a encontrar páginas, mas <strong>não garantem indexação</strong>. Evite enviar um sitemap com URLs apagadas, redirecionadas ou duplicadas. Se o artigo antigo mudou de endereço, confira se o redirecionamento chega à versão definitiva sem cadeias desnecessárias. Veja o <a href="https://developers.google.com/search/docs/fundamentals/seo-starter-guide?hl=pt-BR" target="_blank" rel="noopener noreferrer">guia oficial de SEO do Google</a>.</p>

<h2>3. Responda à intenção da busca logo no início</h2>
<p>Quem pesquisa “como melhorar posicionamento no Google” espera ações que possa executar. Quem pesquisa “o que é SEO” espera uma explicação básica. São necessidades relacionadas, mas não idênticas. Para uma página existente, compare a pergunta real do visitante com o primeiro parágrafo e os subtítulos.</p>
<p>Acrescente demonstrações e detalhes que você consegue sustentar: um procedimento testado, um exemplo, condições de uso, limitações, uma comparação ou uma captura de tela própria. Evite parágrafos feitos apenas para repetir variações da palavra-chave.</p>
<p>Se estiver começando do zero, leia também <a href="/significado-o-que-e-seo-e-para-que-serve/">o que é SEO e como funciona</a>. Para planejamento completo, consulte <a href="/estrategia-de-seo-seu-potencial/">como montar uma estratégia de SEO</a>.</p>

<h2>4. Escreva um título claro e organize o texto</h2>
<p>O título exibido pelo Google nem sempre corresponde exatamente ao <code>&lt;title&gt;</code> da página. Ainda assim, um título descritivo e um H1 coerente ajudam as pessoas a reconhecer o assunto. Não acrescente o ano automaticamente quando ele não muda a informação.</p>
<p>Divida a solução em seções que respondem a perguntas reais. Use listas apenas quando houver uma sequência, tabelas quando uma comparação for melhor e imagens quando mostrarem algo relevante. A descrição da página deve resumir o valor para quem ainda não entrou; não é um atalho garantido para melhorar a posição.</p>
<p><strong>Exemplo de decisão editorial:</strong> “Como melhorar o posicionamento no Google: 8 ações de SEO” explica melhor a proposta do que “8 dicas imperdíveis que vão explodir seu tráfego”.</p>

<h2>5. Crie links internos que realmente ajudem a navegação</h2>
<p>Links internos dão contexto sobre a relação entre páginas e permitem ao usuário aprofundar a leitura. Prefira texto âncora específico: “<a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/">ferramentas gratuitas para analisar SEO</a>” explica melhor o destino do que “clique aqui”.</p>
<p>Ligue conteúdos de uma mesma jornada. Uma página que ensina <a href="/como-clonar-um-site-completo/">a estudar a estrutura de um site</a> pode conduzir a quem deseja identificar sua tecnologia; uma página sobre diagnóstico pode encaminhar para uma ferramenta prática. Evite colocar dezenas de links iguais em todos os artigos apenas para inflar uma contagem.</p>
<p>Confira periodicamente links quebrados e páginas antigas que apontam para endereços de hospedagens anteriores. Preservar URLs úteis e aplicar redirecionamentos corretos costuma ser mais seguro do que mudar os slugs sem necessidade.</p>

<h2>6. Melhore a experiência mobile e os Core Web Vitals</h2>
<p>Use o <a href="https://pagespeed.web.dev/" target="_blank" rel="noopener noreferrer">PageSpeed Insights</a> para separar métricas de campo (experiência real, quando disponíveis) de testes de laboratório. As referências de boa experiência para Core Web Vitals são <strong>LCP até 2,5 segundos, INP até 200 milissegundos e CLS até 0,1</strong>.</p>
<p>Se o problema é CLS, observe imagens sem tamanho definido, banners e anúncios inseridos depois que o conteúdo começa a aparecer. Se é LCP, investigue o conteúdo principal, resposta do servidor e arquivos carregados antes da primeira dobra. Se é INP, revise JavaScript pesado e tarefas demoradas no navegador.</p>
<p><strong>Importante:</strong> não desative anúncios, consentimento de cookies ou funcionalidades essenciais apenas para atingir uma pontuação de teste. O objetivo é uma página realmente utilizável. Consulte a <a href="https://developers.google.com/search/docs/appearance/core-web-vitals?hl=pt-br" target="_blank" rel="noopener noreferrer">documentação do Google sobre Core Web Vitals</a>.</p>

<h2>7. Atualize conteúdo com evidências, não apenas com a data</h2>
<p>Antes de colocar “atualizado em 2026” no topo de um artigo, confira se os passos, telas e links realmente continuam funcionando. Remova tutoriais obsoletos e números sem fonte. Se houver limitações — por exemplo, diferenças entre celulares, planos gratuitos e recursos pagos — explique-as.</p>
<p>Ferramentas de IA podem apoiar a organização e a revisão, mas não substituem testes, exemplos originais e responsabilidade editorial. Para mecanismos de busca e respostas de IA, publique conteúdo acessível, coerente e rastreável; não há marcação que garanta uma citação ou recomendação.</p>
<p>Use autoria e fontes quando acrescentarem contexto. Não invente estudos de caso, resultados de clientes, avaliações ou testes que você não fez. O <a href="https://developers.google.com/search/docs/fundamentals/creating-helpful-content?hl=pt-br" target="_blank" rel="noopener noreferrer">guia de conteúdo útil do Google</a> oferece perguntas para uma revisão honesta.</p>

<h2>8. Meça as mudanças e saiba quando parar de mexer</h2>
<p>Anote a URL, a alteração realizada e a data. Compare períodos semelhantes no Search Console, observando a mesma consulta quando possível. A indexação e a reavaliação podem levar tempo; mudanças diárias de título ou de endereço dificultam entender o que funcionou.</p>
<p>Uma página que já recebe cliques pode se beneficiar mais de correções pontuais do que de uma reescrita completa. Já uma página desatualizada ou que não responde ao tema exige intervenção mais profunda. O ideal é priorizar pelo impacto esperado, não pelo número de artigos editados.</p>
<p>Se uma queda coincide com alterações na lista de rejeição de backlinks, não presuma causalidade. Verifique relatórios de ações manuais, datas das mudanças, links editoriais reais e outros problemas de indexação. Um arquivo de rejeição não substitui uma auditoria técnica e editorial.</p>

<h2>Plano prático: o que fazer nos próximos 30 dias?</h2>
<div style="overflow-x:auto"><table><thead><tr><th>Período</th><th>Trabalho prioritário</th><th>Como validar</th></tr></thead><tbody>
<tr><td><strong>Dias 1–7</strong></td><td>Escolher 5 URLs pelo Search Console; conferir indexação, títulos, links internos e experiência mobile</td><td>Registrar situação inicial e corrigir erros verificáveis</td></tr>
<tr><td><strong>Dias 8–14</strong></td><td>Melhorar respostas, exemplos, legibilidade e acesso às ferramentas relevantes</td><td>Revisar a página como usuário e validar links/formulários</td></tr>
<tr><td><strong>Dias 15–30</strong></td><td>Acompanhar consultas, CTR e tráfego qualificado sem reescrever tudo novamente</td><td>Comparar períodos equivalentes; registrar aprendizados e próximos testes</td></tr>
</tbody></table></div>
<p>O plano acima é uma sequência de trabalho, não uma promessa de retorno em 30 dias. Algumas oportunidades demoram mais para refletir nos resultados da pesquisa.</p>

<h2>Perguntas frequentes sobre posicionamento no Google</h2>
<h3>Quanto tempo demora para melhorar uma posição?</h3>
<p>Depende da alteração, da frequência de rastreamento, da concorrência e da intenção de busca. Corrigir um erro técnico pode ser percebido rapidamente após novo rastreamento, mas ganhar posições não tem prazo garantido.</p>
<h3>Preciso pagar uma ferramenta de SEO para começar?</h3>
<p>Não. Search Console, PageSpeed Insights e outras ferramentas oficiais já permitem fazer uma auditoria inicial consistente. Veja nossa <a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/">seleção de ferramentas gratuitas para SEO</a> e comece pelo problema que seus dados realmente mostram.</p>
<h3>Fazer SEO para IA é diferente do SEO tradicional?</h3>
<p>As experiências de busca com IA podem apresentar respostas de outra forma, mas os fundamentos continuam relevantes: conteúdo claro, original, acessível e bem estruturado. Evite promessas de “aparecer em toda resposta de IA” por meio de truques técnicos.</p>
<h3>Links internos ajudam um site antigo a recuperar tráfego?</h3>
<p>Podem ajudar visitantes e mecanismos de pesquisa a descobrir páginas e entender relações entre assuntos. Não são garantia de classificação. Prefira ligações naturais entre conteúdos complementares, não listas artificiais de centenas de links.</p>

<p><strong>Próximo passo:</strong> abra nosso <a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/#bmd-seo-worklist">checklist de auditoria SEO gratuita</a>, marque as verificações concluídas e copie as pendências para organizar a execução.</p>
BMD_RANK_2026;

    $result = wp_update_post(array(
        'ID' => $post->ID,
        'post_title' => 'Como Melhorar o Posicionamento no Google: 8 Ações de SEO',
        'post_content' => $html
    ), true);

    if (is_wp_error($result)) {
        update_option($done, 'update-failed', false);
        return;
    }
    update_post_meta($post->ID, 'rank_math_title', 'Como Melhorar o Posicionamento no Google: 8 Ações de SEO');
    update_post_meta($post->ID, 'rank_math_description', 'Veja 8 ações práticas para melhorar seu posicionamento no Google: Search Console, indexação, conteúdo, links internos e Core Web Vitals.');
    update_option($done, 'applied', false);
}, 28);
