<?php
/**
 * Search Console intent enhancement: migration decision helper.
 * Original source editorial text remains intact; no database rewrite.
 * Only the already-published WhatsApp transfer guide gets the content.
 */
if (!defined('ABSPATH')) { exit; }

function bmd_whatsapp_migration_guide_html() {
    return <<<'BMD_WA'
<section class="bmd-wa-helper" aria-labelledby="bmd-wa-title">
  <div class="bmd-wa-header">
    <span class="bmd-wa-eyebrow">PASSO A PASSO · ATUALIZADO EM 2026</span>
    <h2 id="bmd-wa-title">Qual é a maneira certa de transferir seu WhatsApp?</h2>
    <p>Selecione seu celular antigo e o novo. Veja o método recomendado e o que conferir antes de começar. Este guia <strong>não pede número, senha nem acesso às conversas</strong>.</p>
  </div>
  <div class="bmd-wa-fields">
    <div><label for="bmd-wa-from">Celular antigo</label><select id="bmd-wa-from"><option value="android">Android</option><option value="iphone">iPhone</option></select></div>
    <span class="bmd-wa-arrow" aria-hidden="true">→</span>
    <div><label for="bmd-wa-to">Celular novo</label><select id="bmd-wa-to"><option value="android">Android</option><option value="iphone">iPhone</option></select></div>
  </div>
  <div class="bmd-wa-answer" aria-live="polite" aria-atomic="true">
    <div class="bmd-wa-route" data-route="android-android">
      <strong class="bmd-wa-method">Android → Android: transferência por QR Code</strong>
      <ol><li>Atualize o WhatsApp nos dois aparelhos e mantenha o número.</li><li>No Android antigo, abra <strong>Configurações → Conversas → Transferir conversas → Iniciar</strong>.</li><li>No novo, instale o WhatsApp, confirme o mesmo número e escolha transferir do celular antigo.</li><li>Escaneie o QR Code com o aparelho antigo e aguarde sem fechar o aplicativo.</li></ol>
      <p><a href="https://faq.whatsapp.com/209942271778103/?cms_platform=android&amp;locale=pt_BR" target="_blank" rel="noopener noreferrer">Ver instruções oficiais do WhatsApp</a></p>
    </div>
    <div class="bmd-wa-route" data-route="iphone-iphone" hidden>
      <strong class="bmd-wa-method">iPhone → iPhone: transferência direta do histórico</strong>
      <ol><li>Atualize o WhatsApp e mantenha ambos os iPhones próximos, carregados e na mesma rede Wi-Fi.</li><li>No iPhone antigo, procure <strong>Configurações → Conversas → Transferir conversas</strong>.</li><li>No novo iPhone, instale o WhatsApp, confirme o mesmo número e siga a opção de transferência.</li><li>Use o aparelho antigo para escanear o QR Code apresentado no novo e aguarde a conclusão.</li></ol>
      <p><a href="https://www.youtube.com/watch?v=Atdc6BAdMcU" target="_blank" rel="noopener noreferrer">Ver tutorial oficial em vídeo do WhatsApp</a></p>
    </div>
    <div class="bmd-wa-route" data-route="iphone-android" hidden>
      <strong class="bmd-wa-method">iPhone → Android: transferência pelo WhatsApp</strong>
      <ol><li>No iPhone, abra <strong>WhatsApp → Configurações → Conversas → Transferir histórico de conversas → Transferir para Android</strong>.</li><li>Prepare as conversas e mantenha o iPhone desbloqueado.</li><li>No Android novo, verifique o mesmo número e siga o fluxo de transferência apresentado.</li><li>Quando aparecer o QR Code no Android, escaneie com o iPhone e aguarde a conclusão.</li></ol>
      <p>A disponibilidade e as telas podem variar por aparelho e versão do aplicativo. <a href="https://faq.whatsapp.com/1295296267926284/?locale=pt_BR" target="_blank" rel="noopener noreferrer">Consulte as etapas oficiais para iPhone → Android</a>.</p>
    </div>
    <div class="bmd-wa-route" data-route="android-iphone" hidden>
      <strong class="bmd-wa-method">Android → iPhone: transferência na configuração do iPhone</strong>
      <ol><li>Antes de ativar o WhatsApp no iPhone, inicie a configuração do aparelho novo.</li><li>Na etapa de transferência de dados, escolha migrar do Android e siga o método compatível com os aparelhos.</li><li>Quando o fluxo oferecer a migração do WhatsApp, selecione os dados e espere o processo terminar.</li><li>Instale o WhatsApp no iPhone, confirme o mesmo número e conclua a importação, se solicitada.</li></ol>
      <p>Se o iPhone já foi configurado, o fluxo pode exigir que ele seja apagado e configurado novamente. Confira as alternativas e os requisitos na <a href="https://support.apple.com/pt-br/118670" target="_blank" rel="noopener noreferrer">documentação oficial da Apple</a>.</p>
    </div>
  </div>
  <div class="bmd-wa-warning"><strong>Antes de apagar o celular antigo:</strong> abra conversas recentes e antigas no aparelho novo e confira as mídias. Se você não tem mais o aparelho antigo, verifique se existe um backup acessível com o mesmo número e a mesma conta Google ou iCloud do backup. A restauração de um histórico anterior pode substituir conversas criadas no celular novo.</div>
</section>
<section class="bmd-wa-diagnosis" aria-labelledby="bmd-wa-compare-title">
  <h2 id="bmd-wa-compare-title">Transferir por QR Code, restaurar backup ou usar Migrar para iOS?</h2>
  <p>O método depende da origem, destino e disponibilidade do celular antigo. Não instale programas desconhecidos nem forneça códigos de verificação para “serviços de transferência”.</p>
  <div class="bmd-wa-table" role="region" aria-label="Tabela de métodos para transferir WhatsApp" tabindex="0">
  <table>
    <thead><tr><th>Troca</th><th>Método principal</th><th>O que precisa</th></tr></thead>
    <tbody>
      <tr><td>Android → Android</td><td>Transferir conversas no WhatsApp, via QR Code</td><td>Os dois aparelhos e o mesmo número</td></tr>
      <tr><td>iPhone → iPhone</td><td>Transferência direta no WhatsApp</td><td>Os dois iPhones, número igual e conexão local</td></tr>
      <tr><td>iPhone → Android</td><td>Transferência de histórico orientada pelo WhatsApp</td><td>iPhone antigo acessível, Android compatível e mesmo número</td></tr>
      <tr><td>Android → iPhone</td><td>Fluxo de migração durante a configuração do iPhone</td><td>Android antigo e configuração inicial do iPhone</td></tr>
      <tr><td>Celular antigo indisponível</td><td>Tentar restaurar backup existente, quando compatível</td><td>Mesmo número, acesso à conta do backup e cópia disponível</td></tr>
    </tbody>
  </table></div>
  <h3>O que fazer se as conversas não aparecerem?</h3>
  <p><strong>Não apague o aparelho antigo.</strong> Confira o número usado, a conta do backup, as versões do WhatsApp e o espaço disponível. Caso tenha criado conversas no aparelho novo, lembre que os históricos não são simplesmente mesclados. Veja as orientações do <a href="https://support.google.com/android/answer/14669513?hl=pt-BR" target="_blank" rel="noopener noreferrer">suporte Android</a> e da <a href="https://faq.whatsapp.com/1144861179456352?lang=pt" target="_blank" rel="noopener noreferrer">central de ajuda do WhatsApp</a>.</p>
  <p><small><strong>Fonte e revisão editorial:</strong> central de ajuda do WhatsApp, suporte Android e suporte Apple, consultados em 28 de outubro de 2026. As telas podem mudar sem aviso. Não somos afiliados ao WhatsApp ou à Meta.</small></p>
</section>
<style>
.bmd-wa-helper,.bmd-wa-diagnosis{max-width:950px;margin:26px 0 32px;color:#1e2b3f}
.bmd-wa-helper{overflow:hidden;border:1px solid #dfe7ed;border-radius:19px;box-shadow:0 9px 30px rgba(20,35,50,.045);background:#fff}
.bmd-wa-helper *,.bmd-wa-diagnosis *{box-sizing:border-box}
.bmd-wa-header{padding:clamp(20px,3.2vw,30px);background:linear-gradient(125deg,#f7fffa,#f7f9ff);border-bottom:1px solid #e3e9f0}
.bmd-wa-eyebrow{font-size:11px;font-weight:800;letter-spacing:.095em;color:#25775b;display:inline-block;margin-bottom:12px}
.bmd-wa-header h2{margin:0 0 12px!important;font-size:clamp(25px,3.1vw,33px)!important;letter-spacing:-.04em;line-height:1.18!important}
.bmd-wa-header p{margin:0!important;font-size:14px;line-height:1.65;color:#536479}
.bmd-wa-fields{display:grid;grid-template-columns:minmax(0,1fr) auto minmax(0,1fr);align-items:end;gap:13px;padding:23px clamp(20px,3.2vw,30px)}
.bmd-wa-fields label{display:block;margin-bottom:8px;font-weight:750;font-size:13px}
.bmd-wa-fields select{display:block;width:100%;min-width:0;min-height:47px;background:#fff;border:1px solid #becbd9;border-radius:10px;padding:10px 13px;font-size:15px;color:#1e2b3f}
.bmd-wa-fields select:focus{outline:2px solid #399c79;outline-offset:2px}
.bmd-wa-arrow{font-size:24px;line-height:47px;color:#7f8ea2}
.bmd-wa-answer{padding:0 clamp(20px,3.2vw,30px) 24px}
.bmd-wa-route{border:1px solid #cee8d9;border-radius:12px;padding:20px;background:#f3fbf6}
.bmd-wa-route[hidden]{display:none!important}
.bmd-wa-method{display:block;font-size:17px;line-height:1.4;margin-bottom:12px}
.bmd-wa-route ol{padding-left:20px;margin:11px 0!important}
.bmd-wa-route li{font-size:14px;line-height:1.65;margin:7px 0}
.bmd-wa-route p{margin:12px 0 0!important;font-size:13px;line-height:1.6}
.bmd-wa-warning{background:#f7f9fc;border-top:1px solid #e7ecf3;color:#58677b;font-size:13px;line-height:1.65;padding:18px clamp(20px,3.2vw,30px)}
.bmd-wa-diagnosis h2{font-size:clamp(22px,3vw,28px)!important;letter-spacing:-.025em;margin:10px 0 13px!important}
.bmd-wa-diagnosis h3{font-size:19px;margin:27px 0 10px}
.bmd-wa-diagnosis p{line-height:1.7}
.bmd-wa-table{max-width:100%;overflow-x:auto;-webkit-overflow-scrolling:touch;border:1px solid #e2e8f0;border-radius:11px;margin:16px 0}
.bmd-wa-table table{width:100%;min-width:560px;border-collapse:collapse;margin:0!important}
.bmd-wa-table th,.bmd-wa-table td{font-size:13px;line-height:1.6;vertical-align:top;text-align:left;padding:13px 14px;border-bottom:1px solid #e8edf3}
.bmd-wa-table th{font-weight:750;background:#f5f8fb}
@media(max-width:540px){.bmd-wa-fields{grid-template-columns:1fr;gap:5px}.bmd-wa-arrow{transform:rotate(90deg);justify-self:center;font-size:19px;line-height:22px}.bmd-wa-route{padding:16px}.bmd-wa-diagnosis{margin:20px 0}}
</style>
<script>
(function(){
  var from=document.getElementById('bmd-wa-from'),to=document.getElementById('bmd-wa-to');
  if(!from||!to)return;
  var entries=document.querySelectorAll('.bmd-wa-route[data-route]');
  function update(){var key=from.value+'-'+to.value;for(var i=0;i<entries.length;i++){entries[i].hidden=(entries[i].getAttribute('data-route')!==key);}}
  from.addEventListener('change',update);to.addEventListener('change',update);update();
})();
</script>
BMD_WA;
}

add_filter('the_content',function($content){
    if (is_admin() || !is_singular('post') || !is_main_query() || !in_the_loop() || is_preview()) { return $content; }
    $slug=(string)get_post_field('post_name',get_the_ID());
    if ($slug!=='como-transferir-o-whatsapp-para-outro-celular') {return $content;}
    if (strpos($content,'bmd-wa-helper')!==false || strpos($content,'<h2')===false || strlen($content)<3500) {return $content;}
    // Preserve the original guide and its updated 2026 official procedures.
    if (stripos(wp_strip_all_tags($content),'Transferir conversas')===false) {return $content;}
    $pos=strpos($content,'<h2');
    if ($pos===false || $pos>8000) {return $content;}
    return substr($content,0,$pos).bmd_whatsapp_migration_guide_html().substr($content,$pos);
},58);
