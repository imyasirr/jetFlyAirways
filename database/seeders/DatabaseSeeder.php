<?php

namespace Database\Seeders;

use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = env('ADMIN_EMAIL', 'admin@jetflyairways.com');
        $adminPassword = env('ADMIN_PASSWORD', 'admin123');

        User::query()->updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Administrator',
                'password' => Hash::make($adminPassword),
                'is_admin' => true,
            ]
        );

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'is_admin' => false,
        ]);

        $couponRows = [
            ['code' => 'JETFLY500', 'discount_type' => 'flat', 'discount_value' => 500, 'valid_from' => now()->subDay(), 'valid_to' => now()->addMonths(6)],
            ['code' => 'WELCOME10', 'discount_type' => 'percent', 'discount_value' => 10, 'valid_from' => now()->subDay(), 'valid_to' => now()->addYear()],
            ['code' => 'SUMMER2K', 'discount_type' => 'flat', 'discount_value' => 2000, 'valid_from' => now()->subWeek(), 'valid_to' => now()->addMonths(3)],
        ];
        foreach ($couponRows as $row) {
            Coupon::query()->updateOrCreate(
                ['code' => $row['code']],
                [
                    'discount_type' => $row['discount_type'],
                    'discount_value' => $row['discount_value'],
                    'valid_from' => $row['valid_from'],
                    'valid_to' => $row['valid_to'],
                    'max_usage' => 5000,
                    'used_count' => 0,
                    'is_active' => true,
                ]
            );
        }

        if (Schema::hasTable('cms_pages')) {
            $this->call(PageSeeder::class);
        }

        if (Schema::hasTable('menu_items')) {
            $this->call(MenuItemSeeder::class);
        }

        if (Schema::hasTable('offers')) {
            $this->call(OfferSeeder::class);
        }

        if (Schema::hasTable('faqs')) {
            $this->call(SupportContentSeeder::class);
        }

        if (Schema::hasTable('home_sections')) {
            $this->call(HomeSectionSeeder::class);
        }

        if (Schema::hasTable('travel_addons')) {
            $this->call(TravelAddonSeeder::class);
        }
    }
}
