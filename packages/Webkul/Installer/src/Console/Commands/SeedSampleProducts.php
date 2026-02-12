<?php

namespace Webkul\Installer\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Installer\Database\Seeders\ProductTableSeeder;

class SeedSampleProducts extends Command
{
    protected $signature = 'bagisto:seed-sample-products';

    protected $description = 'Seed sample products (requires categories and attribute family from installer). Wipes existing products.';

    public function handle(): int
    {
        $rootExists = \Illuminate\Support\Facades\DB::table('categories')->where('id', 1)->exists();
        if (! $rootExists) {
            $this->error('Root category not found. Run the installer or bagisto:seed-sample-categories first.');
            return self::FAILURE;
        }

        $categoriesExist = \Illuminate\Support\Facades\DB::table('categories')->whereIn('id', [2, 3])->exists();
        if (! $categoriesExist) {
            $this->warn('Sample categories (Men, Winter Wear) not found. Run: php artisan bagisto:seed-sample-categories');
            return self::FAILURE;
        }

        $familyExists = \Illuminate\Support\Facades\DB::table('attribute_families')->where('id', 1)->exists();
        if (! $familyExists) {
            $this->error('Attribute family id 1 not found. Run the full Bagisto installer first.');
            return self::FAILURE;
        }

        $parameters = [
            'default_locale'  => config('app.locale'),
            'allowed_locales' => [config('app.locale')],
        ];

        try {
            $seeder = new ProductTableSeeder;
            $seeder->runProductsOnly($parameters);
            $this->info('Sample products seeded. Refresh your storefront to see them.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Seeding failed: ' . $e->getMessage());
            return self::FAILURE;
        }
    }
}
