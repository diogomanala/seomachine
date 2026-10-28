/* BMD 2026 photo cards: photo from own WP library + original typography.
   All art/downloads are rendered locally in-browser. No tracking or uploads. */
(function(){
'use strict';
var root=document.getElementById('imagens-bom-dia-fotograficas');
function init(){
  root=document.getElementById('imagens-bom-dia-fotograficas');
  if(!root)return;
  var cards=Array.prototype.slice.call(root.querySelectorAll('[data-bmd-card]'));
  cards.forEach(function(card){
    var id=card.getAttribute('data-bmd-card')||'cartao';
    var image=card.querySelector('.bmd-photo-visual img');
    var phrase=card.querySelector('.bmd-photo-caption');
    var editButton=card.querySelector('[data-bmd-edit]');
    var downloadButton=card.querySelector('[data-bmd-download]');
    var editor=card.querySelector('.bmd-photo-editor');
    var field=editor?editor.querySelector('textarea'):null;
    var feedback=card.querySelector('.bmd-photo-feedback');
    if(!image||!phrase||!editButton||!downloadButton||!editor||!field)return;

    editButton.addEventListener('click',function(){
      var expand=editor.hidden;
      editor.hidden=!expand;
      editButton.setAttribute('aria-expanded',expand?'true':'false');
      editButton.textContent=expand?'Fechar edição':'Editar frase';
      if(expand)field.focus();
    });
    field.addEventListener('input',function(){
      phrase.textContent=field.value.trim()||'Que seu dia seja especial.';
      if(feedback)feedback.textContent='';
    });
    downloadButton.addEventListener('click',async function(){
      if(downloadButton.disabled)return;
      downloadButton.disabled=true;
      if(feedback)feedback.textContent='Preparando imagem...';
      try{
        await loadPhoto(image);
        // Wait for the exact cursive face when available, not an invisible
        // network dependency: fallback still works when fonts are blocked.
        if(document.fonts&&document.fonts.load){
          try{await Promise.all([
            document.fonts.load('210px Allura'),
            document.fonts.load('38px "DM Sans"')
          ]);}catch(_e){}
        }
        var canvas=document.createElement('canvas');
        canvas.width=1200;
        canvas.height=675;
        var ctx=canvas.getContext('2d');
        if(!ctx)throw new Error('Canvas indisponível');
        cover(ctx,image,canvas.width,canvas.height);
        var shade=ctx.createLinearGradient(0,0,0,675);
        shade.addColorStop(0,'rgba(8,10,14,.21)');
        shade.addColorStop(.48,'rgba(13,14,19,.38)');
        shade.addColorStop(1,'rgba(18,14,21,.54)');
        ctx.fillStyle=shade;ctx.fillRect(0,0,1200,675);

        ctx.save();
        ctx.textAlign='center';
        ctx.textBaseline='middle';
        ctx.fillStyle='#fff';
        ctx.shadowColor='rgba(10,10,15,.30)';
        ctx.shadowBlur=17;
        ctx.shadowOffsetY=3;
        var size=214;
        ctx.font='400 '+size+'px Allura, "Segoe Script", cursive';
        while(ctx.measureText('Bom dia').width>975&&size>125){
          size-=5;ctx.font='400 '+size+'px Allura, "Segoe Script", cursive';
        }
        ctx.save();
        ctx.translate(600,291);
        ctx.rotate(-.045);
        ctx.fillText('Bom dia',0,0,1020);
        ctx.restore();
        // Short brush underline, separated from subtitle for easy reading.
        ctx.shadowBlur=0;ctx.shadowOffsetY=0;
        ctx.strokeStyle='rgba(255,255,255,.9)';
        ctx.lineWidth=4.2;ctx.lineCap='round';
        ctx.beginPath();
        ctx.moveTo(420,379);ctx.bezierCurveTo(490,369,650,390,777,372);ctx.stroke();

        var message=(field.value.trim()||'Que seu dia seja especial.').replace(/\s+/g,' ');
        var fontSize=message.length>72?30:message.length>49?34:39;
        ctx.font='500 '+fontSize+'px "DM Sans",system-ui,Arial,sans-serif';
        var lines=wrap(ctx,message,975);
        if(lines.length>3){
          fontSize=29;ctx.font='500 '+fontSize+'px "DM Sans",system-ui,Arial,sans-serif';
          lines=wrap(ctx,message,1030);
        }
        if(lines.length>4)lines=lines.slice(0,4);
        ctx.fillStyle='#fff';ctx.shadowColor='rgba(0,0,0,.32)';ctx.shadowBlur=9;
        var lineHeight=fontSize*1.37,start=430-(lines.length-1)*lineHeight*.32;
        lines.forEach(function(line,i){ctx.fillText(line,600,start+i*lineHeight,1040);});
        ctx.restore();

        var filename='bom-dia-fotografico-'+id+'.png';
        if(canvas.toBlob){
          await new Promise(function(resolve,reject){
            canvas.toBlob(function(blob){
              if(!blob){reject(new Error('PNG vazio'));return;}
              try{saveBlob(blob,filename);resolve();}catch(e){reject(e);}
            },'image/png');
          });
        }else{
          saveData(canvas.toDataURL('image/png'),filename);
        }
        if(feedback)feedback.textContent='PNG pronto! Confira os downloads do navegador.';
      }catch(err){
        if(feedback)feedback.textContent='Não foi possível exportar a imagem. Confira a conexão e tente novamente.';
      }finally{
        downloadButton.disabled=false;
      }
    });
  });
}
function loadPhoto(img){
  if(img.complete&&img.naturalWidth>0)return Promise.resolve();
  if(img.decode){
    return img.decode().catch(function(){
      if(img.complete&&img.naturalWidth>0)return;
      return waitImage(img);
    });
  }
  return waitImage(img);
}
function waitImage(img){
  return new Promise(function(resolve,reject){
    if(img.complete&&img.naturalWidth>0){resolve();return;}
    img.addEventListener('load',resolve,{once:true});
    img.addEventListener('error',function(){reject(new Error('Falha de imagem'));},{once:true});
  });
}
function cover(ctx,img,w,h){
  var iw=img.naturalWidth||img.width,ih=img.naturalHeight||img.height;
  if(!iw||!ih)throw new Error('Imagem indisponível');
  var ratio=Math.max(w/iw,h/ih);
  var sw=w/ratio,sh=h/ratio,sx=(iw-sw)/2,sy=(ih-sh)/2;
  ctx.drawImage(img,sx,sy,sw,sh,0,0,w,h);
}
function wrap(ctx,message,maxWidth){
  var words=message.split(' '),lines=[],line='';
  words.forEach(function(word){
    if(!word)return;
    while(ctx.measureText(word).width>maxWidth && word.length>1){
      if(line){lines.push(line);line='';}
      var p=1;while(p<word.length&&ctx.measureText(word.slice(0,p+1)).width<=maxWidth)p++;
      lines.push(word.slice(0,p));word=word.slice(p);
    }
    var test=line?line+' '+word:word;
    if(ctx.measureText(test).width>maxWidth&&line){lines.push(line);line=word;}
    else line=test;
  });
  if(line)lines.push(line);
  return lines;
}
function saveBlob(blob,filename){
  var url=URL.createObjectURL(blob);
  saveData(url,filename);
  setTimeout(function(){URL.revokeObjectURL(url);},2500);
}
function saveData(url,filename){
  var a=document.createElement('a');
  a.href=url;a.download=filename;
  document.body.appendChild(a);
  a.click();a.remove();
}
if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',init,{once:true});
else init();
})();
