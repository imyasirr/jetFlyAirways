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

        $header = [
            ['label' => 'Home', 'href' => '/', 'sort' => 1],
            ['label' => 'Discover', 'href' => '/welcome', 'sort' => 2],
        ];
        foreach ($header as $row) {
            MenuItem::create([
                'location' => 'header',
                'label' => $row['label'],
                'href' => $row['href'],
                'sort_order' => $row['sort'],
                'is_active' => true,
            ]);
        }

        $book = MenuItem::create([
            'location' => 'header',
            'label' => 'Book',
            'href' => null,
            'sort_order' => 10,
            'is_active' => true,
        ]);
        foreach ([
            ['Flights', '/flights'],
            ['Hotels', '/hotels'],
            ['Packages', '/packages'],
        ] as $i => $r) {
            MenuItem::create([
                'parent_id' => $book->id,
                'location' => 'header',
                'label' => $r[0],
                'href' => $r[1],
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $ground = MenuItem::create([
            'location' => 'header',
            'label' => 'Ground transport',
            'href' => null,
            'sort_order' => 20,
            'is_active' => true,
        ]);
        foreach ([
            ['Buses', '/buses'],
            ['Trains', '/trains'],
            ['Cabs', '/cabs'],
        ] as $i => $r) {
            MenuItem::create([
                'parent_id' => $ground->id,
                'location' => 'header',
                'label' => $r[0],
                'href' => $r[1],
                'sort_order' => $i,
                'is_active' => true,
            ]);
        }

        $more = MenuItem::create([
            'location' => 'header',
            'label' => 'More services',
            'href' => null,
            'sort_order' => 30,
            'is_active' => true,
        ]);
        foreach ([
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

        MenuItem::create([
            'location' => 'header',
            'label' => 'My account',
            'href' => '/account',
            'sort_order' => 40,
            'is_active' => true,
            'requires_auth' => true,
        ]);

        $footerServices = MenuItem::create([
            'location' => 'footer',
            'label' => 'Services',
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

        $footerCompany = MenuItem::create([
            'location' => 'footer',
            'label' => 'Company',
            'href' => null,
            'sort_order' => 2,
            'is_active' => true,
        ]);
        foreach ([
            ['Discover', '/welcome'],
            ['Home', '/'],
            ['About us', '/p/about'],
            ['Blog', '/blog'],
            ['Open jobs', '/jobs'],
            ['Careers (info)', '/p/careers'],
            ['FAQ', '/faq'],
            ['Contact form', '/contact-us'],
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
            'sort_order' => 3,
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
