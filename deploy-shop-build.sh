#!/usr/bin/env bash
# Deploy Shop theme build to fix 404s for CSS/logo on live server.
# Run from project root: ./deploy-shop-build.sh [user@host:path]

set -e
PROJECT_ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cd "$PROJECT_ROOT"

echo "==> Rebuilding Shop theme..."
(cd packages/Webkul/Shop && npm run build)

BUILD_DIR="public/themes/shop/default/build"
if [ ! -f "$BUILD_DIR/manifest.json" ]; then
  echo "ERROR: $BUILD_DIR/manifest.json not found. Build may have failed."
  exit 1
fi

echo "==> Build OK. Contents:"
ls -la "$BUILD_DIR/assets/" | head -20

REMOTE="${1:-}"
if [ -n "$REMOTE" ]; then
  echo "==> Syncing build to $REMOTE ..."
  rsync -avz --delete "$BUILD_DIR/" "$REMOTE/$BUILD_DIR/"
  echo "==> Done. Run on server: php artisan view:clear && php artisan cache:clear"
else
  echo "==> To sync to server, run:"
  echo "    ./deploy-shop-build.sh user@82.180.132.134:/path/to/bagisto-master"
  echo "    Then on server: php artisan view:clear && php artisan cache:clear"
fi
