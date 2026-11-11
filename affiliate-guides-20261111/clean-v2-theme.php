<?php
/**
 * BMD editorial comparison v2. Only post 9166. Does not edit content.
 * Content stored in WP DB; CSS and JS are isolated from the editor.
 */
if(!defined('ABSPATH')){exit;}
function bmdshop_is_target(){
 return !is_admin() && is_singular('post') && (int)get_queried_object_id()===9166;
}
add_action('wp_head',static function(){
if(!bmdshop_is_target())return;
echo <<<'CSS'
<style id="bmdshop-v2-style">
/* Full-width editorial article layout, only post 9166. Do not touch header/footer. */
body.postid-9166 #primary,body.postid-9166 .content-area{width:100%!important;float:none!important;max-width:1140px!important;margin:0 auto!important}
body.postid-9166 #right-sidebar,body.postid-9166 #secondary,body.postid-9166 aside#right-sidebar{display:none!important}
body.postid-9166 #content-wrap{max-width:1200px!important}
body.postid-9166 article#post-9166 .single-post-title{font-size:clamp(27px,3.5vw,39px)!important;line-height:1.2!important;letter-spacing:-.035em!important;max-width:1020px!important}
.bmdshop{--bs-ink:#252b34;--bs-muted:#67717d;--bs-red:#b72f4a;--bs-line:#e4e6eb;--bs-bg:#f8f9fa;color:var(--bs-ink);max-width:1140px;margin:0 auto 60px;font-family:inherit;line-height:1.68}
.bmdshop *{box-sizing:border-box}
.bmdshop h2,.bmdshop h3,.bmdshop p{max-width:none}
.bmdshop h2{margin:0!important;font-size:clamp(24px,3.4vw,33px)!important;line-height:1.22!important;letter-spacing:-.035em!important;color:#212933!important;font-weight:800!important}
.bmdshop h3{font-size:clamp(18px,2.2vw,21px)!important;color:#202832!important;line-height:1.3!important;font-weight:800!important;margin:0 0 6px!important}
.bmdshop a{overflow-wrap:anywhere}
.bmdshop .bs-disclosure{font-size:12px!important;color:#596470!important;border:1px solid #e9e1e3;background:#fffafb;padding:13px 17px;border-radius:12px;margin:20px 0!important;line-height:1.65!important}
.bmdshop .bs-disclosure strong{color:#37424b}
.bmdshop .bs-hero{display:grid;grid-template-columns:minmax(0,1.25fr) minmax(280px,.75fr);gap:clamp(18px,3vw,35px);align-items:stretch;border-radius:24px;overflow:hidden;background:#222833;color:white;min-height:342px;position:relative;margin:24px 0 17px}
.bmdshop .bs-hero-copy{padding:clamp(25px,4vw,48px);position:relative;z-index:2;display:flex;flex-direction:column;justify-content:center}
.bmdshop .bs-eyebrow{display:block;font-size:11px;font-weight:800;letter-spacing:.14em;color:#f0a2b5;margin:0 0 18px}
.bmdshop .bs-hero h2{font-size:clamp(29px,4.4vw,47px)!important;color:#fff!important;max-width:690px;margin:0 0 16px!important}
.bmdshop .bs-hero p{font-size:15px!important;line-height:1.62!important;color:#d9e0e5!important;margin:0 0 22px!important}
.bmdshop .bs-hero-pills{display:flex;gap:7px;flex-wrap:wrap}
.bmdshop .bs-hero-pills span{font-size:10px;color:#f2e7e9;background:#ffffff1c;border:1px solid #ffffff30;border-radius:999px;padding:7px 10px}
.bmdshop .bs-hero-art{min-height:285px;background:radial-gradient(circle at 47% 36%,#b74458 0%,#713647 29%,#292e38 73%);position:relative;isolation:isolate}
.bmdshop .bs-signal{position:absolute;top:50%;left:42%;width:235px;height:235px;border-radius:50%;border:1px solid #ffffff36;transform:translate(-50%,-50%)}
.bmdshop .bs-signal-b{width:310px;height:310px;border-color:#ffffff1a}
.bmdshop .bs-mic{position:absolute;width:88px;height:155px;left:26%;top:23%;filter:drop-shadow(9px 16px 14px #0005);transform:rotate(-10deg)}
.bmdshop .bs-mic-head{width:86px;height:86px;background:linear-gradient(145deg,#3a4047,#131a20);border:7px solid #252c33;border-radius:23px;box-shadow:inset 0 0 0 1px #747d86;display:flex;align-items:center;justify-content:center;gap:5px}
.bmdshop .bs-mic-head span{width:4px;height:28px;border-radius:5px;background:#5c666e}
.bmdshop .bs-mic-head span:nth-child(even){height:38px}
.bmdshop .bs-mic-neck{height:40px;width:14px;background:#161e25;margin:0 auto;box-shadow:inset -3px 0 0 #37434c}
.bmdshop .bs-mic-foot{height:18px;width:64px;background:linear-gradient(#252e35,#161c21);margin:auto;border-radius:8px}
.bmdshop .bs-rx{position:absolute;right:12%;top:41%;width:83px;height:102px;border:7px solid #232a32;border-radius:16px;background:linear-gradient(155deg,#525b67,#151e27);box-shadow:10px 12px 20px #090e1799;transform:rotate(11deg)}
.bmdshop .bs-rx-screen{width:49px;height:38px;margin:14px auto 8px;border:3px solid #1a292d;border-radius:7px;background:linear-gradient(145deg,#65aea3,#225d62)}
.bmdshop .bs-rx-screen:after{content:"";display:block;height:12px;width:26px;margin:9px auto;border-left:3px solid #d1eee4;border-right:3px solid #d1eee4}
.bmdshop .bs-rx-port{width:19px;height:7px;background:#11191d;border-radius:2px;margin:auto}
.bmdshop .bs-art-caption{position:absolute;bottom:21px;right:22px;font-size:10px;color:#ebd9df;letter-spacing:.12em}
.bmdshop .bs-nav{display:flex;gap:8px;flex-wrap:nowrap;overflow-x:auto;padding:10px 0 14px;margin:0 0 22px;scrollbar-width:thin}
.bmdshop .bs-nav a{white-space:nowrap;flex:none;padding:9px 13px;border-radius:999px;background:white;border:1px solid var(--bs-line);font-size:12px!important;font-weight:700;color:#48525b!important;text-decoration:none!important}
.bmdshop .bs-nav a:hover,.bmdshop .bs-nav a:focus-visible{background:#fff1f4;border-color:#e4b1bd;color:#9a2a40!important}
.bmdshop .bs-section{margin:34px 0 48px}
.bmdshop .bs-section-heading{margin-bottom:22px;max-width:850px}
.bmdshop .bs-section-kicker{font-size:11px;letter-spacing:.13em;font-weight:800;color:#a53349;display:block;margin:0 0 11px}
.bmdshop .bs-section-heading>p{font-size:14px!important;color:#65717b!important;margin:9px 0 0!important;line-height:1.64!important}
.bmdshop .bs-quiz{display:grid;grid-template-columns:minmax(0,1fr) minmax(0,1fr);gap:17px;padding:18px;border:1px solid var(--bs-line);border-radius:21px;background:#fff}
.bmdshop .bs-options{display:flex;flex-direction:column;gap:8px}
.bmdshop .bs-options label{display:flex;align-items:flex-start;gap:13px;padding:17px 16px;border:1px solid var(--bs-line);background:#fafbfc;border-radius:12px;cursor:pointer;margin:0!important;min-width:0}
.bmdshop .bs-options label:has(input:checked){border-color:#c3596c;background:#fff4f6}
.bmdshop .bs-options input{accent-color:#b72f4a;width:17px;height:17px;flex:none;margin:3px 0 0}
.bmdshop .bs-options span{display:flex;flex-direction:column;gap:5px}
.bmdshop .bs-options strong{font-size:14px;color:#27323c}
.bmdshop .bs-options small{font-size:11px;color:#697783}
.bmdshop .bs-recommend{background:#242b35;color:white;border-radius:15px;padding:clamp(19px,3vw,28px);display:flex;flex-direction:column;justify-content:center;align-items:flex-start;min-height:230px}
.bmdshop .bs-recommend-top{font-size:10px;color:#ffc5d0;font-weight:800;letter-spacing:.12em;display:flex;align-items:center;gap:9px;margin:0 0 18px}
.bmdshop .bs-dot{width:8px;height:8px;background:#8bd6b2;border-radius:50%}
.bmdshop #bs-model{font-size:clamp(21px,2.6vw,27px);line-height:1.25;color:#fff;margin:0 0 12px}
.bmdshop #bs-reason{font-size:13px!important;color:#dce2e5!important;line-height:1.6!important;margin:0 0 19px!important}
.bmdshop .bs-recommend small{font-size:10px;color:#c5cbd1;margin-top:13px}
.bmdshop .bs-buybtn{display:inline-flex!important;align-items:center;justify-content:center;gap:11px;background:#b72f4a!important;color:#fff!important;padding:13px 18px!important;border-radius:11px!important;font-size:13px!important;line-height:1.3!important;font-weight:800!important;text-decoration:none!important;text-align:center;box-shadow:none!important;border:1px solid #b72f4a!important;margin:10px 0!important}
.bmdshop .bs-buybtn span{font-size:15px}
.bmdshop .bs-buybtn:hover{background:#9d2a40!important}
.bmdshop .bs-buybtn-outline{background:#fff!important;color:#982c42!important;border-color:#d5a6b0!important}
.bmdshop .bs-buybtn-outline:hover{background:#fff0f2!important}
.bmdshop .bs-product-grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:14px;align-items:stretch}
.bmdshop .bs-product{border:1px solid #e5e8eb;border-radius:20px;overflow:hidden;background:#fff;display:flex;flex-direction:column;min-width:0}
.bmdshop .bs-product.bs-featured{border-color:#d29ca8;box-shadow:0 8px 28px #8c32440f}
.bmdshop .bs-product-head{min-height:184px;background:linear-gradient(140deg,#f5f6f7,#edeff0);position:relative;display:flex;justify-content:center;align-items:center;overflow:hidden}
.bmdshop .bs-featured .bs-product-head{background:linear-gradient(140deg,#f9eef0,#f1f2f3)}
.bmdshop .bs-pill{position:absolute;left:16px;top:16px;border-radius:999px;background:#fff;padding:7px 10px;font-size:10px;font-weight:800;color:#a63348;border:1px solid #eacbd1;z-index:1}
.bmdshop .bs-device{display:flex;align-items:center;gap:10px;filter:drop-shadow(8px 11px 8px #0002);transform:rotate(-5deg)}
.bmdshop .bs-device i{display:block;width:51px;height:69px;border-radius:13px;background:linear-gradient(140deg,#434950,#10161c);border:5px solid #262d34;position:relative;box-shadow:inset 0 0 0 1px #626b74}
.bmdshop .bs-device i:after{content:"";position:absolute;left:12px;top:14px;width:17px;height:25px;border-radius:6px;background:repeating-linear-gradient(90deg,#505e68 0 2px,#222b31 2px 5px)}
.bmdshop .bs-device b{display:block;width:58px;height:83px;border-radius:15px;background:linear-gradient(140deg,#626b73,#202831);border:5px solid #272e37;position:relative}
.bmdshop .bs-device b:after{content:"";display:block;width:32px;height:25px;border-radius:4px;background:linear-gradient(#80b9b0,#356e70);margin:17px auto}
.bmdshop .bs-device-usb b{width:43px;height:55px}
.bmdshop .bs-device-usb b:after{height:9px;width:17px;margin:14px auto}
.bmdshop .bs-device-c1 i{border-radius:19px 19px 9px 9px}
.bmdshop .bs-device-c1 b{height:63px}
.bmdshop .bs-product-body{padding:22px 20px;display:flex;flex-direction:column;flex:1}
.bmdshop .bs-product-body h3{font-size:20px!important;margin:0 0 8px!important}
.bmdshop .bs-for{font-size:12px!important;color:#a23048!important;font-weight:650;margin:0 0 17px!important}
.bmdshop .bs-product-body ul{padding-left:19px!important;margin:0 0 12px!important}
.bmdshop .bs-product-body li{font-size:12px!important;line-height:1.64!important;color:#4d5a64;margin:0 0 6px}
.bmdshop .bs-limit{font-size:11px!important;background:#f8f9fa;color:#56626a!important;border-left:2px solid #aeb6bd;padding:11px;margin:5px 0 14px!important;line-height:1.6!important}
.bmdshop .bs-product-body .bs-buybtn{margin-top:auto!important}
.bmdshop .bs-product-body>small{font-size:10px;color:#8d969e;margin-top:4px}
.bmdshop .bs-caption{font-size:11px!important;color:#7a848d!important;line-height:1.6!important;margin:13px 0!important}
.bmdshop .bs-table{overflow-x:auto;border:1px solid #e2e6e9;border-radius:15px;scrollbar-width:thin}
.bmdshop .bs-table table{border-collapse:collapse!important;margin:0!important;min-width:655px;width:100%!important}
.bmdshop .bs-table th,.bmdshop .bs-table td{font-size:12px!important;line-height:1.5!important;padding:14px 15px!important;vertical-align:top!important}
.bmdshop .bs-table thead th{background:#252e38!important;color:white!important;text-align:left!important;border:0!important}
.bmdshop .bs-table tbody th{font-weight:800;color:#29333d;min-width:165px}
.bmdshop .bs-table tbody td,.bmdshop .bs-table tbody th{border:0!important;border-top:1px solid #e6e9eb!important}
.bmdshop .bs-table tbody tr:nth-child(even) td,.bmdshop .bs-table tbody tr:nth-child(even) th{background:#f7f9fa}
.bmdshop .bs-checks{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px}
.bmdshop .bs-checks>div{display:flex;gap:15px;padding:22px 20px;border-radius:15px;border:1px solid #e3e7e9;background:white}
.bmdshop .bs-checks>div>span{display:grid;place-items:center;width:37px;height:37px;flex:0 0 37px;border:1px solid #f0ced6;color:#a82d45;background:#fff2f5;border-radius:11px;font-size:12px;font-weight:800}
.bmdshop .bs-checks h3{font-size:15px!important;margin:0 0 8px!important}
.bmdshop .bs-checks p{font-size:12px!important;color:#52606b!important;line-height:1.72!important;margin:0!important}
.bmdshop .bs-tip{border:1px solid #e4e7ea;background:#f8f9fa;padding:22px;border-radius:16px;display:flex;gap:20px;align-items:center;margin:0 0 15px}
.bmdshop .bs-audio-bars{min-width:120px;display:flex;align-items:center;justify-content:center;gap:6px;height:110px;border-radius:15px;background:#26323d}
.bmdshop .bs-audio-bars i{display:block;width:5px;height:20px;background:#e78296;border-radius:5px}
.bmdshop .bs-audio-bars i:nth-child(2),.bmdshop .bs-audio-bars i:nth-child(6){height:44px}
.bmdshop .bs-audio-bars i:nth-child(3),.bmdshop .bs-audio-bars i:nth-child(5){height:66px}
.bmdshop .bs-audio-bars i:nth-child(4){height:83px;background:#fff}
.bmdshop .bs-tip ol{margin:0!important;padding-left:20px!important}
.bmdshop .bs-tip li{font-size:12px!important;line-height:1.6!important;margin:0 0 7px!important;color:#4e5c66}
.bmdshop .bs-faq{display:flex;flex-direction:column;gap:9px}
.bmdshop .bs-faq details{border:1px solid #e3e7e9;border-radius:12px;background:white;overflow:hidden}
.bmdshop .bs-faq summary{cursor:pointer;padding:18px 20px;font-weight:800;font-size:14px;color:#253039}
.bmdshop .bs-faq details[open] summary{border-bottom:1px solid #e6e8ea;color:#a12d45}
.bmdshop .bs-faq details p{margin:0!important;padding:17px 20px!important;font-size:13px!important;color:#51606b!important;line-height:1.7!important}
.bmdshop .bs-method{border:1px solid #e4e6e9;border-radius:18px;background:#f7f8fa;padding:clamp(20px,3vw,30px);margin:36px 0}
.bmdshop .bs-method>span{font-size:10px;letter-spacing:.13em;color:#aa344a;font-weight:800}
.bmdshop .bs-method h2{font-size:24px!important;margin:10px 0 14px!important}
.bmdshop .bs-method p{font-size:12px!important;color:#5a6670!important;line-height:1.74!important}
.bmdshop a:focus-visible,.bmdshop input:focus-visible,.bmdshop summary:focus-visible{outline:3px solid #a83049;outline-offset:3px}
@media(max-width:1000px){.bmdshop .bs-product-grid{grid-template-columns:repeat(2,minmax(0,1fr))}.bmdshop .bs-hero{grid-template-columns:minmax(0,1fr) minmax(230px,.7fr)}}
@media(max-width:690px){.bmdshop .bs-hero{grid-template-columns:1fr}.bmdshop .bs-hero-art{min-height:210px}.bmdshop .bs-hero-copy{padding:24px}.bmdshop .bs-hero h2{font-size:29px!important}.bmdshop .bs-hero-art .bs-mic{top:13%;left:30%}.bmdshop .bs-hero-art .bs-rx{top:32%;right:23%}.bmdshop .bs-quiz,.bmdshop .bs-product-grid,.bmdshop .bs-checks{grid-template-columns:1fr}.bmdshop .bs-recommend{min-height:auto}.bmdshop .bs-product-head{min-height:175px}.bmdshop .bs-tip{align-items:stretch;flex-direction:column}.bmdshop .bs-audio-bars{min-width:0;height:85px}.bmdshop .bs-section{margin:28px 0 36px}}
</style>
CSS;
},115);
add_action('wp_footer',static function(){
if(!bmdshop_is_target())return;
echo <<<'JS'
<script id="bmdshop-v2-script">
(function(){
 var root=document.getElementById('bmdshop-v2');if(!root)return;
 var options={
 combo:{name:"Hollyland Lark M2 Combo",reason:"A opção mais flexível desta seleção para alternar entre celular e câmera. Confirme se você precisa de todos os receptores incluídos.",url:"https://www.amazon.com.br/dp/B0CP7QXWPN?tag=supercompra0d-20"},
 usb:{name:"Hollyland Lark M2 USB-C",reason:"Focado em gravações no smartphone USB-C. A página consultada não mostrava uma oferta principal, então confira estoque e vendedores.",url:"https://www.amazon.com.br/dp/B0H97WGKS2?tag=supercompra0d-20"},
 c1:{name:"Hollyland Lark C1",reason:"Alternativa voltada ao smartphone. Confira cuidadosamente a variante Android/USB-C e a disponibilidade do anúncio.",url:"https://www.amazon.com.br/dp/B0BHWF94KR?tag=supercompra0d-20"}
 };
 var name=root.querySelector('#bs-model'),reason=root.querySelector('#bs-reason'),link=root.querySelector('#bs-out');
 root.querySelectorAll('input[name="bs-use"]').forEach(function(input){
  input.addEventListener('change',function(){
   if(!input.checked||!options[input.value])return;
   var d=options[input.value];name.textContent=d.name;reason.textContent=d.reason;link.href=d.url;
   link.textContent='Consultar oferta na Amazon ↗';
  });
 });
})();
</script>
JS;
},115);
add_action('wp_head',static function(){
 if(!bmdshop_is_target())return;
 $url=get_permalink(9166);
 $schema=array('@context'=>'https://schema.org','@type'=>'ItemList','@id'=>$url.'#microfones-comparativo','name'=>'Comparativo editorial de microfones de lapela sem fio para Reels','numberOfItems'=>3,'itemListOrder'=>'https://schema.org/ItemListUnordered','itemListElement'=>array(
 array('@type'=>'ListItem','position'=>1,'name'=>'Hollyland Lark M2 Combo','url'=>'https://www.amazon.com.br/dp/B0CP7QXWPN?tag=supercompra0d-20'),
 array('@type'=>'ListItem','position'=>2,'name'=>'Hollyland Lark M2 USB-C','url'=>'https://www.amazon.com.br/dp/B0H97WGKS2?tag=supercompra0d-20'),
 array('@type'=>'ListItem','position'=>3,'name'=>'Hollyland Lark C1','url'=>'https://www.amazon.com.br/dp/B0BHWF94KR?tag=supercompra0d-20')));
 echo '<script id="bmdshop-itemlist" type="application/ld+json">'.wp_json_encode($schema,JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE|JSON_HEX_TAG|JSON_HEX_AMP).'</script>';
},118);
