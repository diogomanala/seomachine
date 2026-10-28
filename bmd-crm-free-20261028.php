<?php
/**
 * BMD 3.6.1: first-party Search Console opportunity.
 * CRM gratuito 2026: original editorial update, official primary references
 * and a local decision helper. No commercial affiliate attribution, APIs or tracking.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_crm_free_selector_2026() {
    return <<<'BMD_SELECTOR'
<section class="bmd-crm-picker" aria-labelledby="bmd-crm-picker-title">
 <div class="bmd-crm-picker-top">
  <span class="bmd-crm-kicker">COMPARADOR GRÁTIS · SEM CADASTRO</span>
  <h2 id="bmd-crm-picker-title">Qual CRM grátis combina com sua equipe?</h2>
  <p>Selecione o tamanho do time e a necessidade principal. A indicação considera limites oficiais e casos de uso — <strong>não é um teste feito na sua operação</strong> nem uma classificação patrocinada.</p>
 </div>
 <div class="bmd-crm-picker-form">
  <div><label for="bmd-crm-team">Pessoas que precisam acessar o CRM</label>
   <select id="bmd-crm-team">
    <option value="1">1 pessoa</option><option value="2">2 pessoas</option><option value="3">3 pessoas</option><option value="4">4 pessoas</option><option value="5">5 ou mais pessoas</option>
   </select></div>
  <div><label for="bmd-crm-goal">O mais importante agora</label>
   <select id="bmd-crm-goal">
    <option value="simple">Organizar contatos e funil com simplicidade</option>
    <option value="whatsapp">Acompanhar vendas pelo WhatsApp</option>
    <option value="growth">Crescer com ferramentas de marketing e vendas</option>
    <option value="oneapp">Ter vários usuários usando somente o CRM</option>
   </select></div>
 </div>
 <div class="bmd-crm-result" role="status" aria-live="polite" aria-atomic="true">
  <span class="bmd-crm-result-label">UMA OPÇÃO PARA CONFERIR PRIMEIRO</span>
  <strong id="bmd-crm-choice">Agendor CRM</strong>
  <p id="bmd-crm-why">Comece pelo plano gratuito e teste o fluxo com dados reais.</p>
  <a id="bmd-crm-official" href="https://www.agendor.com.br/planos-precos/crm" target="_blank" rel="noopener noreferrer">Conferir plano oficial ↗</a>
 </div>
 <p class="bmd-crm-note">Critérios: tamanho da equipe e necessidade declarada. O plano grátis não garante API oficial do WhatsApp, múltiplos funis ou automações avançadas. Valores e limites podem mudar; confirme na página oficial antes de cadastrar sua equipe.</p>
</section>
<style>
.bmd-crm-picker{max-width:940px;margin:25px 0 34px;background:#fff;border:1px solid #dde5ee;border-radius:18px;box-shadow:0 12px 28px rgba(20,31,50,.045);overflow:hidden;color:#1c2d43}
.bmd-crm-picker *{box-sizing:border-box}
.bmd-crm-picker-top{padding:clamp(22px,3vw,32px);background:linear-gradient(125deg,#f8fbff,#fff8f2)}
.bmd-crm-kicker{font-size:11px;letter-spacing:.11em;font-weight:800;color:#b35436}
.bmd-crm-picker-top h2{font-size:clamp(23px,3.5vw,33px)!important;letter-spacing:-.04em;line-height:1.2!important;margin:12px 0!important}
.bmd-crm-picker-top p{font-size:14px;color:#53647b;line-height:1.65;margin:0!important}
.bmd-crm-picker-form{padding:23px clamp(22px,3vw,32px) 18px;display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:18px}
.bmd-crm-picker label{font-size:13px;font-weight:750;display:block;margin-bottom:9px}
.bmd-crm-picker select{display:block!important;width:100%;min-width:0;border:1px solid #bfcbd8;border-radius:10px;padding:10px;min-height:49px;font-size:14px;background:#fff;color:#1c2d43}
.bmd-crm-picker select:focus{outline:2px solid #cf8f6c;outline-offset:2px}
.bmd-crm-result{margin:0 clamp(22px,3vw,32px) 21px;background:#f4f8fb;border:1px solid #dee9f2;padding:18px;border-radius:12px}
.bmd-crm-result-label{display:block;font-size:10px;font-weight:850;letter-spacing:.09em;color:#697a8d}
.bmd-crm-result strong{display:block;font-size:clamp(19px,2.5vw,24px);margin:8px 0;color:#21364d}
.bmd-crm-result p{font-size:13px;line-height:1.6;color:#4f6173;margin:0 0 14px}
.bmd-crm-result a{display:inline-block;color:#fff;background:#203249;padding:11px 15px;text-decoration:none;border-radius:9px;font-size:13px;font-weight:750}
.bmd-crm-note{font-size:12px;line-height:1.65;margin:0!important;color:#627489;background:#f9fbfd;border-top:1px solid #e9edf3;padding:15px clamp(22px,3vw,32px)}
@media(max-width:650px){.bmd-crm-picker-form{grid-template-columns:1fr;gap:13px}.bmd-crm-picker-top,.bmd-crm-picker-form{padding:20px}.bmd-crm-result{margin:0 20px 18px}.bmd-crm-note{padding:15px 20px}}
</style>
<script>
(function(){
 var team=document.getElementById('bmd-crm-team'),goal=document.getElementById('bmd-crm-goal'),
 title=document.getElementById('bmd-crm-choice'),why=document.getElementById('bmd-crm-why'),link=document.getElementById('bmd-crm-official');
 if(!team||!goal||!title||!why||!link)return;
 var choices={
  agendor:{name:'Agendor CRM',url:'https://www.agendor.com.br/planos-precos/crm',reason:'Plano gratuito para até 3 usuários, com contatos, tarefas e funil. É uma opção brasileira para começar a organizar o comercial; verifique os limites de negócios e personalizações.'},
  rd:{name:'RD Station CRM',url:'https://www.rdstation.com/planos/crm/',reason:'O Free anuncia até 4 usuários, funil e extensão para WhatsApp. Confira o que a extensão faz; não confunda com acesso gratuito à API oficial do WhatsApp.'},
  hubspot:{name:'HubSpot CRM',url:'https://br.hubspot.com/pricing/crm',reason:'O plano grátis informa até 2 usuários e 1.000 contatos. Pode servir para conhecer o ecossistema de marketing e vendas, mas recursos mais avançados exigem upgrade.'},
  zoho:{name:'Zoho CRM',url:'https://www.zoho.com/pt-br/crm/zohocrm-pricing.html',reason:'A edição gratuita informa até 3 usuários com recursos de contatos, oportunidades e tarefas. Confirme integrações específicas antes de adotar.'},
  odoo:{name:'Odoo CRM — One App Free',url:'https://www.odoo.com/pt_BR/pricing',reason:'O plano de um aplicativo anuncia usuários ilimitados. É uma escolha a verificar quando você realmente precisa apenas do CRM, pois outros aplicativos podem exigir plano pago.'},
  none:{name:'Compare um plano pago ou reduza o escopo',url:'https://www.rdstation.com/planos/crm/',reason:'Para 5 ou mais usuários com integração de WhatsApp ou vários módulos, não encontramos nesta seleção uma opção gratuita que possamos garantir. Compare as condições oficiais e o custo total.'}
 };
 function update(){
  var n=Number(team.value),g=goal.value,k='agendor';
  if(g==='oneapp')k='odoo';
  else if(n>=5)k=g==='simple'?'odoo':'none';
  else if(g==='whatsapp')k='rd';
  else if(n===4)k='rd';
  else if(g==='growth')k=n<=2?'hubspot':'zoho';
  else k='agendor';
  var item=choices[k];title.textContent=item.name;why.textContent=item.reason;link.href=item.url;
  link.textContent=k==='none'?'Comparar os planos oficiais ↗':'Conferir plano oficial ↗';
 }
 team.addEventListener('change',update);goal.addEventListener('change',update);update();
})();
</script>
BMD_SELECTOR;
}

add_shortcode('bmd_crm_free_selector_2026','bmd_crm_free_selector_2026');

add_action('init',function(){
    $key='bmd_crm_nine_20261028_v1';
    if(get_option($key,false)==='applied')return;
    $p=get_page_by_path('melhor-crm-gratuito-pequenas-empresas',OBJECT,'post');
    if(!$p || (int)$p->ID!==8432 || $p->post_status!=='publish')return;
    $old=(string)$p->post_content;
    $back='_bmd_crm_seven_original_20261028';
    if(metadata_exists('post',$p->ID,$back)){update_option($key,'applied',false);return;}
    if(strlen($old)<6000||strlen($old)>35000||
        stripos(wp_strip_all_tags($old),'Odoo CRM')===false||
        stripos(wp_strip_all_tags($old),'Capsule CRM')===false||
        stripos(wp_strip_all_tags($old),'Bitrix24')===false){
        update_option($key,'original-mismatch',false);return;
    }
    if(!add_post_meta($p->ID,$back,$old,true))return;
    $body=<<<'BMD_CRM'
<p><strong>Qual é o melhor CRM gratuito para pequenas empresas em 2026?</strong> Depende de quantas pessoas precisam acessar, quantos contatos a empresa registra e quais recursos realmente serão usados. Para equipes de até duas pessoas, HubSpot é uma opção; para três, Zoho, Agendor e Freshsales merecem avaliação; para quatro, o RD Station CRM anuncia plano Free; para um número maior usando apenas um aplicativo, o Odoo oferece uma proposta diferente.</p>
<p>Este comparativo reúne <strong>nove opções com modalidade gratuita permanente</strong>, sem confundir teste temporário com plano grátis. Os limites abaixo foram conferidos em páginas oficiais em <strong>28 de outubro de 2026</strong>. Não testamos os nove produtos em contas reais e não recebemos pagamento por classificação; verifique condições atuais antes de migrar dados.</p>

[bmd_crm_free_selector_2026]

<h2>Resumo: nove CRMs com plano grátis e seus limites</h2>
<div class="bmd-crm-table" role="region" aria-label="Planos gratuitos de CRM comparados" tabindex="0"><table>
<thead><tr><th>CRM</th><th>Usuários no grátis</th><th>Limite / principal condição</th><th>Verificar fornecedor</th></tr></thead>
<tbody>
<tr><td><strong>HubSpot</strong></td><td>Até 2</td><td>1.000 contatos no plano gratuito</td><td><a href="https://br.hubspot.com/pricing/crm" target="_blank" rel="noopener noreferrer">Página oficial</a></td></tr>
<tr><td><strong>Zoho CRM</strong></td><td>Até 3</td><td>CRM tradicional; confirme recursos por edição</td><td><a href="https://www.zoho.com/pt-br/crm/zohocrm-pricing.html" target="_blank" rel="noopener noreferrer">Página oficial</a></td></tr>
<tr><td><strong>RD Station CRM</strong></td><td>Até 4</td><td>Funil, tarefas, extensão WhatsApp; automações avançadas nos pagos</td><td><a href="https://www.rdstation.com/planos/crm/" target="_blank" rel="noopener noreferrer">Página oficial</a></td></tr>
<tr><td><strong>Agendor CRM</strong></td><td>Até 3</td><td>Até 10.000 empresas, 10.000 pessoas, 1.500 negócios</td><td><a href="https://www.agendor.com.br/planos-precos/crm" target="_blank" rel="noopener noreferrer">Página oficial</a></td></tr>
<tr><td><strong>Freshsales</strong></td><td>Até 3</td><td>CRM gratuito com Kanban e produtividade básica</td><td><a href="https://crmsupport.freshworks.com/support/solutions/articles/50000002465-how-to-activate-the-free-plan" target="_blank" rel="noopener noreferrer">Ajuda oficial</a></td></tr>
<tr><td><strong>Capsule</strong></td><td>Até 2</td><td>250 contatos e um funil</td><td><a href="https://capsulecrm.com/pt/free-crm/" target="_blank" rel="noopener noreferrer">Página oficial</a></td></tr>
<tr><td><strong>EngageBay</strong></td><td>Conferir</td><td>Até 250 contatos e empresas; marketing e CRM básicos</td><td><a href="https://www.engagebay.com/pricing/sales" target="_blank" rel="noopener noreferrer">Página oficial</a></td></tr>
<tr><td><strong>Odoo One App Free</strong></td><td>Ilimitados*</td><td>Um aplicativo grátis; outros módulos podem gerar custo</td><td><a href="https://www.odoo.com/pt_BR/pricing" target="_blank" rel="noopener noreferrer">Página oficial</a></td></tr>
<tr><td><strong>Bitrix24</strong></td><td>1–2</td><td>5 GB e CRM básico, conforme tabela oficial de outubro</td><td><a href="https://www.bitrix24.com/prices/" target="_blank" rel="noopener noreferrer">Página oficial</a></td></tr>
</tbody></table></div>
<p><small>*Odoo oferece usuários ilimitados quando o plano se limita ao aplicativo permitido e dependências incluídas. Se você adicionar outros aplicativos fora dessas condições, poderá precisar contratar um plano. Limites, recursos e preços podem mudar sem aviso.</small></p>

<h2>Qual CRM gratuito vale olhar primeiro?</h2>
<h3>1. HubSpot: ecossistema integrado para equipes pequenas</h3>
<p>O <a href="https://br.hubspot.com/pricing/crm" target="_blank" rel="noopener noreferrer">HubSpot gratuito</a> informa até dois usuários e 1.000 contatos, com CRM básico e integração com e-mail. É uma boa porta de entrada para quem quer registrar negócios e conhecer os produtos de marketing e atendimento da empresa. <strong>Atenção:</strong> planos pagos e recursos avançados, como integrações específicas de WhatsApp, não estão automaticamente incluídos.</p>
<h3>2. Zoho CRM: opção de três usuários com foco em vendas</h3>
<p>A <a href="https://www.zoho.com/pt-br/crm/zohocrm-pricing.html" target="_blank" rel="noopener noreferrer">edição gratuita do Zoho CRM</a> atende até três usuários, com leads, contatos e negócios. Verifique os limites de modelos, relatórios, fluxos de trabalho e APIs na versão vigente antes de importar grandes volumes de dados.</p>
<h3>3. RD Station CRM: até quatro pessoas e fluxo comercial no Brasil</h3>
<p>O <a href="https://www.rdstation.com/planos/crm/" target="_blank" rel="noopener noreferrer">RD Station CRM Free</a> anuncia até quatro usuários, funil de vendas, acompanhamento de tarefas e recursos para trabalhar com WhatsApp. É especialmente relevante para pequenas equipes brasileiras. <strong>Não confunda a extensão para WhatsApp Web com a API oficial do WhatsApp Business:</strong> os produtos têm custos, permissões e capacidades diferentes.</p>
<h3>4. Agendor: organização de vendas consultivas</h3>
<p>O <a href="https://www.agendor.com.br/planos-precos/crm" target="_blank" rel="noopener noreferrer">Agendor Gratuito</a> atende até três usuários e trabalha com contatos, tarefas e funil. Segundo a <a href="https://ajuda.agendor.com.br/pt-BR/articles/5371555-o-agendor-tem-plano-gratuito" target="_blank" rel="noopener noreferrer">central de ajuda oficial</a>, o plano gratuito limita a base a 10.000 empresas, 10.000 pessoas e 1.500 negócios, com 2 GB de armazenamento. É uma opção para equipes B2B que precisam de acompanhamento e importação de planilhas.</p>
<h3>5. Freshsales: funil visual para uma equipe pequena</h3>
<p>O <a href="https://crmsupport.freshworks.com/support/solutions/articles/50000002465-how-to-activate-the-free-plan" target="_blank" rel="noopener noreferrer">Freshsales Free</a> descreve suporte a até três usuários, contatos, contas, negócios e visualização Kanban, além de recursos básicos de produtividade. O processo para ativar ou retornar à edição Free deve ser confirmado na conta.</p>
<h3>6. Capsule CRM: simplicidade, com teto de contatos baixo</h3>
<p>O <a href="https://capsulecrm.com/signup/" target="_blank" rel="noopener noreferrer">Capsule Free</a> informa até dois usuários e 250 contatos. Faz mais sentido quando poucos profissionais acompanham uma carteira pequena e querem uma interface de CRM sem implantar uma estrutura grande.</p>
<h3>7. EngageBay: CRM com recursos de relacionamento</h3>
<p>O <a href="https://www.engagebay.com/pricing/sales" target="_blank" rel="noopener noreferrer">EngageBay Free</a> inclui até 250 contatos e empresas, negócios, tarefas e recursos básicos de e-mail e atendimento. Confira os recursos específicos da modalidade de marketing, vendas ou all-in-one antes de escolher — são tabelas distintas.</p>
<h3>8. Odoo: CRM de um aplicativo com usuários ilimitados</h3>
<p>O <a href="https://www.odoo.com/pt_BR/pricing" target="_blank" rel="noopener noreferrer">Odoo One App Free</a> anuncia um aplicativo e usuários ilimitados. Isso pode ser interessante quando a equipe precisa essencialmente do CRM. O principal cuidado é o crescimento: acrescentar módulos além dos permitidos pode exigir planos pagos. Não escolha a opção só pelo número de usuários.</p>
<h3>9. Bitrix24: CRM, tarefas e colaboração com limites atuais</h3>
<p>O <a href="https://www.bitrix24.com/prices/" target="_blank" rel="noopener noreferrer">Bitrix24 Free</a> informa atualmente <strong>1–2 usuários e 5 GB</strong>, com CRM básico, tarefas, calendário e chat. Comparativos antigos que indicam usuários ilimitados não refletem a página oficial consultada em outubro de 2026. Confirme o país e o tipo de conta antes de aderir.</p>

<h2>CRM grátis com WhatsApp: o que realmente vem incluso?</h2>
<p>Esta é uma das dúvidas mais importantes para empresas no Brasil. Algumas plataformas oferecem extensão que facilita registrar um contato enquanto você usa WhatsApp Web. Outras vendem integrações por parceiros ou produtos específicos. <strong>Isso não significa que o plano gratuito inclua a API oficial da Meta</strong>, chatbot, disparos automatizados ou múltiplos atendentes no mesmo número.</p>
<p>Antes de decidir, pergunte se a integração funciona no aplicativo, no navegador, pela API oficial ou por um serviço de terceiros; se há limites de conversas e mensagens; e quanto custa ativar o recurso com sua quantidade real de atendentes. Consulte a <a href="https://www.rdstation.com/produtos/crm/vendas/vender-pelo-whatsapp/" target="_blank" rel="noopener noreferrer">descrição oficial da extensão do RD Station</a> para entender a modalidade indicada no plano Free.</p>

<h2>Como migrar da planilha para um CRM gratuito?</h2>
<ol>
<li><strong>Limpe a base:</strong> remova contatos duplicados, revise permissões de acesso e identifique a origem dos dados.</li>
<li><strong>Crie um funil simples:</strong> novo contato, qualificado, proposta, negociação, ganho ou perdido. Adapte as etapas à operação.</li>
<li><strong>Teste com poucos registros:</strong> importe uma amostra e confira se campos, responsáveis e observações permanecem legíveis.</li>
<li><strong>Organize os próximos passos:</strong> toda oportunidade precisa de responsável e data de acompanhamento.</li>
<li><strong>Confira saída e portabilidade:</strong> teste como exportar contatos, empresas, negócios e atividades antes de importar o histórico completo.</li>
<li><strong>Documente a rotina:</strong> indique quem atualiza dados e com qual frequência.</li>
</ol>
<p>Além de organizar o CRM, é importante identificar de onde vêm os novos contatos. Para campanhas, use o <a href="/gerador-de-utm/">gerador de UTM</a> e defina os indicadores de vendas com nosso <a href="/o-que-sao-kpis-conheca-os-9-melhores-kpis-de-marketing-digital/">guia de KPIs</a>. Esses recursos ajudam a medir a aquisição, mas não substituem a integração real de eventos e dados.</p>

<h2>Qual CRM gratuito escolher por tamanho de equipe?</h2>
<div class="bmd-crm-table" role="region" aria-label="Critérios para selecionar CRM por tamanho da equipe" tabindex="0"><table><thead><tr><th>Equipe</th><th>Planos a verificar</th><th>Decisão importante</th></tr></thead><tbody>
<tr><td>1–2 pessoas</td><td>HubSpot, Capsule, Bitrix24, Agendor</td><td>Quantidade de contatos, facilidade de uso e suporte</td></tr>
<tr><td>3 pessoas</td><td>Zoho, Agendor, Freshsales, RD Station</td><td>Recursos de funil, uso no celular e exportação</td></tr>
<tr><td>4 pessoas</td><td>RD Station</td><td>Limitações de funil e integrações no Free</td></tr>
<tr><td>5+ pessoas</td><td>Odoo One App Free</td><td>Se CRM sozinho atende ou módulos adicionais são necessários</td></tr>
</tbody></table></div>

<h2>Perguntas frequentes sobre CRM gratuito</h2>
<h3>Existe CRM gratuito sem limite de tempo?</h3>
<p>Sim, as nove opções listadas oferecem alguma modalidade gratuita sem prazo anunciado de expiração, embora recursos, usuários e contatos sejam limitados. Isso é diferente de um período de avaliação que exige assinatura depois.</p>
<h3>Pipedrive ou Kommo têm CRM grátis para sempre?</h3>
<p>Não trate um teste promocional ou gratuito de poucos dias como plano sem mensalidade. Consulte as páginas oficiais atuais desses produtos para ver se há alguma modalidade permanente antes de incluí-los em uma comparação com plano Free.</p>
<h3>Qual CRM grátis é melhor para WhatsApp?</h3>
<p>O RD Station Free merece avaliação pela extensão para WhatsApp Web. Mas, se você precisa de API oficial, múltiplos agentes, automação de mensagens ou integrações avançadas, compare os custos totais: não presuma que isso vem incluído.</p>
<h3>É seguro colocar dados de clientes em um CRM grátis?</h3>
<p>O preço não determina a segurança. Confira política de privacidade, controle de acesso, autenticação, exportação, localização e responsabilidades de tratamento de dados. Uma empresa deve aplicar a LGPD ao tratamento de dados pessoais independentemente da mensalidade do software.</p>

<h2>Metodologia e fontes</h2>
<p>Revisão editorial de páginas públicas de planos e centrais de ajuda dos nove fornecedores em <strong>28 de outubro de 2026</strong>. Critérios considerados: existência de plano gratuito não limitado a trial, teto de usuários, contatos quando divulgado, tarefas/funil e restrições de expansão. <strong>Não há testes de contas realizados por nossa equipe e nenhum pagamento por posicionamento.</strong> Não atribuímos uma nota numérica artificial a funcionalidades que não verificamos.</p>
<p>Para escolher, comece por sua necessidade real: usuários, contatos, recursos de WhatsApp e exportação. Antes de assinar ou migrar o histórico, revise sempre a página oficial. Veja também nosso <a href="/melhores-plataformas-de-automacao-de-marketing-em-2026-guia-completo-para-escolher-a-certa/">comparativo de automação de marketing</a> e o <a href="/montar-briefing-profissional-marketing-digital/">modelo de briefing gratuito</a> para organizar a implantação.</p>
BMD_CRM;
    $res=wp_update_post(array('ID'=>$p->ID,'post_title'=>'Melhor CRM Gratuito para Pequenas Empresas: 9 Opções em 2026','post_content'=>$body),true);
    if(is_wp_error($res)||!$res){delete_post_meta($p->ID,$back);return;}
    update_post_meta($p->ID,'rank_math_title','Melhor CRM Gratuito em 2026: 9 Planos e Limites | BMD');
    update_post_meta($p->ID,'rank_math_description','Compare 9 CRMs gratuitos para pequenas empresas em 2026: HubSpot, Zoho, RD Station, Agendor e mais. Limites oficiais e seletor para sua equipe.');
    clean_post_cache($p->ID);
    update_option($key,'applied',false);
},34);

add_filter('rank_math/frontend/title',function($title){
    if(!is_admin()&&is_singular('post')&&get_post_field('post_name',get_queried_object_id())==='melhor-crm-gratuito-pequenas-empresas'&&metadata_exists('post',get_queried_object_id(),'_bmd_crm_seven_original_20261028'))
        return 'Melhor CRM Gratuito em 2026: 9 Planos e Limites | BMD';
    return $title;
},86);
add_filter('rank_math/frontend/description',function($desc){
    if(!is_admin()&&is_singular('post')&&get_post_field('post_name',get_queried_object_id())==='melhor-crm-gratuito-pequenas-empresas'&&metadata_exists('post',get_queried_object_id(),'_bmd_crm_seven_original_20261028'))
        return 'Compare 9 CRMs gratuitos para pequenas empresas em 2026: HubSpot, Zoho, RD Station, Agendor e mais. Limites oficiais e seletor para sua equipe.';
    return $desc;
},86);
add_action('wp_head',function(){
    if(is_admin()||!is_singular('post')||get_post_field('post_name',get_queried_object_id())!=='melhor-crm-gratuito-pequenas-empresas')return;
    echo '<style>.entry-content .bmd-crm-table{width:100%;max-width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;border:1px solid #e1e7ee;border-radius:12px;margin:20px 0}.entry-content .bmd-crm-table table{width:100%;min-width:590px;border-collapse:collapse;margin:0}.entry-content .bmd-crm-table th,.entry-content .bmd-crm-table td{padding:12px 13px;vertical-align:top;text-align:left;font-size:13px;line-height:1.6;border-bottom:1px solid #e7ecf2}.entry-content .bmd-crm-table th{background:#f5f8fa;font-weight:760;color:#24334b}</style>';
},79);
