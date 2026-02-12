# Fixing 404s for Shop Assets and Images on Live Server

## Why you see these errors

- **`/themes/shop/default/build/assets/app-CleEPv5e.css`** or **`logo-En7xVNLI.png`** → 404  
  The live server is using an **old** Shop theme build. After changing the logo and rebuilding, the correct files are:
  - `app-CPtPcCEQ.css` / `app-DsP8OK1c.css` (CSS)
  - `logo-B5mD-7ei.png` (logo)
  The HTML is still asking for the old filenames because the **build folder on the server was not updated**.

- **`/cache/large/category/...`** or **`/cache/medium/product/...`** → 404  
  Bagisto serves resized images from `public/cache/`. These 404s mean either the cache was never generated on the server or the original images are missing in `storage/`.

---

## 1. Fix CSS and logo 404s (Shop theme build)

**On your local machine** (where you already ran the build):

1. Rebuild the Shop theme (optional, only if you changed assets again):
   ```bash
   cd packages/Webkul/Shop && npm run build
   ```

2. Upload the **entire** Shop build folder to the server so it **replaces** the existing one:
   - **Local folder:** `public/themes/shop/default/build/`
   - **Server path:** `public/themes/shop/default/build/` (same path inside your project root on 82.180.132.134)

   Or use the script (from project root):
   ```bash
   chmod +x deploy-shop-build.sh
   ./deploy-shop-build.sh user@82.180.132.134:/path/to/bagisto-master
   ```

   Make sure these exist on the server:
   - `public/themes/shop/default/build/manifest.json`
   - `public/themes/shop/default/build/assets/app-*.css` (e.g. `app-DTQxKFqW.css`)
   - `public/themes/shop/default/build/assets/logo-B5mD-7ei.png`
   - and all other files under `public/themes/shop/default/build/assets/`

3. Clear any **server-side** view/cache after deploying:
   ```bash
   php artisan view:clear
   php artisan cache:clear
   ```

4. **On the server** (after uploading the build), run:
   ```bash
   php artisan view:clear && php artisan cache:clear
   ```

5. Hard-refresh the browser (Ctrl+Shift+R / Cmd+Shift+R) so it doesn’t use cached HTML/CSS.

After this, the app will read the new `manifest.json` and request the new CSS and logo; 404s for those assets should stop.

---

## 2. Fix category/product image 404s (cache)

- Ensure `storage/app/public` is linked on the server:
  ```bash
  php artisan storage:link
  ```
- Ensure category and product images exist under `storage/app/public/` (e.g. from seeders or uploads).
- Generate image cache (if your Bagisto version supports it), or re-save categories/products in Admin so cache is created. Cache lives under `public/cache/` (e.g. `cache/large/category/`, `cache/medium/product/`).

If you don’t need real images for now, the frontend will show placeholders when the image URLs 404; fixing the **build** (step 1) is what stops the CSS and logo from breaking the layout.
