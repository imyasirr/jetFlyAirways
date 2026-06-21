<?php

namespace Database\Seeders;

use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        if (! Schema::hasTable('site_settings')) {
            return;
        }

        if (SiteSetting::query()->exists()) {
            return;
        }

        SiteSetting::query()->create([
            'topstrip_left' => 'Upto 25% off* on domestic flights — book now · Easy cancellations on select fares',
            'support_phone' => '+91 1800-000-0000',
            'support_phones' => [
                ['label' => 'Toll Free', 'phone' => '+91 1800-000-0000'],
                ['label' => 'Sales', 'phone' => '+91 98765 43210'],
            ],
            'support_emails' => [
                ['label' => 'Support', 'email' => 'support@jetflyairways.com'],
                ['label' => 'Bookings', 'email' => 'bookings@jetflyairways.com'],
            ],
            'office_address_label' => 'Registered Office',
            'office_address' => "Jet Fly Airways Pvt. Ltd.\n123 Aviation Park, Sector 18\nGurugram, Haryana 122015\nIndia",
            'office_addresses' => [
                [
                    'label' => 'Registered Office',
                    'address' => "Jet Fly Airways Pvt. Ltd.\n123 Aviation Park, Sector 18\nGurugram, Haryana 122015\nIndia",
                ],
            ],
            'brand_name' => 'Jet Fly',
            'brand_tagline' => 'Flights · Hotels · Holidays · More',
            'footer_about' => 'Book flights, hotels, buses, trains, cabs and holiday packages in one place. Best deals, simple search, and secure checkout — your travel, simplified.',
            'footer_badges' => 'UPI · Cards · NetBanking · EMI (where available) · PCI-ready checkout',
            'footer_copyright_name' => 'Jet Fly Airways',
            'social_facebook_url' => 'https://www.facebook.com/',
            'social_instagram_url' => 'https://www.instagram.com/',
            'social_linkedin_url' => 'https://www.linkedin.com/',
            'social_twitter_url' => 'https://twitter.com/',
        ]);
    }
}
