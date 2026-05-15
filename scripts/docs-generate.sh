#!/bin/bash
set -e

DATE=$(date +"%Y-%m-%d")
mkdir -p docs/beheer/generated

echo "# Technische status" > docs/beheer/generated/technische-status.md
echo "" >> docs/beheer/generated/technische-status.md
echo "Laatst bijgewerkt: $DATE" >> docs/beheer/generated/technische-status.md
echo "" >> docs/beheer/generated/technische-status.md
echo "## Sites" >> docs/beheer/generated/technische-status.md
ddev wp site list --path=public --fields=blog_id,url,last_updated --format=table >> docs/beheer/generated/technische-status.md
echo "" >> docs/beheer/generated/technische-status.md
echo "## Plugins" >> docs/beheer/generated/technische-status.md
ddev wp plugin list --path=public --format=table >> docs/beheer/generated/technische-status.md
echo "" >> docs/beheer/generated/technische-status.md
echo "## Thema's" >> docs/beheer/generated/technische-status.md
ddev wp theme list --path=public --format=table >> docs/beheer/generated/technische-status.md

echo "✓ Documentatie gegenereerd"
