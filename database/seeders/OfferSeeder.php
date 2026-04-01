<?php

namespace Database\Seeders;

use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        if (Offer::query()->exists()) {
            return;
        }

        Offer::create([
            'title' => 'Summer Sale — up to 20% off packages',
            'description' => 'Book select domestic holiday packages before the season ends.',
            'redirect_url' => '/packages',
            'start_date' => now()->subDays(7)->toDateString(),
            'end_date' => now()->addMonths(2)->toDateString(),
            'is_active' => true,
        ]);

        Offer::create([
            'title' => 'Weekend flights — best fares',
            'description' => 'Explore our flight deals from your city.',
            'redirect_url' => '/flights',
            'start_date' => null,
            'end_date' => null,
            'is_active' => true,
        ]);
    }
}
