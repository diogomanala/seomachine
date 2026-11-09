<?php
/**
 * RDC product sitemap-only patch, for odontonegocios.com.
 * Keeps existing entries, refuses invalid data, stores an out-of-webroot backup.
 */
$path = '/home/u592724545/domains/odontonegocios.com/public_html/sitemap.xml';
$backup = '/home/u592724545/rdc-sitemap-before-20261109.xml';
$url = 'https://odontonegocios.com/rdc/';
if (!is_file($path) || !is_readable($path) || !is_writable($path)) {
    echo "ERROR:sitemap-not-accessible\n"; exit(1);
}
$xml = file_get_contents($path);
if ($xml === false) { echo "ERROR:read-failed\n"; exit(1); }
if (strpos($xml, $url) !== false) { echo "OK:already-present\n"; exit(0); }
if (substr_count($xml, '</urlset>') !== 1 || strpos($xml, '<urlset') === false) {
    echo "ERROR:unexpected-xml\n"; exit(1);
}
if (!is_file($backup) && !copy($path, $backup)) { echo "ERROR:backup-failed\n"; exit(1); }
$entry = "  <url>\n    <loc>".$url."</loc>\n  </url>\n";
$new = str_replace('</urlset>', $entry.'</urlset>', $xml);
if (!preg_match('~<urlset\\b~', $new) || strpos($new, $url) === false) {
    echo "ERROR:xml-test-failed\n"; exit(1);
}
$result = file_put_contents($path, $new, LOCK_EX);
if ($result !== strlen($new)) {
    echo "ERROR:write-failed\n"; exit(1);
}
echo "OK:sitemap-updated\n";
