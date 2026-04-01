<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('home_sections')) {
            return;
        }

        $rows = [
            ['partial_key' => 'offers', 'admin_label' => 'Offers strip', 'sort_order' => 10],
            ['partial_key' => 'destinations', 'admin_label' => 'Popular destinations', 'sort_order' => 20],
            ['partial_key' => 'flights', 'admin_label' => 'Featured flights', 'sort_order' => 30],
            ['partial_key' => 'hotels', 'admin_label' => 'Featured hotels', 'sort_order' => 40],
            ['partial_key' => 'packages', 'admin_label' => 'Holiday packages', 'sort_order' => 50],
            ['partial_key' => 'testimonials', 'admin_label' => 'Testimonials', 'sort_order' => 60],
            ['partial_key' => 'services', 'admin_label' => 'Service grid', 'sort_order' => 70],
            ['partial_key' => 'trust_row', 'admin_label' => 'Trust row', 'sort_order' => 80],
        ];

        foreach ($rows as $row) {
            HomeSection::query()->updateOrCreate(
                ['partial_key' => $row['partial_key']],
                [
                    'admin_label' => $row['admin_label'],
                    'sort_order' => $row['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
