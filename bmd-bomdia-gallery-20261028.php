<?php
/**
 * BMD Bom Dia Studio — 24 original vector share cards.
 * All artwork is rendered in the visitor's browser without paid APIs,
 * third-party image downloads, uploads, cookies or other storage.
 * Production scope: existing public post ID 4977 and its exact slug.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_bomdia_models_v2() {
    return array(
        array(1,'abencoado','Luz de bênçãos','Que Deus ilumine seus passos e traga paz ao seu coração.','sunrise','center','serif','#FFE7BD','#F0A677','#543C43','#FFF2CE'),
        array(2,'abencoado','Jardim da fé','Bom dia! Que a fé floresça e a esperança renove suas forças.','botanical','left','sans','#E5F3DF','#A1CFB7','#2E5447','#F7FFE8'),
        array(3,'abencoado','Noite que vira dia','Que Deus abençoe sua casa, seus caminhos e tudo que importa.','night','center','serif','#233450','#6A7894','#FFF6E4','#FFD798'),
        array(4,'abencoado','Flor da gratidão','Uma nova manhã para agradecer, confiar e recomeçar.','floral','center','serif','#FCE8DE','#E3B7B0','#684558','#FFFCED'),
        array(5,'motivacional','Coragem de hoje','Dia novo. Comece com coragem e avance um passo de cada vez.','rays','left','sans','#FFF0C5','#F5B978','#503F39','#FFF8DB'),
        array(6,'motivacional','Caminhos possíveis','Você não precisa ter tudo resolvido para começar. Bom dia!','mountain','center','serif','#E8DFF5','#BEB9D9','#453F65','#FFF4E1'),
        array(7,'motivacional','Ritmo próprio','Faça o que importa hoje. O resto pode esperar a sua vez.','checker','left','sans','#D5F1E3','#9FC3B8','#264C45','#FDF1C8'),
        array(8,'familia','Abraço de casa','Bom dia, família! Que nunca faltem saúde, união e carinho.','hearts','center','serif','#FFE5E3','#EFAEAF','#6E4158','#FFF7E9'),
        array(9,'familia','Bilhete de afeto','Um bom dia cheio de amor para as pessoas que fazem a vida bonita.','paper','left','serif','#F7EBD6','#E3C49B','#55463D','#FFFDF2'),
        array(10,'familia','Paz no lar','Que a nossa casa tenha paz, risadas e bons encontros hoje.','olive','center','sans','#E4EBD6','#B1C6A0','#385845','#F9EDCC'),
        array(11,'amizade','Gente que soma','Bom dia, amigo! Que a vida surpreenda você com coisas boas.','confetti','center','sans','#FFF0BD','#F4C98E','#4E4A57','#E3A85F'),
        array(12,'amizade','Céu aberto','Passando para desejar um dia leve para alguém muito especial.','clouds','left','serif','#DCEFFF','#A5CBE4','#355775','#FEFAE6'),
        array(13,'amizade','Laços bonitos','Que nossa amizade continue sendo um abraço nos dias corridos.','ribbon','center','serif','#ECE4FF','#C8B6E3','#51486C','#FAEEF9'),
        array(14,'engracado','Café e coragem','Bom dia! Meu plano é tomar café e fingir que acordei disposto.','coffee','left','sans','#F8E1C7','#D9AA89','#5B4139','#FFEAC5'),
        array(15,'engracado','Modo reiniciar','Acordei cheio de energia. Só estou tentando descobrir onde ela está.','checker','center','sans','#F4E49A','#C6CFA8','#394748','#F9F6D5'),
        array(16,'carinhoso','Flores para você','Que seu dia seja gentil com seu coração. Bom dia!','floral','left','serif','#FFEAE9','#F2BAC9','#754A61','#FFF5DD'),
        array(17,'carinhoso','Um carinho leve','Um abraço em forma de mensagem para iluminar sua manhã.','petals','center','serif','#FFE0C5','#EBADAA','#6C4959','#FFF1CC'),
        array(18,'curto','Mar de calma','Bom dia! Menos pressa, mais presença.','waves','center','sans','#D9F1F1','#A5CED5','#305968','#E9F5D6'),
        array(19,'curto','Novo sol','Respire. Sorria. Recomece.','sunburst','center','sans','#FFE1AA','#F4B26B','#644744','#FFF1C1'),
        array(20,'curto','Bilhete azul','Que hoje seja bom. De verdade.','frame','left','serif','#E2E9F7','#ABC6E4','#374F6C','#FFF0CE'),
        array(21,'cafe','Café quentinho','Bom dia! Que o café aqueça e as notícias sejam boas.','coffee','center','serif','#F1E5D4','#C7A17A','#503C33','#F8E2C0'),
        array(22,'cafe','Primeiro gole','Que o dia comece com café, paz e boas ideias.','arches','left','sans','#F8E7D0','#DEBA99','#59483E','#FCEFD9'),
        array(23,'status','Manhã em movimento','Que as coisas simples façam seu dia valer a pena.','grid','center','sans','#DDEFE7','#A9D2C9','#314F4C','#F8E8C6'),
        array(24,'status','Brilho próprio','Hoje é uma boa chance de espalhar coisa boa por aí.','sparkles','left','serif','#EBE4FB','#C3C2E8','#4C4D76','#FFF1CF')
    );
}

function bmd_bomdia_active_v2() {
    return is_singular('post') && (int)get_queried_object_id() === 4977 &&
        (string)get_post_field('post_name',4977)==='melhores-imagens-de-bom-dia-para-compartilhar-no-whatsapp';
}

add_action('wp_enqueue_scripts',function(){
    if (!bmd_bomdia_active_v2()) { return; }
    $css_file=__DIR__.'/assets/bmd-bomdia-gallery.css';
    $js_file=__DIR__.'/assets/bmd-bomdia-gallery.js';
    if (is_file($css_file)) {
        wp_enqueue_style('bmd-bomdia-studio-v2',plugins_url('assets/bmd-bomdia-gallery.css',__FILE__),array(),filemtime($css_file),'all');
    }
    if (is_file($js_file)) {
        wp_enqueue_script('bmd-bomdia-studio-v2',plugins_url('assets/bmd-bomdia-gallery.js',__FILE__),array(),filemtime($js_file),true);
    }
},16);

add_filter('body_class',function($classes){
    if (bmd_bomdia_active_v2()) { $classes[]='bmd-bomdia-page'; }
    return $classes;
},17);

add_shortcode('bmd_bomdia_cards',function(){
    $categories=array(
        'all'=>'Todos os modelos','abencoado'=>'Abençoado','motivacional'=>'Motivacional',
        'familia'=>'Família','amizade'=>'Amizade','engracado'=>'Engraçado',
        'carinhoso'=>'Carinhoso','curto'=>'Curtinho','cafe'=>'Com café','status'=>'Para Status'
    );
    $models=bmd_bomdia_models_v2();
    ob_start();
    ?>
<section id="bmd-bomdia-studio" aria-labelledby="gm-studio-heading">
 <header class="gm-hero">
  <span class="gm-eyebrow">ESTÚDIO DE BOM DIA · GRÁTIS</span>
  <h2 id="gm-studio-heading">24 imagens de bom dia para criar, editar e compartilhar</h2>
  <p>Escolha um modelo original, escreva sua mensagem e baixe um PNG pronto para WhatsApp, Status ou Instagram. Cada arte é desenhada no próprio navegador — sem cadastro, fotos de terceiros ou envio de arquivos.</p>
  <div class="gm-hero-markers" aria-label="Benefícios"><span>24 artes originais</span><span>9 temas</span><span>3 formatos</span><span>Sem marca-d'água</span></div>
 </header>
 <div class="gm-workspace">
  <div class="gm-browse" aria-label="Galeria de modelos">
   <div class="gm-subhead"><h3>Escolha sua arte</h3><span class="gm-count" id="gm-count" aria-live="polite">24 modelos</span></div>
   <div class="gm-filters" role="group" aria-label="Filtrar modelos de bom dia">
   <?php foreach ($categories as $key=>$name): ?>
    <button class="gm-filter" type="button" data-gm-filter="<?php echo esc_attr($key); ?>" aria-pressed="<?php echo $key==='all'?'true':'false'; ?>"><?php echo esc_html($name); ?></button>
   <?php endforeach; ?>
   </div>
   <div class="gm-gallery" role="list" aria-label="24 modelos originais">
   <?php foreach ($models as $model):
       list($id,$cat,$label,$quote,$art,$layout,$font,$a,$b,$ink,$accent)=$model; ?>
    <div class="gm-item" role="listitem">
     <button type="button" class="gm-tile" aria-pressed="<?php echo $id===1?'true':'false'; ?>"
      aria-label="<?php echo esc_attr('Usar modelo '.$id.': '.$label.'. '.$quote); ?>"
      data-gm-id="<?php echo esc_attr($id); ?>"
      data-gm-cat="<?php echo esc_attr($cat); ?>"
      data-gm-name="<?php echo esc_attr($label); ?>"
      data-gm-quote="<?php echo esc_attr($quote); ?>"
      data-gm-art="<?php echo esc_attr($art); ?>"
      data-gm-layout="<?php echo esc_attr($layout); ?>"
      data-gm-font="<?php echo esc_attr($font); ?>"
      data-gm-a="<?php echo esc_attr($a); ?>"
      data-gm-b="<?php echo esc_attr($b); ?>"
      data-gm-ink="<?php echo esc_attr($ink); ?>"
      data-gm-accent="<?php echo esc_attr($accent); ?>"
      data-selected="<?php echo $id===1?'true':'false'; ?>">
      <span class="gm-thumb" style="--gm-bg-a:<?php echo esc_attr($a); ?>;--gm-bg-b:<?php echo esc_attr($b); ?>;">
       <canvas width="220" height="275" aria-hidden="true"></canvas>
      </span>
      <span class="gm-tile-caption">
       <strong><?php echo esc_html(sprintf('%02d · %s',$id,$label)); ?></strong>
       <small><?php echo esc_html($categories[$cat]); ?> · <?php echo esc_html(wp_trim_words($quote,5,'…')); ?></small>
      </span>
     </button>
    </div>
   <?php endforeach; ?>
   </div>
  </div>
  <aside class="gm-editor" id="gm-editor" aria-labelledby="gm-editor-heading">
   <div class="gm-editor-inner">
    <h3 id="gm-editor-heading">Personalize a mensagem</h3>
    <p class="gm-edit-hint">Escolha um modelo da galeria para mudar a arte. Sua prévia é atualizada automaticamente.</p>
    <div class="gm-preview-shell">
      <canvas id="gm-main-canvas" width="1080" height="1920" role="img" aria-label="Prévia da imagem de bom dia pronta para baixar"></canvas>
    </div>
    <span class="gm-preview-label">PRÉVIA ORIGINAL · GERADA NESTE DISPOSITIVO</span>
    <div class="gm-control">
     <label for="gm-message">Sua mensagem <span id="gm-length" class="gm-limit">0 / 170</span></label>
     <textarea id="gm-message" rows="3" maxlength="170"><?php echo esc_textarea($models[0][3]); ?></textarea>
    </div>
    <div class="gm-control-row">
     <div class="gm-control">
      <label for="gm-format">Formato da imagem</label>
      <select id="gm-format"><option value="status">Status · 9:16</option><option value="portrait">Feed · 4:5</option><option value="square">Quadrado · 1:1</option></select>
     </div>
     <div class="gm-control">
      <label for="gm-colors">Cores do cartão</label>
      <select id="gm-colors"><option value="original">Cores originais</option><option value="light">Claro e suave</option><option value="dark">Escuro e elegante</option></select>
     </div>
    </div>
    <div class="gm-actions">
     <button type="button" class="gm-action-primary" id="gm-download">Baixar PNG <span aria-hidden="true">↓</span></button>
     <button type="button" class="gm-action-secondary" id="gm-copy">Copiar frase</button>
    </div>
    <p class="gm-feedback" id="gm-feedback" role="status" aria-live="polite">Sua arte está pronta para editar.</p>
    <p class="gm-privacy">Seu texto e suas imagens ficam no navegador. Sem upload, assinatura ou marca-d'água.</p>
   </div>
  </aside>
 </div>
 <noscript><p style="padding:20px">Para personalizar e baixar os cartões, habilite JavaScript no navegador. As frases do artigo continuam disponíveis normalmente abaixo.</p></noscript>
</section>
    <?php
    return ob_get_clean();
});

add_filter('the_content',function($content){
    if(is_admin() || !is_singular('post') || !is_main_query() || !in_the_loop() || is_preview())return $content;
    if(get_the_ID()!==4977 || get_post_field('post_name',get_the_ID())!=='melhores-imagens-de-bom-dia-para-compartilhar-no-whatsapp')return $content;
    if(strpos($content,'bmd-bomdia-studio')!==false || strlen($content)<2200 || strpos($content,'<h2')===false)return $content;
    $point=strpos($content,'<h2');
    if($point>9000)return $content;
    return substr($content,0,$point).do_shortcode('[bmd_bomdia_cards]').substr($content,$point);
},61);
