<?php
/**
 * BMD 28/10/2026 — premium photographic good-morning cards.
 * Sources are six photos licensed through Unsplash and uploaded as original
 * JPEG attachments to the site's own media library. We create an original
 * text overlay on the client; no Canva content, watermark, paid API or upload.
 * Only the public post 4977 is changed at render time, without modifying DB.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_bomdia_photo_cards(){
    return array(
        array(
            'id'=>'aurora', 'name'=>'Amanhecer coral', 'message'=>'Que seu dia seja incrível.',
            'src'=>'/wp-content/uploads/2026/10/Por-do-sol-com-capim-ao-vento-para-cartao-de-bom-dia.jpg',
            'alt'=>'Pôr do sol coral e capim em silhueta, com mensagem de bom dia',
            'author'=>'Katelyn G','credit'=>'https://unsplash.com/@katelyn_g',
            'dark'=>1
        ),
        array(
            'id'=>'flores', 'name'=>'Flores ao sol', 'message'=>'Que hoje floresçam coisas boas.',
            'src'=>'/wp-content/uploads/2026/10/Flores-rosadas-ao-entardecer-para-mensagem-de-bom-dia.jpg',
            'alt'=>'Flores rosadas em luz suave com mensagem de bom dia',
            'author'=>'Irina Iriser','credit'=>'https://unsplash.com/@iriser',
            'dark'=>1
        ),
        array(
            'id'=>'montanhas', 'name'=>'Manhã nas montanhas', 'message'=>'Calma no coração e coragem para seguir.',
            'src'=>'/wp-content/uploads/2026/10/Montanhas-e-nevoa-ao-amanhecer-para-bom-dia.jpg',
            'alt'=>'Montanhas com névoa ao amanhecer e frase motivacional de bom dia',
            'author'=>'ki Zhang','credit'=>'https://unsplash.com/@2879686ki',
            'dark'=>1
        ),
        array(
            'id'=>'praia', 'name'=>'Luz do litoral', 'message'=>'Um dia leve, bonito e cheio de paz.',
            'src'=>'/wp-content/uploads/2026/10/Paisagem-praiana-de-amanhecer-para-bom-dia.jpg',
            'alt'=>'Litoral iluminado pelo amanhecer e frase de bom dia',
            'author'=>'Heather Newsom','credit'=>'https://unsplash.com/@native7photo',
            'dark'=>1
        ),
        array(
            'id'=>'campo', 'name'=>'Fim de tarde dourado', 'message'=>'Novos caminhos, novas possibilidades.',
            'src'=>'/wp-content/uploads/2026/10/Campo-ao-entardecer-para-bom-dia.jpg',
            'alt'=>'Vegetação em silhueta diante do pôr do sol e frase de bom dia',
            'author'=>'Mario Beducci','credit'=>'https://unsplash.com/@mariobeducci',
            'dark'=>1
        ),
        array(
            'id'=>'flor-branca', 'name'=>'Um dia especial', 'message'=>'Que a alegria encontre você hoje.',
            'src'=>'/wp-content/uploads/2026/10/Flor-branca-iluminada-pelo-sol-para-bom-dia.jpg',
            'alt'=>'Flor branca em luz suave com mensagem carinhosa de bom dia',
            'author'=>'Malachi Brooks','credit'=>'https://unsplash.com/@mebrooks01',
            'dark'=>1
        )
    );
}
function bmd_bomdia_photos_page(){
    return is_singular('post') && (int)get_queried_object_id()===4977
        && (string)get_post_field('post_name',4977)==='melhores-imagens-de-bom-dia-para-compartilhar-no-whatsapp';
}
add_action('wp_enqueue_scripts',function(){
    if (!bmd_bomdia_photos_page()) { return; }
    $css=__DIR__.'/assets/bmd-bomdia-photos.css';
    $js=__DIR__.'/assets/bmd-bomdia-photos.js';
    wp_enqueue_style('bmd-bomdia-photo-fonts','https://fonts.googleapis.com/css2?family=Allura&family=DM+Sans:wght@400;500;600;700&display=swap',array(),null);
    if(is_file($css)){
        wp_enqueue_style('bmd-bomdia-photos',plugins_url('assets/bmd-bomdia-photos.css',__FILE__),array('bmd-bomdia-photo-fonts'),filemtime($css));
    }
    if(is_file($js)){
        wp_enqueue_script('bmd-bomdia-photos',plugins_url('assets/bmd-bomdia-photos.js',__FILE__),array(),filemtime($js),true);
    }
},17);

add_shortcode('bmd_bomdia_photo_gallery',function(){
    $cards=bmd_bomdia_photo_cards();
    ob_start(); ?>
<section class="bmd-photo-collection" id="imagens-bom-dia-fotograficas" aria-labelledby="bmd-photo-heading">
 <div class="bmd-photo-intro">
  <div><span class="bmd-photo-kicker">NOVA COLEÇÃO · FOTOS + FRASES</span>
   <h3 id="bmd-photo-heading">Bom dia com a beleza de um novo amanhecer</h3>
   <p>Artes com fotografias de paisagens, caligrafia elegante e mensagens que você pode editar. Escolha sua favorita e baixe a imagem pronta em PNG, sem marca-d'água.</p></div>
  <span class="bmd-photo-counter">06 novas imagens</span>
 </div>
 <div class="bmd-photo-grid">
 <?php foreach($cards as $card): $id=esc_attr($card['id']); ?>
  <figure class="bmd-photo-card" data-bmd-card="<?php echo $id; ?>">
   <div class="bmd-photo-visual">
    <img loading="lazy" decoding="async" width="1200" height="800"
      src="<?php echo esc_url(home_url($card['src'])); ?>"
      alt="<?php echo esc_attr($card['alt']); ?>"/>
    <div class="bmd-photo-shade" aria-hidden="true"></div>
    <div class="bmd-photo-lettering">
      <span class="bmd-photo-script">Bom dia</span>
      <span class="bmd-photo-swash" aria-hidden="true"></span>
      <span class="bmd-photo-caption"><?php echo esc_html($card['message']); ?></span>
    </div>
   </div>
   <figcaption>
    <div class="bmd-photo-cardinfo">
      <strong><?php echo esc_html($card['name']); ?></strong>
      <small>Foto: <a href="<?php echo esc_url($card['credit']); ?>" rel="noopener noreferrer" target="_blank"><?php echo esc_html($card['author']); ?></a> · Unsplash</small>
    </div>
    <div class="bmd-photo-actions">
      <button class="bmd-photo-download" type="button" data-bmd-download="<?php echo $id; ?>">Baixar PNG <span aria-hidden="true">↓</span></button>
      <button class="bmd-photo-edit" type="button" data-bmd-edit="<?php echo $id; ?>" aria-expanded="false" aria-controls="bmd-photo-editor-<?php echo $id; ?>">Editar frase</button>
    </div>
    <div class="bmd-photo-editor" id="bmd-photo-editor-<?php echo $id; ?>" hidden>
      <label for="bmd-photo-text-<?php echo $id; ?>">Mensagem da imagem</label>
      <textarea id="bmd-photo-text-<?php echo $id; ?>" rows="2" maxlength="105"><?php echo esc_textarea($card['message']); ?></textarea>
      <small>O texto aparece na prévia e será incluído no PNG.</small>
    </div>
    <span class="bmd-photo-feedback" aria-live="polite"></span>
   </figcaption>
  </figure>
 <?php endforeach; ?>
 </div>
 <p class="bmd-photo-legal">As fotografias são licenciadas pelo Unsplash, com créditos aos autores. A composição e a tipografia foram criadas para esta página. Baixe a imagem final em PNG e compartilhe para uso pessoal.</p>
</section>
    <?php
    return ob_get_clean();
});

/**
 * Insert only after the existing three legacy images, just before the
 * next existing section. No destructive WordPress post-content changes.
 */
add_filter('the_content',function($content){
    if(is_admin() || !is_main_query() || !in_the_loop() || is_preview() ||
       get_the_ID()!==4977 ||
       get_post_field('post_name',4977)!=='melhores-imagens-de-bom-dia-para-compartilhar-no-whatsapp'){
        return $content;
    }
    if(strpos($content,'id="imagens-bom-dia-fotograficas"')!==false){ return $content; }
    if(strpos($content,'id="imagens"')===false || strpos($content,'1657822901_921_Imagens-de-bom-dia')===false){
        return $content;
    }
    $needle='<h2 id="semana">';
    $pos=strpos($content,$needle);
    $photo_pos=strpos($content,'1657822901_921_Imagens-de-bom-dia');
    if($pos===false || $photo_pos===false || $photo_pos>$pos){ return $content; }
    // Visitors were missing the new collection because this article is long.
    // Add a clear jump link in the existing table of contents, without writing
    // anything to the post database or duplicating the photographic gallery.
    $content=substr($content,0,$pos).do_shortcode('[bmd_bomdia_photo_gallery]').substr($content,$pos);
    $old_nav='<li><a href="#imagens">Imagens para baixar</a></li>';
    if(strpos($content,'href="#imagens-bom-dia-fotograficas"')===false &&
       strpos($content,$old_nav)!==false){
        $content=str_replace(
            $old_nav,
            $old_nav."\n".'<li><a href="#imagens-bom-dia-fotograficas"><strong>Novas imagens fotográficas (6)</strong></a></li>',
            $content
        );
    }
    return $content;
},83);
