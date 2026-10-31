<?php
/**
 * BMD / Search Console intent: WordPress platform detection based on public HTML.
 * User pastes code or chooses a local file. No uploads, remote crawling or user tracking.
 * Read-only enhancement of one established WordPress article, reversible via plugin.
 */
if(!defined('ABSPATH'))exit;
add_action('wp_enqueue_scripts',static function(){
 if(!is_singular('post')||(int)get_queried_object_id()!==4907)return;
 foreach(array('css','js') as $ext){
  $file='assets/bmd-technology-detector-20261031.'.$ext;
  $path=__DIR__.'/'.$file;if(!is_file($path))continue;
  if($ext==='css')wp_enqueue_style('bmd-technology-detector-2026',plugins_url($file,__FILE__),array(),(string)filemtime($path));
  else wp_enqueue_script('bmd-technology-detector-2026',plugins_url($file,__FILE__),array(),(string)filemtime($path),true);
 }
},20);
add_shortcode('bmd_technology_detector_2026',static function(){
 ob_start();?>
 <section class="bmd-technology-detector" id="detector-gratuito-wordpress" aria-labelledby="bmd-technology-title">
 <span class="bmd-tech-kicker">FERRAMENTA GRATUITA • ANÁLISE LOCAL</span>
 <h2 id="bmd-technology-title">Esse site usa WordPress? Analise os sinais do HTML</h2>
 <p>Abra o site que você tem permissão para inspecionar, pressione <strong>Ctrl+U</strong> no Windows ou <strong>Option+Command+U</strong> no Chrome para macOS e copie o HTML. Cole abaixo para procurar pistas de WordPress, Elementor, Shopify, Webflow, Framer e frameworks modernos.</p>
 <label for="bmd-technology-source" class="bmd-tech-input-label">Cole o código-fonte HTML da página</label>
 <textarea id="bmd-technology-source" data-technology-html rows="6" spellcheck="false" placeholder='<!doctype html><html><head>...</head><body>...</body></html>'></textarea>
 <div class="bmd-tech-toolbar">
 <button type="button" data-technology-submit>Analisar HTML</button>
 <button type="button" data-technology-demo>Exemplo WordPress</button>
 <button type="button" data-technology-clear>Limpar</button>
 </div>
 <label class="bmd-tech-input-label" for="bmd-technology-file">Ou selecione um arquivo HTML salvo no computador
 <input type="file" id="bmd-technology-file" data-technology-file accept=".html,.htm,text/html" aria-label="Analisar arquivo HTML local">
 </label>
 <p class="bmd-tech-status" data-technology-status role="status">O código é processado somente no seu navegador.</p>
 <div class="bmd-tech-results" data-technology-results hidden>
 <h3>Sinais públicos encontrados</h3>
 <div class="bmd-tech-list" data-technology-list></div>
 </div>
 <p class="bmd-tech-footer"><strong>Limitações:</strong> esta análise não acessa a URL, a aba Network nem o backend. Não confirma o CMS oculto, o construtor original de IA, todos os plugins ou serviços internos. Uma página pode usar várias tecnologias ao mesmo tempo. Para conferir a detecção, compare com o <a href="https://www.wappalyzer.com/lookup/" target="_blank" rel="nofollow noopener noreferrer">Wappalyzer</a> e com os recursos carregados no DevTools. Seu HTML não é transmitido ao servidor.</p>
 </section>
 <?php return ob_get_clean();
});
add_filter('the_content',static function($content){
 if(is_admin()||!is_singular('post')||!is_main_query()||!in_the_loop()||is_feed()||is_preview()||(int)get_the_ID()!==4907)return $content;
 if(strpos($content,'class="bmd-technology-detector"')!==false)return $content;
 $needle='<div class="answer">';
 if(substr_count($content,$needle)!==1)return $content;
 $widget=do_shortcode('[bmd_technology_detector_2026]');
 return str_replace($needle,$widget."\n".$needle,$content);
},58);
