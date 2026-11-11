<?php
/**
 * Plugin Name: BMD PS5 GTA6 - Official Product Imagery
 * Description: Scoped editorial images for the three PS5 product comparison cards.
 * Version: 1.0.0
 */
if (!defined('ABSPATH')) exit;
function bmd_ps5_product_imagery_is_target() {
    if (!is_singular('post')) return false;
    $post = get_queried_object();
    return $post instanceof WP_Post && (int)$post->ID === 9175 &&
       $post->post_name === 'qual-ps5-comprar-para-gta-6';
}
function bmd_ps5_product_imagery_items(){
    return array(
       1 => array(
          'asin'=>'B0CSPNDPKG',
          'label'=>'PS5 Slim com leitor de discos e controle DualSense',
          'url'=>'https://gmedia.playstation.com/is/image/SIEPDC/ps5-slim-disc-dualsense-image-block-02-en-18nov24?$800px$',
          'caption'=>'Imagem de referência do PS5 com leitor. O kit e os jogos incluídos dependem da oferta.'
       ),
       2 => array(
          'asin'=>'B0FPGF9J2J',
          'label'=>'PS5 Slim Edição Digital sem leitor de discos e controle DualSense',
          'url'=>'https://gmedia.playstation.com/is/image/SIEPDC/ps5-slim-digital-dualsense-image-block-02-en-18nov24?$800px$',
          'caption'=>'Imagem de referência do PS5 Edição Digital. Confira a capacidade e a versão no anúncio.'
       ),
       3 => array(
          'asin'=>'B0DH8J5YGH',
          'label'=>'PlayStation 5 Pro sem leitor de discos e controle DualSense',
          'url'=>'https://gmedia.playstation.com/is/image/SIEPDC/ps5-pro-dualsense-image-block-02-en-18nov24?$800px$',
          'caption'=>'Imagem de referência do PS5 Pro. Leitor de discos vendido separadamente.'
       )
    );
}
add_filter('the_content', static function($content){
    if(is_admin() || !is_main_query() || !in_the_loop() || !bmd_ps5_product_imagery_is_target())return $content;
    if(strpos($content,'bmd-ps5-product-figure')!==false)return $content;
    $items=bmd_ps5_product_imagery_items();
    foreach($items as $n=>$info){
        $pattern='~(<article\s+class="bmd-aff-card"\s+id="bmd-aff-item-'.(int)$n.'">)(.*?)(</article>)~s';
        $content=preg_replace_callback($pattern,static function($m)use($info){
            $card=$m[2];
            // Prevent mismatch between the actual Amazon product/variant and its illustration.
            if(strpos($card,'data-asin="'.esc_attr($info['asin']).'"')===false)return $m[0];
            $endTop=strpos($card,'</div>');
            if($endTop===false || strpos($card,'<h3>')<$endTop)return $m[0];
            $figure='<figure class="bmd-ps5-product-figure">'
               .'<div class="bmd-ps5-product-media"><img src="'.esc_url($info['url']).'"'
               .' alt="'.esc_attr($info['label']).'"'
               .' loading="lazy" decoding="async" fetchpriority="low" width="800" height="600"></div>'
               .'<figcaption>'.esc_html($info['caption']).'</figcaption></figure>';
            $card=substr_replace($card,"\n".$figure."\n",$endTop+strlen('</div>'),0);
            return $m[1].$card.$m[3];
        },$content,1);
    }
    return $content;
},99);
add_action('wp_head',static function(){
    if(!bmd_ps5_product_imagery_is_target())return;
    echo <<<'CSS'
<style id="bmd-ps5-product-imagery-css">
#bmd-aff-v2 .bmd-aff-card .bmd-ps5-product-figure {
    margin:0 0 16px!important;
    padding:0!important;
    background:#f6f8fb!important;
    border:1px solid #e0e6ec!important;
    border-radius:13px!important;
    overflow:hidden!important;
    box-shadow:none!important;
}
#bmd-aff-v2 .bmd-aff-card .bmd-ps5-product-media{
    min-height:165px;
    aspect-ratio:5/4;
    display:flex;
    align-items:center;
    justify-content:center;
    padding:8px 12px;
    background:linear-gradient(160deg,#fff,#ecf1f5);
}
#bmd-aff-v2 .bmd-aff-card .bmd-ps5-product-media img{
    width:100%!important;
    height:100%!important;
    max-width:100%!important;
    max-height:226px!important;
    display:block!important;
    object-fit:contain!important;
    object-position:center!important;
    border:0!important;
    box-shadow:none!important;
    filter:none!important;
    border-radius:0!important;
}
#bmd-aff-v2 .bmd-aff-card .bmd-ps5-product-figure figcaption{
    color:#5c6a77!important;
    background:#f7f9fa!important;
    border-top:1px solid #e4e9ed!important;
    padding:9px 11px!important;
    margin:0!important;
    font-size:10px!important;
    line-height:1.45!important;
    font-weight:400!important;
}
@media(max-width:700px){
  #bmd-aff-v2 .bmd-aff-card .bmd-ps5-product-media {min-height:180px;aspect-ratio:4/3;}
  #bmd-aff-v2 .bmd-aff-card .bmd-ps5-product-media img {max-height:230px!important;}
}
</style>
CSS;
},117);
