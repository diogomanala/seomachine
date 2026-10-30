<?php
/**
 * BMD | Custos da automação WhatsApp para empresas em 2026.
 * Texto editorial e simulação independente, sem dependência de plataformas externas.
 */
if (!defined('ABSPATH')) exit;

add_action('wp_enqueue_scripts',static function(){
  if(!is_singular('post'))return;
  $post=get_queried_object();
  if(!$post instanceof WP_Post||$post->post_name!=='quanto-custa-automatizar-whatsapp-business-api')return;
  $css=__DIR__.'/assets/bmd-whatsapp-cost-20261030.css';
  $js=__DIR__.'/assets/bmd-whatsapp-cost-20261030.js';
  if(is_file($css))wp_enqueue_style('bmd-wa-cost',plugins_url('assets/bmd-whatsapp-cost-20261030.css',__FILE__),array(),(string)filemtime($css));
  if(is_file($js))wp_enqueue_script('bmd-wa-cost',plugins_url('assets/bmd-whatsapp-cost-20261030.js',__FILE__),array(),(string)filemtime($js),true);
},20);

add_shortcode('bmd_whatsapp_cost_2026',static function(){
  ob_start();?>
  <section class="bmd-wac" id="simulador-custo-whatsapp" aria-labelledby="bmd-wac-title">
    <div class="bmd-wac-head"><span class="bmd-wac-kicker">CALCULADORA GRATUITA</span>
      <h2 id="bmd-wac-title">Quanto custará o WhatsApp automatizado na sua empresa?</h2>
      <p>Altere os volumes, as tarifas e a mensalidade do fornecedor. Os preços de referência são para destinatários no Brasil, em BRL, na tabela de outubro de 2026. Não são uma proposta comercial.</p>
    </div>
    <div class="bmd-wac-columns">
      <div class="bmd-wac-fields">
        <h3>Mensagens entregues por mês</h3>
        <div class="bmd-wac-input-grid">
          <label>Marketing <small>Ofertas, promoções</small><input data-wa="marketing" type="number" min="0" max="10000000" step="1" inputmode="numeric" value="200"></label>
          <label>Utilidade <small>Pedidos, lembretes</small><input data-wa="utility" type="number" min="0" max="10000000" step="1" inputmode="numeric" value="800"></label>
          <label>Autenticação <small>Códigos de acesso</small><input data-wa="auth" type="number" min="0" max="10000000" step="1" inputmode="numeric" value="0"></label>
          <label>Serviço <small>Respostas da empresa pela API</small><input data-wa="service" type="number" min="0" max="10000000" step="1" inputmode="numeric" value="3000"></label>
          <label>Números de WhatsApp <small>Com franquia de serviço</small><input data-wa="numbers" type="number" min="1" max="1000" step="1" inputmode="numeric" value="1"></label>
        </div>
        <h3>Tarifas da Meta por mensagem (R$)</h3>
        <div class="bmd-wac-input-grid bmd-wac-rates">
          <label>Marketing<input data-wa="marketingRate" type="number" min="0" max="1000" step="0.0001" inputmode="decimal" value="0.3217"></label>
          <label>Utilidade<input data-wa="utilityRate" type="number" min="0" max="1000" step="0.0001" inputmode="decimal" value="0.035"></label>
          <label>Autenticação<input data-wa="authRate" type="number" min="0" max="1000" step="0.0001" inputmode="decimal" value="0.035"></label>
          <label>Serviço<input data-wa="serviceRate" type="number" min="0" max="1000" step="0.0001" inputmode="decimal" value="0.035"></label>
          <label>Franquia de serviço por número<input data-wa="serviceFree" type="number" min="0" max="1000000" step="1" inputmode="numeric" value="1000"></label>
        </div>
        <h3>Plataforma, operação e implantação</h3>
        <div class="bmd-wac-input-grid">
          <label>Plataforma / provedor por mês (R$)<input data-wa="platform" type="number" min="0" max="10000000" step="1" inputmode="decimal" value="299"></label>
          <label>Automação e IA por mês (R$)<input data-wa="ai" type="number" min="0" max="10000000" step="1" inputmode="decimal" value="120"></label>
          <label>Taxa extra do provedor por mensagem (R$)<input data-wa="markup" type="number" min="0" max="1000" step="0.0001" inputmode="decimal" value="0"></label>
          <label>Mensagens sujeitas à taxa do provedor <small>Inclua recebidas, se o contrato cobrar</small><input data-wa="providerMessages" type="number" min="0" max="100000000" step="1" inputmode="numeric" value="4000"></label>
          <label>Implantação, pagamento único (R$)<input data-wa="setup" type="number" min="0" max="100000000" step="1" inputmode="decimal" value="900"></label>
        </div>
        <button class="bmd-wac-reset" type="button">Restaurar exemplo</button>
      </div>
      <div class="bmd-wac-results" aria-live="polite">
        <span class="bmd-wac-kicker">SUA SIMULAÇÃO</span>
        <p>Custo mensal total estimado</p><strong data-wa-result="monthly">R$ 581,34</strong>
        <div class="bmd-wac-divider"></div>
        <div class="bmd-wac-line"><span>Meta: marketing</span><b data-wa-result="marketingCost">R$ 64,34</b></div>
        <div class="bmd-wac-line"><span>Meta: utilidade</span><b data-wa-result="utilityCost">R$ 28,00</b></div>
        <div class="bmd-wac-line"><span>Meta: autenticação</span><b data-wa-result="authCost">R$ 0,00</b></div>
        <div class="bmd-wac-line"><span>Meta: serviço (após franquia)</span><b data-wa-result="serviceCost">R$ 70,00</b></div>
        <div class="bmd-wac-line"><span>Provedor e plataforma</span><b data-wa-result="platformCost">R$ 299,00</b></div>
        <div class="bmd-wac-line"><span>Automação e IA</span><b data-wa-result="aiCost">R$ 120,00</b></div>
        <div class="bmd-wac-line"><span>Taxa extra por mensagem</span><b data-wa-result="markupCost">R$ 0,00</b></div>
        <div class="bmd-wac-divider"></div>
        <p>Primeiro ano, com implantação</p><strong data-wa-result="year">R$ 7.876,08</strong>
        <p>Custo equivalente mensal no primeiro ano</p><strong data-wa-result="effective">R$ 656,34</strong>
        <p class="bmd-wac-service-note" data-wa-result="serviceInfo">1.000 mensagens de serviço consideradas na franquia; 2.000 cobradas pela Meta na simulação.</p>
        <small>Estimativa apenas para mensagens classificadas nos volumes digitados. Descontos por escala, categorias, janelas gratuitas, impostos, câmbio e outras taxas do fornecedor podem alterar a fatura.</small>
      </div>
    </div>
    <p class="bmd-wac-disclaimer">Os números iniciais são um exemplo ilustrativo, exceto as tarifas de referência indicadas como Meta. Valores, franquias e regras devem ser conferidos na <a href="https://whatsappbusiness.com/products/platform-pricing/" target="_blank" rel="noopener noreferrer">página de preços da Meta</a> e no seu contrato. Nenhuma informação da simulação é enviada ao servidor.</p>
  </section>
  <?php return ob_get_clean();
});

add_action('init',static function(){
 $key='bmd_whatsapp_cost_article_20261030_v1';
 if(get_option($key))return;
 $slug='quanto-custa-automatizar-whatsapp-business-api';
 $found=get_page_by_path($slug,OBJECT,'post');
 if($found instanceof WP_Post){
  update_option($key,array('status'=>'existing','id'=>(int)$found->ID),false);
  return;
 }
 $seed=get_post(8134);
 if(!$seed instanceof WP_Post)return;
 $content=<<<'HTML'
<p>Para automatizar o WhatsApp de uma empresa, o preço final pode ter quatro parcelas diferentes: mensagens cobradas pela Meta, mensalidade do fornecedor de API ou plataforma, ferramenta de atendimento e implantação. Uma oferta de “bot por R$ 99” não significa que o atendimento inteiro custará R$ 99.</p>
<p>Se você chegou aqui procurando <strong>quanto custa o WhatsApp Business API em 2026</strong>, vamos separar cada cobrança, explicar a diferença entre o aplicativo gratuito e a API oficial e colocar os números em uma calculadora que você pode editar. Os valores de referência da Meta abaixo são para números destinatários brasileiros e seguem a tabela consultada em 30 de outubro de 2026. Não usamos preços fictícios de fornecedores como se fossem ofertas reais.</p>
<h2>Resposta rápida: qual é a conta mensal?</h2>
<p><strong>Custo mensal = tarifas da Meta sobre as mensagens entregues + mensalidade da plataforma + uso de automação/IA + eventuais taxas do intermediário.</strong> No primeiro ano, acrescente treinamento, parametrização, importação de contatos e integração com CRM. Pode haver pagamento em outra moeda e cobrança por mensagens recebidas pelo provedor, mesmo quando não há tarifa equivalente da Meta.</p>
<p>O erro mais comum é comparar apenas o preço do software e esquecer o volume. Uma clínica com 100 lembretes mensais tem necessidades diferentes de uma operação com milhares de notificações e centenas de atendimentos simultâneos.</p>
[bmd_whatsapp_cost_2026]
<h2>Tarifas da Meta para mensagens no Brasil em outubro de 2026</h2>
<p>A plataforma oficial cobra pela categoria e pelo mercado do <strong>destinatário</strong>, considerando mensagens entregues. A partir de 1º de outubro de 2026, a categoria serviço passou a ter cobrança após uma franquia mensal por número. A tabela em reais para destinatários no Brasil indicava:</p>
<table><thead><tr><th>Categoria</th><th>Tarifa de referência, R$ por mensagem</th><th>Exemplo</th></tr></thead><tbody>
<tr><td>Marketing</td><td>R$ 0,3217</td><td>Oferta, promoção, recuperação de carrinho e campanha comercial.</td></tr>
<tr><td>Utilidade</td><td>R$ 0,0350</td><td>Confirmação de pagamento ou atualização de entrega, sem promoção.</td></tr>
<tr><td>Autenticação</td><td>R$ 0,0350</td><td>Código de verificação de conta.</td></tr>
<tr><td>Serviço</td><td>R$ 0,0350 após franquia</td><td>Resposta da empresa em atendimento iniciado pelo usuário, na plataforma.</td></tr>
</tbody></table>
<p>Na regra de outubro de 2026, cada número comercial tem <strong>1.000 mensagens de serviço gratuitas por mês</strong>, e o excedente entra na tarifa de serviço. Mensagens de utilidade e autenticação podem ter faixas de desconto por volume; marketing e serviço têm outras condições. Janelas de entrada gratuita, inclusive originadas de anúncios que levam ao WhatsApp, também podem alterar a cobrança. A calculadora usa tarifas de referência sem descontos de escala e considera uma franquia por número, de modo que <strong>a fatura efetiva pode ser diferente</strong>.</p>
<p>Para conferir a regra atual e a moeda da conta, consulte a <a href="https://whatsappbusiness.com/products/platform-pricing/" target="_blank" rel="noopener noreferrer">página oficial de preços do WhatsApp Business</a> e sua documentação técnica: <a href="https://developers.facebook.com/documentation/business-messaging/whatsapp/pricing" target="_blank" rel="noopener noreferrer">Meta for Developers, Pricing</a>. A atualização de outubro e a linha Brasil também foram descritas em uma <a href="https://lia.im/blog/tarifas-whatsapp-meta-outubro-2026/" target="_blank" rel="noopener noreferrer">análise técnica das mudanças de outubro</a>. Confirme antes de contratar: tarifas e janelas podem ser alteradas.</p>
<h2>Aplicativo WhatsApp Business, Cloud API e provedores: o que muda?</h2>
<h3>WhatsApp Business no celular</h3>
<p>O aplicativo atende negócios pequenos com catálogo, respostas rápidas e etiquetas. Instalar o aplicativo não significa adquirir uma API oficial. É uma alternativa adequada quando uma pessoa atende um volume controlável e não precisa de webhooks ou integrações avançadas. O uso do aplicativo e os limites de seus recursos seguem as regras próprias da Meta.</p>
<h3>WhatsApp Cloud API oficial</h3>
<p>É o caminho para integração com software próprio, automação, CRM, roteamento de atendentes e modelos de mensagens aprovados. A empresa precisa organizar os números, permissões, mensagens e políticas. Se montar uma aplicação própria, também haverá despesas de hospedagem, desenvolvimento, monitoramento e manutenção.</p>
<h3>Fornecedor de API, BSP ou plataforma de atendimento</h3>
<p>Um fornecedor pode facilitar a contratação do canal, oferecer suporte e cobrar mensalidade fixa, valor por número ou taxa por mensagem. Uma ferramenta de atendimento pode cobrar por operador, caixas de entrada, funções de IA ou automações. É comum contratar mais de uma camada, então peça uma proposta que detalhe cada cobrança para evitar duplicidade.</p>
<h2>Quanto os provedores cobram além das tarifas da Meta?</h2>
<p>Há modelos diferentes. Não converta valores para reais sem considerar câmbio, tributos e cartão. Exemplos consultados em 30/10/2026:</p>
<table><thead><tr><th>Serviço</th><th>Modelo publicado</th><th>Observação importante</th></tr></thead><tbody>
<tr><td>Twilio WhatsApp</td><td>US$ 0,005 por mensagem trafegada, além da tarifa da Meta aplicável</td><td>Pode cobrar mensagens recebidas e enviadas; outros produtos da Twilio podem acrescentar custos.</td></tr>
<tr><td>360dialog Regular</td><td>€ 49/mês por número, além das tarifas da Meta</td><td>A mensalidade é do canal; a ferramenta de atendimento/integração pode ter seu próprio preço.</td></tr>
<tr><td>Cloud API com integração própria</td><td>Tarifas da Meta + infraestrutura e operação própria</td><td>O trabalho técnico de implantação, manutenção, observabilidade e proteção de dados não é gratuito.</td></tr>
</tbody></table>
<p><strong>Fontes dos fornecedores:</strong> <a href="https://www.twilio.com/en-us/whatsapp/pricing" target="_blank" rel="noopener noreferrer">tabela de preços da Twilio</a> e <a href="https://360dialog.com/br/pricing" target="_blank" rel="noopener noreferrer">planos públicos da 360dialog</a>. Não fizemos teste operacional comparativo dessas soluções e não recebemos pagamento por esta tabela.</p>
<h2>Exemplo: quanto custa uma clínica com atendimento e lembretes?</h2>
<p>Considere uma operação hipotética com um número, 200 mensagens de marketing, 800 de utilidade, 3.000 de serviço e nenhuma de autenticação no mês. Usando as tarifas de referência da Meta, são R$ 64,34 em marketing, R$ 28,00 em utilidade e R$ 70,00 nas 2.000 mensagens de serviço acima da franquia: <strong>R$ 162,34 em tarifas da Meta</strong>, antes de eventuais janelas gratuitas e regras específicas.</p>
<p>Se, apenas como hipótese de orçamento, a plataforma custasse R$ 299 por mês, a automação R$ 120 por mês e a implantação R$ 900 uma única vez, a operação ficaria em R$ 581,34 por mês e <strong>R$ 7.876,08 no primeiro ano</strong>. O simulador já abre com esses números fictícios e permite trocá-los por propostas reais. Não se trata de preço ofertado por nenhuma empresa.</p>
<h2>Como descobrir o custo de uma automação que usa IA?</h2>
<p>Peça a quantidade de conversas ou respostas incluídas no plano, o modelo de IA utilizado, a regra de excedente e o que acontece quando o saldo se esgota. Em bots com IA, custo de tokens, execução de fluxos, armazenamento do histórico, busca em documentos e atendimento humano podem ser cobrados separadamente.</p>
<p>Para não se perder, monte o fluxo em cinco passos: chegada do contato, identificação da intenção, consulta de dados, resposta ou encaminhamento e registro no CRM. Se a automação só precisa responder horário e endereço, não há motivo para pagar uma arquitetura complexa de IA generativa.</p>
<h2>API oficial ou conexão por QR Code?</h2>
<p>Algumas soluções conectam o WhatsApp Web por QR Code e automatizam a sessão. Isso <strong>não equivale à Cloud API oficial</strong>. A empresa precisa avaliar risco de desconexões, limitações de suporte, privacidade, conformidade e eventual restrição de conta. Para operações críticas ou disparos recorrentes, exija clareza sobre a tecnologia e as políticas da Meta.</p>
<h2>Checklist para pedir três orçamentos realmente comparáveis</h2>
<ol>
<li><strong>Perfil de uso:</strong> quantos números, atendentes e mensagens de marketing, utilidade, autenticação e serviço por mês.</li>
<li><strong>Serviço contratado:</strong> aplicativo, API oficial direta, provedor de API ou software de atendimento completo.</li>
<li><strong>Mensageria:</strong> cobrança por categoria, mensagens recebidas, impostos, moeda, desconto por volume e reajustes.</li>
<li><strong>Automação:</strong> chatbot baseado em regras, IA, integrações, SLA, suporte e custo de excedente.</li>
<li><strong>Implantação:</strong> configuração, modelos aprovados, treinamento, migração, LGPD e revisão do fluxo antes da operação.</li>
<li><strong>Saída:</strong> propriedade do número, exportação de dados, acesso ao histórico, cancelamento e migração para outro fornecedor.</li>
</ol>
<h2>Qual automação compensa para uma pequena empresa?</h2>
<p>Comece pela tarefa que mais consome tempo: confirmar agendamentos, qualificar interessados, recuperar orçamentos ou distribuir leads. Meça o volume de atendimento, o custo por conversa útil e os agendamentos ou negócios gerados. Só então acrescente canais e IA.</p>
<p>Para um exemplo de uso específico, veja nosso artigo sobre <a href="/automacao-whatsapp-clinicas-estetica-agendamento/">agendamento via WhatsApp para clínicas de estética</a>. Se precisar registrar os leads no funil, consulte o guia de <a href="/melhor-crm-gratuito-pequenas-empresas/">CRM gratuito para pequenas empresas</a> e a <a href="/quanto-custa-crm-para-pequenas-empresas/">calculadora de custo anual de CRM</a>. Para começar com regras e chatbots simples, veja também <a href="/opcoes-gratuitas-para-criar-um-chatbot-de-atendimento-via-whatsapp/">as opções de chatbot via WhatsApp</a>, observando que os métodos e planos podem ter mudado desde a publicação original.</p>
<h2>Perguntas frequentes</h2>
<h3>WhatsApp Business API é gratuito?</h3>
<p>O aplicativo WhatsApp Business tem uma modalidade gratuita. Já a plataforma de API oficial tem regras próprias de cobrança por mensagens, e a infraestrutura ou o provedor escolhido pode acrescentar custos. A conta final depende do uso.</p>
<h3>O WhatsApp cobra por conversa ou por mensagem em 2026?</h3>
<p>O modelo atual da plataforma é por mensagem entregue, com categorias e exceções. Evite usar tabelas antigas que cobravam por uma conversa inteira. Confirme o rate card do país destinatário.</p>
<h3>Automação de WhatsApp com IA tem um preço único?</h3>
<p>Não. Uma solução pode cobrar usuários, números, mensalidade, mensagens e consumo de IA separadamente. Compare a despesa do primeiro ano com o volume esperado, não apenas o menor plano anunciado.</p>
<h3>Preciso contratar um provedor para usar a API oficial?</h3>
<p>Uma empresa com equipe técnica pode integrar diretamente a Cloud API e assumir a operação. Um provedor pode facilitar implantação, atendimento e suporte, mas cobra por isso. Avalie o trabalho interno antes de decidir.</p>
<p><small><strong>Metodologia e atualização:</strong> revisão editorial em 30 de outubro de 2026. Tarifas por mensagem baseadas em tabela brasileira de outubro de 2026, com links para Meta e fornecedores; exemplos adicionais de software são fictícios. A calculadora usa valores editáveis e não prevê descontos por volume nem todas as exceções de cobrança. Verifique o contrato e a documentação oficial antes de contratar.</small></p>
HTML;
 $id=wp_insert_post(array(
  'post_type'=>'post','post_status'=>'publish',
  'post_name'=>$slug,
  'post_title'=>'Quanto custa automatizar o WhatsApp Business API? Preços e calculadora (2026)',
  'post_content'=>$content,
  'post_excerpt'=>'Veja tarifas da Meta, planos de provedores e custos de IA. Calcule quanto custa o WhatsApp Business API para sua empresa em 2026.',
  'post_author'=>(int)$seed->post_author,
  'post_category'=>array(74),
  'comment_status'=>'closed','ping_status'=>'closed'
 ),true);
 if(is_wp_error($id)||!$id)return;
 update_post_meta($id,'rank_math_title','WhatsApp Business API: Preços e Calculadora 2026');
 update_post_meta($id,'rank_math_description','Quanto custa automatizar o WhatsApp? Veja tarifas da Meta, custos dos provedores e simule o gasto mensal e anual da API oficial em 2026.');
 update_post_meta($id,'rank_math_focus_keyword','quanto custa whatsapp business api');
 update_option($key,array('status'=>'created','id'=>(int)$id,'at'=>current_time('mysql')),false);
},40);

add_filter('the_content',static function($content){
 if(is_admin()||is_feed()||!is_singular('post')||!in_the_loop()||!is_main_query())return $content;
 if(!in_array((int)get_the_ID(),array(7425,8134,8432,8429),true))return $content;
 if(strpos($content,'bmd-wac-link')!==false)return $content;
 $url=esc_url(home_url('/quanto-custa-automatizar-whatsapp-business-api/'));
 $html='<aside class="bmd-wac-link" style="border:1px solid #dae4f3;background:#f7faff;border-radius:13px;padding:20px 24px;margin:28px 0">';
 $html.='<span style="color:#005ff4;font-weight:800;font-size:11px;letter-spacing:.1em">LEITURA RELACIONADA</span>';
 $html.='<h3 style="font-size:19px;margin:8px 0 10px;line-height:1.3">Quanto custa automatizar o WhatsApp em 2026?</h3>';
 $html.='<p style="font-size:14px;color:#586478;margin:0 0 10px">Veja as tarifas de mensagens da Meta, o custo do provedor e uma calculadora para simular seu orçamento.</p>';
 $html.='<a href="'.$url.'" style="color:#005ff4;font-weight:750;text-decoration:underline">Calcular custo mensal e anual do WhatsApp Business API</a></aside>';
 return $content.$html;
},85);
