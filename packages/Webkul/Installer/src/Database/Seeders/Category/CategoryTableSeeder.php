<?php

namespace Webkul\Installer\Database\Seeders\Category;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Http\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/*
 * Category table seeder.
 *
 * Command: php artisan db:seed --class=Webkul\\Category\\Database\\Seeders\\CategoryTableSeeder
 */
class CategoryTableSeeder extends Seeder
{
    /**
     * Base path for category placeholder images (reuse product seed images).
     */
    const IMAGE_BASE_PATH = 'packages/Webkul/Installer/src/Resources/assets/images/seeders/products/';
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        DB::table('categories')->delete();

        DB::table('category_translations')->delete();

        $now = Carbon::now();

        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        DB::table('categories')->insert([
            [
                'id'          => 1,
                'position'    => 1,
                'logo_path'   => null,
                'status'      => 1,
                '_lft'        => 1,
                '_rgt'        => 6,
                'parent_id'   => null,
                'banner_path' => null,
                'created_at'  => $now,
                'updated_at'  => $now,
            ],
        ]);

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        foreach ($locales as $locale) {
            DB::table('category_translations')->insert([
                [
                    'name'             => trans('installer::app.seeders.category.categories.name', [], $locale),
                    'slug'             => 'root',
                    'description'      => trans('installer::app.seeders.category.categories.description', [], $locale),
                    'meta_title'       => '',
                    'meta_description' => '',
                    'meta_keywords'    => '',
                    'category_id'      => '1',
                    'locale'           => $locale,
                ],
            ]);
        }
    }

    /**
     * Create Sample Categories.
     *
     * @return void
     */
    public function sampleCategories(array $parameters = [])
    {
        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');

        $now = Carbon::now();

        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        $logo2 = $this->storeCategoryLogo(2, '1.webp');
        $logo3 = $this->storeCategoryLogo(3, '2.webp');
        $logo4 = $this->storeCategoryLogo(4, '3.webp');

        if (DB::table('categories')->whereIn('id', [2, 3])->exists()) {
            $this->ensureCategoryLogosAndFourthCategory($parameters, $now, $logo2, $logo3, $logo4);
            return;
        }

        DB::table('categories')->where('id', 1)->update(['_rgt' => 8]);

        DB::table('categories')->insert([
            [
                'id'            => 2,
                'position'      => 1,
                'logo_path'     => $logo2,
                'status'        => 1,
                'display_mode'  => 'products_and_description',
                '_lft'          => 2,
                '_rgt'          => 5,
                'parent_id'     => 1,
                'additional'    => null,
                'banner_path'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,

            ], [
                'id'            => 3,
                'position'      => 1,
                'logo_path'     => $logo3,
                'status'        => 1,
                'display_mode'  => 'products_and_description',
                '_lft'          => 3,
                '_rgt'          => 4,
                'parent_id'     => 2,
                'additional'    => null,
                'banner_path'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,

            ], [
                'id'            => 4,
                'position'      => 2,
                'logo_path'     => $logo4,
                'status'        => 1,
                'display_mode'  => 'products_and_description',
                '_lft'          => 6,
                '_rgt'          => 7,
                'parent_id'     => 1,
                'additional'    => null,
                'banner_path'   => null,
                'created_at'    => $now,
                'updated_at'    => $now,

            ],
        ]);

        foreach ($locales as $locale) {
            DB::table('category_translations')->insert([
                [
                    'category_id'      => 2,
                    'name'             => trans('installer::app.seeders.sample-categories.category-translation.2.name', [], $locale),
                    'slug'             => trans('installer::app.seeders.sample-categories.category-translation.2.slug', [], $locale),
                    'url_path'         => '',
                    'description'      => trans('installer::app.seeders.sample-categories.category-translation.2.description', [], $locale),
                    'meta_title'       => trans('installer::app.seeders.sample-categories.category-translation.2.meta-title', [], $locale),
                    'meta_description' => trans('installer::app.seeders.sample-categories.category-translation.2.meta-description', [], $locale),
                    'meta_keywords'    => trans('installer::app.seeders.sample-categories.category-translation.2.meta-keywords', [], $locale),
                    'locale_id'        => null,
                    'locale'           => $locale,
                ], [
                    'category_id'      => 3,
                    'name'             => trans('installer::app.seeders.sample-categories.category-translation.3.name', [], $locale),
                    'slug'             => trans('installer::app.seeders.sample-categories.category-translation.3.slug', [], $locale),
                    'url_path'         => '',
                    'description'      => trans('installer::app.seeders.sample-categories.category-translation.3.description', [], $locale),
                    'meta_title'       => trans('installer::app.seeders.sample-categories.category-translation.3.meta-title', [], $locale),
                    'meta_description' => trans('installer::app.seeders.sample-categories.category-translation.3.meta-description', [], $locale),
                    'meta_keywords'    => trans('installer::app.seeders.sample-categories.category-translation.3.meta-keywords', [], $locale),
                    'locale_id'        => null,
                    'locale'           => $locale,
                ], [
                    'category_id'      => 4,
                    'name'             => trans('installer::app.seeders.sample-categories.category-translation.4.name', [], $locale),
                    'slug'             => trans('installer::app.seeders.sample-categories.category-translation.4.slug', [], $locale),
                    'url_path'         => '',
                    'description'      => trans('installer::app.seeders.sample-categories.category-translation.4.description', [], $locale),
                    'meta_title'       => trans('installer::app.seeders.sample-categories.category-translation.4.meta-title', [], $locale),
                    'meta_description' => trans('installer::app.seeders.sample-categories.category-translation.4.meta-description', [], $locale),
                    'meta_keywords'    => trans('installer::app.seeders.sample-categories.category-translation.4.meta-keywords', [], $locale),
                    'locale_id'        => null,
                    'locale'           => $locale,
                ],
            ]);
        }

        DB::table('category_filterable_attributes')->insert([
            [
                'category_id'  => 2,
                'attribute_id' => 11,
            ], [
                'category_id'  => 2,
                'attribute_id' => 23,
            ], [
                'category_id'  => 2,
                'attribute_id' => 24,
            ], [
                'category_id'  => 2,
                'attribute_id' => 25,
            ], [
                'category_id'  => 3,
                'attribute_id' => 11,
            ], [
                'category_id'  => 3,
                'attribute_id' => 23,
            ], [
                'category_id'  => 3,
                'attribute_id' => 24,
            ], [
                'category_id'  => 3,
                'attribute_id' => 25,
            ], [
                'category_id'  => 4,
                'attribute_id' => 11,
            ], [
                'category_id'  => 4,
                'attribute_id' => 23,
            ], [
                'category_id'  => 4,
                'attribute_id' => 24,
            ], [
                'category_id'  => 4,
                'attribute_id' => 25,
            ],
        ]);
    }

    /**
     * When categories 2,3 already exist: add logos and category 4 (Women).
     */
    protected function ensureCategoryLogosAndFourthCategory(array $parameters, $now, $logo2, $logo3, $logo4): void
    {
        $defaultLocale = $parameters['default_locale'] ?? config('app.locale');
        $locales = $parameters['allowed_locales'] ?? [$defaultLocale];

        if ($logo2) {
            DB::table('categories')->where('id', 2)->update(['logo_path' => $logo2, 'updated_at' => $now]);
        }
        if ($logo3) {
            DB::table('categories')->where('id', 3)->update(['logo_path' => $logo3, 'updated_at' => $now]);
        }

        if (DB::table('categories')->where('id', 4)->exists()) {
            if ($logo4) {
                DB::table('categories')->where('id', 4)->update(['logo_path' => $logo4, 'updated_at' => $now]);
            }
            return;
        }

        DB::table('categories')->where('id', 1)->update(['_rgt' => 8]);

        DB::table('categories')->insert([
            'id'            => 4,
            'position'      => 2,
            'logo_path'     => $logo4,
            'status'        => 1,
            'display_mode'  => 'products_and_description',
            '_lft'          => 6,
            '_rgt'          => 7,
            'parent_id'     => 1,
            'additional'    => null,
            'banner_path'   => null,
            'created_at'    => $now,
            'updated_at'    => $now,
        ]);

        foreach ($locales as $locale) {
            DB::table('category_translations')->insert([
                'category_id'      => 4,
                'name'             => trans('installer::app.seeders.sample-categories.category-translation.4.name', [], $locale),
                'slug'             => trans('installer::app.seeders.sample-categories.category-translation.4.slug', [], $locale),
                'url_path'         => '',
                'description'      => trans('installer::app.seeders.sample-categories.category-translation.4.description', [], $locale),
                'meta_title'       => trans('installer::app.seeders.sample-categories.category-translation.4.meta-title', [], $locale),
                'meta_description' => trans('installer::app.seeders.sample-categories.category-translation.4.meta-description', [], $locale),
                'meta_keywords'    => trans('installer::app.seeders.sample-categories.category-translation.4.meta-keywords', [], $locale),
                'locale_id'        => null,
                'locale'           => $locale,
            ]);
        }

        DB::table('category_filterable_attributes')->insert([
            ['category_id' => 4, 'attribute_id' => 11],
            ['category_id' => 4, 'attribute_id' => 23],
            ['category_id' => 4, 'attribute_id' => 24],
            ['category_id' => 4, 'attribute_id' => 25],
        ]);
    }

    /**
     * Copy a seed image to storage for category logo.
     *
     * @return string|null
     */
    protected function storeCategoryLogo(int $categoryId, string $file)
    {
        $path = base_path(self::IMAGE_BASE_PATH.$file);
        if (! file_exists($path)) {
            return null;
        }

        return Storage::disk('public')->putFile('category/'.$categoryId, new File($path));
    }
}
