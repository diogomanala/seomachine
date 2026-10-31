<?php
/**
 * BMD | Internal Search Console CSV prioritizer (not a crawler or automatic
 * URL auditor). Keeps user data client-side. SEO recovery sprint 31/10/2026.
 */
if(!defined('ABSPATH'))exit;
add_action('wp_enqueue_scripts',static function(){
 if(!is_singular('post')||(int)get_queried_object_id()!==2402)return;
 foreach(array('js','css') as $ext){
  $file='assets/bmd-gsc-opportunities-20261031.'.$ext;
  $path=__DIR__.'/'.$file;if(!is_file($path))continue;
  if($ext==='js')wp_enqueue_script('bmd-gsc-opportunities-2026',plugins_url($file,__FILE__),array(),(string)filemtime($path),true);
  else wp_enqueue_style('bmd-gsc-opportunities-2026',plugins_url($file,__FILE__),array(),(string)filemtime($path));
 }
});
add_shortcode('bmd_gsc_opportunities_2026',static function(){
 ob_start();?>
 <section class="bmd-gsc-audit" aria-labelledby="bmd-gsc-title" id="priorizador-gratuito-search-console">
  <span class="bmd-gsc-eyebrow">FERRAMENTA GRATUITA • SEM CADASTRO</span>
  <h2 id="bmd-gsc-title">Quais palavras-chave vale corrigir primeiro?</h2>
  <p>O Search Console mostra impressões, cliques e posição, mas não decide quais consultas merecem sua atenção. Cole ou envie o CSV de <strong>Desempenho → Consultas → Exportar</strong>. Esta ferramenta cria uma lista inicial de prioridades, sem consultar sua conta, sem subir arquivos para o servidor e sem prometer posições no Google.</p>
  <div class="bmd-gsc-controls">
   <label class="bmd-gsc-upload" for="bmd-gsc-csv">Selecionar CSV exportado do Google Search Console
    <input id="bmd-gsc-csv" data-audit-file type="file" accept=".csv,.tsv,text/csv,text/tab-separated-values" aria-label="Carregar CSV de consultas do Search Console">
   </label>
  </div>
  <label for="bmd-gsc-text" style="display:block;font-size:12px;font-weight:800;color:#263e5a;margin-bottom:7px">Ou cole os dados de consultas aqui</label>
  <textarea id="bmd-gsc-text" data-audit-input rows="5" spellcheck="false" placeholder="Consulta;Cliques;Impressões;CTR;Posição&#10;analise seo gratuita;0;199;0%;29,2"></textarea>
  <div class="bmd-gsc-actions"><button type="button" data-audit-run>Analisar oportunidades</button><button type="button" data-audit-demo>Carregar exemplo</button></div>
  <p class="bmd-gsc-message" data-audit-message role="status">Os resultados aparecem abaixo depois que você analisar o CSV.</p>
  <div class="bmd-gsc-results" data-audit-results hidden>
   <strong style="font-size:16px">10 consultas para revisar primeiro</strong>
   <p class="bmd-gsc-summary" data-audit-summary></p>
   <div class="bmd-gsc-scroll"><table><thead><tr><th>#</th><th>Consulta</th><th>Impressões</th><th>Cliques</th><th>Posição</th><th>Próxima ação</th></tr></thead><tbody data-audit-tbody></tbody></table></div>
   <p class="bmd-gsc-postlude">A ordem é uma <strong>heurística editorial</strong> baseada em dados fornecidos por você. Uma posição média não equivale à posição de cada pesquisa. O marcador "Sinal comercial" é uma classificação por palavras do termo, não uma estimativa de CPC, RPM ou receita. Verifique intenção, SERP e conteúdo antes de alterar uma página.</p>
  </div>
  <p class="bmd-gsc-privacy"><strong>Privacidade:</strong> arquivos e consultas são processados somente no seu navegador. O botão não salva nem envia os dados. Recarregar a página descarta a análise.</p>
 </section>
 <?php return ob_get_clean();
});
add_filter('the_content',static function($content){
 if(is_admin()||is_feed()||!is_singular('post')||!in_the_loop()||!is_main_query())return $content;
 $id=(int)get_the_ID();
 if($id===2402){
  if(strpos($content,'bmd-gsc-audit')!==false)return $content;
  $widget=do_shortcode('[bmd_gsc_opportunities_2026]');
  // Keep the original legacy article, links and heading hierarchy unchanged.
  $pos=strpos($content,'</p>');
  if($pos===false)return $widget.$content;
  $pos+=4;
  return substr($content,0,$pos)."\n".$widget."\n".substr($content,$pos);
 }
 if(!in_array($id,array(8436,8571,4907),true))return $content;
 if(strpos($content,'bmd-gsc-internal-link')!==false)return $content;
 if($id===8436){$title='Antes de investir em Google Ads, verifique o SEO do site';$desc='Confira indexação, velocidade e consultas que já recebem impressões. O guia inclui uma ferramenta gratuita para priorizar correções.';}
 elseif($id===8571){$title='Clonou e editou uma página? Revise o SEO antes de publicar';$desc='Confira títulos, canonical, indexação e desempenho com um guia prático e uma análise de consultas do Search Console.';}
 else{$title='Descobriu a plataforma de um site? Agora analise o SEO';$desc='Verifique indexação, desempenho e problemas técnicos com ferramentas gratuitas e um priorizador de consultas.';}
 $html='<aside class="bmd-gsc-internal-link" style="margin:27px 0;padding:20px 23px;border:1px solid #d8e3f0;border-left:4px solid #005ff4;border-radius:0 11px 11px 0;background:#f7faff"><strong style="font-size:17px">'.esc_html($title).'</strong><p style="font-size:14px;line-height:1.65;margin:8px 0 11px;color:#53647a">'.esc_html($desc).'</p><a style="font-weight:800;color:#005ff4" href="'.esc_url(home_url('/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/#priorizador-gratuito-search-console')).'">Abrir análise SEO gratuita</a></aside>';
 return $content.$html;
},77);
