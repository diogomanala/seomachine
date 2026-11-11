#!/bin/bash
set -euo pipefail
TARGET=/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-content/mu-plugins/bmd-ps5-gta6-official-images.php
SRC='https://raw.githubusercontent.com/diogomanala/seomachine/6cf61cb3cc59d26bb552e28f71c4097ca0e37d8f/affiliate-guides-20261111/ps5-gta6-official-product-images.php'
TMP=$(mktemp)
trap 'rm -f "$TMP"' EXIT
curl -fsSL --max-time 35 "$SRC" -o "$TMP"
test "$(wc -c < "$TMP")" -gt 4500
grep -q 'bmd_ps5_product_imagery_is_target' "$TMP"
/opt/alt/php82/usr/bin/php -l "$TMP"
if test -f "$TARGET"; then cp "$TARGET" "$TARGET.backup-20261111"; fi
install -m 644 "$TMP" "$TARGET"
echo 'BMD_PS5_PRODUCT_IMAGES_INSTALLED'
