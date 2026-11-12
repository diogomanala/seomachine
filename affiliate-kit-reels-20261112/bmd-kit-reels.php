<?php
/**
 * Plugin Name: BMD Kit Reels Interativo
 * Description: Guia editorial com configurador de equipamento para gravar Reels. Escopo isolado ao shortcode.
 * Version: 1.0.0
 */
if (!defined('ABSPATH')) exit;
if (!function_exists('bmdkr_is_page')) {
 function bmdkr_is_page() {
  if (!is_singular('post')) return false;
  $p = get_queried_object();
  return $p instanceof WP_Post && $p->post_name === 'kit-para-gravar-reels-em-casa';
 }
}
add_action('wp_enqueue_scripts', static function(){
 if(!bmdkr_is_page())return;
 $dir=__DIR__; $uri=content_url('/mu-plugins/');
 $css=$dir.'/bmd-kit-reels.css'; $js=$dir.'/bmd-kit-reels.js';
 if(is_file($css))wp_enqueue_style('bmd-kit-reels',$uri.'bmd-kit-reels.css',[],(string)filemtime($css));
 if(is_file($js))wp_enqueue_script('bmd-kit-reels',$uri.'bmd-kit-reels.js',[],(string)filemtime($js),true);
},20);
add_shortcode('bmd_kit_reels', static function(){
 $affiliate='supercompra0d-20';
 $items=[
  ['id'=>'mic-v10','category'=>'audio','type'=>'usb','name'=>'BOYA BY-V10 USB-C','subtitle'=>'Lapela sem fio, gravação individual','asin'=>'B0D69WQFYQ','brief'=>'Opção com um transmissor para quem grava sozinho em smartphone com USB-C.','watch'=>'Confira suporte do app a microfone externo e a versão exata do kit.'],
  ['id'=>'mic-v20','category'=>'audio','type'=>'usb','name'=>'BOYA BY-V20 USB-C','subtitle'=>'Dois transmissores para entrevistas','asin'=>'B0DJS8HCGJ','brief'=>'Conjunto com dois transmissores e receptor USB-C, indicado para vídeos com duas pessoas.','watch'=>'Receptor USB-C não é o mesmo que receptor de câmera.'],
  ['id'=>'mic-v2','category'=>'audio','type'=>'lightning','name'=>'BOYA BY-V2 Lightning','subtitle'=>'Dois microfones para iPhones Lightning','asin'=>'B0BR3S4KPD','brief'=>'Variante da linha BOYA para iPhones com conector Lightning, útil em conversas e entrevistas.','watch'=>'Não compre para iPhones USB-C; confira versão e conteúdo do anúncio.'],
  ['id'=>'mic-lark','category'=>'audio','type'=>'universal','name'=>'Hollyland Lark M2 Combo','subtitle'=>'Kit versátil para smartphone e câmera','asin'=>'B0CP7QXWPN','brief'=>'Mais opções de receptores, interessante para quem grava com celular e câmera dedicada.','watch'=>'A composição do kit muda conforme a variante. Confira os receptores incluídos.'],
  ['id'=>'tripod','category'=>'support','type'=>'universal','name'=>'Ulanzi MT-44','subtitle'=>'Tripé extensível para celular','asin'=>'B09T92NK4J','brief'=>'Suporte extensível que ajuda em gravações sozinho, em pé ou sentado.','watch'=>'Verifique altura, estabilidade, peso suportado e o tipo de suporte.'],
  ['id'=>'light','category'=>'light','type'=>'universal','name'=>'Ulanzi VL49 RGB','subtitle'=>'Luz LED compacta de apoio','asin'=>'B08MDQ3CJY','brief'=>'Boa para preencher sombras ou criar luz de fundo em espaços pequenos.','watch'=>'Não substitui necessariamente uma luz principal grande ou a janela.']
 ];
 ob_start();?>
 <div class="bkr" id="bmd-kit-reels" data-version="1.0.0">
  <p class="bkr-disclosure"><strong>Publicidade e transparência.</strong> Este guia contém links de afiliado da Amazon. Como participante do Programa de Associados da Amazon, podemos receber comissão por compras qualificadas. Não realizamos testes físicos dos aparelhos citados. O conteúdo usa fichas técnicas e informações públicas, e os preços devem ser confirmados na loja.</p>
  <section class="bkr-hero" aria-labelledby="bkr-hero-title">
   <div class="bkr-hero-text">
    <span class="bkr-kicker">GUIA INTERATIVO · EDIÇÃO 2026</span>
    <h2 id="bkr-hero-title">Monte seu kit para gravar Reels em casa.</h2>
    <p>Escolha o orçamento, o conector do celular e o tipo de vídeo. Nós organizamos os equipamentos por prioridade, com links reais e avisos de compatibilidade.</p>
    <a class="bkr-start" href="#montar-kit">Montar meu kit <span aria-hidden="true">↓</span></a>
    <div class="bkr-trust"><span>Sem cadastro</span><span>Resultado na hora</span><span>Links Amazon</span></div>
   </div>
   <div class="bkr-hero-visual">
   <?php if(has_post_thumbnail()) echo get_the_post_thumbnail(get_the_ID(),'large',['class'=>'bkr-hero-img','loading'=>'eager','fetchpriority'=>'high','alt'=>'Cenário editorial ilustrativo de estúdio caseiro com celular, microfone, tripé e iluminação']); ?>
   <span class="bkr-photo-note">Imagem editorial ilustrativa</span>
   </div>
  </section>
  <nav class="bkr-nav" aria-label="Seções do guia">
   <a href="#montar-kit">Configurador</a><a href="#resultado-kit">Meu resultado</a><a href="#guia-produtos">Equipamentos</a><a href="#dicas-estudio">Montagem do cenário</a><a href="#duvidas-kit">Dúvidas</a>
  </nav>
  <section class="bkr-section" id="montar-kit">
   <div class="bkr-heading"><span>01 / SEU SETUP</span><h2>Encontre o kit certo para a sua rotina</h2><p>São três escolhas rápidas. Não pedimos dados pessoais nem mostramos um preço total inventado.</p></div>
   <form class="bkr-form" id="bkr-form" autocomplete="off">
    <fieldset class="bkr-field"><legend><span>1</span> Quanto pretende investir?</legend>
     <div class="bkr-choices bkr-budget">
      <label><input type="radio" name="bkr-budget" value="200"><span><strong>Até R$ 200</strong><small>Começar pelo essencial</small></span></label>
      <label><input type="radio" name="bkr-budget" value="400" checked><span><strong>Até R$ 400</strong><small>Priorizar áudio e estabilidade</small></span></label>
      <label><input type="radio" name="bkr-budget" value="1000"><span><strong>Até R$ 1.000</strong><small>Considerar um setup mais completo</small></span></label>
     </div>
    </fieldset>
    <fieldset class="bkr-field"><legend><span>2</span> Qual entrada tem seu celular?</legend>
     <div class="bkr-choices bkr-device">
      <label><input type="radio" name="bkr-device" value="usb" checked><span><strong>USB-C</strong><small>Android e iPhones mais novos</small></span></label>
      <label><input type="radio" name="bkr-device" value="lightning"><span><strong>Lightning</strong><small>iPhones com conector antigo</small></span></label>
     </div>
    </fieldset>
    <fieldset class="bkr-field"><legend><span>3</span> O que você vai gravar?</legend>
     <div class="bkr-choices bkr-use">
      <label><input type="radio" name="bkr-use" value="solo" checked><span><strong>Reels sozinho</strong><small>Vídeos falando para a câmera</small></span></label>
      <label><input type="radio" name="bkr-use" value="interview"><span><strong>Entrevistas</strong><small>Duas pessoas e mais mobilidade</small></span></label>
      <label><input type="radio" name="bkr-use" value="product"><span><strong>Produtos e tutoriais</strong><small>Detalhes, bancada e demonstrações</small></span></label>
     </div>
    </fieldset>
   </form>
  </section>
  <section class="bkr-result" id="resultado-kit" aria-live="polite" aria-atomic="false">
   <div class="bkr-result-head"><div><span class="bkr-result-label"><span class="bkr-live-dot"></span> CONFIGURAÇÃO SUGERIDA</span><h2 id="bkr-result-title">Seu kit para Reels sozinho</h2><p id="bkr-result-intro">Comece pelo microfone certo para USB-C e aproveite a luz da janela. Veja o que vale comprar primeiro.</p></div><button type="button" class="bkr-copy" id="bkr-copy" aria-label="Copiar lista de compra">Copiar lista <span aria-hidden="true">⧉</span></button></div>
   <div class="bkr-result-grid" id="bkr-result-grid">
    <div class="bkr-result-item"><span class="bkr-index">01</span><div><small>PRIORIDADE</small><h3>BOYA BY-V10 USB-C</h3><p>Microfone para gravação individual em celular USB-C.</p><a rel="nofollow sponsored noopener" target="_blank" href="https://www.amazon.com.br/dp/B0D69WQFYQ?tag=supercompra0d-20">Ver na Amazon</a></div></div>
    <div class="bkr-result-item"><span class="bkr-index">02</span><div><small>APOIO</small><h3>Ulanzi MT-44</h3><p>Suporte ajustável para posicionar o celular.</p><a rel="nofollow sponsored noopener" target="_blank" href="https://www.amazon.com.br/dp/B09T92NK4J?tag=supercompra0d-20">Ver na Amazon</a></div></div>
    <div class="bkr-result-item bkr-free"><span class="bkr-index">03</span><div><small>SEM COMPRA</small><h3>Janela como luz principal</h3><p>Comece com o que já tem e coloque o rosto voltado para a luz.</p></div></div>
   </div>
   <p class="bkr-budgets" id="bkr-budget-note"><strong>Importante:</strong> a faixa escolhida serve para ordenar prioridades. Os valores da Amazon não são consultados em tempo real e a soma dos itens pode ultrapassar o orçamento. Confira antes de comprar.</p>
   <p class="bkr-result-footer">Links com sua loja de destino Amazon Brasil. Preços, frete, estoque e variantes podem mudar.</p>
  </section>
  <section class="bkr-section" id="guia-produtos"><div class="bkr-heading"><span>02 / CATÁLOGO CURADO</span><h2>Os equipamentos usados nas recomendações</h2><p>Não se trata de um ranking universal: cada produto resolve um problema diferente. Fotos comerciais e notas da loja não foram reproduzidas.</p></div>
   <div class="bkr-products">
   <?php $i=0;foreach($items as $item):$i++;$link='https://www.amazon.com.br/dp/'.$item['asin'].'?tag='.$affiliate; ?>
    <article class="bkr-product" id="bkr-<?php echo esc_attr($item['id']); ?>">
     <div class="bkr-product-icon bkr-i-<?php echo esc_attr($item['category']); ?>" aria-hidden="true"><div class="bkr-illust"><span></span><i></i><b></b></div></div>
     <div class="bkr-product-body"><span class="bkr-product-category"><?php echo esc_html($item['category']==='audio'?'ÁUDIO':($item['category']==='light'?'ILUMINAÇÃO':'ESTABILIDADE')); ?></span><h3><?php echo esc_html($item['name']); ?></h3><p class="bkr-product-subtitle"><?php echo esc_html($item['subtitle']); ?></p><p><?php echo esc_html($item['brief']); ?></p><p class="bkr-product-note"><strong>Antes de comprar:</strong> <?php echo esc_html($item['watch']); ?></p><a class="bkr-product-link" href="<?php echo esc_url($link); ?>" rel="nofollow sponsored noopener" target="_blank">Conferir na Amazon <span aria-hidden="true">↗</span></a><small>ASIN <?php echo esc_html($item['asin']); ?> · Estoque e preço sujeitos a alteração</small></div>
    </article>
   <?php endforeach; ?>
   </div>
  </section>
  <section class="bkr-section bkr-article" id="dicas-estudio"><div class="bkr-heading"><span>03 / COMO MONTAR</span><h2>Um estúdio de Reels bom começa pela posição da luz e do áudio</h2><p>Você não precisa comprar tudo no primeiro dia. A ordem correta evita equipamentos caros que ficam guardados.</p></div>
   <div class="bkr-advice">
    <div class="bkr-advice-art" role="img" aria-label="Diagrama simples: janela de luz lateral, celular à frente, microfone perto da boca e fundo atrás"><div class="bkr-window">JANELA</div><div class="bkr-direction"></div><div class="bkr-subject">VOCÊ</div><div class="bkr-camera">CELULAR</div><div class="bkr-background">FUNDO</div></div>
    <div><h3>Posicionamento inicial sem custo</h3><ol><li>Fique de frente ou a 45° para uma janela, evitando luz forte atrás da cabeça.</li><li>Posicione o celular um pouco acima da linha dos olhos, na vertical, com espaço para as mãos.</li><li>Grave dez segundos de teste e ouça com fones antes de iniciar.</li><li>Se o quarto produzir eco, experimente cortinas, tapete ou móveis estofados.</li><li>Ao comprar uma luz LED pequena, use-a como preenchimento ou para separar o fundo, não como substituta automática de um softbox.</li></ol></div>
   </div>
   <h3>Qual equipamento comprar primeiro?</h3><p>Para vídeos falando para a câmera, normalmente vale resolver o áudio antes de investir em luzes decorativas. Mas, se o celular fica apoiado em livros e o enquadramento sempre cai, um tripé pode trazer ganho mais imediato. Para tutoriais de bancada, a luz e o enquadramento do objeto passam a pesar mais do que um microfone duplo.</p>
   <h3>USB-C não significa compatibilidade garantida</h3><p>O receptor físico precisa corresponder ao conector do aparelho, mas alguns aplicativos não selecionam automaticamente o microfone externo. Verifique o suporte do modelo e faça uma gravação curta. Em iPhones Lightning, prefira versões próprias para esse conector em vez de assumir que um adaptador USB-C vai funcionar.</p>
   <h3>Como escolher um microfone de lapela para duas pessoas?</h3><p>Procure um kit com dois transmissores e um receptor compatível. O <a href="https://www.amazon.com.br/dp/B0DJS8HCGJ?tag=supercompra0d-20" rel="nofollow sponsored noopener" target="_blank">BOYA BY-V20 USB-C</a> tem essa proposta para celulares USB-C, enquanto a linha BY-V2 atende versões Lightning. Se você também usa uma câmera dedicada, confira o receptor e o cabo do kit antes de comprar.</p>
   <h3>Iluminação para gravar vídeos em quarto pequeno</h3><p>A janela é uma boa fonte de luz principal durante o dia. Uma luz compacta como a <a href="https://www.amazon.com.br/dp/B08MDQ3CJY?tag=supercompra0d-20" rel="nofollow sponsored noopener" target="_blank">Ulanzi VL49 RGB</a> pode ajudar a preencher sombras ou criar iluminação de fundo. Para gravações noturnas frequentes, um difusor ou uma fonte maior e suave pode ser mais importante que efeitos RGB.</p>
  </section>
  <section class="bkr-section bkr-faq" id="duvidas-kit"><div class="bkr-heading"><span>04 / DÚVIDAS REAIS</span><h2>Antes de gastar com seu primeiro kit</h2></div>
   <details><summary>Dá para gravar Reels de qualidade gastando menos de R$ 200?</summary><p>Sim, especialmente com boa luz natural e o microfone do próprio celular. A faixa de R$ 200 neste configurador indica prioridades, não um carrinho com preço garantido. Consulte o valor atual de cada item antes de comprar.</p></details>
   <details><summary>O que comprar primeiro, tripé, microfone ou ring light?</summary><p>Para gravação de fala em lugar silencioso, comece com enquadramento estável e som limpo. Se a luz da janela já resolve, priorize tripé ou microfone. Para produto de mesa, uma luz bem posicionada pode ser a principal melhoria.</p></details>
   <details><summary>O microfone USB-C serve em qualquer iPhone?</summary><p>Não. IPhones com conector Lightning exigem uma versão compatível com essa porta. Mesmo em iPhones USB-C, confira a ficha e o aplicativo usado para capturar vídeo.</p></details>
   <details><summary>Os kits já incluem todos os acessórios?</summary><p>Não necessariamente. O configurador recomenda itens por prioridade e pode apresentar mais de um produto. Confira no anúncio se o suporte, cabo, receptor e acessórios necessários fazem parte da embalagem.</p></details>
   <details><summary>Vocês testaram os produtos?</summary><p>Não. A comparação é editorial, feita a partir de especificações públicas e propostas de uso, sem medições próprias de áudio, estabilidade ou luminosidade.</p></details>
   <details><summary>Os preços e estoques são atualizados automaticamente?</summary><p>Não. Os botões levam ao anúncio da Amazon para consultar preço, vendedor, frete e disponibilidade. Não publicamos preços fixos nem prometemos que a soma cabe na faixa selecionada.</p></details>
  </section>
  <aside class="bkr-footnotes"><h2>Como selecionamos os equipamentos</h2><p>Foram considerados o conector de áudio, a quantidade de transmissores, a possibilidade de usar câmera, estabilidade do apoio e adequação da iluminação para pequenos ambientes. O configurador é uma recomendação editorial e não avalia preços em tempo real. As informações técnicas e os ASINs foram checados em fontes públicas em 12/11/2026. Não usamos notas de usuários de outras lojas nem avaliações inventadas.</p><p><strong>Fontes técnicas:</strong> fichas de BOYA BY-V10, BOYA BY-V20, BOYA BY-V2, Hollyland Lark M2 Combo, Ulanzi MT-44 e VL49, incluindo documentação pública e páginas de anúncios. Consulte sempre o anúncio específico antes da compra.</p><p><strong>Continue lendo:</strong> <a href="https://blogdemarketingdigital.com.br/melhores-microfones-lapela-sem-fio-para-gravar-reels/">comparativo de microfones de lapela sem fio para Reels</a> e <a href="https://blogdemarketingdigital.com.br/automatizar-direct-instagram-comentarios/">como automatizar comentários e Direct no Instagram</a>.</p></aside>
 </div>
 <?php return ob_get_clean();
});
add_action('wp_head',static function(){
 if(!bmdkr_is_page())return;
 $url=get_permalink(get_queried_object_id());
 $schema=['@context'=>'https://schema.org','@type'=>'ItemList','@id'=>$url.'#lista-equipamentos',
 'name'=>'Equipamentos mencionados no guia de kit para gravar Reels em casa',
 'numberOfItems'=>6,'itemListOrder'=>'https://schema.org/ItemListUnordered',
 'itemListElement'=>[]];
 foreach([
  ['BOYA BY-V10 USB-C','B0D69WQFYQ'],['BOYA BY-V20 USB-C','B0DJS8HCGJ'],
  ['BOYA BY-V2 Lightning','B0BR3S4KPD'],['Hollyland Lark M2 Combo','B0CP7QXWPN'],
  ['Ulanzi MT-44','B09T92NK4J'],['Ulanzi VL49 RGB','B08MDQ3CJY']
 ] as $i=>$p){
  $schema['itemListElement'][]=['@type'=>'ListItem','position'=>$i+1,'name'=>$p[0],
   'url'=>'https://www.amazon.com.br/dp/'.$p[1].'?tag=supercompra0d-20'];
 }
 echo '<script type="application/ld+json" id="bmd-kit-itemlist">'.wp_json_encode($schema,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_HEX_AMP|JSON_HEX_TAG).'</script>';
},118);
