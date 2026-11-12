#!/bin/bash
set -euo pipefail
H=/home/u783725167
ROOT="$H/domains/blogdemarketingdigital.com.br/public_html"
MU="$ROOT/wp-content/mu-plugins"
PHP=/opt/alt/php82/usr/bin/php
BASE=https://raw.githubusercontent.com/diogomanala/seomachine/42e1df9b528167653a6a4612bde7ced8571f6b43/affiliate-kit-20261112
S=$(mktemp)
J=$(mktemp)
trap 'rm -f "$S" "$J"' EXIT
curl -fsSL --max-time 30 "$BASE/article.html" -o "$H/bmdkit26-article.html"
curl -fsSL --max-time 30 "$BASE/style.php" -o "$S"
curl -fsSL --max-time 30 "$BASE/script.php" -o "$J"
curl -fsSL --max-time 30 "$BASE/publish.php" -o "$H/bmdkit26-publish.php"
test "$(wc -c < "$H/bmdkit26-article.html")" -gt 10500
test "$(wc -c < "$S")" -gt 13000
test "$(wc -c < "$J")" -gt 8500
$PHP -l "$S"
$PHP -l "$J"
$PHP -l "$H/bmdkit26-publish.php"
cp "$S" "$MU/bmdkit26-style.php"
cp "$J" "$MU/bmdkit26-script.php"
$PHP "$H/bmdkit26-publish.php"
echo "BMD-KIT-DEPLOY:END"
