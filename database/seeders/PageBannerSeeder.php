<?php

namespace Database\Seeders;

use App\Models\PageBanner;
use Illuminate\Database\Seeder;

class PageBannerSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PageBanner::catalog() as $pageKey => $label) {
            PageBanner::query()->updateOrCreate(
                ['page_key' => $pageKey],
                ['label' => $label, 'is_active' => true, 'is_system' => true]
            );
        }
    }
}
