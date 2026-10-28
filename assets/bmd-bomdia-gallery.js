/* BMD Bom Dia Studio — original procedural illustrations, no external images, APIs, storage or fonts. */
(function () {
  'use strict';
  var ROOT_ID = 'bmd-bomdia-studio';
  var WIDTH = 1080;
  var HEIGHTS = { status: 1920, portrait: 1350, square: 1080 };
  var CAT = {
    abencoado: 'Abençoado', motivacional: 'Motivacional', familia: 'Família',
    amizade: 'Amizade', engracado: 'Engraçado', carinhoso: 'Carinhoso',
    curto: 'Curto', cafe: 'Com café', status: 'Para Status'
  };
  function onReady() {
    var root = document.getElementById(ROOT_ID);
    if (!root) return;
    var items = Array.prototype.slice.call(root.querySelectorAll('.gm-item'));
    var filters = Array.prototype.slice.call(root.querySelectorAll('[data-gm-filter]'));
    var mainCanvas = root.querySelector('#gm-main-canvas');
    var field = root.querySelector('#gm-message');
    var fmtField = root.querySelector('#gm-format');
    var paletteField = root.querySelector('#gm-colors');
    var number = root.querySelector('#gm-length');
    var feedback = root.querySelector('#gm-feedback');
    var count = root.querySelector('#gm-count');
    var download = root.querySelector('#gm-download');
    var copy = root.querySelector('#gm-copy');
    var editor = root.querySelector('#gm-editor');
    if (!items.length || !mainCanvas || !field || !fmtField || !paletteField || !download) return;

    var cards = items.map(function (node) {
      var button = node.querySelector('.gm-tile');
      return {
        node: node, button: button,
        canvas: node.querySelector('canvas'),
        id: Number(button.getAttribute('data-gm-id')) || 1,
        cat: button.getAttribute('data-gm-cat') || 'curto',
        name: button.getAttribute('data-gm-name') || '',
        quote: button.getAttribute('data-gm-quote') || '',
        art: button.getAttribute('data-gm-art') || 'sunrise',
        layout: button.getAttribute('data-gm-layout') || 'center',
        font: button.getAttribute('data-gm-font') || 'sans',
        colors: [
          button.getAttribute('data-gm-a') || '#f7e5c6',
          button.getAttribute('data-gm-b') || '#d2ac88',
          button.getAttribute('data-gm-ink') || '#473e39',
          button.getAttribute('data-gm-accent') || '#ffffff'
        ],
        drawn: false
      };
    });
    var current = cards[0];
    var painting = false;
    var observer;
    var idle = window.requestIdleCallback || function (fn) { return window.setTimeout(fn, 1); };

    function rgba(hex, alpha) {
      var c = String(hex || '#000000').replace('#', '');
      if (c.length === 3) c = c.split('').map(function (s) { return s + s; }).join('');
      var num = parseInt(c, 16) || 0;
      return 'rgba(' + ((num >> 16) & 255) + ',' + ((num >> 8) & 255) + ',' + (num & 255) + ',' + alpha + ')';
    }
    function colorsFor(card, mode) {
      if (mode === 'light') return ['#fff6e9', '#e9f0e4', '#23483c', '#ca8357'];
      if (mode === 'dark') return ['#172934', '#30435c', '#fff3da', '#e4aa69'];
      return card.colors;
    }
    function ctxCircle(ctx, x, y, radius, color, opacity) {
      ctx.save(); ctx.globalAlpha = opacity == null ? 1 : opacity;
      ctx.fillStyle = color; ctx.beginPath(); ctx.arc(x, y, radius, 0, Math.PI * 2); ctx.fill(); ctx.restore();
    }
    function ctxLine(ctx, x1, y1, x2, y2, color, width, alpha) {
      ctx.save();ctx.strokeStyle = color;ctx.globalAlpha = alpha == null ? 1 : alpha;ctx.lineWidth = width || 4;
      ctx.beginPath();ctx.moveTo(x1,y1);ctx.lineTo(x2,y2);ctx.stroke();ctx.restore();
    }
    function ellipse(ctx, x, y, rx, ry, rotation, fill, alpha) {
      ctx.save();ctx.globalAlpha = alpha == null ? 1 : alpha;ctx.translate(x,y);ctx.rotate(rotation);
      ctx.fillStyle = fill;ctx.beginPath();ctx.ellipse(0,0,rx,ry,0,0,Math.PI*2);ctx.fill();ctx.restore();
    }
    function curve(ctx, points, stroke, width, alpha) {
      ctx.save();ctx.globalAlpha=alpha == null ? 1:alpha;ctx.strokeStyle=stroke;ctx.lineWidth=width||5;
      ctx.lineCap='round';ctx.beginPath();ctx.moveTo(points[0],points[1]);
      ctx.bezierCurveTo(points[2],points[3],points[4],points[5],points[6],points[7]);
      ctx.stroke();ctx.restore();
    }
    function rounded(ctx,x,y,w,h,r,fill,stroke) {
      r=Math.min(r,w/2,h/2);
      ctx.beginPath();ctx.moveTo(x+r,y);ctx.lineTo(x+w-r,y);ctx.quadraticCurveTo(x+w,y,x+w,y+r);
      ctx.lineTo(x+w,y+h-r);ctx.quadraticCurveTo(x+w,y+h,x+w-r,y+h);
      ctx.lineTo(x+r,y+h);ctx.quadraticCurveTo(x,y+h,x,y+h-r);ctx.lineTo(x,y+r);
      ctx.quadraticCurveTo(x,y,x+r,y);ctx.closePath();
      if(fill){ctx.fillStyle=fill;ctx.fill();}
      if(stroke){ctx.strokeStyle=stroke;ctx.lineWidth=3;ctx.stroke();}
    }
    function star(ctx,x,y,r,color,n) {
      n=n||5;
      ctx.save();ctx.translate(x,y);ctx.fillStyle=color;ctx.beginPath();
      for(var i=0;i<n*2;i++){var a=(Math.PI*i/n)-Math.PI/2;var rr=i%2?r*.35:r;
        var px=Math.cos(a)*rr,py=Math.sin(a)*rr;
        if(i===0)ctx.moveTo(px,py);else ctx.lineTo(px,py);
      }
      ctx.closePath();ctx.fill();ctx.restore();
    }
    function flower(ctx,x,y,r,color,core) {
      for(var i=0;i<7;i++){
        var a=i*Math.PI*2/7;
        ellipse(ctx,x+Math.cos(a)*r*.77,y+Math.sin(a)*r*.77,r*.57,r*.34,a,color,.72);
      }
      ctxCircle(ctx,x,y,r*.29,core,.94);
    }
    function leaf(ctx,x,y,scale,rotation,stem,fill) {
      ctx.save();ctx.translate(x,y);ctx.rotate(rotation);ctx.scale(scale,scale);
      ctx.strokeStyle=stem;ctx.lineWidth=5;ctx.beginPath();ctx.moveTo(0,0);ctx.quadraticCurveTo(0,-110,54,-235);ctx.stroke();
      for(var i=0;i<4;i++){
        ellipse(ctx,16+(i%2?23:-13),-52-i*52,31,69,(i%2?-.55:.45),fill,.56);
      }ctx.restore();
    }
    function cup(ctx,x,y,scale,ink,light) {
      ctx.save();ctx.translate(x,y);ctx.scale(scale,scale);
      ctx.fillStyle=rgba(light,.5);ctx.beginPath();ctx.ellipse(0,125,162,24,0,0,Math.PI*2);ctx.fill();
      rounded(ctx,-125,-38,235,153,28,rgba(light,.72),rgba(ink,.55));
      ctx.strokeStyle=rgba(ink,.6);ctx.lineWidth=15;ctx.beginPath();ctx.arc(109,25,60,-Math.PI/2,Math.PI/2);ctx.stroke();
      for(var i=0;i<3;i++){
        ctx.strokeStyle=rgba(ink,.46);ctx.lineWidth=7;ctx.beginPath();ctx.moveTo(-60+i*52,-75);
        ctx.bezierCurveTo(-105+i*52,-145,-20+i*52,-155,-58+i*52,-217);ctx.stroke();
      }ctx.restore();
    }
    function cloud(ctx,x,y,s,c) {
      ctxCircle(ctx,x,y,76*s,c,.38);ctxCircle(ctx,x+85*s,y-39*s,110*s,c,.38);
      ctxCircle(ctx,x+166*s,y,72*s,c,.38);
      rounded(ctx,x-61*s,y,298*s,74*s,32*s,rgba(c,.38));
    }
    function heart(ctx,x,y,scale,color) {
      ctx.save();ctx.translate(x,y);ctx.scale(scale,scale);ctx.fillStyle=color;
      ctx.beginPath();ctx.moveTo(0,35);ctx.bezierCurveTo(-105,-50,-112,-111,-45,-111);
      ctx.bezierCurveTo(-13,-111,0,-87,0,-71);ctx.bezierCurveTo(21,-131,109,-124,107,-64);
      ctx.bezierCurveTo(106,-22,42,8,0,35);ctx.fill();ctx.restore();
    }
    function seeded(id,offset){var v=Math.sin(id*192.19+offset*73.9)*43758.5453;return v-Math.floor(v);}

    function artLayer(ctx, card, h, p) {
      var motif=card.art;
      var a=p[3],ink=p[2];
      var cx=540, cy=h*.50;
      ctx.save();
      switch(motif) {
        case 'sunrise':
          ctxCircle(ctx,800,h*.21,245,a,.53);
          for(var j=0;j<12;j++){var ang=j*Math.PI/6;
            ctxLine(ctx,800+Math.cos(ang)*270,h*.21+Math.sin(ang)*270,
             800+Math.cos(ang)*335,h*.21+Math.sin(ang)*335,ink,4,.15);
          }break;
        case 'botanical':
          leaf(ctx,92,h*.84,1.55,-.22,ink,a);
          leaf(ctx,998,h*.25,1.10,Math.PI-.25,ink,a);
          break;
        case 'coffee':
          ctxCircle(ctx,795,h*.76,260,a,.43);cup(ctx,795,h*.77,.87,ink,a);break;
        case 'night':
          ctxCircle(ctx,828,h*.19,160,a,.62);
          ctxCircle(ctx,903,h*.15,153,p[0],1);
          for(var i=0;i<26;i++) star(ctx,60+seeded(card.id,i)*970,h*(.05+seeded(card.id,i+28)*.92),5+seeded(card.id,i+44)*10,a,4);
          break;
        case 'floral':
          flower(ctx,79,h*.20,152,a,p[0]);flower(ctx,1025,h*.82,205,a,p[0]);break;
        case 'rays':
          for(var k=0;k<22;k++){var t=k*Math.PI/11;
            ctxLine(ctx,cx+Math.cos(t)*350,h*.18+Math.sin(t)*350,
              cx+Math.cos(t)*730,h*.18+Math.sin(t)*730,a,5,.26);
          }ctxCircle(ctx,cx,h*.18,180,a,.38);break;
        case 'paper':
          ctx.save();ctx.translate(850,h*.70);ctx.rotate(.25);
          rounded(ctx,-210,-360,420,590,30,rgba(a,.45));ctx.restore();
          ctx.save();ctx.translate(190,h*.21);ctx.rotate(-.23);
          rounded(ctx,-180,-170,360,300,23,rgba(p[0],.54));ctx.restore();
          break;
        case 'arches':
          for(var r=0;r<4;r++){
            ctx.save();ctx.strokeStyle=rgba(a,.62-r*.1);ctx.lineWidth=34-r*4;
            ctx.beginPath();ctx.ellipse(820,h*.34,175+r*55,290+r*72,0,Math.PI,Math.PI*2);ctx.stroke();ctx.restore();
          }break;
        case 'confetti':
          for(var z=0;z<38;z++){
            var xx=seeded(card.id,z)*1080,yy=seeded(card.id,z+53)*h;
            ctx.save();ctx.translate(xx,yy);ctx.rotate(seeded(card.id,z+19)*6);
            ctx.fillStyle=rgba(a,.3);ctx.fillRect(-6,-15,10+seeded(card.id,z+88)*20,30);ctx.restore();
          }break;
        case 'waves':
          for(var q=0;q<5;q++){
            curve(ctx,[-100,h*(.68+q*.063),260,h*(.55+q*.065),780,h*(.9+q*.035),1210,h*(.73+q*.047)],
              a,16-q*2,.46-q*.055);
          }break;
        case 'mountain':
          ctx.save();ctx.fillStyle=rgba(a,.46);
          ctx.beginPath();ctx.moveTo(-70,h*.93);ctx.lineTo(270,h*.51);ctx.lineTo(590,h*.92);ctx.closePath();ctx.fill();
          ctx.fillStyle=rgba(ink,.10);
          ctx.beginPath();ctx.moveTo(450,h*.96);ctx.lineTo(865,h*.60);ctx.lineTo(1160,h*.98);ctx.closePath();ctx.fill();ctx.restore();
          ctxCircle(ctx,775,h*.29,120,a,.50);break;
        case 'checker':
          for(var rr=0;rr<6;rr++)for(var cc=0;cc<5;cc++){
            if((rr+cc)%2===0)rounded(ctx,cc*110+520,rr*110+h*.58,86,86,18,rgba(a,.20));
          }break;
        case 'dunes':
          for(var n=0;n<4;n++){
            ctx.save();ctx.fillStyle=rgba(a,.17+n*.055);ctx.beginPath();
            ctx.moveTo(-70,h*(.76+n*.065));
            ctx.bezierCurveTo(250,h*(.62+n*.06),760,h*(.96+n*.01),1160,h*(.75+n*.060));
            ctx.lineTo(1160,h+20);ctx.lineTo(-70,h+20);ctx.closePath();ctx.fill();ctx.restore();
          }break;
        case 'clouds':
          cloud(ctx,170,h*.17,1.2,a);cloud(ctx,720,h*.80,1.0,a);break;
        case 'ribbon':
          for(var b=0;b<3;b++){
            curve(ctx,[-150,h*(.18+b*.25),260,h*(.11+b*.25),520,h*(.42+b*.21),1180,h*(.15+b*.24)],a,25-b*5,.36);
          }break;
        case 'leaves':
          leaf(ctx,135,h*.55,1.7,.25,ink,a);leaf(ctx,970,h*.88,1.45,3.8,ink,a);break;
        case 'sunburst':
          for(var v=0;v<13;v++){
            var theta=v*Math.PI*2/13;
            ctxLine(ctx,820+Math.cos(theta)*115,h*.17+Math.sin(theta)*115,
              820+Math.cos(theta)*260,h*.17+Math.sin(theta)*260,a,20,.43);
          }ctxCircle(ctx,820,h*.17,102,a,.58);break;
        case 'circles':
          for(var m=0;m<7;m++){ctx.save();ctx.strokeStyle=rgba(a,.48);ctx.lineWidth=4;
            ctx.beginPath();ctx.arc(880,h*.29,80+m*43,0,Math.PI*2);ctx.stroke();ctx.restore();
          }break;
        case 'petals':
          for(var e=0;e<11;e++){
            var theta2=e*Math.PI*2/11;
            ellipse(ctx,785+Math.cos(theta2)*170,h*.19+Math.sin(theta2)*170,38,118,theta2,a,.34);
          }break;
        case 'grid':
          for(var gg=0;gg<12;gg++){ctxLine(ctx,gg*120,0,gg*120,h,a,2,.13);}
          for(var hh=0;hh<h/120;hh++){ctxLine(ctx,0,hh*120,1080,hh*120,a,2,.13);}
          ctxCircle(ctx,810,h*.73,240,a,.24);break;
        case 'olive':
          leaf(ctx,100,h*.89,1.95,-.32,ink,a);
          ctxCircle(ctx,890,h*.17,144,a,.25);break;
        case 'hearts':
          heart(ctx,810,h*.18,1.65,rgba(a,.57));
          heart(ctx,156,h*.84,.8,rgba(a,.37));break;
        case 'sparkles':
          for(var sp=0;sp<28;sp++)star(ctx,55+seeded(card.id,sp)*990,h*(.07+seeded(card.id,sp+39)*.88),
             9+seeded(card.id,sp+19)*15,rgba(a,.60),4);break;
        case 'frame':
          rounded(ctx,55,55,970,h-110,58,null,rgba(a,.40));
          rounded(ctx,90,90,900,h-180,41,null,rgba(a,.23));
          ctxCircle(ctx,893,h*.15,85,a,.46);break;
      }
      ctx.restore();
    }
    function wrapText(ctx, raw, maxWidth) {
      var words = raw.trim().replace(/\s+/g,' ').split(' '), lines=[], line='';
      words.forEach(function(word){
        if(!word)return;
        // Split unusually long tokens rather than letting text bleed outside the canvas.
        while(ctx.measureText(word).width>maxWidth && word.length>1){
          if(line){lines.push(line);line='';}
          var cut=1;
          while(cut<word.length && ctx.measureText(word.slice(0,cut+1)).width<=maxWidth)cut++;
          lines.push(word.slice(0,cut));word=word.slice(cut);
        }
        var test=line?line+' '+word:word;
        if(ctx.measureText(test).width>maxWidth && line){lines.push(line);line=word;}
        else{line=test;}
      });
      if(line)lines.push(line);
      return lines;
    }
    function drawText(ctx, card, h, p, text) {
      var left=card.layout==='left', ink=p[2], accent=p[3];
      var x=left?122:540,maxWidth=left?830:840;
      ctx.textBaseline='middle';ctx.textAlign=left?'left':'center';
      ctx.fillStyle=rgba(ink,.65);
      ctx.font='700 27px system-ui, Arial, sans-serif';
      ctx.fillText('UMA NOVA MANHÃ',x,h*.14,maxWidth);
      ctx.fillStyle=ink;
      ctx.font=(card.font==='serif'?'700 123px Georgia, serif':'800 118px system-ui, Arial, sans-serif');
      ctx.fillText('BOM DIA',x,h*.32,maxWidth);
      ctxLine(ctx,left?122:390,h*.389,left?410:690,h*.389,accent,9,.85);

      var size=h===1080?72:h===1350?77:84;
      var maxHeight=h===1080?345:h===1350?485:710;
      var lines=[],leading=0,maxLines=h===1080?6:h===1350?7:9;
      for(;size>=31;size-=2){
        ctx.font=(card.font==='serif'?'500 ':'600 ')+size+'px '+(card.font==='serif'?'Georgia, serif':'system-ui, Arial, sans-serif');
        lines=wrapText(ctx,text,maxWidth);
        leading=size*1.43;
        if(lines.length<=maxLines&&lines.length*leading<=maxHeight)break;
      }
      if(lines.length>maxLines)lines=lines.slice(0,maxLines);
      var cy=h===1080?h*.646:h===1350?h*.638:h*.635;
      var offset=(lines.length-1)*leading/2;
      ctx.fillStyle=ink;
      for(var i=0;i<lines.length;i++)ctx.fillText(lines[i],x,cy-offset+i*leading,maxWidth);
      ctxLine(ctx,125,h*.89,955,h*.89,ink,2,.27);
      ctx.font='600 26px system-ui, Arial, sans-serif';
      ctx.fillStyle=rgba(ink,.57);
      ctx.fillText(String(CAT[card.cat]||'Bom dia').toLocaleUpperCase('pt-BR'),x,h*.925,maxWidth);
    }
    function paint(canvas, card, which, raw, mode, thumb) {
      if(!canvas || !card)return false;
      var ctx=canvas.getContext && canvas.getContext('2d');
      if(!ctx)return false;
      var h=HEIGHTS[which]||1350;
      var pixelWidth=thumb?220:WIDTH;
      canvas.width=pixelWidth;
      canvas.height=Math.round(pixelWidth*h/WIDTH);
      ctx.setTransform(pixelWidth/WIDTH,0,0,pixelWidth/WIDTH,0,0);
      ctx.imageSmoothingEnabled=true;
      ctx.imageSmoothingQuality='high';
      var p=colorsFor(card,mode);
      var grad=ctx.createLinearGradient(0,0,1080,h);
      grad.addColorStop(0,p[0]);grad.addColorStop(1,p[1]);
      ctx.fillStyle=grad;ctx.fillRect(0,0,1080,h);
      artLayer(ctx,card,h,p);
      var words=(raw||card.quote||'Que seu dia seja especial.').trim().replace(/\s+/g,' ');
      drawText(ctx,card,h,p,words);
      return true;
    }
    function renderThumb(card) {
      if(card.drawn||!card.canvas)return;
      if(paint(card.canvas,card,'portrait',card.quote,'original',true))card.drawn=true;
    }
    function queueRepaint() {
      number.textContent=String(field.value.length)+' / 170';
      if(painting)return;
      painting=true;
      window.requestAnimationFrame(function(){
        painting=false;
        if(!paint(mainCanvas,current,fmtField.value,field.value,paletteField.value,false)){
          feedback.textContent='Não foi possível criar a imagem neste navegador.';
          download.disabled=true;
        }
      });
    }
    function selectCard(card,scrollToEdit) {
      current=card;
      cards.forEach(function(c){
        var active=c===card;
        c.button.setAttribute('aria-pressed',active?'true':'false');
        c.button.setAttribute('data-selected',active?'true':'false');
      });
      field.value=card.quote;
      paletteField.value='original';
      feedback.textContent='Modelo '+card.id+' selecionado. Personalize sua mensagem.';
      queueRepaint();
      if(scrollToEdit&&window.matchMedia&&window.matchMedia('(max-width: 810px)').matches) {
        var reduced=window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        editor.scrollIntoView({behavior:reduced?'auto':'smooth',block:'start'});
      }
    }
    function applyFilter(value) {
      var shown=0;
      cards.forEach(function(c){
        var visible=value==='all'||c.cat===value;
        c.node.hidden=!visible;
        if(visible){shown++;if(!observer&&!c.drawn)idle(function(){renderThumb(c);});}
      });
      filters.forEach(function(b){b.setAttribute('aria-pressed',b.getAttribute('data-gm-filter')===value?'true':'false');});
      count.textContent=shown+' '+(shown===1?'modelo':'modelos');
      if(current.node.hidden){var next=cards.filter(function(c){return !c.node.hidden;})[0];if(next)selectCard(next,false);}
    }
    cards.forEach(function(c){c.button.addEventListener('click',function(){selectCard(c,true);});});
    filters.forEach(function(b){b.addEventListener('click',function(){applyFilter(b.getAttribute('data-gm-filter'));});});
    field.addEventListener('input',queueRepaint);
    fmtField.addEventListener('change',queueRepaint);
    paletteField.addEventListener('change',queueRepaint);

    function deliverBlob(blob) {
      if(!blob){feedback.textContent='Não foi possível gerar o PNG. Tente outro navegador.';return;}
      var url=URL.createObjectURL(blob);
      var a=document.createElement('a');
      a.href=url;a.download='bom-dia-modelo-'+String(current.id).padStart(2,'0')+'-'+fmtField.value+'.png';
      document.body.appendChild(a);
      a.click();a.remove();
      window.setTimeout(function(){URL.revokeObjectURL(url);},2500);
      feedback.textContent='Seu PNG foi preparado. Confira os downloads do navegador.';
    }
    download.addEventListener('click',function(){
      if(!paint(mainCanvas,current,fmtField.value,field.value,paletteField.value,false)){
        feedback.textContent='Seu navegador não oferece suporte para exportar PNG.';return;
      }
      try{
        if(mainCanvas.toBlob){mainCanvas.toBlob(deliverBlob,'image/png');}
        else {
          var a=document.createElement('a');
          a.href=mainCanvas.toDataURL('image/png');
          a.download='bom-dia-modelo-'+String(current.id)+'.png';
          document.body.appendChild(a);a.click();a.remove();
          feedback.textContent='Imagem PNG preparada para download.';
        }
      }catch(e){feedback.textContent='Não foi possível baixar o arquivo neste navegador.';}
    });
    copy.addEventListener('click',function(){
      var text=field.value.trim();
      if(!text){feedback.textContent='Digite uma mensagem antes de copiar.';return;}
      if(navigator.clipboard&&navigator.clipboard.writeText){
        navigator.clipboard.writeText(text).then(function(){feedback.textContent='Mensagem copiada.';})
          .catch(function(){field.focus();field.select();feedback.textContent='Texto selecionado; use Copiar no seu dispositivo.';});
      }else{
        field.focus();field.select();
        try{
          if(document.execCommand('copy')){feedback.textContent='Mensagem copiada.';return;}
        }catch(e){}
        feedback.textContent='Texto selecionado; use Copiar no seu dispositivo.';
      }
    });
    if('IntersectionObserver' in window){
      observer=new IntersectionObserver(function(entries){
        entries.forEach(function(entry){
          if(entry.isIntersecting){
            var found=cards.filter(function(c){return c.node.contains(entry.target);})[0];
            if(found){renderThumb(found);observer.unobserve(entry.target);}
          }
        });
      },{rootMargin:'320px 0px'});
      cards.forEach(function(c){if(c.canvas)observer.observe(c.canvas);});
    }else{
      var next=0;
      function batch(){var end=Math.min(next+4,cards.length);for(;next<end;next++)renderThumb(cards[next]);if(next<cards.length)idle(batch);}
      idle(batch);
    }
    selectCard(current,false);
    applyFilter('all');
  }
  if(document.readyState==='loading')document.addEventListener('DOMContentLoaded',onReady,{once:true});
  else onReady();
})();
