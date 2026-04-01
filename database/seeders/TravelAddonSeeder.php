<?php

namespace Database\Seeders;

use App\Models\TravelAddon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class TravelAddonSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('travel_addons')) {
            return;
        }

        $rows = [
            ['category' => TravelAddon::CATEGORY_VISA, 'name' => 'Tourist visa — UAE', 'summary' => 'Single entry, standard processing.', 'description' => 'Includes application review and submission support. Processing time varies by embassy.', 'price' => 4500, 'sort_order' => 10],
            ['category' => TravelAddon::CATEGORY_VISA, 'name' => 'Tourist visa — Thailand', 'summary' => 'E-visa assistance.', 'description' => 'Document checklist and online filing support.', 'price' => 2800, 'sort_order' => 20],
            ['category' => TravelAddon::CATEGORY_INSURANCE, 'name' => 'International travel — 7 days', 'summary' => 'Medical + trip delay cover.', 'description' => 'Indicative plan; final policy subject to insurer T&Cs.', 'price' => 899, 'sort_order' => 10],
            ['category' => TravelAddon::CATEGORY_INSURANCE, 'name' => 'Domestic travel — 15 days', 'summary' => 'Baggage + cancellation basics.', 'description' => 'Indicative plan; final policy subject to insurer T&Cs.', 'price' => 499, 'sort_order' => 20],
        ];

        foreach ($rows as $row) {
            TravelAddon::query()->updateOrCreate(
                ['category' => $row['category'], 'name' => $row['name']],
                [
                    'summary' => $row['summary'],
                    'description' => $row['description'],
                    'price' => $row['price'],
                    'sort_order' => $row['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
