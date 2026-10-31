<?php
/**
 * BMD Radar WordPress — public, source-grounded plugin vulnerability checker.
 * WPVulnerability public API (CC0): https://www.wpvulnerability.com/faq/
 * Data fetched once per slug/6h; visitor version checked locally in PHP.
 * No scanning other people's sites, no private credentials, no claims of safety.
 */
if (!defined('ABSPATH')) exit;
const BMD_RADAR_SLUG='verificar-plugin-wordpress-vulneravel';
function bmd_radar_catalog() {
 return array(
 'elementor'=>'Elementor','woocommerce'=>'WooCommerce','contact-form-7'=>'Contact Form 7',
 'seo-by-rank-math'=>'Rank Math SEO','wordpress-seo'=>'Yoast SEO',
 'litespeed-cache'=>'LiteSpeed Cache','wordfence'=>'Wordfence Security',
 'wpforms-lite'=>'WPForms Lite','all-in-one-wp-migration'=>'All-in-One WP Migration',
 'updraftplus'=>'UpdraftPlus','wp-mail-smtp'=>'WP Mail SMTP',
 'classic-editor'=>'Classic Editor','redirection'=>'Redirection',
 'advanced-custom-fields'=>'Advanced Custom Fields','jetpack'=>'Jetpack',
 'wordpress-importer'=>'WordPress Importer','wp-super-cache'=>'WP Super Cache',
 'autoptimize'=>'Autoptimize','google-site-kit'=>'Site Kit by Google',
 'cookie-law-info'=>'CookieYes','duplicator'=>'Duplicator',
 'astra-sites'=>'Starter Templates','ultimate-addons-for-gutenberg'=>'Spectra',
 'easy-wp-smtp'=>'Easy WP SMTP','w3-total-cache'=>'W3 Total Cache',
 'wp-smushit'=>'Smush','all-in-one-seo-pack'=>'All in One SEO',
 'insert-headers-and-footers'=>'WPCode','polylang'=>'Polylang'
 );
}
function bmd_radar_valid_version($v) {
 return is_string($v) && strlen($v)<=35 && (bool)preg_match('/^[0-9]{1,5}(?:\.[0-9]{1,5}){0,6}(?:[-._+]?[a-zA-Z0-9.-]{1,20})?$/D',$v);
}
function bmd_radar_match($v,$record) {
 if(!is_array($record)||!isset($record['operator'])||!is_array($record['operator']))return null;
 $o=$record['operator'];$checked=0;
 foreach(array('min','max') as $edge){
  $bound=isset($o[$edge.'_version'])?trim((string)$o[$edge.'_version']):'';
  $op=isset($o[$edge.'_operator'])?strtolower((string)$o[$edge.'_operator']):'';
  if($bound==='')continue;
  if(!bmd_radar_valid_version($bound))return null;
  $map=array('lt'=>'<','lte'=>'<=','le'=>'<=','gt'=>'>','gte'=>'>=','ge'=>'>=','eq'=>'==');
  if(!isset($map[$op]))return null;
  $checked++;
  if(!version_compare($v,$bound,$map[$op]))return false;
 }
 return $checked?true:null;
}
function bmd_radar_get_feed($slug){
 $key='bmd_radar_wpv_'.md5($slug);
 $cache=get_transient($key);
 if(is_array($cache) && isset($cache['data']))return $cache;
 $endpoint='https://www.wpvulnerability.net/plugin/'.rawurlencode($slug).'/';
 $r=wp_remote_get($endpoint,array('timeout'=>12,'redirection'=>2,'headers'=>array('Accept'=>'application/json','User-Agent'=>'BMD-RadarWordPress/1.0'),'limit_response_size'=>3000000));
 if(is_wp_error($r))return new WP_Error('source_unavailable','Não foi possível consultar a fonte agora. Tente novamente mais tarde.');
 if(wp_remote_retrieve_response_code($r)!==200)return new WP_Error('source_unavailable','A fonte de dados não respondeu corretamente. Tente mais tarde.');
 $body=wp_remote_retrieve_body($r);
 if(strlen($body)>3000000)return new WP_Error('source_unavailable','A resposta da fonte excedeu o limite de segurança.');
 $j=json_decode($body,true);
 if(!is_array($j)||!isset($j['data'])||!is_array($j['data'])||isset($j['error'])&&(int)$j['error']!==0)return new WP_Error('source_invalid','A fonte retornou dados inesperados. Nenhuma conclusão foi gerada.');
 if(!array_key_exists('vulnerability',$j['data']))return new WP_Error('source_invalid','A fonte não retornou a lista de avisos necessária.');
 // Cache the full source response; all subsequent visitor versions reuse it.
 set_transient($key,$j,6*HOUR_IN_SECONDS);
 return $j;
}
function bmd_radar_source_links($sources){
 $out=array();
 if(!is_array($sources))return $out;
 foreach($sources as $entry){
  if(!is_array($entry))continue;
  $url=isset($entry['link'])?esc_url_raw($entry['link'],array('https')):'';
  $id=isset($entry['id'])?sanitize_text_field($entry['id']):'';
  if(!$id&&isset($entry['name']))$id=sanitize_text_field($entry['name']);
  if(!$url)continue;
  if(preg_match('/^[a-f0-9]{32,64}$/i',$id)) {
   $host=(string)wp_parse_url($url,PHP_URL_HOST);
   $id= $host?preg_replace('/^www\./','',$host):'Referência';
  }
  $out[]=array('label'=>mb_substr($id?:'Referência',0,90),'url'=>$url);
  if(count($out)>=3)break;
 }
 return $out;
}
function bmd_radar_extract($record,$state){
 $op=isset($record['operator']) && is_array($record['operator'])?$record['operator']:array();
 $score=null;$impact=isset($record['impact']) && is_array($record['impact'])?$record['impact']:array();
 foreach(array('cvss3','cvss4','cvss') as $key){
  if(isset($impact[$key]['score'])&&is_numeric($impact[$key]['score'])){
   $score=max(0,min(10,(float)$impact[$key]['score']));break;
  }
 }
 $name=isset($record['name'])?wp_strip_all_tags((string)$record['name']):'Aviso de vulnerabilidade';
 return array(
   'name'=>mb_substr($name,0,240),
   'range'=>array('min'=>isset($op['min_version'])?(string)$op['min_version']:'',
                  'min_op'=>isset($op['min_operator'])?(string)$op['min_operator']:'',
                  'max'=>isset($op['max_version'])?(string)$op['max_version']:'',
                  'max_op'=>isset($op['max_operator'])?(string)$op['max_operator']:'',
                  'unfixed'=>!empty($op['unfixed'])),
   'cvss'=>$score,'sources'=>bmd_radar_source_links($record['source']??array()),'state'=>$state
 );
}
function bmd_radar_query(WP_REST_Request $request){
 $slug=strtolower(trim((string)$request->get_param('slug')));
 $version=trim((string)$request->get_param('version'));
 if(!preg_match('/^[a-z0-9][a-z0-9-]{1,78}[a-z0-9]$/D',$slug)||!bmd_radar_valid_version($version))
  return new WP_Error('invalid_input','Informe o slug do plugin e uma versão válida, como 3.21.4.',array('status'=>400));
 // Lightweight privacy-preserving throttle for cache misses. No IPs stored.
 $key='bmd_radar_request_limit_'.md5((string)($_SERVER['REMOTE_ADDR']??'anonymous').wp_salt('nonce'));
 $limit=(int)get_transient($key);
 if($limit>=16)return new WP_Error('too_many_requests','Limite temporário de consultas atingido. Tente novamente em alguns minutos.',array('status'=>429));
 set_transient($key,$limit+1,10*MINUTE_IN_SECONDS);
 $feed=bmd_radar_get_feed($slug);
 if(is_wp_error($feed))return new WP_Error($feed->get_error_code(),$feed->get_error_message(),array('status'=>503));
 $data=$feed['data'];
 if(empty($data['name']) && empty($data['latest']) && empty($data['link'])){
  return new WP_Error('plugin_unknown','Este slug não foi encontrado na base. Confira o endereço do plugin no WordPress.org.',array('status'=>404));
 }
 $rows=$data['vulnerability'];
 if($rows!==null && !is_array($rows))return new WP_Error('source_invalid','Os dados de vulnerabilidade estão incompletos.',array('status'=>503));
 $counts=array('affected'=>0,'not_affected'=>0,'inconclusive'=>0);
 $affected=array();$inconclusive=array();$checked=0;
 foreach(($rows?:array()) as $entry){
  if(!is_array($entry))continue;
  $checked++;
  $match=bmd_radar_match($version,$entry);
  if($match===true){$counts['affected']++;$affected[]=bmd_radar_extract($entry,'affected');}
  elseif($match===false){$counts['not_affected']++;}
  else{$counts['inconclusive']++;$inconclusive[]=bmd_radar_extract($entry,'inconclusive');}
 }
 usort($affected,static function($a,$b){return ($b['cvss']??0)<=>($a['cvss']??0);});
 $risk=$counts['affected']?'affected':($counts['inconclusive']?'inconclusive':'no_known_match');
 $out=array(
  'slug'=>$slug,'plugin'=>sanitize_text_field((string)($data['name']?:$slug)),
  'version'=>$version,'latest'=>null, // The WPVulnerability 'latest' field is a timestamp, NOT the current plugin version.
  'risk'=>$risk,'counts'=>$counts,'total_records'=>$checked,
  'affected'=>array_slice($affected,0,16),'inconclusive'=>array_slice($inconclusive,0,6),
  'last_updated'=>!empty($feed['updated'])&&is_numeric($feed['updated'])?gmdate('c',(int)$feed['updated']):null,
  'source_url'=>'https://www.wpvulnerability.net/plugin/'.$slug.'/',
  'note'=>'Checagem de avisos públicos, não uma auditoria de segurança. Ausência de ocorrências não garante segurança.'
 );
 return rest_ensure_response($out);
}
add_action('rest_api_init',static function(){
 register_rest_route('bmd-radar/v1','/lookup',array('methods'=>'GET','callback'=>'bmd_radar_query','permission_callback'=>'__return_true','args'=>array(
  'slug'=>array('required'=>true,'type'=>'string'),
  'version'=>array('required'=>true,'type'=>'string'))));
});
add_action('init',static function(){
 $existing=get_page_by_path(BMD_RADAR_SLUG,OBJECT,'page');
 if($existing)return;
 if(get_option('bmd_radar_page_initialized_v1'))return;
 $body=<<<'HTML'
<p>Um plugin pode funcionar normalmente e ainda ter uma vulnerabilidade conhecida em determinadas versões. Se você administra um site WordPress, use este verificador gratuito para comparar a <strong>versão instalada</strong> com avisos públicos da base WPVulnerability. Não é necessário informar a URL do seu site, criar conta ou instalar plugins.</p>
[bmd_radar_wordpress]
<h2>Como descobrir a versão de um plugin WordPress?</h2>
<p>No painel do seu WordPress, acesse <strong>Plugins → Plugins instalados</strong>. Localize o plugin e veja a versão exibida abaixo do nome. Caso você administre vários sites, verifique cada instalação separadamente, já que as versões podem ser diferentes.</p>
<h2>O que significa uma vulnerabilidade encontrada?</h2>
<p>O aviso indica que a versão informada corresponde a uma faixa potencialmente afetada em um registro público. A gravidade e as condições de exploração variam. Confira os identificadores CVE, as referências e as recomendações do desenvolvedor antes de tomar decisões. Dependendo da falha, ela pode exigir autenticação, permissões específicas ou alguma configuração do plugin.</p>
<h2>O que fazer se a versão estiver afetada?</h2>
<ol><li>Faça backup verificado de arquivos e banco de dados.</li><li>Confira o changelog e a versão corrigida nas fontes oficiais e atualize o plugin quando houver correção disponível.</li><li>Teste formulários, checkout e outros recursos críticos depois da atualização.</li><li>Se não existir correção, avalie desativar temporariamente a funcionalidade vulnerável, restringir exposição ou buscar alternativa com suporte profissional.</li></ol>
<h2>O que significa não encontrar vulnerabilidades?</h2>
<p>Significa apenas que <strong>não encontramos um aviso público aplicável à versão informada</strong> dentro das faixas reconhecíveis. Não é certificado de segurança: falhas inéditas, registros incompletos, plugins privados ou configurações do servidor não são avaliados por esta ferramenta.</p>
<h2>Como verificamos os dados?</h2>
<p>Consultamos a <a href="https://www.wpvulnerability.com/" rel="noopener noreferrer" target="_blank">WPVulnerability</a>, uma base gratuita que reúne avisos de diversas fontes. O código compara versões com os operadores indicados pela base, como “menor que 5.9.5” ou “menor ou igual a 5.9.4”. Os dados externos são guardados em cache por até seis horas; o resultado da sua consulta informa a data da fonte. As consultas não analisam o site do visitante nem recolhem credenciais.</p>
<h2>Perguntas frequentes</h2>
<h3>O verificador instala alguma coisa no meu site?</h3>
<p>Não. Basta informar o nome do plugin e sua versão. Não acessamos a administração ou os arquivos do site analisado.</p>
<h3>Posso pesquisar plugins gratuitos e pagos?</h3>
<p>A busca é feita pelo identificador público (slug) na base WPVulnerability. A cobertura depende dos registros disponíveis; algumas extensões comerciais ou privadas podem não constar.</p>
<h3>O resultado é atualizado em tempo real?</h3>
<p>Os dados são renovados periodicamente, não a cada segundo. O cache da ferramenta dura até seis horas e a base de origem possui seu próprio calendário de atualizações.</p>
<h3>O que é CVE?</h3>
<p>CVE é um identificador público de uma vulnerabilidade conhecida. Ele permite consultar detalhes em fontes técnicas e avisos dos desenvolvedores, quando disponível.</p>
<h3>O resultado garante que meu WordPress está seguro?</h3>
<p>Não. A verificação considera avisos públicos por plugin e versão. Segurança também depende de atualizações, permissões, hospedagem, backups e configurações do site.</p>
<p><strong>Próximo passo:</strong> se você está avaliando a tecnologia de outro projeto, veja <a href="/como-descobrir-plataforma-do-site/">como identificar se um site usa WordPress</a>. Para verificar indexação, velocidade e SEO técnico, consulte nossas <a href="/23-ferramentas-gratuitas-de-seo-e-analise-de-websites/">23 ferramentas gratuitas de auditoria de sites</a>.</p>
HTML;
 $pid=wp_insert_post(array('post_type'=>'page','post_status'=>'publish','post_title'=>'Verificar Plugin WordPress Vulnerável: Consulte Versão e CVE','post_name'=>BMD_RADAR_SLUG,'post_content'=>$body,'post_excerpt'=>'Consulte vulnerabilidades conhecidas de plugins WordPress por versão, com CVEs e referências públicas. Ferramenta gratuita, sem cadastro ou instalação.','comment_status'=>'closed'),true);
 if(is_wp_error($pid)||!$pid)return;
 update_post_meta($pid,'rank_math_title','Plugin WordPress Vulnerável? Verifique Versão e CVE Grátis');
 update_post_meta($pid,'rank_math_description','Verifique se a versão de um plugin WordPress consta em avisos de segurança. Consulte CVEs e fontes gratuitamente, sem instalar nada ou cadastrar seu site.');
 update_post_meta($pid,'rank_math_focus_keyword','plugin wordpress vulnerável,verificar vulnerabilidade plugin wordpress');
 update_option('bmd_radar_page_initialized_v1',(int)$pid,false);
},40);
add_action('wp_enqueue_scripts',static function(){
 if(!is_page(BMD_RADAR_SLUG))return;
 foreach(array('css','js') as $ext){
  $file='assets/bmd-radar-wordpress-20261031.'.$ext;$path=__DIR__.'/'.$file;
  if(!is_file($path))continue;
  if($ext==='css')wp_enqueue_style('bmd-radar-wordpress',plugins_url($file,__FILE__),array(),(string)filemtime($path));
  else wp_enqueue_script('bmd-radar-wordpress',plugins_url($file,__FILE__),array(),(string)filemtime($path),true);
 }
},20);
add_shortcode('bmd_radar_wordpress',static function(){
 $catalog=bmd_radar_catalog();ob_start();?>
 <section class="bmd-radar" aria-label="Verificar vulnerabilidades públicas de plugins WordPress" data-endpoint="<?php echo esc_attr(rest_url('bmd-radar/v1/lookup')); ?>">
  <div class="bmd-radar-top"><span class="bmd-radar-eyebrow">RADAR WORDPRESS · VERIFICAÇÃO GRATUITA</span>
  <h2>Seu plugin tem uma vulnerabilidade conhecida?</h2>
  <p>Informe o plugin e sua versão exata. A ferramenta cruza essas informações com avisos públicos de segurança e mostra as fontes para conferência.</p></div>
  <form class="bmd-radar-form" data-form>
    <div class="bmd-radar-fields">
      <label>Plugin WordPress
        <select data-plugin>
        <option value="">Selecione um plugin conhecido</option>
        <?php foreach($catalog as $slug=>$name): ?>
         <option value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($name); ?></option>
        <?php endforeach; ?>
        <option value="outro">Outro plugin (informar slug)</option>
        </select>
      </label>
      <label data-custom-wrap hidden>Slug do plugin no WordPress.org
        <input data-custom type="text" placeholder="Ex.: contact-form-7" autocomplete="off" maxlength="80">
      </label>
      <label>Versão instalada
        <input data-version type="text" inputmode="text" placeholder="Ex.: 5.9.4" maxlength="35" required>
      </label>
    </div>
    <div class="bmd-radar-help">O slug é o identificador no endereço <code>wordpress.org/plugins/<strong>nome-do-plugin</strong>/</code>. Não informe senha, URL do painel ou dados privados.</div>
    <div class="bmd-radar-actions">
      <button type="submit" data-submit>Verificar versão <span aria-hidden="true">→</span></button>
      <span class="bmd-radar-privacy">Sem cadastro · Sem instalação · Dados abertos</span>
    </div>
  </form>
  <div class="bmd-radar-message" data-message role="status" aria-live="polite"></div>
  <div class="bmd-radar-results" data-results hidden>
    <div class="bmd-radar-summary" data-summary></div>
    <div class="bmd-radar-numbers" data-counts></div>
    <div class="bmd-radar-advisories" data-advisories></div>
    <div class="bmd-radar-meta" data-meta></div>
  </div>
  <p class="bmd-radar-disclaimer">Este verificador não executa varredura de sites e não comprova que uma instalação seja segura. Falhas inéditas e dados sem faixas de versões suficientes podem não ser classificados. Fonte: <a href="https://www.wpvulnerability.com/" rel="noopener noreferrer" target="_blank">WPVulnerability</a>, informações públicas CC0.</p>
 </section>
 <?php return ob_get_clean();
});
add_filter('the_content',static function($content){
 if(is_admin()||!is_singular('post')||!is_main_query()||!in_the_loop())return $content;
 $id=get_the_ID();
 if(!in_array((int)$id,array(4907,2402),true)||strpos($content,'/verificar-plugin-wordpress-vulneravel/')!==false)return $content;
 $lead='<aside class="bmd-radar-related" style="border:1px solid #dde7f5;border-left:3px solid #005ff4;padding:16px 18px;margin:23px 0;border-radius:9px;font-size:.94em;line-height:1.6;"><strong>Ferramenta gratuita:</strong> usa WordPress? <a href="'.esc_url(home_url('/'.BMD_RADAR_SLUG.'/')).'">Verifique se a versão dos seus plugins aparece em avisos de vulnerabilidades</a>, sem instalar nada.</aside>';
 return $content.$lead;
},83);
