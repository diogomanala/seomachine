<?php
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
require '/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-load.php';
global $wpdb;
$slug='melhores-microfones-lapela-sem-fio-para-gravar-reels';
if((int)$wpdb->get_var("SELECT GET_LOCK('bmd_aff_microfones_20261111',5)")!==1)exit(2);
try{
$existing=get_page_by_path($slug,OBJECT,'post');
if($existing){echo "EXISTS:".$existing->ID.":".get_permalink($existing)."\n";exit;}
$url='https://raw.githubusercontent.com/diogomanala/seomachine/7cfbc4958215d560e49435a100acb8ff29aa1c39/affiliate-guides-20261111/microfones-reels.html';
$r=wp_remote_get($url,array('timeout'=>30));
if(is_wp_error($r)||wp_remote_retrieve_response_code($r)!==200){echo "ERROR:download\n";exit(3);}
$html=wp_remote_retrieve_body($r);
if(strlen($html)<9500||substr_count($html,'tag=supercompra0d-20')<3||strpos($html,'rel="nofollow sponsored noopener"')===false||strpos($html,'Transparência:')===false){echo "ERROR:guard\n";exit(4);}
$cat=get_category_by_slug('marketing-digital');
$id=wp_insert_post(array('post_type'=>'post','post_status'=>'publish','post_author'=>1,'post_name'=>$slug,'post_title'=>'Melhores microfones de lapela sem fio para gravar Reels: 3 opções','post_content'=>$html,'post_excerpt'=>'Compare três microfones sem fio para Reels, entrevistas e vídeos: Hollyland Lark M2 Combo, M2 USB-C e Lark C1. Veja conexões, vantagens e links Amazon.','post_category'=>array($cat?$cat->term_id:(int)get_option('default_category')),'comment_status'=>'closed'),true);
if(is_wp_error($id)){echo "ERROR:publish:".$id->get_error_code()."\n";exit(5);}
update_post_meta($id,'rank_math_title','Microfone de Lapela Sem Fio para Reels: 3 Opções (2026)');
update_post_meta($id,'rank_math_description','Qual microfone de lapela sem fio comprar para gravar Reels? Compare Lark M2 Combo, USB-C e Lark C1, compatibilidade e pontos de atenção.');
update_post_meta($id,'rank_math_focus_keyword','microfone de lapela sem fio para reels');
echo "PUBLISHED:".$id.":".get_permalink($id)."\n";
}finally{$wpdb->get_var("SELECT RELEASE_LOCK('bmd_aff_microfones_20261111')");}
