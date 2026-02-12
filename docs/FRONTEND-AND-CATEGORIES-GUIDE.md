# Bagisto: Front template, categories & products – where they come from

This guide explains where categories and products come from, how the shop front is built, and how you can change the front template or make the storefront richer (e.g. more like Alibaba).

---

## 1. Where categories and products come from

### Categories

- **Data**: Stored in the database. Admin manages them at **Catalog → Categories** (`CategoryController`).
- **Tree**: The shop header and home use the **category tree** for the current channel:
  - **API**: `GET shop.api.categories.tree` → used by the desktop/mobile header to show the category menu.
  - **Home**: `HomeController` loads the visible category tree for the channel’s root category and passes `$categories` to the home view.
- **Listing**: Category page (e.g. `/category/{slug}`) uses **shop** views under `packages/Webkul/Shop/src/Resources/views/categories/` and gets products via `shop.api.products.index` with `category_id`.

So: **categories come from Admin (Catalog → Categories)** and are shown on the front via the category tree API and the category repository.

### Products

- **Data**: Stored in the database. Admin manages them at **Catalog → Products**.
- **Shop**: Products are shown via:
  - **Home**: “Theme customizations” of type **Product Carousel** and **Category Carousel** (see below).
  - **Category page**: Products filtered by category (API above).
  - **Search**: `shop.search.index` and product API with filters.

So: **products come from Admin (Catalog → Products)** and are displayed on home (carousels), category pages, and search.

---

## 2. Why the homepage looks “simple”

The **homepage content is fully driven by “Theme customizations”** (order, type, and options).  
If you only have a few sections or didn’t configure them, the home will look minimal.

- **File**: `packages/Webkul/Shop/src/Resources/views/home/index.blade.php`
- **Logic**: `packages/Webkul/Shop/src/Http/Controllers/HomeController.php`  
  It loads `$customizations` (from DB) and `$categories` (from category tree), then renders the home view.
- **Types** used on home:
  - **Image carousel** – hero sliders
  - **Static content** – HTML/CSS blocks (e.g. offers, top collections)
  - **Category carousel** – categories (from API `shop.api.categories.index`)
  - **Product carousel** – products (from API `shop.api.products.index`)

So: **categories and products on the home page “come from” the same APIs/DB as above; what you see is controlled by how many and which theme customizations you add and enable.**

---

## 3. Can you change the front template?

**Yes.** You can:

- **Option A – Theme customizations (no code)**  
  In **Admin → Settings → Themes**, add/reorder/edit sections (image carousel, category carousel, product carousel, static content, footer links, services). This already gives you more sections and a richer home (e.g. multiple category/product blocks).

- **Option B – Override shop views with a custom theme**  
  Shop views live under the `shop` namespace (from package `Webkul\Shop`).  
  The default shop theme is configured in `config/themes.php`:
  - `views_path` for the default shop theme: `resources/themes/default/views`
  - View finder checks theme paths first, then package views.

  So you can **override any shop view** by putting a file in `resources/themes/default/views/` with the **same path as under the package**.  
  Package views are in:  
  `packages/Webkul/Shop/src/Resources/views/`  
  Examples:
  - Override home: create `resources/themes/default/views/home/index.blade.php` (replaces `shop::home.index`).
  - Override header: create `resources/themes/default/views/components/layouts/header/desktop/bottom.blade.php`, etc.
  - Override category page: copy structure from `packages/Webkul/Shop/src/Resources/views/categories/` into `resources/themes/default/views/categories/`.

- **Option C – New theme**  
  You can add a new theme in `config/themes.php` (new key under `themes.shop`) with its own `views_path` and use it for a channel so the front uses that theme’s views.

So: **categories and products are still loaded the same way; only the template (view) that renders them changes when you override or add a theme.**

---

## 3b. Changing the frontend theme/template (included setup)

- **Default theme** now uses a custom layout with a **Modern** look (teal accent, header border, primary buttons, footer gradient). Override: `resources/themes/default/views/components/layouts/index.blade.php`.
- A second theme **"Modern"** is in `config/themes.php`. To use it: **Admin → Settings → Channels → Edit your channel → Theme → select "Modern" → Save**.
- To tweak the look: edit that layout file or add **Configuration → General → Content → Custom CSS**.

---

## 4. Key files for a “complete” storefront (categories + products)

| What | Where |
|------|--------|
| **Home content** | `packages/Webkul/Shop/src/Resources/views/home/index.blade.php` (or override in `resources/themes/default/views/home/index.blade.php`) |
| **Header + category menu** | `packages/Webkul/Shop/src/Resources/views/components/layouts/header/desktop/bottom.blade.php` (categories from `shop.api.categories.tree`) |
| **Category listing page** | `packages/Webkul/Shop/src/Resources/views/categories/view.blade.php` + `filters.blade.php`, `toolbar.blade.php` |
| **Product/category carousels** | `packages/Webkul/Shop/src/Resources/views/components/categories/carousel.blade.php`, `packages/Webkul/Shop/src/Resources/views/components/products/carousel.blade.php` |
| **Theme customization types** | `packages/Webkul/Theme/src/Models/ThemeCustomization.php` (e.g. `IMAGE_CAROUSEL`, `CATEGORY_CAROUSEL`, `PRODUCT_CAROUSEL`, `STATIC_CONTENT`) |
| **Admin theme UI** | **Settings → Themes** (ThemeController, ThemeDataGrid) |
| **Admin categories** | **Catalog → Categories** (`packages/Webkul/Admin/src/Http/Controllers/Catalog/CategoryController.php`) |
| **Admin products** | **Catalog → Products** |

---

## 5. Making it more “Alibaba-like” (more categories and products on the front)

1. **Add categories and products in Admin**  
   Use **Catalog → Categories** and **Catalog → Products** so you have a real tree and products assigned to categories.

2. **Use more theme customizations**  
   In **Settings → Themes**:
   - Add several **Category carousel** sections (e.g. “Main categories”, “Subcategories”, etc.) with different filters (parent_id, limit, sort).
   - Add several **Product carousel** sections (e.g. “New”, “Featured”, “By category”) with different filters (new, featured, category, limit).
   - Use **Static content** for banners or text blocks between carousels.

3. **Enrich the header**  
   The header already shows the category tree from `shop.api.categories.tree`. If the tree is populated in Admin, you’ll get multi-level menus. You can customize the look by overriding:
   - `resources/themes/default/views/components/layouts/header/desktop/bottom.blade.php`
   - and related header components.

4. **Customize the home layout**  
   Copy `packages/Webkul/Shop/src/Resources/views/home/index.blade.php` to `resources/themes/default/views/home/index.blade.php` and change the layout (e.g. add a prominent category grid, more product sections, different order of sections). The same `$customizations` and `$categories` are available; you’re just changing the HTML/structure.

5. **Category page**  
   Category pages already show products and filters. To change layout or add more “category” feel, override views under `resources/themes/default/views/categories/` (e.g. `view.blade.php`, `filters.blade.php`).

---

## 6. Quick fix: "No categories yet" / "No products yet"

- **Seed sample data (categories + products) in one go:**
  ```bash
  php artisan bagisto:seed-sample-data
  ```
  This runs sample categories then sample products (requires Bagisto to be installed so attributes/channel exist).

- **Or run separately:**
  - **Sample categories only:** `php artisan bagisto:seed-sample-categories` — adds e.g. "Men", "Winter Wear".
  - **Sample products only:** `php artisan bagisto:seed-sample-products` — adds multiple sample products (wipes existing products). Run after categories exist.

- **Add more manually:** **Admin** → **Catalog** → **Categories** and **Catalog** → **Products**.

---

## 7. Summary

- **Categories**: From **Admin → Catalog → Categories**; shown in header (category tree API) and on home (category carousels) and category pages.
- **Products**: From **Admin → Catalog → Products**; shown on home (product carousels), category pages, and search.
- **Front template**: Yes, you can change it – via **Theme customizations** (Admin) and/or **view overrides** in `resources/themes/default/views/` (same path as in `packages/Webkul/Shop/src/Resources/views/`).
- To get a “complete” ecommerce look with more categories and products on the front: fill Categories and Products in Admin, then add and configure more Theme customizations (category/product carousels, static content) and optionally override the home and header views in your theme.
