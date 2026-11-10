#!/bin/bash
set -euo pipefail
H=/home/u783725167
WP=$H/domains/blogdemarketingdigital.com.br/public_html
PHP=/opt/alt/php82/usr/bin/php
BASE=https://raw.githubusercontent.com/diogomanala/seomachine/59d26a8e2f987b1f723db36c938453aa7565dafe/growth-guides-20261110
curl --fail --silent --show-error --location --max-time 35 "$BASE/instagram-dm-troubleshoot.html" -o "$H/bmd-dm-troubleshoot-20261110.html"
curl --fail --silent --show-error --location --max-time 35 "$BASE/meta-ai-business.html" -o "$H/bmd-meta-ai-business-20261110.html"
curl --fail --silent --show-error --location --max-time 35 "$BASE/publish-cli.php" -o "$H/bmd-growth-publisher-20261110.php"
curl --fail --silent --show-error --location --max-time 35 "$BASE/editorial-style.php" -o "$H/bmd-growth-style-20261110.php"
test "$(wc -c < "$H/bmd-dm-troubleshoot-20261110.html")" -gt 10500
test "$(wc -c < "$H/bmd-meta-ai-business-20261110.html")" -gt 10500
$PHP -l "$H/bmd-growth-publisher-20261110.php"
$PHP -l "$H/bmd-growth-style-20261110.php"
cp "$H/bmd-growth-style-20261110.php" "$WP/wp-content/mu-plugins/bmd-growth-editorial-20261110.php"
$PHP "$H/bmd-growth-publisher-20261110.php"
echo "BMD-GROWTH:done"
