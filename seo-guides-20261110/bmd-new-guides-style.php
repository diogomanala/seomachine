<?php
/**
 * BMD 2026 editorial components for two guides, scoped to those pages.
 * No dependency, no post database modifications.
 */
if (!defined('ABSPATH')) { exit; }
function bmd_20261110_current_article() {
  if (is_admin() || !is_singular('post')) return false;
  $p=get_queried_object();
  return $p instanceof WP_Post && in_array($p->post_name,array(
    'avaliacoes-google-sumiram-nao-aparecem',
    'nome-de-usuario-whatsapp-nao-aparece-como-reservar'
  ),true);
}
add_action('wp_head', static function(){
if(!bmd_20261110_current_article())return;
echo <<<'BMD26_CSS'
<style id="bmd26-editorial">
.bmd26-article{--bmd26-ink:#1c242c;--bmd26-accent:#b52f42;--bmd26-line:#e7e8e9;--bmd26-soft:#f7f8f9;color:var(--bmd26-ink);line-height:1.74}
.bmd26-article *{box-sizing:border-box}
.bmd26-article .bmd26-intro{padding:clamp(21px,3.5vw,35px);background:linear-gradient(125deg,#f9fafb 0%,#f2f4f6 100%);border:1px solid #e6e8ea;border-radius:20px;margin:8px 0 22px}
.bmd26-article .bmd26-eyebrow{display:block;font-size:10px!important;letter-spacing:.105em;text-transform:uppercase;font-weight:800;color:#9c3041;margin-bottom:13px}
.bmd26-article .bmd26-deck{font-size:clamp(16px,2.3vw,20px)!important;line-height:1.55!important;color:#303c45;margin:0 0 18px!important;font-weight:520}
.bmd26-article .bmd26-summary{border-left:3px solid #bb3b4d;padding:12px 15px;background:white;border-radius:0 12px 12px 0}
.bmd26-article .bmd26-summary strong{font-size:14px}
.bmd26-article .bmd26-summary p{font-size:13px!important;margin:6px 0 0!important;color:#46525d;line-height:1.72!important}
.bmd26-article .bmd26-statrow{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:12px;margin:24px 0 29px}
.bmd26-article .bmd26-statrow>div{background:#fff;border:1px solid var(--bmd26-line);padding:18px;border-radius:14px;min-width:0}
.bmd26-article .bmd26-statrow span{display:grid;place-items:center;background:#fbecee;color:#a12640;border-radius:9px;font-size:16px;font-weight:800;width:36px;height:36px;margin-bottom:11px}
.bmd26-article .bmd26-statrow strong{display:block;font-size:14px;line-height:1.35}
.bmd26-article .bmd26-statrow small{display:block;font-size:11px;color:#6a737d;line-height:1.5;margin-top:7px}
.bmd26-article .bmd26-toc{padding:17px 21px;border:1px solid #e5e8eb;border-radius:13px;background:#fcfcfd;margin:22px 0 30px;display:flex;align-items:center;gap:7px 13px;flex-wrap:wrap}
.bmd26-article .bmd26-toc strong{margin-right:8px;font-size:12px;color:#343e46}
.bmd26-article .bmd26-toc a{font-size:12px;text-decoration:none!important;color:#a62f43!important;border-bottom:1px solid #e2bec3;line-height:1.65}
.bmd26-article h2{font-size:clamp(23px,3vw,30px)!important;color:#202c34!important;line-height:1.24!important;letter-spacing:-.035em!important;margin:38px 0 15px!important}
.bmd26-article h3{font-size:17px!important;color:#293841!important;line-height:1.35!important;margin:0 0 8px!important}
.bmd26-article p,.bmd26-article li{line-height:1.76!important}
.bmd26-article a{overflow-wrap:anywhere}
.bmd26-article .bmd26-table{margin:18px 0 24px;border-radius:14px;overflow-x:auto;border:1px solid #e3e5e7;-webkit-overflow-scrolling:touch}
.bmd26-article .bmd26-table table{width:100%!important;min-width:575px;margin:0!important;border-collapse:collapse;box-shadow:none!important;white-space:normal}
.bmd26-article .bmd26-table th{background:#252b32!important;color:#fff!important;padding:13px 14px!important;text-align:left!important;font-size:12px!important;font-weight:700}
.bmd26-article .bmd26-table td{padding:14px!important;font-size:12.5px!important;vertical-align:top;border:0!important;border-top:1px solid #e8ebed!important;color:#4e5961}
.bmd26-article .bmd26-table tr:nth-child(even) td{background:#f8f9fa!important}
.bmd26-article .bmd26-steps{border:1px solid #e7e9ea;border-radius:16px;padding:8px 22px;background:#fff;margin:18px 0 26px}
.bmd26-article .bmd26-steps>div{display:flex;align-items:flex-start;gap:15px;padding:20px 0;border-bottom:1px solid #eceeee}
.bmd26-article .bmd26-steps>div:last-child{border-bottom:0}
.bmd26-article .bmd26-steps em{display:grid;place-items:center;font-style:normal;flex:0 0 37px;height:37px;border:1px solid #edcdd1;background:#fff5f6;color:#a82e40;border-radius:10px;font-size:12px;font-weight:800}
.bmd26-article .bmd26-steps p{font-size:13px!important;color:#54616a;margin:0!important}
.bmd26-article .bmd26-copy,.bmd26-article .bmd26-callout{background:#f7f8f9;border:1px solid #e4e7e8;border-radius:15px;padding:21px;margin:20px 0 26px}
.bmd26-article .bmd26-label{display:block;font-size:10px;letter-spacing:.1em;font-weight:800;color:#a23045;margin-bottom:10px}
.bmd26-article .bmd26-copy p,.bmd26-article .bmd26-callout p{font-size:13px!important;line-height:1.8!important;margin:0!important;color:#4b5863}
.bmd26-article .bmd26-callout strong{display:block;font-size:15px;margin-bottom:8px}
.bmd26-article .bmd26-pair{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:13px;margin:20px 0 25px}
.bmd26-article .bmd26-pair>div{padding:21px;background:#fff;border:1px solid #e6e8e9;border-radius:14px}
.bmd26-article .bmd26-pair p{font-size:12.5px!important;color:#58636b;margin:0!important}
.bmd26-article .bmd26-faq{border-top:1px solid #e5e8e9;margin:15px 0 25px}
.bmd26-article .bmd26-faq h3{font-size:15px!important;margin:0!important;padding-top:18px!important}
.bmd26-article .bmd26-faq p{padding-bottom:18px!important;border-bottom:1px solid #e5e8e9;margin:7px 0 0!important;font-size:13px!important;color:#53616a}
.bmd26-related{background:#f8f9fa;border-left:3px solid #a63849;border-radius:0 12px 12px 0;padding:17px 20px;margin:24px 0;font-size:13px}
.bmd26-related strong{display:block;margin-bottom:6px}
@media(max-width:690px){.bmd26-article .bmd26-statrow{grid-template-columns:1fr;gap:9px}.bmd26-article .bmd26-statrow>div{display:grid;grid-template-columns:36px 1fr;column-gap:13px;padding:14px}.bmd26-article .bmd26-statrow span{grid-row:span 2;margin:0}.bmd26-article .bmd26-pair{grid-template-columns:1fr}.bmd26-article .bmd26-toc{align-items:flex-start}.bmd26-article .bmd26-steps{padding:6px 13px}}
</style>
BMD26_CSS;
},110);
add_filter('the_content',static function($html){
 if(is_admin()||!is_singular('post')||!is_main_query()||!in_the_loop())return $html;
 $p=get_queried_object();if(!($p instanceof WP_Post))return $html;
 $guides=array(
 'scripts-de-whatsapp-para-pedir-avaliacao-no-google'=>array('/avaliacoes-google-sumiram-nao-aparecem/','Avaliações do Google sumiram ou não aparecem? Veja como identificar o motivo.'),
 'gerador-de-link-para-whatsapp-os-6-mais-confiaveis'=>array('/nome-de-usuario-whatsapp-nao-aparece-como-reservar/','Novo @ do WhatsApp: o que fazer quando o nome de usuário ainda não aparece.')
 );
 if(!isset($guides[$p->post_name]))return $html;
 $item=$guides[$p->post_name];$href=home_url($item[0]);
 if(strpos($html,$href)!==false)return $html;
 return $html.'<aside class="bmd26-related"><strong>Leia também</strong><a href="'.esc_url($href).'">'.esc_html($item[1]).'</a></aside>';
},120);
