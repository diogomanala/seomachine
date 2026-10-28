<?php
/**
 * Original browser-only share-card generator for the existing WhatsApp morning
 * message article. No paid APIs, tracking, data upload, server storage or assets
 * sourced from other websites. Existing post text and image URLs remain intact.
 */
if (!defined('ABSPATH')) { exit; }

add_shortcode('bmd_bomdia_cards', function () {
    return <<<'BMD_BOMDIA'
<section class="bmd-bomdia-tool" aria-labelledby="bmd-bomdia-title">
 <div class="bmd-bomdia-top">
  <span class="bmd-bomdia-eyebrow">CRIE SUA IMAGEM · 100% NO NAVEGADOR</span>
  <h2 id="bmd-bomdia-title">Crie uma imagem de bom dia para WhatsApp</h2>
  <p>Escolha uma mensagem, personalize as cores e baixe um cartão PNG original para enviar no Status, em grupos ou para amigos. Não é preciso instalar nada.</p>
 </div>
 <div class="bmd-bomdia-grid">
  <div class="bmd-bomdia-form">
   <label for="bmd-bomdia-preset">Mensagem para começar</label>
   <select id="bmd-bomdia-preset">
     <option value="Que hoje seja leve, bonito e cheio de boas surpresas.">Um dia leve e bonito</option>
     <option value="Que não faltem motivos para sorrir e agradecer.">Gratidão e alegria</option>
     <option value="Bom dia! Que a paz acompanhe cada passo do seu caminho.">Mensagem de paz</option>
     <option value="Uma nova manhã, novas oportunidades e coragem para seguir.">Motivação</option>
     <option value="Que Deus abençoe seu dia com saúde, amor e serenidade.">Bom dia abençoado</option>
     <option value="Um abraço apertado e um bom dia para quem mora no meu coração.">Família e amigos</option>
     <option value="Sorria! Hoje é uma boa oportunidade para começar de novo.">Recomeço</option>
     <option value="Café quentinho, boas notícias e um dia que vale a pena.">Bom dia com café</option>
   </select>
   <label for="bmd-bomdia-message">Sua mensagem</label>
   <textarea id="bmd-bomdia-message" rows="4" maxlength="160">Que hoje seja leve, bonito e cheio de boas surpresas.</textarea>
   <div class="bmd-bomdia-fields">
    <div><label for="bmd-bomdia-theme">Estilo de cor</label><select id="bmd-bomdia-theme">
      <option value="sun">Amanhecer</option><option value="garden">Verde suave</option><option value="lavender">Lavanda</option><option value="sky">Céu azul</option>
    </select></div>
    <div><label for="bmd-bomdia-format">Formato do cartão</label><select id="bmd-bomdia-format">
      <option value="story">Status / Stories · 9:16</option><option value="square">Post quadrado · 1:1</option>
    </select></div>
   </div>
   <button type="button" id="bmd-bomdia-download">Baixar imagem PNG <span aria-hidden="true">↓</span></button>
   <p class="bmd-bomdia-feedback" id="bmd-bomdia-feedback" aria-live="polite">Prévia pronta para personalizar.</p>
   <p class="bmd-bomdia-privacy">A imagem é criada no seu dispositivo. Nenhum texto é enviado para o nosso servidor. Não há fotos de terceiros ou marcas d'água.</p>
  </div>
  <div class="bmd-bomdia-preview">
    <span>PRÉVIA DO CARTÃO</span>
    <canvas id="bmd-bomdia-canvas" width="1080" height="1920" role="img" aria-label="Prévia gráfica da imagem de bom dia personalizada"></canvas>
    <small>A composição usa tipografia e formas originais, não fotografias copiadas.</small>
  </div>
 </div>
</section>
<style>
.bmd-bomdia-tool{max-width:980px;margin:24px 0 34px;border:1px solid #e2e7ed;border-radius:18px;background:#fff;overflow:hidden;box-shadow:0 8px 32px rgba(20,30,48,.045);color:#1e2d43}
.bmd-bomdia-tool *{box-sizing:border-box}
.bmd-bomdia-top{padding:clamp(20px,3vw,30px);background:linear-gradient(115deg,#fff8f0,#f9fbff);border-bottom:1px solid #e8ebf1}
.bmd-bomdia-eyebrow{font-size:11px;font-weight:800;letter-spacing:.09em;color:#b65b37}
.bmd-bomdia-top h2{font-size:clamp(24px,3.3vw,34px)!important;line-height:1.2!important;letter-spacing:-.035em;margin:10px 0 12px!important;color:#1e2d43}
.bmd-bomdia-top p{font-size:14px;color:#576779;line-height:1.7;margin:0!important}
.bmd-bomdia-grid{display:grid;grid-template-columns:minmax(0,1fr) minmax(250px,.73fr);gap:26px;padding:clamp(20px,3vw,28px)}
.bmd-bomdia-form{min-width:0}
.bmd-bomdia-form label{font-size:13px;font-weight:750;display:block;margin:0 0 8px}
.bmd-bomdia-form select,.bmd-bomdia-form textarea{width:100%!important;min-width:0;max-width:100%;display:block!important;border:1px solid #bfccda!important;border-radius:10px!important;padding:11px 12px!important;color:#213149!important;background:#fff!important;font-size:15px!important;box-shadow:none!important;margin:0 0 17px!important}
.bmd-bomdia-form select{min-height:46px}
.bmd-bomdia-form textarea{line-height:1.5!important;resize:vertical}
.bmd-bomdia-form select:focus,.bmd-bomdia-form textarea:focus{outline:2px solid #e0a27d;outline-offset:2px}
.bmd-bomdia-fields{display:grid;grid-template-columns:1fr 1fr;gap:12px}
.bmd-bomdia-form button{border:0!important;background:#1e2e46!important;color:#fff!important;font-size:14px;font-weight:780;min-height:48px;width:100%;border-radius:10px!important;padding:12px!important;cursor:pointer}
.bmd-bomdia-form button:hover{background:#31465f!important}
.bmd-bomdia-feedback{font-size:13px;color:#48647e;line-height:1.5;margin:11px 0!important;min-height:20px}
.bmd-bomdia-privacy{font-size:12px;color:#6d7a8a;line-height:1.6;margin:12px 0 0!important}
.bmd-bomdia-preview{display:flex;flex-direction:column;align-items:center;gap:10px;min-width:0;background:#f5f7fb;padding:18px;border-radius:13px}
.bmd-bomdia-preview>span{font-size:10px;letter-spacing:.1em;font-weight:850;color:#718097}
.bmd-bomdia-preview canvas{display:block;width:auto;max-width:100%;max-height:490px;height:auto;border-radius:7px;box-shadow:0 8px 25px rgba(38,45,63,.14);background:#ffe8cb}
.bmd-bomdia-preview small{font-size:11px;color:#6f7a8c;line-height:1.5;text-align:center}
@media(max-width:760px){.bmd-bomdia-grid{grid-template-columns:1fr;gap:20px}.bmd-bomdia-preview canvas{max-height:400px}}
@media(max-width:430px){.bmd-bomdia-fields{grid-template-columns:1fr}.bmd-bomdia-grid{padding:17px}.bmd-bomdia-preview{padding:14px}}
</style>
<script>
(function(){
'use strict';
var message=document.getElementById('bmd-bomdia-message');
var preset=document.getElementById('bmd-bomdia-preset');
var theme=document.getElementById('bmd-bomdia-theme');
var format=document.getElementById('bmd-bomdia-format');
var canvas=document.getElementById('bmd-bomdia-canvas');
var download=document.getElementById('bmd-bomdia-download');
var status=document.getElementById('bmd-bomdia-feedback');
if(!message||!preset||!theme||!format||!canvas||!download||!status)return;
var ctx=canvas.getContext('2d');
if(!ctx){download.disabled=true;status.textContent='Seu navegador não oferece suporte a esta ferramenta de imagem.';return;}
var palettes={
 sun:{a:'#ffdfaf',b:'#f89b7e',ink:'#653e46',label:'#794c50',circle:'#fff0c7'},
 garden:{a:'#d9f4e1',b:'#88c9b3',ink:'#254b43',label:'#3b6156',circle:'#f1ffe3'},
 lavender:{a:'#ece4ff',b:'#c5b0ee',ink:'#493d77',label:'#6c5a92',circle:'#fdf1ff'},
 sky:{a:'#d9edff',b:'#91c4ee',ink:'#2b4f77',label:'#4b6b8e',circle:'#fff6de'}
};
function multiline(text,maxWidth,maxLines){
 var words=text.trim().replace(/\s+/g,' ').split(' '),lines=[],line='';
 for(var i=0;i<words.length;i++){
  var candidate=line?line+' '+words[i]:words[i];
  if(ctx.measureText(candidate).width>maxWidth && line){lines.push(line);line=words[i];}
  else{line=candidate;}
 }
 if(line)lines.push(line);
 if(lines.length>maxLines){lines=lines.slice(0,maxLines);var last=lines[maxLines-1];
  while(last.length>1&&ctx.measureText(last+'…').width>maxWidth){last=last.slice(0,-1);}
  lines[maxLines-1]=last+'…';
 }
 return lines;
}
function paint(){
 var h=format.value==='square'?1080:1920,w=1080;
 canvas.width=w;canvas.height=h;
 var p=palettes[theme.value]||palettes.sun;
 var grad=ctx.createLinearGradient(0,0,w,h);grad.addColorStop(0,p.a);grad.addColorStop(1,p.b);
 ctx.fillStyle=grad;ctx.fillRect(0,0,w,h);
 ctx.globalAlpha=.55;ctx.fillStyle=p.circle;
 ctx.beginPath();ctx.arc(w*.82,h*.18,w*.24,0,Math.PI*2);ctx.fill();
 ctx.globalAlpha=.18;ctx.beginPath();ctx.arc(w*.12,h*.81,w*.38,0,Math.PI*2);ctx.fill();
 ctx.globalAlpha=1;
 ctx.strokeStyle=p.label;ctx.globalAlpha=.25;ctx.lineWidth=3;
 ctx.beginPath();ctx.moveTo(106,h*.13);ctx.lineTo(w-106,h*.13);ctx.stroke();
 ctx.beginPath();ctx.moveTo(106,h*.88);ctx.lineTo(w-106,h*.88);ctx.stroke();ctx.globalAlpha=1;
 ctx.textAlign='center';ctx.textBaseline='middle';
 ctx.fillStyle=p.label;ctx.font='700 43px Arial, sans-serif';
 ctx.fillText('UMA NOVA MANHÃ',w/2,h*.21,900);
 ctx.fillStyle=p.ink;ctx.font='bold 126px Arial, sans-serif';
 ctx.fillText('BOM DIA',w/2,h*.32,900);
 var txt=message.value.trim()||'Que hoje seja um dia especial.';
 var size=txt.length>110?55:txt.length>72?65:76;
 ctx.font='600 '+size+'px Arial, sans-serif';
 var lines=multiline(txt,850,5);
 var spacing=size*1.4,start=h*.56-(lines.length-1)*spacing/2;
 for(var i=0;i<lines.length;i++){ctx.fillText(lines[i],w/2,start+i*spacing,890);}
 ctx.font='500 36px Arial, sans-serif';ctx.fillStyle=p.label;
 ctx.fillText('Compartilhe carinho.',w/2,h*.92,850);
 status.textContent='Prévia atualizada. Você pode baixar o PNG.';
}
preset.addEventListener('change',function(){message.value=preset.value;paint();});
message.addEventListener('input',paint);
theme.addEventListener('change',paint);
format.addEventListener('change',paint);
download.addEventListener('click',function(){
 try{
  var filename='bom-dia-'+(format.value==='square'?'quadrado':'status')+'.png';
  function save(url,release){
   var a=document.createElement('a');a.download=filename;a.href=url;
   document.body.appendChild(a);a.click();a.remove();
   if(release)setTimeout(function(){URL.revokeObjectURL(url);},1500);
   status.textContent='Imagem PNG preparada para download.';
  }
  if(canvas.toBlob){canvas.toBlob(function(blob){
   if(!blob){status.textContent='Não foi possível preparar a imagem. Tente novamente.';return;}
   save(URL.createObjectURL(blob),true);
  },'image/png');}
  else save(canvas.toDataURL('image/png'),false);
 }catch(e){status.textContent='Não foi possível baixar a imagem neste navegador.';}
});
paint();
})();
</script>
BMD_BOMDIA;
});

add_filter('the_content',function($content){
    if(is_admin() || !is_singular('post') || !is_main_query() || !in_the_loop() || is_preview())return $content;
    if(get_the_ID()!==4977 || get_post_field('post_name',get_the_ID())!=='melhores-imagens-de-bom-dia-para-compartilhar-no-whatsapp')return $content;
    if(strpos($content,'bmd-bomdia-tool')!==false || strlen($content)<2200 || strpos($content,'<h2')===false)return $content;
    $point=strpos($content,'<h2');
    if($point>9000)return $content;
    return substr($content,0,$point).do_shortcode('[bmd_bomdia_cards]').substr($content,$point);
},61);
