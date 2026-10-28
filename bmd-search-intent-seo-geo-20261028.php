<?php
/**
 * BMD Search Intent Enhancement — 2026-10-28.
 * Small original additions to three verified posts based on our first-party
 * Search Console query segments and a review of public editorial alternatives.
 * No external scraping at runtime, no additional APIs, no mass content updates.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_search_intent_blocks_oct28() {
    return array(
        'como-clonar-um-site-completo' => <<<'BMD_CLONE'
<section class="bmd-serp-answer" aria-label="Comparação dos métodos para copiar um site">
<span class="bmd-serp-eyebrow">ESCOLHA O MÉTODO CERTO</span>
<h2>Como copiar um site com HTML, CSS, JavaScript e imagens?</h2>
<p>Para obter uma cópia de uma página, primeiro identifique o que realmente precisa: <strong>salvar o conteúdo, inspecionar o código, reunir os recursos públicos ou construir uma nova interface editável</strong>. São tarefas diferentes. O navegador só recebe o front-end disponibilizado para aquela visita; código de servidor, banco de dados e áreas privadas não fazem parte do download.</p>
<div class="bmd-serp-table" role="region" aria-label="Comparação de formas de copiar o front-end" tabindex="0">
<table><thead><tr><th>Método</th><th>O que você consegue</th><th>Limitação prática</th></tr></thead>
<tbody>
<tr><td><strong>Salvar página como</strong></td><td>HTML e parte dos arquivos associados</td><td>Conteúdo montado por JavaScript, rotas e animações podem falhar</td></tr>
<tr><td><strong>DevTools / Network</strong></td><td>Inspecionar HTML renderizado, CSS, scripts, fontes e imagens carregadas</td><td>É um diagnóstico; reunir e adaptar os arquivos exige trabalho</td></tr>
<tr><td><strong>Captura do front-end no navegador</strong></td><td>Organizar recursos públicos em uma base para testes e edição</td><td>Não obtém backend, credenciais nem garante execução de todas as interações</td></tr>
<tr><td><strong>Reconstrução manual ou com IA</strong></td><td>Criar um projeto próprio e adaptar a interface ao objetivo</td><td>Exige revisar comportamento, responsividade, direitos de uso e integrações</td></tr>
</tbody></table></div>
<p><strong>Como validar o resultado?</strong> Abra os arquivos em um servidor local quando necessário, confira as imagens e fontes, teste menu, links, vídeos e animações e compare em uma tela de celular. Para identificar a tecnologia antes de começar, consulte o <a href="/como-descobrir-plataforma-do-site/">guia de identificação de plataformas</a>.</p>
<p class="bmd-serp-caution">Use apenas páginas próprias, materiais licenciados ou referências cuja captura e reutilização tenham autorização. Capturar o visual de uma página não transfere direitos sobre sua marca, textos ou imagens.</p>
</section>
BMD_CLONE
        ,
        '23-ferramentas-gratuitas-de-seo-e-analise-de-websites' => <<<'BMD_AUDIT'
<section class="bmd-serp-answer" aria-label="Roteiro de auditoria SEO e GEO">
<span class="bmd-serp-eyebrow">AUDITORIA QUE GERA AÇÃO</span>
<h2>Como fazer uma análise SEO gratuita — e por onde começar a corrigir?</h2>
<p>O primeiro passo não é buscar uma nota 100/100: é entender <strong>qual problema impede as pessoas de encontrar ou usar sua página</strong>. Em uma auditoria útil, a ordem começa por acesso e indexação, passa pela resposta ao usuário e termina nos detalhes técnicos. A tabela abaixo conecta sintomas a verificações e ações concretas.</p>
<div class="bmd-serp-table" role="region" aria-label="Roteiro de diagnóstico SEO gratuito" tabindex="0">
<table><thead><tr><th>Sintoma</th><th>Confira gratuitamente</th><th>Próxima ação</th></tr></thead><tbody>
<tr><td><strong>Página importante não aparece no Google</strong></td><td>Inspeção de URL no Search Console, robots, noindex e canonical</td><td>Corrija bloqueios e URLs incoerentes antes de otimizar o título</td></tr>
<tr><td><strong>Há impressões, mas poucos cliques</strong></td><td>Consulta e página no relatório de desempenho do Search Console</td><td>Compare intenção de busca, título visível, snippet e qualidade da resposta</td></tr>
<tr><td><strong>Visitantes chegam e saem sem concluir a tarefa</strong></td><td>Conteúdo principal, navegação, formulário e métricas de engajamento</td><td>Entregue a resposta e o recurso esperado sem etapas desnecessárias</td></tr>
<tr><td><strong>A experiência no celular é ruim</strong></td><td>PageSpeed Insights, Core Web Vitals e teste manual em smartphone</td><td>Corrija imagens pesadas, layout instável e controles difíceis de usar</td></tr>
<tr><td><strong>Você quer aparecer em respostas com IA</strong></td><td>Indexação, texto acessível, clareza, fontes e links internos</td><td>Publique informações verificáveis e originais; citações por IA não são garantidas</td></tr>
</tbody></table></div>
<h3>SEO e GEO são duas estratégias separadas?</h3>
<p>GEO é o nome usado para trabalhar a presença em respostas geradas por IA. Na Pesquisa Google, as recomendações oficiais para AI Overviews e AI Mode continuam baseadas nos fundamentos de SEO: páginas rastreáveis, conteúdo útil e confiável, texto legível e links internos relevantes. <strong>Não há schema especial, arquivo <code>llms.txt</code> ou formatação obrigatória que garanta citações</strong>. Confira as <a href="https://developers.google.com/search/docs/appearance/ai-features?hl=pt-BR" rel="noopener noreferrer" target="_blank">orientações oficiais para recursos de IA</a>.</p>
<p>Depois de uma alteração, registre a data e acompanhe a mesma página e consulta no Search Console em períodos comparáveis. Mudanças de posição podem ter várias causas; não atribua ganhos automaticamente a uma edição.</p>
</section>
BMD_AUDIT
        ,
        'como-descobrir-plataforma-do-site' => <<<'BMD_STACK'
<section class="bmd-serp-answer" aria-label="Verificação manual de WordPress">
<span class="bmd-serp-eyebrow">RESPOSTA DIRETA · WORDPRESS</span>
<h2>Como saber se um site foi feito com WordPress?</h2>
<p>Abra a página e use <strong>Ver código-fonte</strong> (em muitos navegadores, Ctrl+U). Procure por caminhos como <code>/wp-content/</code> e <code>/wp-includes/</code>. Eles são sinais frequentes de WordPress porque identificam arquivos de temas, plugins e recursos do sistema. A presença desses caminhos é um indício; a ausência não exclui WordPress.</p>
<div class="bmd-serp-table" role="region" aria-label="Sinais públicos de WordPress e suas limitações" tabindex="0">
<table><thead><tr><th>Sinal encontrado</th><th>O que sugere</th><th>Cuidados</th></tr></thead><tbody>
<tr><td><code>/wp-content/themes/</code></td><td>Arquivos de um tema WordPress</td><td>CDN ou build estático pode reescrever os caminhos</td></tr>
<tr><td><code>/wp-content/plugins/</code></td><td>Recursos públicos de plugins</td><td>Não revela todos os plugins instalados</td></tr>
<tr><td><code>/wp-includes/</code></td><td>Recursos do núcleo WordPress</td><td>O site pode bloquear ou mascarar os caminhos</td></tr>
<tr><td><code>/wp-content/plugins/elementor/</code></td><td>Indício de assets públicos do Elementor</td><td>Não comprova quais páginas são editáveis nele</td></tr>
</tbody></table></div>
<p><strong>Em caso de dúvida:</strong> rode a URL no Wappalyzer ou WhatCMS e compare os resultados com a guia <em>Network</em> do DevTools. Sites headless podem usar WordPress no painel administrativo e outra tecnologia na parte pública. Não tente acessar arquivos privados ou burlar autenticação para descobrir a stack.</p>
<p>Se o objetivo for reconstruir uma interface que você pode utilizar, continue com o nosso <a href="/como-clonar-um-site-completo/">guia sobre captura de front-end e suas limitações</a>.</p>
</section>
BMD_STACK
    );
}

add_filter('the_content', function ($content) {
    if (is_admin() || !is_singular('post') || !is_main_query() || !in_the_loop() || is_preview()) {
        return $content;
    }
    $post_id = get_the_ID();
    if (!$post_id) { return $content; }
    $slug = (string)get_post_field('post_name', $post_id);
    $blocks = bmd_search_intent_blocks_oct28();
    if (!isset($blocks[$slug]) || strpos($content, 'bmd-serp-answer') !== false) {
        return $content;
    }
    // Avoid additions to short or radically changed articles and preserve all
    // existing text, media, shortcodes, outbound links and blocks.
    if (strlen($content) < 3000 || strpos($content, '<h2') === false) {
        return $content;
    }
    $pos = strpos($content, '<h2');
    if ($pos === false || $pos > 14000) { return $content; }
    return substr($content, 0, $pos) . $blocks[$slug] . substr($content, $pos);
}, 65);

/** One source-grounded title/description improvement for clone-related intent. */
add_filter('rank_math/frontend/title', function($title) {
    if (!is_admin() && is_singular('post') &&
        get_post_field('post_name', get_queried_object_id()) === 'como-clonar-um-site-completo') {
        return 'Como Clonar um Site Completo: HTML, CSS, JS e Imagens | BMD';
    }
    return $title;
}, 72);
add_filter('rank_math/frontend/description', function($description) {
    if (!is_admin() && is_singular('post') &&
        get_post_field('post_name', get_queried_object_id()) === 'como-clonar-um-site-completo') {
        return 'Veja como copiar HTML, CSS, JavaScript e imagens de uma página, comparar métodos, testar os arquivos e reconhecer os limites da captura de front-end.';
    }
    return $description;
}, 72);

add_action('wp_head', function() {
    if (is_admin() || !is_singular('post')) { return; }
    $slug = (string)get_post_field('post_name', get_queried_object_id());
    if (!in_array($slug, array_keys(bmd_search_intent_blocks_oct28()), true)) { return; }
    echo '<style id="bmd-seo-geo-intent-styles">
.entry-content .bmd-serp-answer{max-width:960px;padding:clamp(18px,3vw,30px);margin:27px 0 34px;background:#f9fafc;border:1px solid #e3e9f1;border-radius:16px;color:#253349}
.entry-content .bmd-serp-answer .bmd-serp-eyebrow{display:block;color:#bd5739;font-weight:800;font-size:11px;letter-spacing:.1em;margin-bottom:11px}
.entry-content .bmd-serp-answer h2{font-size:clamp(23px,3.1vw,29px)!important;line-height:1.22!important;letter-spacing:-.03em;margin:0 0 14px}
.entry-content .bmd-serp-answer h3{font-size:19px;margin:22px 0 12px}
.entry-content .bmd-serp-answer p{margin:12px 0!important;font-size:15px;line-height:1.7}
.entry-content .bmd-serp-answer .bmd-serp-table{max-width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;border-radius:9px;background:#fff;border:1px solid #e0e5ee;margin:18px 0}
.entry-content .bmd-serp-answer table{min-width:560px;width:100%;margin:0;border-collapse:collapse}
.entry-content .bmd-serp-answer th,.entry-content .bmd-serp-answer td{padding:12px 13px!important;border-bottom:1px solid #e7edf4!important;text-align:left;vertical-align:top;font-size:13px;line-height:1.55}
.entry-content .bmd-serp-answer th{background:#f0f4f8;font-weight:750;color:#253349}
.entry-content .bmd-serp-answer .bmd-serp-caution{font-size:13px!important;color:#5e6b7c}
@media(max-width:600px){.entry-content .bmd-serp-answer{margin:22px 0;padding:18px}.entry-content .bmd-serp-answer p{font-size:14px!important}}
</style>';
}, 78);
