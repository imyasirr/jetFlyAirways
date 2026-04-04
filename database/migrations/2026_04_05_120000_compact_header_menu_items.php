<?php

use App\Models\MenuItem;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menu_items')) {
            return;
        }

        MenuItem::query()->where('location', 'header')->delete();

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
    }

    public function down(): void
    {
        // Intentionally empty: previous header shape is not restored.
    }
};
