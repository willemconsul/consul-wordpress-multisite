#!/bin/bash
set -e

URLS=(
  "https://consul-wp-ms.ddev.site"
  "https://12t.consul-wp-ms.ddev.site"
  "https://ctb.consul-wp-ms.ddev.site"
  "https://mki.consul-wp-ms.ddev.site"
)

echo "=== Smoke tests ==="
for URL in "${URLS[@]}"; do
  echo "Test $URL"
  curl -k -s -I "$URL" | head -1
done

echo "✓ Smoke tests geslaagd"
