#!/bin/bash
set -euo pipefail
H=/home/u783725167
WP=$H/domains/blogdemarketingdigital.com.br/public_html
DATA=$H/bmd_ps5_gta6
TMP=$(mktemp -d)
trap 'rm -rf "$TMP"' EXIT
PHP=/opt/alt/php82/usr/bin/php
test -s "$WP/wp-content/mu-plugins/bmd-affiliates-core.php"
test -s "$WP/wp-content/mu-plugins/bmd-aff-questions.php"
curl -fsSL --retry 2 --max-time 45 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-e09c-820e-9b06-59f1c83eeb29/raw?se=2026-11-11T19%3A41%3A33Z&sp=r&sv=2026-06-06&sr=b&scid=244a1073-de63-50a5-83e7-daaa526e0d2f&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T16%3A45%3A58Z&ske=2026-11-12T16%3A45%3A58Z&sks=b&skv=2026-06-06&sig=Do/lc534Ehm6GxAofSImI9R3qfKO1QoTWJtd97tnfBE%3D' -o "$TMP/article.html"
test "$(wc -c < "$TMP/article.html")" -eq 18563
curl -fsSL --retry 2 --max-time 45 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-2d70-820e-b2b0-0f53e0710a3d/raw?se=2026-11-11T19%3A41%3A33Z&sp=r&sv=2026-06-06&sr=b&scid=d83d562b-848a-532e-a485-6a452570d453&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T17%3A26%3A52Z&ske=2026-11-12T17%3A26%3A52Z&sks=b&skv=2026-06-06&sig=6CJjukXk3e9kDunuZ604rOWoRFM4OcwicWfF/1niOOM%3D' -o "$TMP/gta6-components.php"
test "$(wc -c < "$TMP/gta6-components.php")" -eq 7462
curl -fsSL --retry 2 --max-time 45 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-0700-820e-bb19-501b20ac1d6a/raw?se=2026-11-11T19%3A41%3A33Z&sp=r&sv=2026-06-06&sr=b&scid=6f4ea410-7b97-5889-9f4f-412ca105e8dd&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T16%3A45%3A58Z&ske=2026-11-12T16%3A45%3A58Z&sks=b&skv=2026-06-06&sig=n/7EKTbakXmz24SQBryRZX1WLczvuS8jv84BLb5kJuU%3D' -o "$TMP/gta6-premium.css"
test "$(wc -c < "$TMP/gta6-premium.css")" -eq 12901
curl -fsSL --retry 2 --max-time 45 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-7fb8-820e-9d5b-cafc4c88b8dc/raw?se=2026-11-11T19%3A41%3A33Z&sp=r&sv=2026-06-06&sr=b&scid=80d791e2-5125-5385-99ab-747190fb311c&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T12%3A31%3A51Z&ske=2026-11-12T12%3A31%3A51Z&sks=b&skv=2026-06-06&sig=TJa0GGS73iC99i7LyaP%2B%2B8xXdO2AP6qmLM4mY5syq3s%3D' -o "$TMP/publish.php"
test "$(wc -c < "$TMP/publish.php")" -eq 8524
curl -fsSL --retry 2 --max-time 45 'https://sdmntprbrazilsouth.oaiusercontent.com/files/00000000-3e68-820e-b954-9dc38d1e505d/raw?se=2026-11-11T19%3A41%3A33Z&sp=r&sv=2026-06-06&sr=b&scid=a47df928-82b8-586f-b8fd-a8a4a4f84af7&skoid=68f37fa7-4e08-478b-93f3-c72d563a0ab3&sktid=a48cca56-e6da-484e-a814-9c849652bcb3&skt=2026-11-11T10%3A36%3A43Z&ske=2026-11-12T10%3A36%3A43Z&sks=b&skv=2026-06-06&sig=93%2BETWdDgzOGq7oFy5Bj4oQ%2B8DxN1vX/IBxe/5ap6TE%3D' -o "$TMP/cover.webp"
test "$(wc -c < "$TMP/cover.webp")" -eq 81522
$PHP -l "$TMP/gta6-components.php"
$PHP -l "$TMP/publish.php"
grep -q '212 GB' "$TMP/article.html"
grep -q 'bmd-gta-advisor' "$TMP/gta6-components.php"
mkdir -p "$DATA"
install -m 644 "$TMP/article.html" "$DATA/article.html"
install -m 644 "$TMP/cover.webp" "$DATA/cover.webp"
install -m 644 "$TMP/gta6-premium.css" "$WP/wp-content/mu-plugins/bmd-gta6-premium.css"
install -m 644 "$TMP/gta6-components.php" "$WP/wp-content/mu-plugins/bmd-gta6-components.php"
curl -fsSL --retry 2 --max-time 35 'https://raw.githubusercontent.com/diogomanala/seomachine/c61ad86a05e357eec3d2f119a97dd89124d7553b/affiliate-guides-20261111/bmd-affiliates-core.php' -o "$TMP/core.php"
test "$(wc -c < "$TMP/core.php")" -gt 15000
$PHP -l "$TMP/core.php"
cp "$WP/wp-content/mu-plugins/bmd-affiliates-core.php" "$WP/wp-content/mu-plugins/bmd-affiliates-core.php.bak-20261111-gta"
install -m 644 "$TMP/core.php" "$WP/wp-content/mu-plugins/bmd-affiliates-core.php"
$PHP "$TMP/publish.php"
echo 'BMD_GTA6_DEPLOY_COMPLETE'
