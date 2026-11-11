#!/bin/bash
set -euo pipefail
DIR=/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-content/mu-plugins
mkdir -p "$DIR"
SRC=https://raw.githubusercontent.com/diogomanala/seomachine/2ba245d8a4dfdfc7b7f559751f3b42b44bfb57fb/affiliate-guides-20261111/premium-enhancement.php
TMP=$(mktemp)
trap 'rm -f "$TMP"' EXIT
curl -fsSL --max-time 35 "$SRC" -o "$TMP"
test "$(wc -c < "$TMP")" -gt 12000
/opt/alt/php82/usr/bin/php -l "$TMP"
cp "$TMP" "$DIR/bmd-affiliate-premium-20261111.php"
echo "BMD-AFF-PREMIUM:INSTALLED"
