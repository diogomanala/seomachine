#!/bin/bash
set -euo pipefail
H=/home/u783725167
WP="$H/domains/blogdemarketingdigital.com.br/public_html"
MU="$WP/wp-content/mu-plugins"
BASE="https://raw.githubusercontent.com/diogomanala/seomachine/61c160de69c7a61629ff678e134196a86b919ff5/affiliate-kit-reels-20261112"
mkdir -p "$MU"
curl -fLsS --max-time 35 "$BASE/bmd-kit-reels.php" -o "$H/bmd-kit-reels.tmp.php"
curl -fLsS --max-time 35 "$BASE/bmd-kit-reels.css" -o "$H/bmd-kit-reels.tmp.css"
curl -fLsS --max-time 35 "$BASE/bmd-kit-reels.js" -o "$H/bmd-kit-reels.tmp.js"
curl -fLsS --max-time 35 "$BASE/publish-kit-cli.php" -o "$H/bmd-kit-publish.tmp.php"
curl -fLsS --max-time 35 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-2030-820e-89fc-ce4315ac5c28/raw?se=2026-11-12T10%3A02%3A15Z&sp=r&sv=2026-06-06&sr=b&scid=0b3d263a-3c73-5f7c-aa2e-1f12d6f81256&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-12T01%3A24%3A14Z&ske=2026-11-13T01%3A24%3A14Z&sks=b&skv=2026-06-06&sig=XHyEtvK4gqOQOT1k5DN5IM%2Bg3EjJAdsSaioswPoOp98%3D' -o "$H/bmd-reels-kit-cover.webp"
test "$(wc -c < "$H/bmd-reels-kit-cover.webp")" -gt 30000
test "$(wc -c < "$H/bmd-kit-reels.tmp.php")" -gt 16000
test "$(wc -c < "$H/bmd-kit-reels.tmp.css")" -gt 14000
test "$(wc -c < "$H/bmd-kit-reels.tmp.js")" -gt 7200
/opt/alt/php82/usr/bin/php -l "$H/bmd-kit-reels.tmp.php"
/opt/alt/php82/usr/bin/php -l "$H/bmd-kit-publish.tmp.php"
install -m 644 "$H/bmd-kit-reels.tmp.css" "$MU/bmd-kit-reels.css"
install -m 644 "$H/bmd-kit-reels.tmp.js" "$MU/bmd-kit-reels.js"
install -m 644 "$H/bmd-kit-reels.tmp.php" "$MU/bmd-kit-reels.php"
/opt/alt/php82/usr/bin/php "$H/bmd-kit-publish.tmp.php"
echo "BMD-KIT-REELS:END"
