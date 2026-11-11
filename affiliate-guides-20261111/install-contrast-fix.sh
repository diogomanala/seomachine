#!/bin/bash
set -euo pipefail
P=/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-content/mu-plugins
SRC=https://raw.githubusercontent.com/diogomanala/seomachine/6f907bfb108b1858eab48514fb4836c5039a24e3/affiliate-guides-20261111/bmd-affiliates-core.php
TMP=$(mktemp)
trap 'rm -f "$TMP"' EXIT
curl -fsSL --max-time 30 "$SRC" -o "$TMP"
test "$(wc -c < "$TMP")" -gt 14500
grep -q 'bmd-aff-hero-title-contrast' "$TMP"
/opt/alt/php82/usr/bin/php -l "$TMP"
cp "$P/bmd-affiliates-core.php" "$P/bmd-affiliates-core.php.bak-20261111-contrast"
install -m 644 "$TMP" "$P/bmd-affiliates-core.php"
echo 'BMD_HERO_WHITE_DEPLOYED'
