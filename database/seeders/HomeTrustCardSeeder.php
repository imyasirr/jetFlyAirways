<?php

namespace Database\Seeders;

use App\Models\HomeTrustCard;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class HomeTrustCardSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('home_trust_cards')) {
            return;
        }

        $rows = [
            [
                'title' => 'Secure payments',
                'description' => 'Checkout flow ready for gateway integration (Razorpay, UPI, cards).',
                'icon' => 'lock',
                'sort_order' => 10,
            ],
            [
                'title' => 'Best-value fares',
                'description' => 'Admin-managed inventory with live listing on the website.',
                'icon' => 'savings',
                'sort_order' => 20,
            ],
            [
                'title' => 'Full admin control',
                'description' => 'Flights, hotels, packages, routes, cabs, bookings — from one dashboard.',
                'icon' => 'dashboard',
                'sort_order' => 30,
            ],
        ];

        foreach ($rows as $row) {
            HomeTrustCard::query()->updateOrCreate(
                ['title' => $row['title']],
                array_merge($row, ['is_active' => true])
            );
        }
    }
}
