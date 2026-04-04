<?php

namespace Database\Seeders;

use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class MenuItemSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        MenuItem::query()->truncate();
        Schema::enableForeignKeyConstraints();

        /** Header: primary tabs + “More” mega (MMT-style; no duplicate Home / Discover / My account — logo + account CTA cover those). */
        MenuItem::create([
            'location' => 'header',
            'label' => 'Flights',
            'href' => '/flights',
            'sort_order' => 1,
            'is_active' => true,
        ]);
        MenuItem::create([
            'location' => 'header',
            'label' => 'Hotels',
            'href' => '/hotels',
            'sort_order' => 2,
            'is_active' => true,
        ]);
        MenuItem::create([
            'location' => 'header',
            'label' => 'Holidays',
            'href' => '/packages',
            'sort_order' => 3,
            'is_active' => true,
        ]);
        $more = MenuItem::create([
            'location' => 'header',
            'label' => 'More',
            'href' => null,
            'sort_order' => 4,
            'is_active' => true,
        ]);
        foreach ([
            ['Trains', '/trains'],
            ['Buses', '/buses'],
            ['Cabs', '/cabs'],
            ['Visa', '/visa'],
            ['Insurance', '/insurance'],
        ] as $i => $r) {
            MenuItem::create([
                'parent_id' => $more->id,
                'location' => 'header',
                'label' => $r[0],
                'href' => $r[1],
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $footerServices = MenuItem::create([
            'location' => 'footer',
            'label' => 'Products',
            'href' => null,
            'sort_order' => 1,
            'is_active' => true,
        ]);
        foreach ([
            ['Flights', '/flights'],
            ['Hotels', '/hotels'],
            ['Holiday packages', '/packages'],
            ['Buses', '/buses'],
            ['Trains', '/trains'],
            ['Cabs', '/cabs'],
            ['Visa', '/visa'],
            ['Insurance', '/insurance'],
        ] as $i => $r) {
            MenuItem::create([
                'parent_id' => $footerServices->id,
                'location' => 'footer',
                'label' => $r[0],
                'href' => $r[1],
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $footerQuick = MenuItem::create([
            'location' => 'footer',
            'label' => 'Quick links',
            'href' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);
        foreach ([
            ['Search', '#search'],
            ['Blog', '/blog'],
            ['FAQ', '/faq'],
            ['Jobs', '/jobs'],
            ['Contact', '/contact-us'],
        ] as $i => $r) {
            MenuItem::create([
                'parent_id' => $footerQuick->id,
                'location' => 'footer',
                'label' => $r[0],
                'href' => $r[1],
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $footerCompany = MenuItem::create([
            'location' => 'footer',
            'label' => 'Company',
            'href' => null,
            'sort_order' => 3,
            'is_active' => true,
        ]);
        foreach ([
            ['Discover', '/welcome'],
            ['Home', '/'],
            ['About us', '/p/about'],
            ['Open jobs', '/jobs'],
            ['Careers', '/p/careers'],
        ] as $i => $r) {
            MenuItem::create([
                'parent_id' => $footerCompany->id,
                'location' => 'footer',
                'label' => $r[0],
                'href' => $r[1],
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $footerLegal = MenuItem::create([
            'location' => 'footer',
            'label' => 'Support & legal',
            'href' => null,
            'sort_order' => 4,
            'is_active' => true,
        ]);
        foreach ([
            ['Help centre', '/p/help'],
            ['Refund policy', '/p/refund'],
            ['Terms of use', '/p/terms'],
            ['Privacy policy', '/p/privacy'],
            ['Sitemap', '/p/sitemap'],
        ] as $i => $r) {
            MenuItem::create([
                'parent_id' => $footerLegal->id,
                'location' => 'footer',
                'label' => $r[0],
                'href' => $r[1],
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }
    }
}
