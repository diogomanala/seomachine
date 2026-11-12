#!/bin/bash
set -euo pipefail
H=/home/u783725167
MU="$H/domains/blogdemarketingdigital.com.br/public_html/wp-content/mu-plugins"
PHP=/opt/alt/php82/usr/bin/php
test -s "$H/bmd-kit-reels.tmp.php"
test -s "$H/bmd-kit-reels.tmp.css"
test -s "$H/bmd-kit-reels.tmp.js"
test -s "$H/bmd-kit-publish.tmp.php"
echo "DOWNLOAD-COVER"
curl -fLsS --max-time 35 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-b310-820e-a37e-98d784553487/raw?se=2026-11-12T10%3A13%3A22Z&sp=r&sv=2026-06-06&sr=b&scid=4cec3b94-588d-5360-8e25-a2f53e98841e&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-12T08%3A39%3A49Z&ske=2026-11-13T08%3A39%3A49Z&sks=b&skv=2026-06-06&sig=ye47CtbqTvRVEEHaJoVg0N3IL%2BnlWLYzv6dbIZYmFcM%3D' -o "$H/bmd-reels-kit-cover.webp"
test "$(wc -c < "$H/bmd-reels-kit-cover.webp")" -gt 30000
echo "VALIDATE-CODE"
$PHP -l "$H/bmd-kit-reels.tmp.php"
$PHP -l "$H/bmd-kit-publish.tmp.php"
echo "INSTALL-MODULE"
install -m 644 "$H/bmd-kit-reels.tmp.css" "$MU/bmd-kit-reels.css"
install -m 644 "$H/bmd-kit-reels.tmp.js" "$MU/bmd-kit-reels.js"
install -m 644 "$H/bmd-kit-reels.tmp.php" "$MU/bmd-kit-reels.php"
echo "PUBLISH"
$PHP "$H/bmd-kit-publish.tmp.php"
echo "BMD-REELS-DONE"
