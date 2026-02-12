<?php

namespace Webkul\Installer\Console\Commands;

use Illuminate\Console\Command;
use Webkul\Installer\Database\Seeders\Category\CategoryTableSeeder;

class SeedSampleCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bagisto:seed-sample-categories
                            {--force : Run even if sample categories may already exist}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed sample categories (e.g. Men, Winter Wear) so the storefront shows categories on the homepage';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $exists = \Illuminate\Support\Facades\DB::table('categories')
            ->whereIn('id', [2, 3])
            ->exists();

        if ($exists && ! $this->option('force')) {
            $this->comment('Sample categories already exist. Adding logos and Women category if missing...');
        }

        $rootExists = \Illuminate\Support\Facades\DB::table('categories')->where('id', 1)->exists();
        if (! $rootExists) {
            $this->error('Root category (id 1) not found. Run the full installer first.');
            return self::FAILURE;
        }

        $parameters = [
            'default_locale'  => config('app.locale'),
            'allowed_locales' => [config('app.locale')],
        ];

        try {
            $seeder = new CategoryTableSeeder;
            $seeder->sampleCategories($parameters);
            $this->info('Sample categories (Men, Winter Wear, Women) with logos are ready. Refresh the storefront.');
            return self::SUCCESS;
        } catch (\Throwable $e) {
            $this->error('Seeding failed: ' . $e->getMessage());
            if ($e->getCode() === '23000' || str_contains($e->getMessage(), 'Duplicate')) {
                $this->warn('Sample categories may already exist. Add categories manually in Admin → Catalog → Categories.');
            }
            return self::FAILURE;
        }
    }
}
