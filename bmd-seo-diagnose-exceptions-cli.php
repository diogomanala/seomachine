<?php
/** Internal read-only CLI diagnosis, no web access or modifications. */
if (PHP_SAPI !== 'cli') { http_response_code(404); exit; }
require dirname(__DIR__,3).'/wp-load.php';
$state=get_option('bmdseo_bulk_state_20261029_v1',array());
$out=array('source_status'=>$state['status']??'missing','items'=>array());
foreach (($state['issues']??array()) as $issue) {
    $kind=(string)($issue['kind']??'');
    if (!in_array($kind,array('arquivo_host_antigo','link_interno_sem_destino_confirmado'),true))continue;
    $url=(string)($issue['url']??'');
    $parts=wp_parse_url($url);
    $path=is_array($parts)?(string)($parts['path']??''):$url;
    $slug=trim($path,'/');
    $post=get_page_by_path($slug,OBJECT,array('post','page'));
    $last=basename(rtrim($path,'/'));
    $terms=array();
    foreach (get_taxonomies(array('public'=>true),'names') as $tax) {
        $term=get_term_by('slug',$last,$tax);
        if ($term && !is_wp_error($term)) {
            $link=get_term_link($term);
            $terms[$tax]=!is_wp_error($link)?$link:null;
        }
    }
    $dest=array();
    foreach (array('https://blogdemarketingdigital.com.br'.$path) as $candidate) {
        $dest['wp_post_id']=(int)url_to_postid($candidate);
    }
    if (strpos($path,'/wp-content/uploads/')===0) {
        $uploads=wp_get_upload_dir();
        $relative=substr($path,strlen('/wp-content/uploads/'));
        $dest['file_exists']=is_file(trailingslashit($uploads['basedir']).rawurldecode($relative));
    }
    $out['items'][]=array('kind'=>$kind,'post_id'=>$issue['post_id']??null,'url'=>$url,'path'=>$path,
        'published_post'=>$post&&$post->post_status==='publish'?get_permalink($post):null,
        'terms'=>$terms,'destination'=>$dest);
}
echo wp_json_encode($out, JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
