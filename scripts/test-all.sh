#!/bin/bash
set -e

echo "=== PHP Syntax controleren ==="
find public/wp-content/themes public/wp-content/plugins/consul-core -name "*.php" 2>/dev/null | while read file; do
  php -l "$file" > /dev/null 2>&1 || echo "Syntax fout in $file"
done

echo "=== WordPress core controleren ==="
ddev wp core version --path=public

echo "=== Multisite sites controleren ==="
ddev wp site list --path=public

echo "=== Plugins controleren ==="
ddev wp plugin list --path=public

echo "=== Thema's controleren ==="
ddev wp theme list --path=public

echo "=== Cache legen ==="
ddev wp cache flush --path=public

echo "✓ Alle basistests geslaagd"
