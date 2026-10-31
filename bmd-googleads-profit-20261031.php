<?php
/**
 * Commercial-intent SEO: additional unit-economics calculator for the already
 * established Google Ads price guide. No changes to stored post content.
 * Pricing policy refs: Google Ads support 6385083 / 10486637 / 13064207.
 */
if(!defined('ABSPATH'))exit;
add_action('wp_enqueue_scripts',static function(){
 if(is_admin()||!is_singular('post')||(int)get_queried_object_id()!==8436)return;
 foreach(array('css','js') as $ext){
  $file='assets/bmd-googleads-profit-20261031.'.$ext;
  $path=__DIR__.'/'.$file;
  if(!is_file($path))continue;
  if($ext==='css')wp_enqueue_style('bmd-googleads-profit-2026',plugins_url($file,__FILE__),array(),(string)filemtime($path));
  else wp_enqueue_script('bmd-googleads-profit-2026',plugins_url($file,__FILE__),array(),(string)filemtime($path),true);
 }
},20);
add_shortcode('bmd_googleads_breakeven_2026',static function(){
 ob_start();?>
 <section class="bmd-ads-profit" id="simulador-cpc-maximo-google-ads" aria-label="Calculadora de CPC máximo, margem e lucro do Google Ads">
 <span class="bmd-ads-profit-kicker">SIMULADOR EXCLUSIVO • DADOS EDITÁVEIS</span>
 <h2>Qual é o CPC máximo que seu negócio pode pagar?</h2>
 <p>O custo por clique só faz sentido quando você sabe quanto sobra de cada venda. Informe o orçamento, o percentual de visitantes que viram leads, a taxa de fechamento e a margem de contribuição. O cálculo mostra o <strong>CPC de equilíbrio</strong> e o resultado potencial depois da mídia e da gestão.</p>
 <div class="bmd-ads-preset" role="group" aria-label="Orçamento diário para simular">
  <button type="button" data-daily-preset="20" aria-pressed="false">R$ 20/dia</button>
  <button type="button" data-daily-preset="50" aria-pressed="true">R$ 50/dia</button>
  <button type="button" data-daily-preset="100" aria-pressed="false">R$ 100/dia</button>
 </div>
 <div class="bmd-ads-profit-grid">
  <div class="bmd-ads-profit-fields">
   <label>Orçamento diário médio (R$)
    <small>Limite mensal ilustrativo = diário × 30,4</small>
    <input type="number" min=".01" max="10000000" step=".01" data-input="daily" value="50">
   </label>
   <label>CPC estimado (R$)
    <small>Pesquise valores na sua conta, não em tabelas genéricas</small>
    <input type="number" min=".01" max="10000000" step=".01" data-input="cpc" value="4">
   </label>
   <label>Conversão de clique em lead (%)
    <small>Exemplo: 8 leads a cada 100 cliques</small>
    <input type="number" min="0" max="100" step=".1" data-input="land" value="8">
   </label>
   <label>Fechamento de lead em venda (%)
    <small>Exemplo: 15 vendas a cada 100 leads</small>
    <input type="number" min="0" max="100" step=".1" data-input="close" value="15">
   </label>
   <label>Ticket médio de venda (R$)
    <small>Receita por venda concluída</small>
    <input type="number" min="0" max="10000000" step=".01" data-input="ticket" value="900">
   </label>
   <label>Margem de contribuição (%)
    <small>Depois de produto, impostos variáveis e taxas, antes dos anúncios</small>
    <input type="number" min="0" max="100" step=".1" data-input="margin" value="55">
   </label>
   <label>Gestão da campanha por mês (R$)
    <small>Agência ou custo da pessoa responsável, se aplicável</small>
    <input type="number" min="0" max="10000000" step=".01" data-input="fee" value="300">
   </label>
  </div>
  <div class="bmd-ads-profit-output" data-results aria-live="polite">
   <p class="main-label">CPC máximo para equilíbrio, neste cenário</p>
   <strong class="main-value" data-out="max-cpc">R$ 4,96</strong>
   <span class="bmd-ads-profit-status positive" data-profit-state>Contribuição positiva na simulação</span>
   <div class="bmd-ads-profit-mini">
    <div><span>Orçamento mensal de referência</span><b data-out="spend">R$ 1.520,00</b></div>
    <div><span>Cliques estimados</span><b data-out="clicks">380</b></div>
    <div><span>Leads estimados</span><b data-out="leads">30,4</b></div>
    <div><span>Vendas estimadas</span><b data-out="sales">4,56</b></div>
    <div><span>Margem por venda</span><b data-out="margin-sale">R$ 495,00</b></div>
    <div><span>CPA previsto com gestão</span><b data-out="cpa">R$ 399,12</b></div>
    <div><span>CPA de equilíbrio</span><b data-out="max-cpa">R$ 495,00</b></div>
    <div><span>Receita hipotética</span><b data-out="revenue">R$ 4.104,00</b></div>
   </div>
   <div class="bmd-ads-profit-break"></div>
   <p class="main-label">Resultado da contribuição após anúncios e gestão</p>
   <strong class="main-value" data-out="profit">R$ 437,20</strong>
   <p class="bmd-ads-profit-verdict" data-verdict>Neste cenário, o CPC informado fica dentro do limite de equilíbrio calculado.</p>
  </div>
 </div>
 <p class="bmd-ads-profit-error" data-err role="alert" hidden></p>
 <div class="bmd-ads-profit-scenarios">
  <h3>E se o CPC subir ou cair?</h3>
  <table><thead><tr><th>CPC no cenário</th><th>Resultado após anúncios e gestão</th></tr></thead><tbody data-scenarios>
   <tr><td>R$ 3,00</td><td>R$ 1.189,60</td></tr>
   <tr><td>R$ 4,00</td><td>R$ 437,20</td></tr>
   <tr class="loss"><td>R$ 6,00</td><td>-R$ 315,20</td></tr>
  </tbody></table>
 </div>
 <p class="bmd-ads-profit-warning"><strong>Como calcular:</strong> margem por venda = ticket × margem de contribuição. Margem potencial por clique = conversão de lead × taxa de fechamento × margem por venda. O CPC de equilíbrio ajustado por custo fixo de gestão é: margem potencial por clique × orçamento de mídia ÷ (orçamento de mídia + gestão). Resultado esperado = (orçamento de mídia ÷ CPC) × conversão de lead × fechamento × margem por venda − orçamento de mídia − gestão. Valores de vendas podem ficar fracionados por serem <strong>expectativas matemáticas</strong>, não promessas de venda real.</p>
 <p class="bmd-ads-profit-footer">Os números iniciais são ilustrativos, não CPCs médios de mercado. O Google Ads usa um orçamento diário médio, podendo gastar até o dobro em certos dias para a maioria das campanhas e respeitando limites de cobrança definidos pela plataforma. Confira as <a href="https://support.google.com/google-ads/answer/6385083?hl=pt-BR" rel="noopener noreferrer" target="_blank">regras oficiais de orçamento</a> e o <a href="https://support.google.com/google-ads/answer/13064207?hl=pt-BR" rel="noopener noreferrer" target="_blank">uso de valores de conversão</a>. O cálculo não inclui custos fixos empresariais, efeitos de recompra, impostos adicionais nem previsões reais do leilão. Tudo é calculado no navegador.</p>
 </section>
 <?php return ob_get_clean();
});
add_filter('the_content',static function($content){
 if(is_admin()||!is_singular('post')||is_feed()||is_preview()||!is_main_query()||!in_the_loop()||(int)get_the_ID()!==8436)return $content;
 if(strpos($content,'simulador-cpc-maximo-google-ads')!==false)return $content;
 $needle='<h2>Exemplo de cálculo de Google Ads</h2>';
 if(strpos($content,$needle)===false)return $content;
 $block='<p><strong>O que falta em muitos orçamentos:</strong> não basta saber o CPC e a quantidade de leads. É preciso relacionar o custo com a margem que sobra depois da venda. No exemplo abaixo, o preço por clique aceitável muda conforme a conversão da página e a capacidade comercial de transformar leads em clientes.</p>';
 $block.=do_shortcode('[bmd_googleads_breakeven_2026]');
 $block.='<p>Se sua empresa atende os leads por WhatsApp, avalie também os <a href="'.esc_url(home_url('/multiatendimento-whatsapp-3-5-10-atendentes-precos/')).'">custos do atendimento por equipe</a>. Para considerar licenças, funil e acompanhamento das oportunidades, consulte o <a href="'.esc_url(home_url('/quanto-custa-crm-para-pequenas-empresas/')).'">comparativo de custos de CRM</a>. Esses custos precisam entrar na margem ou nas despesas de gestão antes de usar o resultado como decisão financeira.</p>';
 return str_replace($needle,$block.$needle,$content);
},72);
