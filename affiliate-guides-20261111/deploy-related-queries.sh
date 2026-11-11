#!/bin/bash
set -euo pipefail
H=/home/u783725167
DIR="$H/domains/blogdemarketingdigital.com.br/public_html/wp-content/mu-plugins"
PHP=/opt/alt/php82/usr/bin/php
TMP=$(mktemp -d)
trap 'rm -rf "$TMP"' EXIT
curl -fsSL --retry 2 --max-time 35 "https://raw.githubusercontent.com/diogomanala/seomachine/3b91633d97135fd7287424acd045d53b3f52dccd/affiliate-guides-20261111/bmd-aff-questions.php" -o "$TMP/questions.php"
curl -fsSL --retry 2 --max-time 35 "https://raw.githubusercontent.com/diogomanala/seomachine/3b91633d97135fd7287424acd045d53b3f52dccd/affiliate-guides-20261111/bmd-aff-questions.css" -o "$TMP/questions.css"
curl -fsSL --retry 2 --max-time 35 "https://raw.githubusercontent.com/diogomanala/seomachine/3b91633d97135fd7287424acd045d53b3f52dccd/affiliate-guides-20261111/microfones-related-queries.html" -o "$TMP/related.html"
curl -fsSL --retry 2 --max-time 35 "https://raw.githubusercontent.com/diogomanala/seomachine/3b91633d97135fd7287424acd045d53b3f52dccd/affiliate-guides-20261111/migrate-related-queries.php" -o "$TMP/migrate.php"
test "$(wc -c < "$TMP/questions.php")" -gt 6000
test "$(wc -c < "$TMP/questions.css")" -gt 4500
test "$(wc -c < "$TMP/related.html")" -gt 6500
test "$(wc -c < "$TMP/migrate.php")" -gt 5000
$PHP -l "$TMP/questions.php"
$PHP -l "$TMP/migrate.php"
install -m 644 "$TMP/questions.css" "$DIR/bmd-aff-questions.css"
install -m 644 "$TMP/questions.php" "$DIR/bmd-aff-questions.php"
install -m 644 "$TMP/related.html" "$H/bmd-microfones-related-queries-20261111.html"
$PHP "$TMP/migrate.php"
echo "BMD_AFF_RELATED_DEPLOYED"
