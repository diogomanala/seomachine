#!/bin/bash
set -euo pipefail
URL=https://raw.githubusercontent.com/diogomanala/seomachine/2b7d9e7f575f676eea27ca49a1cc72d704f78e14/affiliate-guides-20261111/bmd-aff-internal-links.php
TARGET=/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-content/mu-plugins/bmd-aff-internal-links.php
TMP=$(mktemp)
trap 'rm -f "$TMP"' EXIT
curl -fsSL --max-time 30 "$URL" -o "$TMP"
/opt/alt/php82/usr/bin/php -l "$TMP"
test "$(wc -c < "$TMP")" -gt 1500
install -m 644 "$TMP" "$TARGET"
echo "BMD_INTERNAL_LINKS_INSTALLED"
