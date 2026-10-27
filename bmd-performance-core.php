<?php
/**
 * Plugin Name: BMD Performance Core
 * Description: Reducao seletiva de recursos na home e em artigos; preserva formularios, AdSense, Analytics e administracao.
 * Version: 1.2.1
 * Author: Blog de Marketing Digital
 * Requires PHP: 7.4
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

function bmd_perf_is_formless_post() {
    if ( is_admin() || is_user_logged_in() || is_preview() || is_customize_preview() ) { return false; }
    if ( is_home() || is_front_page() ) { return true; }
    if ( ! is_singular( 'post' ) ) { return false; }
    $post_id = get_queried_object_id();
    if ( ! $post_id ) { return false; }
    $content = (string) get_post_field( 'post_content', $post_id );
    if ( preg_match( '~<form\b|\[contact-form|\[wpforms|\[forminator|\[gravityform|\[fluentform|\[elementor-template|recaptcha|g-recaptcha|elementor-form|form-field~i', $content ) ) { return false; }
    return true;
}

add_action( 'wp_enqueue_scripts', function () {
    if ( ! bmd_perf_is_formless_post() ) { return; }
    wp_dequeue_style( 'dashicons' );
    wp_dequeue_script( 'comment-reply' );
    wp_dequeue_script( 'wp-embed' );
}, 999 );

$GLOBALS['bmd_perf_head_buffer'] = false;
add_action( 'wp_head', function () {
    if ( ! bmd_perf_is_formless_post() ) { return; }
    $GLOBALS['bmd_perf_head_buffer'] = ob_get_level();
    ob_start();
}, -99999 );

add_action( 'wp_head', function () {
    $start_level = $GLOBALS['bmd_perf_head_buffer'];
    if ( $start_level === false || ob_get_level() !== $start_level + 1 ) { return; }
    $head = ob_get_clean();
    $GLOBALS['bmd_perf_head_buffer'] = false;
    $head = preg_replace(
        '~<script\b(?=[^>]*\bsrc\s*=\s*[\x22\x27]https?://(?:www\.)?google\.com/recaptcha/api\.js(?:\?[^\x22\x27]*)?[\x22\x27])[^>]*>\s*</script>~i',
        '',
        $head
    );
    echo $head;
}, 99999 );

foreach ( array( 'litespeed_optimize_js_excludes', 'litespeed_optm_js_defer_exc', 'litespeed_optm_gm_js_exc' ) as $hook ) {
    add_filter( $hook, function ( $excludes ) {
        return array_unique( array_merge( (array) $excludes, array( 'adsbygoogle' ) ) );
    } );
}


/* Diagnostic-only endpoint, to be removed after the audit. Fixed URLs, no secrets. */
add_action( 'rest_api_init', function () {
 register_rest_route( 'bmd-performance/v1', '/assets', array(
  'methods' => 'GET',
  'permission_callback' => '__return_true',
  'callback' => function () {
   $cache_key = 'bmd_assets_audit_20261027_3';
   $cached = get_transient($cache_key);
   if ( is_array($cached) ) return rest_ensure_response($cached);
   $result = array('version'=>'1.2.1-audit', 'pages'=>array());
   foreach(array('home'=>'/', 'post'=>'/como-clonar-um-site-completo/', 'contact'=>'/contato/') as $key=>$path) {
    $res = wp_remote_get(home_url($path),array('timeout'=>15,'redirection'=>2,'headers'=>array('Accept'=>'text/html'),'user-agent'=>'BMD-InternalAudit/1'));
    if(is_wp_error($res)){ $result['pages'][$key]=array('error'=>substr($res->get_error_message(),0,120));continue;}
    $html=(string)wp_remote_retrieve_body($res);
    $parts=explode('</head>',$html,2);$head=$parts[0];
    $jss=array();$styles=array();$inlinejs=array();$inlinestyle=array();
    preg_match_all('~<script\b([^>]*)>(.*?)</script\s*>~is',$html,$matches,PREG_SET_ORDER);
    foreach($matches as $m) {
      if(preg_match('~\bsrc\s*=\s*["\x27]([^"\x27]+)~i',$m[1],$sm)) {
       $host=wp_parse_url(html_entity_decode($sm[1]),PHP_URL_HOST);
       $p=wp_parse_url(html_entity_decode($sm[1]),PHP_URL_PATH);
       $jss[]=($host ? $host : 'local').(string)$p;
      } elseif (strlen(trim($m[2]))>0) $inlinejs[]=strlen($m[2]);
    }
    preg_match_all('~<link\b([^>]+)>~is',$head,$matches,PREG_SET_ORDER);
    foreach($matches as $m) if(preg_match('~\brel\s*=\s*["\x27][^"\x27]*stylesheet[^"\x27]*["\x27]~i',$m[1]) && preg_match('~\bhref\s*=\s*["\x27]([^"\x27]+)~i',$m[1],$ln)) {
      $host=wp_parse_url(html_entity_decode($ln[1]),PHP_URL_HOST);
      $p=wp_parse_url(html_entity_decode($ln[1]),PHP_URL_PATH);
      $styles[]=($host?$host:'local').(string)$p;
    }
    preg_match_all('~<style\b[^>]*>(.*?)</style\s*>~is',$html,$matches);
    foreach($matches[1]??array() as $style) $inlinestyle[]=strlen($style);
    $large_script_meta=array();
    foreach ( $matches as $m ) { /* matches currently refers to CSS links; inspect the scripts again */ }
    preg_match_all('~<script\b([^>]*)>(.*?)</script\s*>~is',$html,$script_matches,PREG_SET_ORDER);
    foreach($script_matches as $m) {
      $length=strlen(trim($m[2]));
      if($length<2000 || preg_match('~\bsrc\s*=~i',$m[1])) continue;
      $id='';
      $type='';
      if(preg_match('~\bid\s*=\s*["\x27]([^"\x27]+)~i',$m[1],$im)) $id=substr($im[1],0,120);
      if(preg_match('~\btype\s*=\s*["\x27]([^"\x27]+)~i',$m[1],$tm)) $type=substr($tm[1],0,70);
      $signals=array();
      foreach(array('gtag','google-analytics','googletagmanager','adsbygoogle','elementor','aiblock','ad-inserter','cookie','complianz','recaptcha','rankmath','schema.org','jQuery','oceanwp','wordfence','affiliate','wp-emoji','pixel','facebook','script src','document.write','base64') as $test) {
       if(stripos($m[2],$test)!==false) $signals[]=$test;
      }
      preg_match_all('~\b(?:function|var|let|const)\s+([a-zA-Z_$][a-zA-Z0-9_$]{2,50})~',$m[2],$idents);
      $symbol_names=array_slice(array_values(array_unique($idents[1]??array())),0,35);
      $large_script_meta[]=array('bytes'=>$length,'id'=>$id,'type'=>$type,'head'=>strpos($html,$m[0])<strlen($head),'signals'=>$signals,'symbols'=>$symbol_names,'classifiers'=>array('has_src_text'=>strpos($m[2],'src=')!==false,'has_jsonld'=>strpos($m[2],'@context')!==false));
    }
    rsort($inlinejs);rsort($inlinestyle);

    $headers=wp_remote_retrieve_headers($res);
    $result['pages'][$key]=array(
      'status'=>wp_remote_retrieve_response_code($res),
      'html_bytes'=>strlen($html),'head_bytes'=>strlen($head),
      'external_js_count'=>count($jss),'external_js'=>$jss,
      'inline_js_total_bytes'=>array_sum($inlinejs),'inline_js_top_bytes'=>array_slice($inlinejs,0,10),'large_script_meta'=>$large_script_meta,
      'stylesheets_count'=>count($styles),'stylesheets'=>$styles,
      'inline_css_total_bytes'=>array_sum($inlinestyle),'inline_css_top_bytes'=>array_slice($inlinestyle,0,10),
      'images_count'=>preg_match_all('~<img\b~i',$html),
      'iframes_count'=>preg_match_all('~<iframe\b~i',$html),
      'elementor_widget_count'=>preg_match_all('~data-widget_type=~i',$html),
      'recaptcha_in_head'=>strpos($head,'recaptcha/api.js')!==false,
      'adsense_in_head'=>strpos($head,'adsbygoogle.js')!==false,
      'litecache_header'=>isset($headers['x-litespeed-cache'])?(string)$headers['x-litespeed-cache']:'',
    );
   }
   set_transient($cache_key,$result,90);
   return rest_ensure_response($result);
  }
 ));
});


/* Temporary safe settings inspection, no option values or credentials. */
add_action('rest_api_init',function(){
 register_rest_route('bmd-performance/v1','/options',array('methods'=>'GET','permission_callback'=>'__return_true','callback'=>function(){
  global $wpdb;
  $rows=$wpdb->get_results("SELECT option_name, LENGTH(option_value) AS bytes FROM {$wpdb->options} WHERE option_name LIKE '%ad_inserter%' OR option_name LIKE 'ai_%' ORDER BY option_name LIMIT 75",ARRAY_A);
  $items=array();
  foreach($rows as $row){
   $name=$row['option_name'];$raw=get_option($name);
   $item=array('name'=>$name,'bytes'=>(int)$row['bytes'],'type'=>gettype($raw));
   if(is_array($raw)){
    $item['keys']=array_slice(array_map('strval',array_keys($raw)),0,150);
    $possible=array();
    foreach($raw as $key=>$val){
     if(preg_match('~(?:javascript|js_code|jquery|frontend|external|_file|adblock|block_detection)~i',(string)$key))
       $possible[] = array('key'=>(string)$key,'type'=>gettype($val),'scalar'=>is_scalar($val)?(in_array((string)$val,array('0','1','2','3','4','inline','file','enabled','disabled','yes','no','on','off'),true)?(string)$val:'[redacted]'):'[complex]');
    }
    $item['candidate_keys']=$possible;
   }
   $items[]=$item;
  }
$raw=get_option('ad_inserter');
  $data=is_string($raw)?json_decode($raw,true):$raw;
  $shape=array('raw_type'=>gettype($raw),'json_valid'=>json_last_error()===JSON_ERROR_NONE,'is_serialized'=>is_string($raw)&&is_serialized($raw),'root_keys'=>is_array($data)?array_keys($data):array());
  $found=array();
  $walk=function($node,$path='', $depth=0) use (&$walk,&$found) {
    if(!is_array($node)||$depth>4) return;
    foreach($node as $k=>$val){
     $p=$path===''?(string)$k:$path.'.'.$k;
     if(preg_match('~(?:javascript|jquery|js_code|frontend|external|adblock|ad_block|js_file|file_js|inline_js|script)~i',(string)$k)) {
       $found[]=array('path'=>$p,'type'=>gettype($val),'safe_value'=>is_scalar($val)&&in_array((string)$val,array('0','1','2','3','4','5','file','inline','on','off','yes','no','true','false'),true)?(string)$val:'[hidden]');
     }
     if(is_array($val))$walk($val,$p,$depth+1);
    }
  };
  $walk($data);
  $shape['candidate_paths']=array_slice($found,0,90);
  $shape['first_bytes_hex']=is_string($raw)?bin2hex(substr($raw,0,14)):'';
  $shape['base64_valid']=is_string($raw)&&base64_decode($raw,true)!==false;
  $shape['starts_html']=is_string($raw)&&strpos(ltrim($raw),'<')===0;
  $shape['starts_phpserialize']=is_string($raw)&&preg_match('~^[aOsibdN]:~',$raw)===1;
  $shape['contains_configuration_keys']=is_string($raw)?array_values(array_filter(array('javascript'=>strpos($raw,'javascript')!==false?'javascript':null,'js_code'=>strpos($raw,'js_code')!==false?'js_code':null,'frontend'=>strpos($raw,'frontend')!==false?'frontend':null,'external'=>strpos($raw,'external')!==false?'external':null,'file'=>strpos($raw,'file')!==false?'file':null,'inline'=>strpos($raw,'inline')!==false?'inline':null))):array();

  $decoded=(is_string($raw)&&strpos($raw,':AI:')===0)?base64_decode(substr($raw,4),true):false;
  $settings=($decoded!==false && is_serialized($decoded))?@unserialize($decoded,array('allowed_classes'=>false)):null;
  $shape['decoded_valid']=$decoded!==false;
  $shape['serialized_valid']=is_array($settings);
  $shape['decoded_keys']=is_array($settings)?array_slice(array_map('strval',array_keys($settings)),0,110):array();
  $flat=array();
  if(is_array($settings)){
   foreach($settings as $key=>$v){
    if(preg_match('~(?:javascript|js|file|external|inline|header|footer|adblock|google|block|banner|output|inject)~i',(string)$key)){
     $flat[]=array('key'=>(string)$key,'kind'=>gettype($v),'value'=>(is_scalar($v)&&preg_match('~^[0-9]{1,3}$|^on$|^off$|^yes$|^no$~i',(string)$v))?(string)$v:'[hidden]');
    }
   }
  }
  $shape['matching_flags']=array_slice($flat,0,100);

  $shape['global_option_keys']=isset($settings['global'])&&is_array($settings['global'])?array_slice(array_map('strval',array_keys($settings['global'])),0,140):array();
  $blocks=array();
  foreach($settings as $k=>$v){
   if(!ctype_digit((string)$k)||!is_array($v))continue;
   $v_str=serialize($v);
   $signals=array();
   foreach(array('sitecloner','autodminsta','love','freelove','hostinger','ferramentas','googlesyndication','adsbygoogle','pub-3155268230972642','iframe','amazon','referral') as $token){
    if(stripos($v_str,$token)!==false)$signals[]=$token;
   }
   $blocks[]=array(
    'slot'=>(string)$k,
    'bytes'=>strlen($v_str),
    'top_keys'=>array_slice(array_map('strval',array_keys($v)),0,55),
    'signals'=>$signals
   );
  }
  $shape['blocks']=array_slice($blocks,0,95);


  return rest_ensure_response(array('ad_inserter_shape'=>$shape));

 }));
});
