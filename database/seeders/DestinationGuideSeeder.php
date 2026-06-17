<?php

namespace Database\Seeders;

use App\Models\DestinationGuideFeature;
use App\Models\DestinationGuideSetting;
use App\Models\DestinationGuideSpot;
use App\Models\DestinationGuideTip;
use Illuminate\Database\Seeder;

class DestinationGuideSeeder extends Seeder
{
    public function run(): void
    {
        DestinationGuideSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'intro' => 'Plan smarter with region highlights, best seasons to visit, and airport or station access tips. Use our search to compare fares and stays — then refine dates for the lowest combined trip cost.',
                'spots_heading' => 'Trending destinations',
                'spots_subheading' => 'Hand-picked routes our travellers love right now. Tap a card to explore packages.',
                'tips_heading' => 'Quick planning tips',
                'callout_title' => 'Custom trip',
                'callout_body' => 'Need a tailored itinerary? Contact us with rough dates and group size — our travel desk will plan it end-to-end.',
                'callout_link' => '/p/contact',
                'callout_link_label' => 'Contact us',
            ]
        );

        $features = [
            ['icon' => 'calendar_month', 'title' => 'When to go', 'body' => 'Shoulder seasons often balance weather and price — adjust for local festivals and holidays.', 'sort_order' => 1],
            ['icon' => 'flight_land', 'title' => 'Hubs & access', 'body' => 'Check the nearest airport or station and ground options (metro, cab, bus) before you lock dates.', 'sort_order' => 2],
            ['icon' => 'luggage', 'title' => 'Stay + fly', 'body' => 'Bundling hotels with flights can simplify changes — compare both in one session.', 'sort_order' => 3],
        ];

        foreach ($features as $row) {
            DestinationGuideFeature::query()->updateOrCreate(
                ['title' => $row['title']],
                $row + ['is_active' => true]
            );
        }

        $spots = [
            ['name' => 'Dubai, UAE', 'tag_line' => 'City luxe', 'best_season' => 'Best Oct–Mar', 'package_destination' => 'Dubai', 'image' => 'https://images.unsplash.com/photo-1512453979798-5ea266f8880c?auto=format&fit=crop&w=900&q=70', 'sort_order' => 1],
            ['name' => 'Bali, Indonesia', 'tag_line' => 'Island escape', 'best_season' => 'Best Apr–Oct', 'package_destination' => 'Bali', 'image' => 'https://images.unsplash.com/photo-1537996194471-e657df975ab4?auto=format&fit=crop&w=900&q=70', 'sort_order' => 2],
            ['name' => 'Paris, France', 'tag_line' => 'Classic Europe', 'best_season' => 'Best Apr–Jun', 'package_destination' => 'Paris', 'image' => 'https://images.unsplash.com/photo-1502602898657-3e91760cbb34?auto=format&fit=crop&w=900&q=70', 'sort_order' => 3],
            ['name' => 'Goa, India', 'tag_line' => 'Beach break', 'best_season' => 'Best Nov–Feb', 'package_destination' => 'Goa', 'image' => 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?auto=format&fit=crop&w=900&q=70', 'sort_order' => 4],
            ['name' => 'Kerala, India', 'tag_line' => 'Backwaters', 'best_season' => 'Best Sep–Mar', 'package_destination' => 'Kerala', 'image' => 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?auto=format&fit=crop&w=900&q=70', 'sort_order' => 5],
            ['name' => 'Kashmir, India', 'tag_line' => 'Mountain retreat', 'best_season' => 'Best Mar–Aug', 'package_destination' => 'Kashmir', 'image' => 'https://images.unsplash.com/photo-1566837945700-30057527ade0?auto=format&fit=crop&w=900&q=70', 'sort_order' => 6],
        ];

        foreach ($spots as $row) {
            DestinationGuideSpot::query()->updateOrCreate(
                ['name' => $row['name']],
                $row + ['is_active' => true]
            );
        }

        $tips = [
            ['title' => 'Book 4–8 weeks ahead', 'body' => 'for domestic routes and 8–16 weeks for international fares.', 'sort_order' => 1],
            ['title' => 'Fly mid-week', 'body' => '— Tuesday and Wednesday departures are usually the cheapest.', 'sort_order' => 2],
            ['title' => 'Watch festival calendars', 'body' => '— prices spike around Diwali, Christmas and regional holidays.', 'sort_order' => 3],
            ['title' => 'Compare nearby airports', 'body' => '— a short transfer can save a big chunk on the fare.', 'sort_order' => 4],
        ];

        foreach ($tips as $row) {
            DestinationGuideTip::query()->updateOrCreate(
                ['title' => $row['title']],
                $row + ['is_active' => true]
            );
        }
    }
}
