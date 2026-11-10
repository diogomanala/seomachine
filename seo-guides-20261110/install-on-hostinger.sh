#!/bin/bash
# One-time WordPress publisher for verified BMD articles. No deploy of WordPress root.
set -euo pipefail
H=/home/u783725167
WP=$H/domains/blogdemarketingdigital.com.br/public_html
PHP=/opt/alt/php82/usr/bin/php
BASE=https://raw.githubusercontent.com/diogomanala/seomachine/d1f3ad3f2cbf06376a93f15b5b2ca0e9aa064144/seo-guides-20261110
curl --fail --silent --show-error --location --max-time 35 "$BASE/google-reviews.html" -o "$H/bmd-google-reviews-20261110.html"
curl --fail --silent --show-error --location --max-time 35 "$BASE/whatsapp-username.html" -o "$H/bmd-whatsapp-username-20261110.html"
curl --fail --silent --show-error --location --max-time 35 "$BASE/publish-cli.php" -o "$H/bmd-seo-publish-20261110.php"
curl --fail --silent --show-error --location --max-time 35 "$BASE/bmd-new-guides-style.php" -o "$H/bmd-seo-style-20261110.php"
test "$(wc -c < "$H/bmd-google-reviews-20261110.html")" -gt 8500
test "$(wc -c < "$H/bmd-whatsapp-username-20261110.html")" -gt 8500
$PHP -l "$H/bmd-seo-publish-20261110.php"
$PHP -l "$H/bmd-seo-style-20261110.php"
cp "$H/bmd-seo-style-20261110.php" "$WP/wp-content/mu-plugins/bmd-editorial-guides-20261110.php"
$PHP "$H/bmd-seo-publish-20261110.php"
echo "BMD-GUIDES: script completed"
