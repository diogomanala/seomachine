<?php
/**
 * BMD Radar: recent dated advisories for a *curated* list of WordPress plugins.
 * Data source: public WPVulnerability API, per-plugin, cached in existing BMD Radar.
 * No key, no scraping, no claims of exhaustive coverage or site vulnerability.
 * Authoring note: source dates are dates of public source references, not necessarily
 * the initial discovery/disclosure date of the vulnerability.
 */
if (!defined('ABSPATH')) exit;

function bmd_radar_alert_watchlist() {
 return array(
  'elementor'=>'Elementor',
  'woocommerce'=>'WooCommerce',
  'litespeed-cache'=>'LiteSpeed Cache',
  'seo-by-rank-math'=>'Rank Math SEO',
  'wordfence'=>'Wordfence Security',
  'contact-form-7'=>'Contact Form 7',
  'wpforms-lite'=>'WPForms Lite',
  'wordpress-seo'=>'Yoast SEO',
  'updraftplus'=>'UpdraftPlus',
  'wp-mail-smtp'=>'WP Mail SMTP',
 );
}

function bmd_radar_alert_source_date($entry) {
 if (!is_array($entry)) return array(null,null);
 $dates=array();$bestlink='';
 $sources=isset($entry['source'])&&is_array($entry['source'])?$entry['source']:array();
 foreach ($sources as $source) {
  if(!is_array($source))continue;
  $date=isset($source['date'])?(string)$source['date']:'';
  if (!preg_match('/^20[0-9]{2}-(0[1-9]|1[0-2])-([0-2][0-9]|3[01])$/D',$date))continue;
  $stamp=strtotime($date.' 12:00:00 UTC');
  if(!$stamp || $stamp>time()+86400)continue;
  $link=isset($source['link'])?esc_url_raw($source['link'],array('https')):'';
  if(!isset($dates[$stamp]))$dates[$stamp]=$link;
  elseif(!$dates[$stamp]&&$link)$dates[$stamp]=$link;
 }
 if (!$dates)return array(null,null);
 krsort($dates,SORT_NUMERIC);
 $latest=(int)array_key_first($dates);
 $bestlink=(string)$dates[$latest];
 if (!$bestlink) foreach($sources as $source) {
  if(is_array($source)&&!empty($source['link'])){
   $bestlink=esc_url_raw($source['link'],array('https'));if($bestlink)break;
  }
 }
 return array($latest,$bestlink);
}

function bmd_radar_alert_collect($limit=10){
 $list=bmd_radar_alert_watchlist();
 $rows=array();$ok=0;$errors=array();$start=time();$cutoff=$start-90*DAY_IN_SECONDS;
 $total=count($list);$count=0;
 foreach($list as $slug=>$label){
  if(++$count>$limit)break;
  $feed=bmd_radar_get_feed($slug);
  if(is_wp_error($feed)){
   $errors[]=array('plugin'=>$label,'error'=>'Fonte temporariamente indisponível');
   continue;
  }
  $ok++;
  $entries=isset($feed['data']['vulnerability'])&&is_array($feed['data']['vulnerability'])?$feed['data']['vulnerability']:array();
  foreach($entries as $record){
   if(!is_array($record))continue;
   list($stamp,$refurl)=bmd_radar_alert_source_date($record);
   if(!$stamp||$stamp<$cutoff)continue;
   $name=isset($record['name'])?trim(wp_strip_all_tags((string)$record['name'])):'';
   if($name==='')continue;
   $id=isset($record['uuid'])?(string)$record['uuid']:md5($slug.'|'.$name);
   $o=isset($record['operator'])&&is_array($record['operator'])?$record['operator']:array();
   $min=isset($o['min_version'])?trim((string)$o['min_version']):'';
   $max=isset($o['max_version'])?trim((string)$o['max_version']):'';
   $minop=isset($o['min_operator'])?(string)$o['min_operator']:'';
   $maxop=isset($o['max_operator'])?(string)$o['max_operator']:'';
   $score=null;
   $impact=isset($record['impact'])&&is_array($record['impact'])?$record['impact']:array();
   foreach(array('cvss4','cvss3','cvss') as $k){
    if(isset($impact[$k]['score'])&&is_numeric($impact[$k]['score'])){$score=max(0,min(10,(float)$impact[$k]['score']));break;}
   }
   $rows[$slug.'|'.$id]=array(
    'slug'=>$slug,'plugin'=>$label,'title'=>mb_substr($name,0,215),
    'stamp'=>$stamp,'range'=>array('min'=>$min,'min_op'=>$minop,'max'=>$max,'max_op'=>$maxop),
    'score'=>$score,'ref_url'=>$refurl?:'https://www.wpvulnerability.net/plugin/'.rawurlencode($slug).'/',
    'source_url'=>'https://www.wpvulnerability.net/plugin/'.rawurlencode($slug).'/'
   );
  }
 }
 $rows=array_values($rows);
 usort($rows,static function($a,$b){return $b['stamp']<=>$a['stamp'] ?: strcmp($a['plugin'],$b['plugin']);});
 $snapshot=array(
  'last_checked'=>time(),'window_days'=>90,'plugins_checked'=>$ok,'plugins_monitored'=>$total,
  'errors'=>$errors,'total_recent'=>count($rows),'items'=>array_slice($rows,0,30),
  'partial'=>$limit<$total||$ok<$count,
 );
 if($ok>=3) {
  update_option('bmd_radar_recent_snapshot_v1',$snapshot,false);
  // Purge just the tool landing page when the snapshot changes, if LiteSpeed is present.
  do_action('litespeed_purge_url',home_url('/'.BMD_RADAR_SLUG.'/'));
 }
 return $snapshot;
}
add_action('bmd_radar_recent_refresh_event',static function(){
 bmd_radar_alert_collect(10);
});
add_action('init',static function(){
 if(!wp_next_scheduled('bmd_radar_recent_refresh_event')){
  wp_schedule_event(time()+180,'twicedaily','bmd_radar_recent_refresh_event');
 }
},55);

function bmd_radar_recent_snapshot(){
 $saved=get_option('bmd_radar_recent_snapshot_v1',null);
 if(!is_array($saved)||empty($saved['last_checked'])){
  // Seed only five public plugin endpoints on first visit to keep initial latency bounded.
  $fresh=bmd_radar_alert_collect(5);
  if(!empty($fresh['items']))return $fresh;
  $saved=get_option('bmd_radar_recent_snapshot_v1',null);
 }
 if(is_array($saved)&&isset($saved['last_checked']) && time()-(int)$saved['last_checked']>12*HOUR_IN_SECONDS) {
  if(!wp_next_scheduled('bmd_radar_recent_refresh_event'))wp_schedule_single_event(time()+20,'bmd_radar_recent_refresh_event');
 }
 return is_array($saved)?$saved:array();
}
function bmd_radar_alert_range($range){
 if(!is_array($range))return '';
 $map=array('lt'=>'<','lte'=>'≤','le'=>'≤','gt'=>'>','gte'=>'≥','ge'=>'≥','eq'=>'=');
 $bits=array();
 foreach(array('min','max') as $edge){
  $v=isset($range[$edge])?(string)$range[$edge]:'';
  $op=isset($range[$edge.'_op'])?(string)$range[$edge.'_op']:'';
  if($v!==''&&isset($map[$op]) && bmd_radar_valid_version($v))$bits[]=$map[$op].' '.$v;
 }
 return $bits?implode(' e ',$bits):'Faixa não especificada';
}
function bmd_radar_recent_html(){
 $snapshot=bmd_radar_recent_snapshot();$list=bmd_radar_alert_watchlist();
 $cards=isset($snapshot['items'])&&is_array($snapshot['items'])?$snapshot['items']:array();
 ob_start();?>
 <section id="radar-alertas-recentes" class="bmd-radar-feed" aria-labelledby="bmd-radar-feed-heading">
  <div class="bmd-radar-feed-head">
   <span class="bmd-radar-feed-eyebrow">RADAR WORDPRESS · DADOS PÚBLICOS</span>
   <h2 id="bmd-radar-feed-heading">Alertas recentes em plugins WordPress</h2>
   <p>Avisos com referências datadas nos <strong>últimos 90 dias</strong>, encontrados entre os plugins que monitoramos. A data indicada é a da referência publicada, que pode ser posterior à descoberta da falha. <strong>Não é uma lista de todos os plugins do WordPress.</strong></p>
  </div>
  <div class="bmd-radar-feed-controls">
   <label for="bmd-radar-feed-filter">Filtrar por plugin
    <select id="bmd-radar-feed-filter" data-radar-feed-filter>
     <option value="">Todos os plugins monitorados</option>
     <?php foreach($list as $slug=>$name):?>
      <option value="<?php echo esc_attr($slug); ?>"><?php echo esc_html($name); ?></option>
     <?php endforeach;?>
    </select>
   </label>
   <div class="bmd-radar-feed-datestamp">
    <?php if(!empty($snapshot['last_checked'])):?>
     <span>Última consulta: <time datetime="<?php echo esc_attr(gmdate('c',(int)$snapshot['last_checked'])); ?>"><?php echo esc_html(wp_date('d/m/Y H:i',(int)$snapshot['last_checked'],new DateTimeZone('America/Sao_Paulo'))); ?></time> BRT</span>
     <span><?php echo (int)($snapshot['plugins_checked']??0); ?> de <?php echo count($list); ?> plugins consultados</span>
    <?php else:?>
     <span>Aguardando a primeira consulta de dados.</span>
    <?php endif;?>
   </div>
  </div>
  <?php if(!empty($snapshot['partial'])):?>
   <p class="bmd-radar-feed-partial">Coleta parcial: alguns plugins ainda não foram verificados nesta atualização. A lista será ampliada na próxima sincronização automática.</p>
  <?php endif;?>
  <div class="bmd-radar-feed-cards" data-radar-feed-cards>
   <?php if($cards): foreach($cards as $item):
    $severity=isset($item['score'])&&$item['score']!==null?(float)$item['score']:null;
    $range=bmd_radar_alert_range($item['range']??array());
    $url=isset($item['ref_url'])?esc_url($item['ref_url']):'';
    $plugin=isset($item['plugin'])?(string)$item['plugin']:'Plugin';
   ?>
    <article class="bmd-radar-feed-card" data-radar-feed-slug="<?php echo esc_attr($item['slug']); ?>">
     <div class="bmd-radar-feed-card-top">
      <span class="bmd-radar-feed-plugin"><?php echo esc_html($plugin); ?></span>
      <time datetime="<?php echo esc_attr(gmdate('Y-m-d',(int)$item['stamp'])); ?>"><?php echo esc_html(wp_date('d/m/Y',(int)$item['stamp'],new DateTimeZone('America/Sao_Paulo'))); ?></time>
     </div>
     <h3><?php echo esc_html((string)$item['title']); ?></h3>
     <div class="bmd-radar-feed-tags">
      <span class="bmd-radar-feed-range"><?php echo esc_html('Faixa informada: '.$range); ?></span>
      <?php if($severity!==null):?>
       <span class="bmd-radar-feed-score"><?php echo esc_html('CVSS '.number_format($severity,1,',','.')); ?></span>
      <?php endif;?>
     </div>
     <div class="bmd-radar-feed-footer">
      <a href="<?php echo $url; ?>" target="_blank" rel="nofollow noopener noreferrer">Consultar a referência</a>
      <a href="#bmd-radar-app" data-radar-feed-check="<?php echo esc_attr($item['slug']); ?>">Verificar minha versão</a>
     </div>
    </article>
   <?php endforeach; else:?>
    <p class="bmd-radar-feed-empty">Não encontramos avisos com referências datadas neste período entre os plugins consultados, ou a coleta inicial ainda não foi concluída. Isso não significa ausência de vulnerabilidades.</p>
   <?php endif;?>
  </div>
  <p class="bmd-radar-feed-no-filter" data-radar-feed-empty-filter hidden>Nenhum aviso deste plugin aparece na lista recente. Você ainda pode verificar uma versão específica na ferramenta abaixo.</p>
  <p class="bmd-radar-feed-foot">Fonte: <a href="https://www.wpvulnerability.com/" target="_blank" rel="nofollow noopener noreferrer">WPVulnerability</a>. As informações são atualizadas em cache, não em tempo real. A simples presença de um aviso não significa que toda instalação do plugin está vulnerável: isso depende da versão, configuração e condições descritas nas referências.</p>
 </section>
 <?php return ob_get_clean();
}
add_filter('the_content',static function($content){
 if(is_admin()||is_feed()||is_preview()||!is_page(BMD_RADAR_SLUG)||!is_main_query()||!in_the_loop())return $content;
 if(strpos($content,'id="radar-alertas-recentes"')!==false)return $content;
 $pos=strpos($content,'<section class="bmd-radar"');
 if($pos===false)return $content;
 return substr_replace($content,bmd_radar_recent_html()."\n", $pos,0);
},74);
add_action('wp_enqueue_scripts',static function(){
 if(!is_page(BMD_RADAR_SLUG))return;
 foreach(array('css','js') as $ext){
  $file='assets/bmd-radar-alerts-20261031.'.$ext;
  $path=__DIR__.'/'.$file;if(!is_file($path))continue;
  if($ext==='css')wp_enqueue_style('bmd-radar-alerts',plugins_url($file,__FILE__),array(),(string)filemtime($path));
  else wp_enqueue_script('bmd-radar-alerts',plugins_url($file,__FILE__),array(),(string)filemtime($path),true);
 }
},22);
