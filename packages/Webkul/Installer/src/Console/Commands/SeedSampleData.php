<?php

namespace Webkul\Installer\Console\Commands;

use Illuminate\Console\Command;

class SeedSampleData extends Command
{
    protected $signature = 'bagisto:seed-sample-data';

    protected $description = 'Seed sample categories and products so the storefront shows content (run after installer)';

    public function handle(): int
    {
        $this->info('Seeding sample categories...');
        $catExit = $this->call('bagisto:seed-sample-categories');
        if ($catExit !== 0) {
            $this->comment('Categories already exist or skipped (ok).');
        }

        $this->info('Seeding sample products...');
        $exit = $this->call('bagisto:seed-sample-products');
        if ($exit !== 0) {
            $this->error('Product seeding failed. Ensure you ran the Bagisto installer (attributes + categories exist).');
            return self::FAILURE;
        }

        $this->info('Done. Refresh your storefront to see categories and products.');
        return self::SUCCESS;
    }
}
