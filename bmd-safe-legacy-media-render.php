<?php
/**
 * Safe, just-in-time correction of stale old-host media src on public articles.
 * No DB writes or network calls; only maps to local upload files that exist.
 */
if (!defined('ABSPATH')) { exit; }
add_filter('the_content',function($html){
    if(is_admin()||!is_singular()||is_feed()||strpos($html,'wordpress-8020-blogmktdigital.cloudclusters.net')===false)return $html;
    return preg_replace_callback(
      '~https?://wordpress-8020-blogmktdigital\.cloudclusters\.net/wp-content/uploads/[^"\\x27<>\s]+~i',
      function($m){
        $url=html_entity_decode($m[0]);
        $path=(string)wp_parse_url($url,PHP_URL_PATH);
        if(strpos($path,'/wp-content/uploads/')!==0)return $m[0];
        $filename=ABSPATH.ltrim(rawurldecode($path),'/');
        if(!is_file($filename))return $m[0];
        $query=(string)wp_parse_url($url,PHP_URL_QUERY);
        return home_url($path).($query!==''?'?'.$query:'');
      },$html);
}, 9);
