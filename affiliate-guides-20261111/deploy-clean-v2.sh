#!/bin/bash
set -euo pipefail
H=/home/u783725167
WP="$H/domains/blogdemarketingdigital.com.br/public_html"
P="$WP/wp-content/mu-plugins"
BASE="https://raw.githubusercontent.com/diogomanala/seomachine/7ca751471377cbb298e82f7cd73da8d179d39851/affiliate-guides-20261111"
PHP=/opt/alt/php82/usr/bin/php
curl -fsSL --max-time 35 "$BASE/clean-v2.html" -o "$H/bmd-aff-clean-v2-20261111.html"
curl -fsSL --max-time 35 "$BASE/clean-v2-theme.php" -o "$H/bmd-aff-clean-v2-theme.tmp.php"
curl -fsSL --max-time 35 "$BASE/clean-v2-apply.php" -o "$H/bmd-aff-clean-v2-apply.tmp.php"
test "$(wc -c < "$H/bmd-aff-clean-v2-20261111.html")" -gt 12000
test "$(wc -c < "$H/bmd-aff-clean-v2-theme.tmp.php")" -gt 17000
$PHP -l "$H/bmd-aff-clean-v2-theme.tmp.php"
$PHP -l "$H/bmd-aff-clean-v2-apply.tmp.php"
cp "$H/bmd-aff-clean-v2-theme.tmp.php" "$P/bmd-affiliate-clean-v2-20261111.php"
$PHP "$H/bmd-aff-clean-v2-apply.tmp.php"
echo "BMD-AFF-REPAIR:END"
