#!/bin/bash
set -euo pipefail
H=/home/u783725167
WP=$H/domains/blogdemarketingdigital.com.br/public_html
PHP=/opt/alt/php82/usr/bin/php
TMP=$(mktemp -d)
trap 'rm -rf "$TMP"' EXIT
curl -fsSL --retry 2 --max-time 35 'https://raw.githubusercontent.com/diogomanala/seomachine/7d15f3a869a150c03f127845485f5251d82b3227/affiliate-guides-20261111/bmd-affiliates-core.php' -o "$TMP/core.php"
curl -fsSL --retry 2 --max-time 35 'https://raw.githubusercontent.com/diogomanala/seomachine/7d15f3a869a150c03f127845485f5251d82b3227/affiliate-guides-20261111/migrate-v2.php' -o "$TMP/migrate.php"
curl -fsSL --retry 2 --max-time 35 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-cd1c-820e-b549-2c124946ac2d/raw?se=2026-11-11T18%3A04%3A08Z&sp=r&sv=2026-06-06&sr=b&scid=9059a407-02e6-5e2d-bf96-c3fbe26cb218&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T10%3A12%3A51Z&ske=2026-11-12T10%3A12%3A51Z&sks=b&skv=2026-06-06&sig=q4qycGZaL3h4rRcUASZJ7u1mUHXo6vGy2A4E/z/VNWM%3D' -o "$TMP/bmd-affiliates-style.css"
test "$(wc -c < "$TMP/bmd-affiliates-style.css")" -eq 12148
curl -fsSL --retry 2 --max-time 35 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-1d2c-820e-99a9-90bbb74e277b/raw?se=2026-11-11T18%3A04%3A08Z&sp=r&sv=2026-06-06&sr=b&scid=7c972cc6-16b1-568b-ab5a-1712343dad16&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T02%3A21%3A22Z&ske=2026-11-12T02%3A21%3A22Z&sks=b&skv=2026-06-06&sig=ynsV7/ngcl1TkwFV7JXuqkpeyFB0msTJTPiQ%2BOdSgCg%3D' -o "$TMP/bmd-aff-repaired-content.html"
test "$(wc -c < "$TMP/bmd-aff-repaired-content.html")" -eq 6339
curl -fsSL --retry 2 --max-time 35 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-32dc-820e-9fea-dca731c3e672/raw?se=2026-11-11T18%3A04%3A08Z&sp=r&sv=2026-06-06&sr=b&scid=f52f47a9-9d06-5a56-be17-972bf25ddf0f&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T14%3A00%3A24Z&ske=2026-11-12T14%3A00%3A24Z&sks=b&skv=2026-06-06&sig=%2BKkmZSrcVOhciDEEwWPWmM65/Q3vUBYQVm%2B9dwgmLPA%3D' -o "$TMP/bmd_microfones_capa_editorial_2026.webp"
test "$(wc -c < "$TMP/bmd_microfones_capa_editorial_2026.webp")" -eq 61282
curl -fsSL --retry 2 --max-time 35 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-d2f0-820e-b83a-37ff5b55abcb/raw?se=2026-11-11T18%3A04%3A08Z&sp=r&sv=2026-06-06&sr=b&scid=561d8fb2-ad8b-5075-85f5-97e37429c474&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T02%3A15%3A04Z&ske=2026-11-12T02%3A15%3A04Z&sks=b&skv=2026-06-06&sig=kJLvpvQaEZYBy/LzTiQ%2B2SveqO4OuWfYFIDc6ezXDLM%3D' -o "$TMP/bmd_microfone_gravacao_reels_2026.webp"
test "$(wc -c < "$TMP/bmd_microfone_gravacao_reels_2026.webp")" -eq 55226
$PHP -l "$TMP/core.php"
$PHP -l "$TMP/migrate.php"
install -m 644 "$TMP/bmd-affiliates-style.css" "$WP/wp-content/mu-plugins/bmd-affiliates-style.css"
install -m 644 "$TMP/core.php" "$WP/wp-content/mu-plugins/bmd-affiliates-core.php"
install -m 644 "$TMP/bmd-aff-repaired-content.html" "$H/bmd-aff-repaired-content.html"
install -m 644 "$TMP/bmd_microfones_capa_editorial_2026.webp" "$H/bmd_microfones_capa_editorial_2026.webp"
install -m 644 "$TMP/bmd_microfone_gravacao_reels_2026.webp" "$H/bmd_microfone_gravacao_reels_2026.webp"
$PHP "$TMP/migrate.php"
echo "DEPLOY:COMPLETE"
