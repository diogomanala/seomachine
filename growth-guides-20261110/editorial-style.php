<?php
/**
 * BMD editorial visuals for two articles (2026-11-10), scoped by slug.
 * CSS and progressive enhancement only. Original HTML stored unchanged.
 */
if(!defined('ABSPATH')){exit;}
function bmd27_editorial_active() {
 if(is_admin()||!is_singular('post'))return false;
 $p=get_queried_object();
 return $p instanceof WP_Post && in_array($p->post_name,array(
   'instagram-comentou-nao-recebeu-direct-automatico',
   'meta-ai-conectar-instagram-meta-ads-google-workspace'
 ),true);
}
add_action('wp_head',static function(){
if(!bmd27_editorial_active())return;
echo <<<'BMD27STYLE'
<style id="bmd27-editorial">
.bmd27{--ed-ink:#202429;--ed-red:#af3042;--ed-fog:#f6f7f8;--ed-line:#e7e9eb;color:var(--ed-ink);line-height:1.73}
.bmd27 *{box-sizing:border-box}
.bmd27 a{overflow-wrap:anywhere}
.bmd27 .bmd27-hero{padding:clamp(23px,4.6vw,40px);background:linear-gradient(140deg,#fafafb 0%,#f1f3f4 100%);border:1px solid #e6e9eb;border-radius:22px;margin:10px 0 21px;position:relative;overflow:hidden}
.bmd27 .bmd27-hero:after{content:"";position:absolute;right:-74px;top:-80px;width:240px;height:240px;border-radius:50%;border:55px solid rgba(161,39,60,.038);pointer-events:none}
.bmd27 .bmd27-kicker{font-size:10px;letter-spacing:.13em;text-transform:uppercase;font-weight:800;color:#a03546;margin:0 0 13px}
.bmd27 .bmd27-hero h2{font-size:clamp(26px,4vw,38px)!important;letter-spacing:-.04em!important;line-height:1.18!important;color:#22272c!important;margin:0 0 15px!important;max-width:750px}
.bmd27 .bmd27-hero>p{font-size:clamp(15px,2vw,18px)!important;color:#49535e;line-height:1.6!important;margin:0 0 19px!important;max-width:790px}
.bmd27 .bmd27-quick{background:#fff;border:1px solid #e4e6e9;border-left:3px solid #ac3245;border-radius:0 14px 14px 0;padding:16px 19px;position:relative}
.bmd27 .bmd27-quick>strong{font-size:13px;color:#8f293d}
.bmd27 .bmd27-quick p{font-size:13px!important;line-height:1.77!important;color:#4a5660;margin:6px 0 0!important}
.bmd27 .bmd27-metrics{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin:24px 0 27px}
.bmd27 .bmd27-metrics>div{padding:20px 18px;background:#fff;border:1px solid #e6e9eb;border-radius:15px;min-width:0}
.bmd27 .bmd27-metrics span{display:flex;align-items:center;justify-content:center;width:39px;height:39px;background:#faecee;border-radius:10px;color:#a83448;font-size:15px;font-weight:800;margin:0 0 14px}
.bmd27 .bmd27-metrics strong{display:block;color:#26323b;font-size:14px;line-height:1.35}
.bmd27 .bmd27-metrics small{display:block;color:#718088;font-size:11px;line-height:1.5;margin:5px 0 0}
.bmd27 .bmd27-index{display:flex;gap:9px 16px;flex-wrap:wrap;padding:16px 19px;border:1px solid #e6e9eb;background:#fcfcfd;border-radius:13px;margin:22px 0 30px}
.bmd27 .bmd27-index a{font-size:12px!important;color:#992c41!important;text-decoration:none!important;border-bottom:1px solid #e9cbd1}
.bmd27 h2{font-size:clamp(23px,3vw,30px)!important;letter-spacing:-.032em!important;line-height:1.26!important;margin:36px 0 15px!important;color:#222a33!important}
.bmd27 h3{font-size:17px!important;line-height:1.35!important;color:#2c3740!important;margin:0 0 8px!important}
.bmd27 p,.bmd27 li{line-height:1.76!important}
.bmd27 .bmd27-diagram{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:11px;margin:16px 0 23px}
.bmd27 .bmd27-diagram>div{padding:20px 17px;border-radius:14px;background:#27313a;color:white;position:relative;min-width:0}
.bmd27 .bmd27-diagram>div:not(:last-child):after{content:"›";font-size:26px;color:#b2d2d4;background:#fff;border-radius:50%;position:absolute;right:-15px;top:40%;height:27px;width:27px;display:grid;place-items:center;z-index:1}
.bmd27 .bmd27-diagram small{display:block;color:#edaeb6;font-weight:800;font-size:10px;letter-spacing:.1em}
.bmd27 .bmd27-diagram strong{display:block;font-size:15px;color:white;margin:10px 0}
.bmd27 .bmd27-diagram span{display:block;font-size:11px;color:#d5dfe2;line-height:1.5}
.bmd27 .bmd27-table{border:1px solid #e4e7ea;border-radius:14px;overflow-x:auto;margin:18px 0 25px;-webkit-overflow-scrolling:touch}
.bmd27 .bmd27-table table{margin:0!important;width:100%!important;min-width:570px;border-collapse:collapse!important;box-shadow:none!important}
.bmd27 .bmd27-table th{background:#252c34!important;color:white!important;font-size:12px!important;text-align:left!important;padding:14px 15px!important;border:0!important}
.bmd27 .bmd27-table td{padding:14px 15px!important;font-size:12.5px!important;vertical-align:top;border:0!important;border-top:1px solid #e9edef!important;color:#485761;line-height:1.62!important}
.bmd27 .bmd27-table tr:nth-child(even) td{background:#f8f9fa!important}
.bmd27 .bmd27-steps{border:1px solid #e4e7ea;border-radius:16px;background:#fff;padding:7px 23px;margin:18px 0 26px}
.bmd27 .bmd27-steps>div{display:flex;align-items:flex-start;gap:16px;padding:20px 0;border-bottom:1px solid #eaedef}
.bmd27 .bmd27-steps>div:last-child{border-bottom:0}
.bmd27 .bmd27-steps i{font-style:normal;display:grid;place-items:center;flex:0 0 39px;height:39px;border-radius:10px;color:#a02f43;background:#fff0f2;border:1px solid #f0d4d9;font-size:12px;font-weight:800}
.bmd27 .bmd27-steps p{font-size:13px!important;color:#52606a;margin:0!important}
.bmd27 .bmd27-note,.bmd27 .bmd27-panel{padding:21px 23px;background:#f6f8f9;border:1px solid #e3e8ea;border-radius:15px;margin:23px 0}
.bmd27 .bmd27-note{border-left:3px solid #b13549}
.bmd27 .bmd27-note strong,.bmd27 .bmd27-panel strong{font-size:15px;color:#26323a}
.bmd27 .bmd27-note p{font-size:13px!important;color:#52616c;margin:8px 0 0!important}
.bmd27 .bmd27-panel li{font-size:13px!important;color:#505d67;margin:7px 0}
.bmd27 .bmd27-split{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:13px;margin:20px 0 27px}
.bmd27 .bmd27-split>div{border:1px solid #e3e7e9;border-radius:14px;padding:22px;background:#fff}
.bmd27 .bmd27-label{font-size:10px;display:block;letter-spacing:.1em;font-weight:800;color:#a33547;margin:0 0 13px}
.bmd27 .bmd27-split strong{display:block;font-size:16px;line-height:1.4}
.bmd27 .bmd27-split p{font-size:12px!important;color:#576670;line-height:1.7!important;margin:8px 0 0!important}
.bmd27 .bmd27-prompts{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:14px;margin:21px 0 28px}
.bmd27 .bmd27-prompts article{background:#fff;border:1px solid #e4e8ea;border-radius:16px;padding:20px;position:relative;min-width:0}
.bmd27 .bmd27-prompts article>small{display:block;color:#a63348;letter-spacing:.09em;font-size:10px;font-weight:800;margin:0 0 12px}
.bmd27 .bmd27-prompts h3{font-size:15px!important;margin:0 0 12px!important}
.bmd27 .bmd27-prompts blockquote{border-left:2px solid #c7d2d7;margin:0!important;padding:4px 0 4px 13px!important;font-size:12px!important;line-height:1.68!important;color:#475660;background:none;box-shadow:none;font-weight:400}
.bmd27 .bmd27-prompts button{border:1px solid #d9e0e3;background:#f7f9fa;color:#3f4f57;border-radius:7px;padding:7px 10px;font-size:11px;font-weight:700;cursor:pointer;margin-top:14px}
.bmd27 .bmd27-faq{border-top:1px solid #e6e9eb;margin:15px 0 25px}
.bmd27 .bmd27-faq h3{font-size:15px!important;margin:0!important;padding-top:18px}
.bmd27 .bmd27-faq p{font-size:13px!important;color:#53616b;border-bottom:1px solid #e7e9ec;padding-bottom:18px;margin:7px 0 0!important}
.bmd27-related{padding:17px 20px;border-left:3px solid #b23c4d;border-radius:0 12px 12px 0;background:#f7f9fa;margin:25px 0;font-size:13px}
.bmd27-related strong{display:block;color:#28343b;margin-bottom:6px}
@media(max-width:700px){.bmd27 .bmd27-metrics{grid-template-columns:1fr;gap:8px}.bmd27 .bmd27-metrics>div{display:grid;grid-template-columns:40px 1fr;gap:4px 12px;padding:13px}.bmd27 .bmd27-metrics span{grid-row:span 2;margin:0}.bmd27 .bmd27-diagram{grid-template-columns:1fr;gap:7px}.bmd27 .bmd27-diagram>div:not(:last-child):after{display:none}.bmd27 .bmd27-prompts,.bmd27 .bmd27-split{grid-template-columns:1fr}.bmd27 .bmd27-steps{padding:7px 13px}}
</style>
BMD27STYLE;
},114);
add_action('wp_footer', static function(){
if(!bmd27_editorial_active())return;
echo <<<'BMD27COPY'
<script id="bmd27-prompts-copy">
(function(){document.querySelectorAll('.bmd27-prompts article blockquote').forEach(function(q){
const b=document.createElement('button');b.type='button';b.textContent='Copiar comando';b.setAttribute('aria-label','Copiar este comando da Meta AI');
q.parentNode.appendChild(b);b.addEventListener('click',function(){const s=q.textContent.trim();if(!navigator.clipboard||!window.isSecureContext){const a=document.createElement('textarea');a.value=s;document.body.appendChild(a);a.select();try{document.execCommand('copy');b.textContent='Copiado';}catch(e){b.textContent='Selecione o texto';}a.remove();}else{navigator.clipboard.writeText(s).then(function(){b.textContent='Copiado';}).catch(function(){b.textContent='Selecione o texto';});}});
});})();
</script>
BMD27COPY;
},114);
add_filter('the_content',static function($html){
if(is_admin()||!is_singular('post')||!is_main_query()||!in_the_loop())return $html;
$p=get_queried_object();if(!($p instanceof WP_Post))return $html;
$related=array(
'automatizar-direct-instagram-comentarios'=>array('/instagram-comentou-nao-recebeu-direct-automatico/','Comentaram no Reel, mas o Direct automático não chegou? Veja o diagnóstico.'),
'meta-business-suite-5-dicas-para-maximizar-seus-resultados'=>array('/meta-ai-conectar-instagram-meta-ads-google-workspace/','Como conectar o Meta AI ao Instagram, anúncios e Google Workspace, com comandos práticos.')
);
if(!isset($related[$p->post_name]))return $html;
$x=$related[$p->post_name];$url=home_url($x[0]);if(strpos($html,$url)!==false)return $html;
return $html.'<aside class="bmd27-related"><strong>Leia também</strong><a href="'.esc_url($url).'">'.esc_html($x[1]).'</a></aside>';
},120);
