<?php
/**
 * BMD RDC checklist | Scoped to original post 9137.
 * Frontend-only client-side checklist, no form submissions or compliance claims.
 * Render-only, no edits to stored article content.
 */
if (!defined('ABSPATH')) { exit; }
function bmd_rdc_checklist_target() {
    return !is_admin() && is_singular('post') && (int) get_queried_object_id() === 9137;
}
add_filter('the_content', static function($html) {
    if (!bmd_rdc_checklist_target() || !in_the_loop() || !is_main_query() || strpos($html, 'id="bmd-rdc-checker"') !== false) {
        return $html;
    }
    $items = array(
      array('Cadastro e licenças', 'Reúna dados da unidade, registros profissionais e licenças aplicáveis.'),
      array('Responsável técnico', 'Identifique o RT e as responsabilidades de revisão e aprovação.'),
      array('SDBPF e planos', 'Confira a série documental e os planos pertinentes ao serviço.'),
      array('POPs e protocolos', 'Descreva as rotinas reais da equipe, com responsáveis definidos.'),
      array('Resíduos e PGRSS', 'Verifique a existência do plano e das evidências de execução.'),
      array('Limpeza e processamento', 'Organize os registros de limpeza, esterilização e monitoramento aplicáveis.'),
      array('Água e equipamentos', 'Reúna controles e comprovantes de manutenção aplicáveis.'),
      array('Treinamentos', 'Registre as capacitações e orientações da equipe.'),
      array('Prestadores e terceiros', 'Confira contratos e comprovações dos serviços terceirizados aplicáveis.'),
      array('Revisão e atualização', 'Planeje validação pelo RT, controle de versões e atualização periódica.'),
    );
    $cards = '';
    foreach($items as $i=>$item) {
        $cards .= '<label class="bmd-rc-item"><input type="checkbox" data-bmd-rc-item="'.esc_attr($i).'"/><span class="bmd-rc-content"><strong>'.esc_html($item[0]).'</strong><span>'.esc_html($item[1]).'</span></span></label>';
    }
    $ui = '<section id="bmd-rdc-checker" aria-labelledby="bmd-rc-title"><div class="bmd-rc-head"><div><span class="bmd-rc-kicker">FERRAMENTA GRATUITA · NO NAVEGADOR</span><h3 id="bmd-rc-title">Checklist inicial de organização documental</h3><p>Marque o que já está organizado para revisão. O preenchimento fica neste navegador e não é enviado ao site.</p></div><div class="bmd-rc-counter" aria-live="polite"><strong id="bmd-rc-count">0/10</strong><span>itens marcados</span></div></div><div class="bmd-rc-progress" role="progressbar" aria-label="Itens organizados" aria-valuemin="0" aria-valuemax="10" aria-valuenow="0"><i id="bmd-rc-bar"></i></div><div class="bmd-rc-grid">'.$cards.'</div><div class="bmd-rc-note"><strong>Importante:</strong> este checklist é um organizador de tarefas. Marcar itens não comprova cumprimento da RDC 1.002/2025. A aplicabilidade depende das atividades da clínica; a análise e validação documental cabem ao responsável técnico e às autoridades competentes.</div><div class="bmd-rc-actions"><button id="bmd-rc-print" type="button">Imprimir checklist</button><button id="bmd-rc-clear" type="button">Limpar marcações</button><span id="bmd-rc-status" aria-live="polite"></span></div></section>';
    $anchor='<h2>Checklist para começar a organizar a pasta documental</h2>';
    if (strpos($html,$anchor)!==false) return str_replace($anchor,$ui.$anchor,$html);
    return $html.$ui;
}, 121);
add_action('wp_head',static function(){
if(!bmd_rdc_checklist_target()) return;
echo <<<'BMD_RDC_CHECK_CSS'
<style id="bmd-rc-css">
#bmd-rdc-checker{font-family:inherit;color:#193b42;background:#fff;border:1px solid #d9e8e5;border-radius:20px;padding:clamp(18px,3vw,28px);margin:32px 0;box-shadow:0 8px 32px rgba(14,54,57,.045)}
#bmd-rdc-checker *{box-sizing:border-box}
#bmd-rdc-checker .bmd-rc-head{display:flex;justify-content:space-between;align-items:center;gap:16px}
#bmd-rdc-checker .bmd-rc-kicker{font-size:10px;letter-spacing:.11em;font-weight:800;color:#34837b}
#bmd-rdc-checker h3{font-size:clamp(20px,2.8vw,27px)!important;color:#173d45!important;margin:7px 0 8px!important;line-height:1.25!important}
#bmd-rdc-checker .bmd-rc-head p{font-size:13px;color:#64777c;line-height:1.65;margin:0!important}
#bmd-rdc-checker .bmd-rc-counter{flex:0 0 auto;background:#e7f3ef;padding:13px 17px;border-radius:13px;text-align:center;min-width:98px}
#bmd-rdc-checker .bmd-rc-counter strong{display:block;color:#1e6764;font-size:25px;line-height:1.15}
#bmd-rdc-checker .bmd-rc-counter span{font-size:10px;color:#5d7778}
#bmd-rdc-checker .bmd-rc-progress{height:7px;background:#e6efec;margin:21px 0;border-radius:10px;overflow:hidden}
#bmd-rdc-checker .bmd-rc-progress i{display:block;width:0;height:100%;background:#358b7d;transition:width .2s ease}
#bmd-rdc-checker .bmd-rc-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:11px}
#bmd-rdc-checker .bmd-rc-item{display:flex;align-items:flex-start;gap:11px;background:#f8faf9;border:1px solid #e1ece9;border-radius:12px;padding:13px;cursor:pointer;margin:0;min-width:0}
#bmd-rdc-checker .bmd-rc-item:has(input:checked){background:#ecf7f2;border-color:#96cbb9}
#bmd-rdc-checker .bmd-rc-item input{margin:3px 0 0;accent-color:#278576;width:18px;height:18px;flex:none}
#bmd-rdc-checker .bmd-rc-content{display:flex;flex-direction:column;gap:5px}
#bmd-rdc-checker .bmd-rc-content strong{font-size:13px;line-height:1.35;color:#213f46}
#bmd-rdc-checker .bmd-rc-content span{font-size:11px;line-height:1.55;color:#63787a}
#bmd-rdc-checker .bmd-rc-note{font-size:12px;line-height:1.65;color:#566c70;background:#f4f8f7;border-left:3px solid #63a99c;padding:12px 14px;margin:21px 0 16px}
#bmd-rdc-checker .bmd-rc-actions{display:flex;align-items:center;gap:10px;flex-wrap:wrap}
#bmd-rdc-checker .bmd-rc-actions button{padding:11px 15px!important;min-height:42px;border:1px solid #266d6b!important;background:#266d6b!important;color:#fff!important;border-radius:9px!important;font-size:12px!important;font-weight:700;cursor:pointer}
#bmd-rdc-checker .bmd-rc-actions button+button{color:#266d6b!important;background:#fff!important}
#bmd-rdc-checker .bmd-rc-actions #bmd-rc-status{font-size:11px;color:#60797b}
@media(max-width:680px){#bmd-rdc-checker .bmd-rc-grid{grid-template-columns:1fr}#bmd-rdc-checker .bmd-rc-counter{padding:10px;min-width:70px}}
@media print{body *{visibility:hidden!important}#bmd-rdc-checker,#bmd-rdc-checker *{visibility:visible!important}#bmd-rdc-checker{position:absolute;left:0;top:0;width:100%;box-shadow:none;border:0}#bmd-rdc-checker .bmd-rc-grid{grid-template-columns:1fr 1fr}#bmd-rdc-checker .bmd-rc-actions{display:none}}
</style>
BMD_RDC_CHECK_CSS;
},115);
add_action('wp_footer',static function(){
if(!bmd_rdc_checklist_target())return;
echo <<<'BMD_RDC_CHECK_JS'
<script id="bmd-rc-js">
(function(){
const root=document.getElementById('bmd-rdc-checker'); if(!root)return;
const inputs=Array.from(root.querySelectorAll('input[type="checkbox"]'));
const count=root.querySelector('#bmd-rc-count'),bar=root.querySelector('#bmd-rc-bar'),prog=root.querySelector('[role=progressbar]'),status=root.querySelector('#bmd-rc-status');
const key='bmd-rdc-9137-checklist-20261109';
try {let a=JSON.parse(localStorage.getItem(key)||'[]');if(Array.isArray(a))inputs.forEach((el,i)=>el.checked=a.includes(i));}catch(e){}
function update(save){const checked=inputs.map((x,i)=>x.checked?i:null).filter(x=>x!==null);count.textContent=checked.length+'/'+inputs.length;bar.style.width=(100*checked.length/inputs.length)+'%';prog.setAttribute('aria-valuenow',String(checked.length));if(save){try{localStorage.setItem(key,JSON.stringify(checked));status.textContent='Marcações salvas neste navegador';}catch(e){status.textContent='Marcações ativas nesta página';}}}
inputs.forEach(x=>x.addEventListener('change',()=>update(true)));
root.querySelector('#bmd-rc-clear').addEventListener('click',()=>{inputs.forEach(x=>x.checked=false);update(true);});
root.querySelector('#bmd-rc-print').addEventListener('click',()=>window.print());
update(false);
})();
</script>
BMD_RDC_CHECK_JS;
},115);
