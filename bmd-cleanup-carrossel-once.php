<?php
// One-time cleanup of the exact temporary Git deployment created for this site's banner.
declare(strict_types=1);
$dir=__DIR__;
$expected='/domains/cortarimagenscarrossel.online/public_html/bmd-maintenance-carrossel';
if (basename($dir)!=='bmd-maintenance-carrossel' || substr($dir,-strlen($expected))!==$expected) {http_response_code(403);exit('WRONG_TARGET');}
if (!isset($_GET['confirm']) || !hash_equals('bmd-20261031-visualcleanup-8bdc92f47fae6c1249fb72ac',(string)$_GET['confirm'])) {http_response_code(404);exit('NOT_FOUND');}
if (!is_file($dir.'/tools/bmd-carrossel-banner-maintenance.php')) {http_response_code(409);exit('MARKER_MISSING');}
$root=dirname($dir);
if(!is_file($root.'/index.html')||!is_file($root.'/guia-visual-carrossel.webp')){http_response_code(409);exit('SITE_CHECK_FAILED');}
$before=file_get_contents($root.'/index.html');
if(!is_string($before)||strpos($before,'bmd-banner-visual-20261031')===false){http_response_code(409);exit('BANNER_NOT_LIVE');}
$it=new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir,FilesystemIterator::SKIP_DOTS),RecursiveIteratorIterator::CHILD_FIRST);
$failures=0;$removed=0;
foreach($it as $node) {
 $p=$node->getPathname();
 if($node->isLink()||$node->isFile()){if(@unlink($p))$removed++;else $failures++;}
 elseif($node->isDir()){if(@rmdir($p))$removed++;else $failures++;}
}
if(!@rmdir($dir))$failures++;
header('Content-Type: text/plain; charset=utf-8');
echo $failures?'CLEANUP_PARTIAL '.$failures:'CLEANUP_COMPLETE '.$removed;
