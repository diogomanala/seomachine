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
   $cache_key = 'bmd_assets_audit_20261027_2';
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
      $large_script_meta[]=array('bytes'=>$length,'id'=>$id,'type'=>$type,'head'=>strpos($html,$m[0])<strlen($head),'signals'=>$signals,'classifiers'=>array('has_src_text'=>strpos($m[2],'src=')!==false,'has_jsonld'=>strpos($m[2],'@context')!==false));
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
