<?php
/**
 * BMD 29/10/2026 — interactive SEO audit worklist.
 *
 * Search intent: "analise gratuita seo", "analise seo online".
 * Based on first-party GSC/SerpBear observations (2026-10-27).
 * Transparent MANUAL checklist; never pretends to crawl or score a URL.
 * No external JS/CSS dependencies or payment/APIs. Keep source post intact.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_is_seo_audit_guide_oct29() {
    return is_singular('post') &&
      (string)get_post_field('post_name',get_queried_object_id())==='23-ferramentas-gratuitas-de-seo-e-analise-de-websites';
}

add_action('wp_enqueue_scripts',function(){
    if (!bmd_is_seo_audit_guide_oct29()) { return; }
    $css=__DIR__.'/assets/bmd-seo-audit-checklist.css';
    $js=__DIR__.'/assets/bmd-seo-audit-checklist.js';
    if (is_file($css)) {
        wp_enqueue_style('bmd-seo-audit-checklist',plugins_url('assets/bmd-seo-audit-checklist.css',__FILE__),array(),filemtime($css));
    }
    if (is_file($js)) {
        wp_enqueue_script('bmd-seo-audit-checklist',plugins_url('assets/bmd-seo-audit-checklist.js',__FILE__),array(),filemtime($js),true);
    }
},24);

add_shortcode('bmd_seo_audit_worklist',function(){
    $steps=array(
      array(
       'Indexação e acesso',
       'A página aparece no índice e não está bloqueada?',
       'Inspecione a URL na propriedade verificada do Search Console. Confirme status de indexação, URL canônica, noindex e eventuais bloqueios. A inspeção exige que você tenha acesso à propriedade.',
       'Abrir Search Console',
       'https://search.google.com/search-console/about'
      ),
      array(
       'Consulta e CTR',
       'Quais buscas já mostram sua página?',
       'No relatório de Desempenho, filtre a URL. Compare consultas com impressões e cliques no mesmo país, período e dispositivo. Um título pode estar claro, mas não responder à intenção de busca.',
       'Conferir desempenho',
       'https://search.google.com/search-console/about'
      ),
      array(
       'Experiência e mobile',
       'O celular abre a página com rapidez e estabilidade?',
       'Analise o endereço no PageSpeed Insights e teste manualmente menus, imagens e formulários no celular. Priorize problemas observados; uma nota isolada não define o desempenho real.',
       'Abrir PageSpeed Insights',
       'https://pagespeed.web.dev/'
      ),
      array(
       'Conteúdo e estrutura',
       'O texto resolve a pergunta com evidência e clareza?',
       'Leia a página como visitante: resposta inicial, subtítulos, exemplos verificáveis, limitações, links úteis e autoria quando relevante. Evite tabelas e palavras-chave sem utilidade.',
       'Consultar conteúdo útil',
       'https://developers.google.com/search/docs/fundamentals/creating-helpful-content?hl=pt-br'
      ),
      array(
       'Dados estruturados e IA',
       'O conteúdo visível, a marcação e o acesso dos robôs estão coerentes?',
       'Teste os dados estruturados quando forem relevantes e confira os fundamentos de SEO para recursos de IA. Não há arquivo ou schema que garanta citações em respostas de IA.',
       'Testar resultados avançados',
       'https://search.google.com/test/rich-results'
      )
    );
    ob_start(); ?>
<section id="bmd-seo-worklist" class="bmd-seo-worklist" aria-labelledby="bmd-seo-worklist-title">
  <div class="bmd-seo-worklist-head">
    <span class="bmd-seo-worklist-eyebrow">PLANO DE AÇÃO · SEM CADASTRO</span>
    <h2 id="bmd-seo-worklist-title">Auditoria SEO gratuita: seu checklist de 5 verificações</h2>
    <p>Este roteiro é <strong>manual e gratuito</strong>: você confere seu site nas ferramentas indicadas e marca cada tarefa concluída. Não coletamos sua URL nem atribuímos uma nota inventada.</p>
  </div>
  <div class="bmd-seo-worklist-toolbar">
    <div>
      <strong id="bmd-seo-worklist-status" role="status" aria-live="polite">0 de 5 verificações concluídas</strong>
      <span>Comece pelos bloqueios de indexação; depois avalie conteúdo e experiência.</span>
    </div>
    <div class="bmd-seo-worklist-meter" role="progressbar" aria-label="Progresso da revisão SEO" aria-valuemin="0" aria-valuemax="5" aria-valuenow="0">
      <span></span>
    </div>
  </div>
  <ol class="bmd-seo-worklist-items">
  <?php foreach($steps as $n=>$step): ?>
    <li class="bmd-seo-worklist-step">
      <label for="bmd-seo-done-<?php echo esc_attr($n+1); ?>" class="bmd-seo-worklist-step-title">
        <input type="checkbox" id="bmd-seo-done-<?php echo esc_attr($n+1); ?>" data-bmd-seo-check="<?php echo esc_attr($n+1); ?>"/>
        <span>
          <span class="bmd-seo-worklist-step-kicker"><?php echo esc_html(sprintf('%02d · %s',$n+1,$step[0])); ?></span>
          <strong><?php echo esc_html($step[1]); ?></strong>
        </span>
      </label>
      <p><?php echo esc_html($step[2]); ?></p>
      <a href="<?php echo esc_url($step[4]); ?>" target="_blank" rel="noopener noreferrer"><?php echo esc_html($step[3]); ?> <span aria-hidden="true">↗</span></a>
    </li>
  <?php endforeach; ?>
  </ol>
  <footer class="bmd-seo-worklist-foot">
    <p>Fez a revisão? Copie as tarefas ainda pendentes para enviar a quem cuida do site. Nenhum dado é armazenado após sair da página.</p>
    <button type="button" id="bmd-seo-worklist-copy">Copiar tarefas pendentes</button>
    <span id="bmd-seo-worklist-feedback" role="status" aria-live="polite"></span>
  </footer>
</section>
    <?php return ob_get_clean();
});

add_filter('the_content',function($content){
    if (is_admin() || !is_main_query() || !in_the_loop() || is_preview() || !bmd_is_seo_audit_guide_oct29()) {
        return $content;
    }
    if (strpos($content,'id="bmd-seo-worklist"')!==false) { return $content; }
    $anchor='<h2 id="bmd-seo-quick-title">';
    $pos=strpos($content,$anchor);
    if($pos===false || strlen($content)<4000){ return $content; }
    return substr($content,0,$pos).do_shortcode('[bmd_seo_audit_worklist]').substr($content,$pos);
},78);

/** One contextually useful bridge from the SEO strategy guide to the actual worklist. */
add_filter('the_content',function($content){
    if(is_admin() || !is_main_query() || !in_the_loop() || is_preview() || !is_singular('post')) { return $content; }
    if ((string)get_post_field('post_name',get_the_ID()) !== 'estrategia-de-seo-seu-potencial') { return $content; }
    $url='/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/';
    if(strpos($content,$url)!==false){ return $content; }
    return $content.'<p class="bmd-seo-action-bridge" style="margin:24px 0;padding:17px 19px;background:#fafafa;border:1px solid #e8e8ea;border-radius:11px"><strong>Próxima etapa prática:</strong> use o <a href="'.esc_url(home_url($url)).'">checklist de auditoria SEO gratuita</a> para conferir indexação, consultas, velocidade, conteúdo e dados estruturados antes de investir em novas páginas.</p>';
},79);
