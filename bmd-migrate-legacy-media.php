<?php
/**
 * One-time, cursor-based cleanup of historic images still pointing to an old
 * WordPress host. Each destination must already exist in the current uploads
 * directory. Original post_content is stored privately before each edit.
 */
if (!defined('ABSPATH')) { exit; }
add_action('init', function() {
    if(get_option('bmd_legacy_assets_migrated_20261027',false))return;
    global $wpdb;
    $cursor=(int)get_option('bmd_legacy_assets_cursor_20261027',0);
    $stats=get_option('bmd_legacy_assets_stats_20261027',array('posts_updated'=>0,'urls_fixed'=>0,'missing_local_media'=>0,'processed'=>0));
    $needle='%'.$wpdb->esc_like('wordpress-8020-blogmktdigital.cloudclusters.net/wp-content/uploads/').'%';
    $sql=$wpdb->prepare("SELECT ID, post_content FROM {$wpdb->posts} WHERE ID>%d AND post_type IN ('post','page') AND post_status='publish' AND post_content LIKE %s ORDER BY ID ASC LIMIT 22",$cursor,$needle);
    $rows=$wpdb->get_results($sql);
    if(!is_array($rows))return;
    if(!$rows){
        $remaining=(int)$wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE post_type IN ('post','page') AND post_status='publish' AND post_content LIKE %s",$needle));
        $stats['remaining']=$remaining;
        $stats['completed_at']=gmdate('Y-m-d H:i:s');
        update_option('bmd_legacy_assets_stats_20261027',$stats,false);
        update_option('bmd_legacy_assets_migrated_20261027','complete',false);
        return;
    }
    foreach($rows as $record){
        $id=(int)$record->ID;
        $body=(string)$record->post_content;
        $fixed=0;$missing=0;
        $regex='~https?://wordpress-8020-blogmktdigital\.cloudclusters\.net/wp-content/uploads/[^"\\x27<>\s]+~i';
        $replaced=preg_replace_callback($regex,function($match)use(&$fixed,&$missing){
            $url=$match[0];
            $path=(string)wp_parse_url(html_entity_decode($url),PHP_URL_PATH);
            if(strpos($path,'/wp-content/uploads/')!==0)return $url;
            $destination=ABSPATH.ltrim(rawurldecode($path),'/');
            if(!is_file($destination)){$missing++;return $url;}
            $fixed++;
            $query=wp_parse_url(html_entity_decode($url),PHP_URL_QUERY);
            return home_url($path).($query?'?'.$query:'');
        },$body);
        if(is_string($replaced) && $replaced!==$body){
            $backup='_bmd_original_media_20261027';
            if(metadata_exists('post',$id,$backup) || add_post_meta($id,$backup,$body,true)){
                $result=wp_update_post(array('ID'=>$id,'post_content'=>wp_slash($replaced)),true);
                if(!is_wp_error($result) && (int)$result===$id){
                    $stats['posts_updated']++;
                    $stats['urls_fixed']+=$fixed;
                }
            }
        }
        $stats['missing_local_media']+=$missing;
        $stats['processed']++;
        $cursor=$id;
    }
    update_option('bmd_legacy_assets_cursor_20261027',$cursor,false);
    update_option('bmd_legacy_assets_stats_20261027',$stats,false);
},24);
