#!/bin/bash
set -euo pipefail
P=/home/u783725167/domains/blogdemarketingdigital.com.br/public_html/wp-content/mu-plugins
PHP=/opt/alt/php82/usr/bin/php
TMP=$(mktemp -d)
trap 'rm -rf "$TMP"' EXIT
curl -fsSL --max-time 35 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-c080-820e-875a-9119c5949504/raw?se=2026-11-11T19%3A46%3A45Z&sp=r&sv=2026-06-06&sr=b&scid=a5719487-f276-57be-81c6-d56cf1c0493a&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T16%3A45%3A46Z&ske=2026-11-12T16%3A45%3A46Z&sks=b&skv=2026-06-06&sig=R8ZDDMixYhuqmnOnDqxYbPXPuhvYXZczA1se8R5C9Is%3D' -o "$TMP/gta6-components.php"
curl -fsSL --max-time 35 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-5f98-820e-a465-fd855255aff9/raw?se=2026-11-11T19%3A46%3A46Z&sp=r&sv=2026-06-06&sr=b&scid=020e3e21-bb33-5e3a-bfec-9618e1aaeccf&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T11%3A39%3A54Z&ske=2026-11-12T11%3A39%3A54Z&sks=b&skv=2026-06-06&sig=oS3pwSJ%2BCEWaVxIMjRJyVq6R2S25SbnNW742FBt/e%2Bs%3D' -o "$TMP/fix-checklist.php"
test "$(wc -c < "$TMP/gta6-components.php")" -eq 8846
test "$(wc -c < "$TMP/fix-checklist.php")" -eq 1447
$PHP -l "$TMP/gta6-components.php"
$PHP -l "$TMP/fix-checklist.php"
cp "$P/bmd-gta6-components.php" "$P/bmd-gta6-components.php.bak-20261111-checklist"
install -m 644 "$TMP/gta6-components.php" "$P/bmd-gta6-components.php"
$PHP "$TMP/fix-checklist.php"
echo BMD_GTA6_CHECKLIST_DEPLOYED
