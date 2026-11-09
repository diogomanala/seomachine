<?php
/**
 * BMD RDC Visual 2026-11-09
 * Render-only improvement for article ID 9137, does not change post_content,
 * canonical, Rank Math metadata, historical links or Article schema.
 * Reversible: delete/rename this file.
 */
if (!defined('ABSPATH')) { exit; }
function bmd_rdc_visual_target() {
 return !is_admin() && is_singular('post') && (int)get_queried_object_id()===9137;
}
add_action('wp_head',function(){
 if(!bmd_rdc_visual_target()) return;
 echo <<<'BMD_RDC_CSS'
<style id="bmd-rdc-visual-20261109">
.bmd-rdc{--ink:#173742;--teal:#1b6568;--line:#deebe8;--cream:#f6f9f8;color:var(--ink)}
.bmd-rdc *{box-sizing:border-box}
.bmd-rdc .rv-hero{display:grid;grid-template-columns:minmax(0,1.12fr) minmax(250px,.88fr);border:1px solid #dcebe6;background:linear-gradient(135deg,#f9fbfa,#eaf4f0);border-radius:23px;overflow:hidden;margin:16px 0 24px;min-height:305px}
.bmd-rdc .rv-hero-text{padding:clamp(20px,3.5vw,35px);display:flex;flex-direction:column;justify-content:center}
.bmd-rdc .rv-eyebrow{font-size:10px;letter-spacing:.11em;font-weight:800;color:#357b75;text-transform:uppercase;display:block;margin:0 0 14px}
.bmd-rdc .rv-hero h2{font-size:clamp(25px,3.1vw,35px)!important;line-height:1.16!important;letter-spacing:-.045em;color:#143942;margin:0 0 12px!important}
.bmd-rdc .rv-hero p{font-size:14px;line-height:1.7;color:#5b7279;margin:0!important}
.bmd-rdc .rv-hero-art{display:flex;align-items:center;justify-content:center;padding:15px;background:radial-gradient(circle at 60% 45%,#d6eee6,#ecf4f1 72%)}
.bmd-rdc .rv-hero-art svg{width:100%;max-width:390px;height:auto;display:block}
.bmd-rdc .rv-facts{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin:0 0 30px}
.bmd-rdc .rv-fact{border:1px solid #e1eae8;border-radius:15px;background:#fff;padding:18px;min-width:0}
.bmd-rdc .rv-fact strong{display:block;font-size:24px;color:#215e64;letter-spacing:-.04em;line-height:1.16;margin:0 0 5px}
.bmd-rdc .rv-fact small{font-size:11.5px;line-height:1.55;display:block;color:#657980}
.bmd-rdc .rv-section-lead{display:flex;align-items:center;gap:12px;margin:21px 0 13px}
.bmd-rdc .rv-section-mark{border-radius:12px;width:43px;height:43px;background:#e5f1eb;display:grid;place-items:center;flex:none}
.bmd-rdc .rv-section-mark svg{width:23px;height:23px;fill:none;stroke:#286968;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.bmd-rdc .rv-section-lead strong{display:block;font-size:15px;color:#1c4650}
.bmd-rdc .rv-section-lead span{font-size:12px;color:#74858b}
.bmd-rdc .rv-card-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin:14px 0 24px}
.bmd-rdc .rv-card{min-width:0;padding:19px 16px 17px;background:#f8faf9;border:1px solid #e2ece9;border-radius:16px}
.bmd-rdc .rv-card svg{width:35px;height:35px;margin:0 0 13px;stroke:#367974;fill:none;stroke-width:1.8;stroke-linecap:round;stroke-linejoin:round}
.bmd-rdc .rv-card strong{display:block;font-size:14px;color:#18444d;margin:0 0 7px;line-height:1.35}
.bmd-rdc .rv-card p{font-size:12px;line-height:1.58;color:#667e80;margin:0!important}
.bmd-rdc .rv-table-wrap{overflow-x:auto;max-width:100%;border:1px solid #dce9e6;border-radius:15px;margin:14px 0 27px;-webkit-overflow-scrolling:touch}
.bmd-rdc .rv-table-wrap table{margin:0!important;width:100%;min-width:590px;border-collapse:collapse!important;border:0!important;box-shadow:none!important}
.bmd-rdc .rv-table-wrap th{background:#193d47!important;color:white!important;border:0!important;text-align:left!important;padding:13px 15px!important;font-size:12px!important}
.bmd-rdc .rv-table-wrap td{padding:12px 15px!important;border:0!important;border-top:1px solid #e5eeeb!important;font-size:12.5px!important;vertical-align:top;line-height:1.65;color:#435b62}
.bmd-rdc .rv-table-wrap tr:nth-child(2n) td{background:#f8faf9}
.bmd-rdc .rv-flow{padding:25px 24px;background:linear-gradient(135deg,#183b47,#1d6164);border-radius:20px;color:#fff;margin:26px 0}
.bmd-rdc .rv-flow .rv-eyebrow{color:#a0dcd3}
.bmd-rdc .rv-flow h3{margin:0 0 18px!important;color:white!important;letter-spacing:-.025em;font-size:21px!important;line-height:1.25!important}
.bmd-rdc .rv-flow-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px}
.bmd-rdc .rv-step{padding:16px;border:1px solid rgba(255,255,255,.2);border-radius:13px;background:rgba(255,255,255,.065)}
.bmd-rdc .rv-step em{font-style:normal;display:grid;place-items:center;background:#afe0d7;color:#19434b;font-size:12px;font-weight:800;width:28px;height:28px;border-radius:9px;margin-bottom:12px}
.bmd-rdc .rv-step strong{color:#fff;display:block;font-size:13px;margin-bottom:7px;line-height:1.35}
.bmd-rdc .rv-step p{color:#ccdfdf;font-size:11.5px;line-height:1.6;margin:0!important}
.bmd-rdc .rv-check{padding:19px;border:1px solid #e0e9e6;border-radius:16px;background:#f5f8f6;margin:12px 0 24px}
.bmd-rdc .rv-check ul{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin:0!important;padding:0!important;list-style:none!important}
.bmd-rdc .rv-check li{position:relative;margin:0!important;padding:11px 11px 11px 35px!important;border:1px solid #e4ece9;border-radius:11px;background:#fff;list-style:none!important;font-size:12.5px;line-height:1.55;color:#465f64}
.bmd-rdc .rv-check li:before{content:"✓";position:absolute;left:11px;top:11px;width:16px;height:16px;background:#44897b;color:#fff;border-radius:50%;font-weight:800;display:grid;place-items:center;font-size:10px}
.bmd-rdc .rv-cta{display:grid;grid-template-columns:1.1fr .9fr;gap:22px;align-items:center;margin:27px 0;padding:clamp(19px,3vw,30px);border:1px solid #dbe8e4;background:#f7faf8;border-radius:20px}
.bmd-rdc .rv-cta h3{font-size:clamp(19px,2.5vw,25px)!important;color:#173c44!important;letter-spacing:-.025em;line-height:1.25!important;margin:0 0 10px!important}
.bmd-rdc .rv-cta p{font-size:12.5px;line-height:1.7;color:#61757c;margin:0 0 13px!important}
.bmd-rdc .rv-link{background:#1e6066;color:white!important;display:inline-flex;align-items:center;justify-content:center;padding:13px 17px;border-radius:10px;min-height:45px;font-size:13px;font-weight:750;text-decoration:none!important}
.bmd-rdc .rv-link:hover{background:#163f49;color:white!important}
.bmd-rdc .rv-cta small{display:block;font-size:10.5px;line-height:1.55;color:#77878b;margin-top:11px}
.bmd-rdc .rv-cta img{display:block;width:100%;max-width:370px;height:auto;object-fit:contain;margin-inline:auto;border-radius:10px}
.bmd-rdc .rv-faq{padding:15px 16px;border:1px solid #e3ebe8;border-radius:13px;margin:10px 0;background:white}
.bmd-rdc .rv-faq h3{font-size:15px!important;color:#234b51;margin:0 0 8px!important;line-height:1.4!important}
.bmd-rdc .rv-faq p{font-size:13px;line-height:1.7;margin:0!important;color:#5b6c72}
@media(max-width:750px){.bmd-rdc .rv-hero{grid-template-columns:1fr}.bmd-rdc .rv-hero-art{padding:6px;max-height:250px}.bmd-rdc .rv-hero-art svg{max-width:330px}.bmd-rdc .rv-facts{gap:7px}.bmd-rdc .rv-fact{padding:13px 10px}.bmd-rdc .rv-fact strong{font-size:19px}.bmd-rdc .rv-fact small{font-size:10.5px}.bmd-rdc .rv-card-grid,.bmd-rdc .rv-flow-grid,.bmd-rdc .rv-cta{grid-template-columns:1fr}.bmd-rdc .rv-card{display:grid;grid-template-columns:40px 1fr;column-gap:12px;padding:13px}.bmd-rdc .rv-card svg{grid-row:span 2;margin:0}.bmd-rdc .rv-card strong{margin:0 0 4px}.bmd-rdc .rv-cta img{max-height:230px}.bmd-rdc .rv-check ul{grid-template-columns:1fr}}
@media(max-width:375px){.bmd-rdc .rv-facts{grid-template-columns:1fr}.bmd-rdc .rv-flow{padding:18px}}
</style>
BMD_RDC_CSS;
},110);

add_filter('the_content',function($content) {
 if (!bmd_rdc_visual_target() || !in_the_loop() || !is_main_query() || is_preview() || is_feed() || strpos($content,'class="bmd-rdc"')!==false) return $content;
 $hero = <<<'RDC_HERO'
<section class="rv-hero" aria-label="Resumo visual do artigo sobre documentação odontológica">
 <div class="rv-hero-text">
  <span class="rv-eyebrow">ODONTOLOGIA · GESTÃO DOCUMENTAL</span>
  <h2>Da norma à rotina da clínica.</h2>
  <p>Uma leitura prática sobre documentos, procedimentos, responsabilidade técnica e organização das evidências do serviço.</p>
 </div>
 <div class="rv-hero-art">
  <svg viewBox="0 0 380 265" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Ilustração editorial de documentos, pasta e símbolo odontológico">
   <defs><linearGradient id="rv-card" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#fff"/><stop offset="1" stop-color="#f2f9f6"/></linearGradient><linearGradient id="rv-folder" x1="0" y1="0" x2="1" y2="1"><stop stop-color="#367d80"/><stop offset="1" stop-color="#19505b"/></linearGradient></defs>
   <circle cx="204" cy="128" r="110" fill="#dfefe9"/><circle cx="309" cy="62" r="20" fill="#c9e5d9"/><circle cx="85" cy="72" r="12" fill="#d0e9df"/>
   <g transform="rotate(-8 202 126)"><rect x="130" y="34" width="154" height="174" rx="12" fill="#cbded7"/><rect x="119" y="27" width="154" height="174" rx="12" fill="url(#rv-card)" stroke="#bad5cc" stroke-width="2"/><rect x="137" y="50" width="46" height="46" rx="12" fill="#dff0ea"/><path d="M156 58c-10-6-20 2-16 16 2 8 8 16 11 17 3 0 3-11 8-11s5 11 8 11c5-3 13-24 5-31-4-3-11-4-16-2" fill="none" stroke="#41847e" stroke-width="2.3" stroke-linecap="round"/><path d="M196 56h53M196 70h46M138 112h112M138 127h101M138 142h112M138 158h86" stroke="#acc8c0" stroke-width="6" stroke-linecap="round"/></g>
   <path d="M57 150a12 12 0 0 1 12-12h43l12 12h97a12 12 0 0 1 12 12v53a13 13 0 0 1-13 13H70a13 13 0 0 1-13-13z" fill="url(#rv-folder)"/><path d="m82 183 12 11 23-25" fill="none" stroke="#bcece0" stroke-width="5" stroke-linecap="round" stroke-linejoin="round"/>
   <circle cx="296" cy="188" r="34" fill="#cde9df" stroke="#e9f4ef" stroke-width="8"/><path d="m278 188 11 11 22-25" fill="none" stroke="#35867a" stroke-width="6" stroke-linecap="round" stroke-linejoin="round"/>
  </svg>
 </div>
</section>
<div class="rv-facts" aria-label="Visão geral do artigo">
 <div class="rv-fact"><strong>16 frentes</strong><small>Itens organizados a partir do artigo 113, além de outras exigências aplicáveis.</small></div>
 <div class="rv-fact"><strong>RT</strong><small>Responsável técnico participa da adaptação, validação e implementação.</small></div>
 <div class="rv-fact"><strong>POPs</strong><small>Procedimentos precisam descrever o funcionamento real da clínica.</small></div>
</div>
RDC_HERO;
 $cards = <<<'RDC_CARDS'
<div class="rv-section-lead"><span class="rv-section-mark"><svg viewBox="0 0 24 24"><path d="M5 2h9l5 5v15H5zM14 2v6h5M8 12h8M8 16h8"/></svg></span><div><strong>Como navegar pela documentação</strong><span>Três grupos para orientar a leitura da tabela</span></div></div>
<div class="rv-card-grid">
 <div class="rv-card"><svg viewBox="0 0 36 36"><path d="M8 4h15l6 6v23H8zM23 4v7h6M13 18h12M13 24h12"/></svg><strong>Estrutura e gestão</strong><p>Projeto, equipe, capacitação e documentação administrativa.</p></div>
 <div class="rv-card"><svg viewBox="0 0 36 36"><path d="M18 4 30 9v10c0 7-5 11-12 13C11 30 6 26 6 19V9zM12 18l4 4 8-9"/></svg><strong>Segurança e processos</strong><p>Rotinas assistenciais, processamento, prevenção e resíduos.</p></div>
 <div class="rv-card"><svg viewBox="0 0 36 36"><circle cx="18" cy="18" r="13"/><path d="M18 10v9l7 4"/></svg><strong>Controle e evidências</strong><p>Manutenção, monitoramento, revisões e registros de execução.</p></div>
</div>
RDC_CARDS;
 $flow = <<<'RDC_FLOW'
<section class="rv-flow" aria-label="Três etapas da organização documental">
<span class="rv-eyebrow">PASSO A PASSO</span>
<h3>Como transformar os documentos em uma rotina</h3>
<div class="rv-flow-grid">
 <div class="rv-step"><em>01</em><strong>Mapear a clínica</strong><p>Liste atividades, responsáveis, equipamentos e os documentos existentes.</p></div>
 <div class="rv-step"><em>02</em><strong>Adaptar os modelos</strong><p>Registre os processos reais e confira as normas aplicáveis à unidade.</p></div>
 <div class="rv-step"><em>03</em><strong>Revisar e acompanhar</strong><p>Encaminhe ao RT, implemente e guarde evidências de revisões e treinamentos.</p></div>
</div></section>
RDC_FLOW;
 $cta = <<<'RDC_CTA'
<section class="rv-cta" aria-label="Ferramenta complementar de organização documental">
 <div><span class="rv-eyebrow">RECURSO COMPLEMENTAR</span><h3>Prefere organizar seus modelos em um painel?</h3>
 <p>O Odonto em Dia reúne 30 materiais de referência, editor online, versões salvas e acompanhamento de pendências para clínicas.</p>
 <a class="rv-link" href="https://odontonegocios.com/rdc/">Conhecer o Odonto em Dia ↗</a>
 <small>Produto próprio. Exige adaptação e revisão do responsável técnico. Não é certificado ou chancelado pela Anvisa.</small></div>
 <img src="https://odontonegocios.com/rdc/assets/dentista-book-kit.webp" loading="lazy" decoding="async" alt="Apresentação ilustrativa do kit de documentos e modelos para clínicas odontológicas">
</section>
RDC_CTA;
 $heading='<h2>Quais documentos são previstos para clínicas odontológicas?</h2>';
 if(strpos($content,$heading)!==false) $content=str_replace($heading,$heading.$cards,$content);
 if(strpos($content,'<table')!==false) $content=preg_replace('~(<table\b[^>]*>.*?</table>)~is','<div class="rv-table-wrap" role="region" aria-label="Tabela de documentação odontológica" tabindex="0">$1</div>',$content,1);
 $content=str_replace('<h2>Checklist para começar a organizar a pasta documental</h2>',$flow.'<h2>Checklist para começar a organizar a pasta documental</h2>',$content);
 $content=preg_replace('~(<h2>Checklist para começar a organizar a pasta documental</h2>)(.*?)(?=<h2>Modelos prontos ajudam\?)~is','$1<div class="rv-check">$2</div>',$content,1);
 $content=str_replace('<h2>Erros que vale evitar</h2>',$cta.'<h2>Erros que vale evitar</h2>',$content);
 $a=strpos($content,'<h2>Perguntas frequentes sobre a RDC 1.002/2025</h2>');
 $b=strpos($content,'<h2>Fontes oficiais e leitura complementar</h2>');
 if($a!==false && $b!==false && $b>$a) {
   $faq=substr($content,$a,$b-$a);
   $faq=preg_replace('~(<h3>[^<]+</h3>\s*<p>.*?</p>)~is','<div class="rv-faq">$1</div>',$faq);
   $content=substr($content,0,$a).$faq.substr($content,$b);
 }
 return '<div class="bmd-rdc">'.$hero.$content.'</div>';
},103);
