<?php
/** CLI-only reversible home page patch; target is Cortar Imagens Carrossel. */
if(PHP_SAPI!=='cli'){http_response_code(403);exit;}
$action=$argv[1]??'status';
$root=dirname(__DIR__,2);
if(!preg_match('~/domains/cortarimagenscarrossel\.online/public_html$~',$root)){fwrite(STDERR,"WRONG_ROOT\n");exit(2);}
$path=$root.'/index.html';$asset=$root.'/guia-visual-carrossel.webp';
$backup=dirname($root).'/.index-before-bmd-banner-20261031.html';
$marker='id="bmd-banner-visual-20261031"';
$source=@file_get_contents($path);
if(!is_string($source)||strlen($source)<5000){fwrite(STDERR,"BAD_INDEX\n");exit(3);}
if($action==='status'){
 echo json_encode(['asset'=>is_file($asset),'asset_bytes'=>is_file($asset)?filesize($asset):0,'banner_present'=>strpos($source,$marker)!==false,'backup_present'=>is_file($backup),'anchor_found'=>strpos($source,'id="ferramentas-complementares"')!==false])."\n";exit;
}
if($action==='rollback'){
 if(!is_file($backup)){fwrite(STDERR,"NO_BACKUP\n");exit(4);}
 if(strpos($source,$marker)===false){echo "NO_BANNER\n";exit;}
 $old=file_get_contents($backup);$temp=$path.'.bmd-rollback-tmp';
 if(file_put_contents($temp,$old,LOCK_EX)!==strlen($old)||!rename($temp,$path)){fwrite(STDERR,"ROLLBACK_FAILED\n");exit(5);}
 echo "ROLLED_BACK\n";exit;
}
if($action!=='apply'){fwrite(STDERR,"UNKNOWN_ACTION\n");exit(6);}
if(strpos($source,$marker)!==false){echo "ALREADY_PRESENT\n";exit;}
if(!is_file($asset)||filesize($asset)<10000||filesize($asset)>250000){fwrite(STDERR,"BAD_ASSET\n");exit(7);}
$pos=strpos($source,'id="ferramentas-complementares"');
if($pos===false){fwrite(STDERR,"ANCHOR_MISSING\n");exit(8);}
$end=strpos($source,'</section>',$pos);
if($end===false||$end-$pos>6000){fwrite(STDERR,"END_MISSING\n");exit(9);}
if(!is_file($backup)&&@file_put_contents($backup,$source,LOCK_EX)!==strlen($source)){fwrite(STDERR,"BACKUP_FAILED\n");exit(10);}
$banner=<<<'HTML'

<figure id="bmd-banner-visual-20261031" style="box-sizing:border-box;width:calc(100% - 40px);max-width:850px;margin:26px auto 36px;padding:0;text-align:center">
  <img src="/guia-visual-carrossel.webp" alt="Infográfico ilustrando imagem panorâmica dividida em slides, formatos 1:1, 4:5 e 16:9 e etapas de criação do carrossel" width="1536" height="1024" loading="lazy" decoding="async" fetchpriority="low" style="display:block;width:100%;height:auto;border-radius:14px;border:1px solid #e7eaf0;background:#f5f5ff" />
  <figcaption style="font:400 13px/1.6 Arial,sans-serif;color:#667085;text-align:left;padding:10px 4px 0">Guia visual de formatos e divisão em slides. Para conferir medidas e recortes de arquivos originais, <a href="/exemplos-de-corte.html" style="color:#4f46e5;font-weight:700">veja os exemplos reais para baixar</a>.</figcaption>
</figure>
HTML;
$new=substr_replace($source,$banner,$end+strlen('</section>'),0);
$tmp=$path.'.bmd-banner-tmp';
if(@file_put_contents($tmp,$new,LOCK_EX)!==strlen($new)){fwrite(STDERR,"WRITE_FAILED\n");exit(11);}
@chmod($tmp,fileperms($path)&0777);
if(!@rename($tmp,$path)){fwrite(STDERR,"RENAME_FAILED\n");exit(12);}
echo "INSERTED_AFTER_TOOLS old=".strlen($source)." new=".strlen($new)." webp=".filesize($asset)."\n";
