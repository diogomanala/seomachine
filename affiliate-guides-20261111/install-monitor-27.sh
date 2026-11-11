#!/bin/bash
set -euo pipefail
H=/home/u783725167
WP=$H/domains/blogdemarketingdigital.com.br/public_html
DEST=$H/bmd_monitor_27
PHP=/opt/alt/php82/usr/bin/php
TMP=$(mktemp -d)
trap 'rm -rf "$TMP"' EXIT
test -s "$WP/wp-content/mu-plugins/bmd-affiliates-core.php"
test -s "$WP/wp-content/mu-plugins/bmd-aff-questions.php"
curl -fsSL --retry 2 --max-time 40 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-89a4-820e-804d-bb0f74754451/raw?se=2026-11-11T19%3A07%3A02Z&sp=r&sv=2026-06-06&sr=b&scid=7a3dfb05-8618-51f5-8b7f-760beb641f5f&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T12%3A31%3A49Z&ske=2026-11-12T12%3A31%3A49Z&sks=b&skv=2026-06-06&sig=jTzNcjpFBIBp8lUM49YSD7w220lgno1wf%2B8qeD/718U%3D' -o "$TMP/cover.webp"
test "$(wc -c < "$TMP/cover.webp")" -eq 52164
curl -fsSL --retry 2 --max-time 40 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-f654-820e-9ec1-3e305b2fbcb8/raw?se=2026-11-11T19%3A07%3A02Z&sp=r&sv=2026-06-06&sr=b&scid=d9d969d0-63af-5636-b167-b77121b265f2&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T12%3A58%3A46Z&ske=2026-11-12T12%3A58%3A46Z&sks=b&skv=2026-06-06&sig=cLpXniKwU%2Bva%2BilotgT6T0w0CUnN4f2b0nIAC%2B0s0qY%3D' -o "$TMP/vesa.webp"
test "$(wc -c < "$TMP/vesa.webp")" -eq 26854
curl -fsSL --retry 2 --max-time 40 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-f748-820e-8bb4-3efcaa8907a6/raw?se=2026-11-11T19%3A07%3A02Z&sp=r&sv=2026-06-06&sr=b&scid=60af3e61-219d-5e8c-8b3d-351b0853fb56&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T13%3A17%3A59Z&ske=2026-11-12T13%3A17%3A59Z&sks=b&skv=2026-06-06&sig=4RcjjsPcAv/xbCRdrieWnx1oEK25Uw8uxNiLYobguVw%3D' -o "$TMP/article.html"
test "$(wc -c < "$TMP/article.html")" -eq 11092
curl -fsSL --retry 2 --max-time 40 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-bf24-820e-a044-f98019aac2e3/raw?se=2026-11-11T19%3A07%3A02Z&sp=r&sv=2026-06-06&sr=b&scid=9ea278eb-fbfd-5099-969f-2ae47ad6052b&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T12%3A58%3A47Z&ske=2026-11-12T12%3A58%3A47Z&sks=b&skv=2026-06-06&sig=XZ1cHxhOVE124j/iHG45PdlWMaglL%2Bh/8WOVCorlSc4%3D' -o "$TMP/checker.php"
test "$(wc -c < "$TMP/checker.php")" -eq 8908
$PHP -l "$TMP/checker.php"
mkdir -p "$DEST"
install -m 644 "$TMP/article.html" "$DEST/article.html"
install -m 644 "$TMP/cover.webp" "$DEST/cover.webp"
install -m 644 "$TMP/vesa.webp" "$DEST/vesa.webp"
install -m 644 "$TMP/checker.php" "$WP/wp-content/mu-plugins/bmd-monitor-checker.php"
curl -fsSL --max-time 35 'https://raw.githubusercontent.com/diogomanala/seomachine/47af8c55c93d97358d120c4678c4cc44ee365870/affiliate-guides-20261111/publish-monitor-27.php' -o "$TMP/publish.php"
$PHP -l "$TMP/publish.php"
$PHP "$TMP/publish.php"
echo 'BMD-MONITOR-ARTICLE-INSTALLED'
