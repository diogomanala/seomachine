<?php
/**
 * BMD 2026-10-29 — Repair two inaccurate legacy indexation guides.
 *
 * Exact known URLs; original title, body and SEO metadata are privately
 * backed up in post meta before any change. One-time and sentinel-guarded.
 * One general Search Console guide and a separate Indexing API explainer.
 *
 * Official references:
 * https://developers.google.com/search/docs/crawling-indexing/ask-google-to-recrawl?hl=pt-br
 * https://developers.google.com/search/apis/indexing-api/v3/using-api?hl=pt-BR
 */
if (!defined('ABSPATH')) { exit; }

function bmd_indexation_repair_posts_oct29() {
    return array(
        'indexar-site-google-rapido' => array(
            'needle' => 'A maneira mais rápida pra você indexar seu site no Google',
            'title' => 'Como Indexar um Site no Google: Search Console e Sitemap',
            'seo_title' => 'Como Indexar um Site no Google: Passo a Passo Atualizado',
            'seo_description' => 'Veja como enviar URLs pelo Search Console, conferir indexação, usar o sitemap e corrigir problemas que impedem páginas de aparecer no Google.',
            'body' => <<<'BMD_INDEX_GENERAL'
<p><strong>Para indexar um site no Google, comece verificando se o Google consegue encontrar, rastrear e avaliar suas páginas.</strong> O caminho oficial para páginas comuns é usar o <a href="https://search.google.com/search-console/about" target="_blank" rel="noopener noreferrer">Google Search Console</a>, conferir a Inspeção de URL e disponibilizar um sitemap XML atualizado. Nenhuma dessas ações garante indexação instantânea ou uma posição na pesquisa.</p>
<p><strong>Revisado em outubro de 2026.</strong> Este artigo substitui uma orientação antiga que apontava para a ferramenta descontinuada <code>google.com/webmasters/tools/submit-url</code>. O procedimento atual exige que você tenha acesso à propriedade correspondente do seu site no Search Console.</p>

<h2>Como solicitar a indexação de uma URL no Google?</h2>
<ol>
<li><strong>Entre no Search Console</strong> e selecione a propriedade que representa o endereço do seu site. A propriedade de domínio reúne protocolos e subdomínios; propriedades de prefixo têm escopo limitado ao endereço selecionado.</li>
<li><strong>Use “Inspeção de URL”.</strong> Cole a URL canônica completa da página que você deseja verificar.</li>
<li><strong>Analise o resultado antes de pedir indexação.</strong> Confira se a URL pode ser rastreada, se existe <code>noindex</code>, se o Google escolheu outra URL canônica e se há erros de resposta.</li>
<li><strong>Se necessário, solicite a indexação.</strong> É um pedido de rastreamento, não uma publicação imediata no índice. Reenviar repetidamente a mesma URL não acelera o processo.</li>
<li><strong>Acompanhe depois.</strong> O processamento pode levar de dias a semanas, e nem toda página rastreada será indexada.</li>
</ol>
<p>Fonte oficial: <a href="https://developers.google.com/search/docs/crawling-indexing/ask-google-to-recrawl?hl=pt-br" target="_blank" rel="noopener noreferrer">como solicitar um novo rastreamento ao Google</a>.</p>

<h2>Como enviar muitas páginas sem pedir uma por uma?</h2>
<p>Para um blog WordPress com dezenas ou centenas de artigos, mantenha o <strong>sitemap XML</strong> produzido pelo seu plugin de SEO. Se usar Rank Math, a instalação normalmente fornece um índice como <code>/sitemap_index.xml</code>. Confirme a URL real no próprio site, em vez de copiar um caminho de outro projeto.</p>
<ol>
<li>Abra o índice de sitemap e veja se aponta para os arquivos de posts, páginas e categorias.</li>
<li>Confira se as URLs importantes aparecem, usam HTTPS, retornam conteúdo público e possuem uma canônica coerente.</li>
<li>No Search Console, acesse <strong>Indexação → Sitemaps</strong> e informe o endereço do índice, se ele ainda não estiver enviado.</li>
<li>Use o relatório de páginas para investigar exclusões. A presença no sitemap apenas ajuda na descoberta: não é confirmação de indexação.</li>
</ol>
<p>Um sitemap bem organizado também evita desperdiçar sinais com páginas removidas, redirecionamentos e cópias não canônicas. <a href="https://developers.google.com/search/docs/crawling-indexing/sitemaps/overview?hl=pt-br" target="_blank" rel="noopener noreferrer">Entenda os limites de um sitemap na documentação do Google</a>.</p>

<h2>Minha página não aparece: o que verificar primeiro?</h2>
<div style="overflow-x:auto"><table><thead><tr><th>Problema observado</th><th>O que conferir</th><th>Correção possível</th></tr></thead><tbody>
<tr><td>URL devolve 404 ou 5xx</td><td>Resposta do servidor e logs</td><td>Restaurar o conteúdo ou corrigir o erro; redirecionar apenas se houver destino equivalente</td></tr>
<tr><td>Marcada como “noindex”</td><td>Meta robots ou cabeçalho HTTP</td><td>Retirar a restrição se a página deve aparecer na busca</td></tr>
<tr><td>Google escolheu outra canônica</td><td>Conteúdo parecido, links e canonical</td><td>Consolidar páginas duplicadas e reforçar a versão principal</td></tr>
<tr><td>Descoberta, mas não indexada</td><td>Qualidade, relevância, links internos e rastreamento</td><td>Melhorar conteúdo útil e acesso; aguardar novo rastreamento</td></tr>
<tr><td>Rastreada, mas não indexada</td><td>Intenção de busca, originalidade e sinais de duplicação</td><td>Revisar a utilidade real antes de republicar versões semelhantes</td></tr>
</tbody></table></div>
<p><strong>Não confunda rastreamento, indexação e ranqueamento.</strong> Uma URL pode ser rastreada e não entrar no índice; outra pode estar indexada e não receber cliques por aparecer longe das primeiras posições.</p>

<h2>Como ajudar o Google a descobrir páginas novas?</h2>
<p>Além do sitemap, crie links internos naturalmente relacionados. Por exemplo, um artigo que ensina <a href="/significado-o-que-e-seo-e-para-que-serve/">o que é SEO</a> pode encaminhar para um <a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/#bmd-seo-worklist">checklist de auditoria de indexação</a>. Garanta que seus menus e links funcionem tanto no computador quanto no celular.</p>
<p>Não vale tentar indexar centenas de artigos quase iguais ou com dados ultrapassados. Conteúdo original, útil e tecnicamente acessível é o fundamento; ferramentas não substituem a qualidade editorial.</p>

<h2>Existe indexação instantânea para qualquer artigo WordPress?</h2>
<p><strong>Não.</strong> A Indexing API do Google é documentada para páginas com marcação <code>JobPosting</code> ou <code>BroadcastEvent</code> incorporado em um <code>VideoObject</code>, não para posts comuns, landing pages ou páginas de produto. Veja <a href="/como-indexar-site-e-postagens-instantaneamente-no-google-instant-indexing/">quando a Indexing API pode ser usada e o que significa receber “Success”</a>.</p>

<h2>Perguntas frequentes</h2>
<h3>Quanto tempo demora para indexar uma página?</h3>
<p>Não existe prazo garantido. O Google informa que um novo rastreamento pode levar de dias a semanas, dependendo de vários fatores. Uma solicitação aceita não significa que a URL foi indexada.</p>
<h3>Devo enviar o sitemap todos os dias?</h3>
<p>Não. Mantenha o arquivo correto e atualizado. Não é necessário enviar repetidamente o mesmo sitemap inalterado.</p>
<h3>O Google pode ignorar uma página no sitemap?</h3>
<p>Sim. O sitemap indica URLs para descoberta, mas não obriga o buscador a rastrear ou indexar todos os itens.</p>

<p><strong>Próximo passo:</strong> siga o <a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/#bmd-seo-worklist">checklist gratuito de auditoria SEO</a> e registre o que precisa ser corrigido, em vez de repetir pedidos de indexação.</p>
BMD_INDEX_GENERAL
        ),
        'como-indexar-site-e-postagens-instantaneamente-no-google-instant-indexing' => array(
            'needle' => 'configurar a indexação instantânea no seu site wordpress',
            'title' => 'Google Indexing API no WordPress: Quando Usar e Limitações',
            'seo_title' => 'Google Indexing API no WordPress: Limites e Alternativas',
            'seo_description' => 'A Indexing API não indexa posts comuns instantaneamente. Entenda os tipos de página aceitos, o significado de Success e como indexar artigos via Search Console.',
            'body' => <<<'BMD_INDEX_API'
<p><strong>A Google Indexing API não é uma ferramenta de indexação instantânea para qualquer artigo WordPress.</strong> Segundo a documentação oficial, ela é destinada a páginas com dados estruturados <code>JobPosting</code> (vagas de emprego) ou <code>BroadcastEvent</code> incorporado a um <code>VideoObject</code> (transmissões ao vivo). Um retorno de sucesso da API confirma que uma notificação foi recebida, <strong>não que a URL tenha entrado imediatamente no índice</strong>.</p>
<p><strong>Nota editorial de outubro de 2026:</strong> este artigo continha um tutorial de 2022 que recomendava criar uma conta de serviço e enviar posts comuns pela Indexing API. Esse uso não está documentado como compatível pelo Google. Atualizamos as orientações sem apagar o endereço histórico nem o acesso ao vídeo original.</p>

<h2>Quando a Google Indexing API pode ser usada?</h2>
<div style="overflow-x:auto"><table><thead><tr><th>Tipo de página</th><th>A API é documentada para esse caso?</th><th>Melhor abordagem</th></tr></thead><tbody>
<tr><td>Vaga de emprego individual com <code>JobPosting</code> válido</td><td>Sim</td><td>API para notificar inclusão, atualização ou remoção; manter sitemap quando adequado</td></tr>
<tr><td>Transmissão ao vivo com <code>BroadcastEvent</code> dentro de <code>VideoObject</code></td><td>Sim</td><td>API conforme as diretrizes e marcação correspondente</td></tr>
<tr><td>Artigo de blog WordPress</td><td>Não</td><td>Search Console, sitemap, links internos e qualidade do conteúdo</td></tr>
<tr><td>Landing page, categoria de blog ou página de serviço</td><td>Não</td><td>Inspeção de URL e sitemap; corrigir rastreamento e canônica</td></tr>
<tr><td>Produto de e-commerce comum</td><td>Não</td><td>Sitemap, dados estruturados apropriados e inspeção de URL</td></tr>
</tbody></table></div>
<p>Consulte a <a href="https://developers.google.com/search/apis/indexing-api/v3/using-api?hl=pt-BR" target="_blank" rel="noopener noreferrer">documentação oficial da Indexing API do Google</a> antes de configurar qualquer plugin que faça notificações automáticas.</p>

<h2>O que significa “Success” no plugin Instant Indexing?</h2>
<p>Uma resposta positiva pode indicar que a solicitação chegou ao endpoint da API, mas <strong>não é prova de indexação nem garantia de posicionamento</strong>. Para conferir o que aconteceu com uma URL específica, use a Inspeção de URL e o relatório de indexação do Search Console.</p>
<p>A API tem limites de uso e políticas de detecção de abuso. Não contorne as cotas com várias contas de serviço nem crie marcação de vagas ou transmissões fictícias para tentar forçar o rastreamento de posts.</p>

<h2>Qual é o método correto para um blog WordPress?</h2>
<ol>
<li><strong>Selecione a propriedade correta</strong> no <a href="https://search.google.com/search-console/about" target="_blank" rel="noopener noreferrer">Google Search Console</a>.</li>
<li><strong>Verifique a URL</strong> com Inspeção de URL, especialmente resposta HTTP, indexabilidade e canonical.</li>
<li><strong>Envie o sitemap do site</strong> em Indexação → Sitemaps, caso ainda não esteja configurado.</li>
<li><strong>Facilite a descoberta</strong> criando links internos úteis entre artigos realmente relacionados.</li>
<li><strong>Acompanhe os dados</strong>, sem confundir pedido de rastreamento com indexação confirmada.</li>
</ol>
<p>Nosso <a href="/indexar-site-google-rapido/">guia atualizado para indexar um site no Google</a> explica o passo a passo e os principais problemas técnicos. Para verificar o seu caso, use também o <a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/#bmd-seo-worklist">checklist gratuito de auditoria SEO</a>.</p>

<h2>E se o meu site publicar vagas ou transmissões ao vivo?</h2>
<p>Nesses cenários documentados, o fluxo oficial exige configurar projeto e credenciais no Google Cloud, verificar a propriedade do site e publicar notificações para URLs elegíveis. Proteja as chaves de serviço e nunca as publique em tutoriais, imagens, código aberto ou repositórios. O Google pode aplicar limitações e rejeitar usos fora das diretrizes.</p>
<p>Leia os <a href="https://developers.google.com/search/apis/indexing-api/v3/quickstart?hl=pt-BR" target="_blank" rel="noopener noreferrer">pré-requisitos oficiais da API</a> antes de seguir qualquer vídeo de configuração.</p>

<h2>Vídeo original de 2022: contexto histórico</h2>
<p>O vídeo abaixo mostra a configuração de um plugin de indexação da época. <strong>O procedimento não deve ser interpretado como recomendação atual para posts comuns do WordPress.</strong> Ele permanece disponível como registro histórico para quem deseja compreender o funcionamento da interface e das credenciais.</p>
<div class="bmd-video-archive" style="position:relative;width:100%;max-width:780px;aspect-ratio:16/9;overflow:hidden;border-radius:12px;background:#17181b">
<iframe title="Tutorial histórico de 2022 sobre plugin Indexing API WordPress" src="https://www.youtube-nocookie.com/embed/mGhGJAKLh-c" width="780" height="439" loading="lazy" referrerpolicy="strict-origin-when-cross-origin" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="display:block;border:0;width:100%;height:100%"></iframe>
</div>

<h2>Perguntas frequentes</h2>
<h3>Posso usar a Indexing API para fazer o Google descobrir meu blog mais rápido?</h3>
<p>A documentação não prevê o uso da Indexing API para artigos comuns. Prefira o sitemap, a Inspeção de URL e ligações internas relevantes. Um pedido de rastreamento não garante a indexação.</p>
<h3>É necessário dar acesso de proprietário a uma conta de serviço para indexar posts?</h3>
<p>Não é necessário criar uma conta de serviço para o procedimento comum via Search Console. Credenciais de API só são apropriadas para fluxos documentados e configurados com as permissões corretas.</p>
<h3>Desativar um plugin de Indexing API prejudica páginas comuns?</h3>
<p>O conteúdo, os links internos e o sitemap continuam sendo os meios normais de descoberta. A desativação não remove automaticamente URLs já existentes do Google; o importante é manter o site acessível e corretamente configurado.</p>
BMD_INDEX_API
        )
    );
}

add_action('init', function() {
    foreach (bmd_indexation_repair_posts_oct29() as $slug => $data) {
        $done = 'bmd_indexation_repair_oct29_' . str_replace('-', '_', $slug);
        if (get_option($done, false) !== false) { continue; }
        $post = get_page_by_path($slug, OBJECT, 'post');
        if (!$post || $post->post_status !== 'publish' || $post->post_name !== $slug) {
            update_option($done, 'not-found', false);
            continue;
        }
        $original = (string)$post->post_content;
        if (strlen($original) < 300 || strlen($original) > 70000 ||
            stripos(wp_strip_all_tags($original), $data['needle']) === false ||
            strpos($original, 'BMD_INDEX_API') !== false) {
            update_option($done, 'original-mismatch', false);
            continue;
        }
        $backup_key = '_bmd_indexation_repair_original_20261029';
        if (metadata_exists('post', $post->ID, $backup_key)) {
            update_option($done, 'backup-exists', false);
            continue;
        }
        $backup = array(
            'title' => $post->post_title,
            'content' => $original,
            'rank_math_title' => get_post_meta($post->ID, 'rank_math_title', true),
            'rank_math_description' => get_post_meta($post->ID, 'rank_math_description', true)
        );
        if (!add_post_meta($post->ID, $backup_key, $backup, true)) { continue; }
        $updated = wp_update_post(array(
            'ID' => $post->ID,
            'post_title' => $data['title'],
            'post_content' => $data['body']
        ), true);
        if (is_wp_error($updated)) {
            update_option($done, 'update-error', false);
            continue;
        }
        update_post_meta($post->ID, 'rank_math_title', $data['seo_title']);
        update_post_meta($post->ID, 'rank_math_description', $data['seo_description']);
        update_option($done, 'applied', false);
    }
}, 29);
