<?php

use App\Models\BusRoute;
use App\Models\CabService;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\TrainRoute;
use App\Models\TravelAddon;
use App\Models\TravelPackage;
use App\Support\InventorySlug;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'flights',
            'hotels',
            'travel_packages',
            'bus_routes',
            'train_routes',
            'cab_services',
            'travel_addons',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && ! Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->string('slug', 190)->nullable()->after('id');
                });
            }
        }

        if (Schema::hasTable('flights')) {
            foreach (Flight::query()->cursor() as $row) {
                if (filled($row->slug)) {
                    continue;
                }
                $row->slug = InventorySlug::forFlight($row);
                $row->saveQuietly();
            }
        }
        if (Schema::hasTable('hotels')) {
            foreach (Hotel::query()->cursor() as $row) {
                if (filled($row->slug)) {
                    continue;
                }
                $row->slug = InventorySlug::forHotel($row);
                $row->saveQuietly();
            }
        }
        if (Schema::hasTable('travel_packages')) {
            foreach (TravelPackage::query()->cursor() as $row) {
                if (filled($row->slug)) {
                    continue;
                }
                $row->slug = InventorySlug::forTravelPackage($row);
                $row->saveQuietly();
            }
        }
        if (Schema::hasTable('bus_routes')) {
            foreach (BusRoute::query()->cursor() as $row) {
                if (filled($row->slug)) {
                    continue;
                }
                $row->slug = InventorySlug::forBusRoute($row);
                $row->saveQuietly();
            }
        }
        if (Schema::hasTable('train_routes')) {
            foreach (TrainRoute::query()->cursor() as $row) {
                if (filled($row->slug)) {
                    continue;
                }
                $row->slug = InventorySlug::forTrainRoute($row);
                $row->saveQuietly();
            }
        }
        if (Schema::hasTable('cab_services')) {
            foreach (CabService::query()->cursor() as $row) {
                if (filled($row->slug)) {
                    continue;
                }
                $row->slug = InventorySlug::forCabService($row);
                $row->saveQuietly();
            }
        }
        if (Schema::hasTable('travel_addons')) {
            foreach (TravelAddon::query()->cursor() as $row) {
                if (filled($row->slug)) {
                    continue;
                }
                $row->slug = InventorySlug::forTravelAddon($row);
                $row->saveQuietly();
            }
        }

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->unique('slug');
                });
            }
        }
    }

    public function down(): void
    {
        foreach ([
            'flights',
            'hotels',
            'travel_packages',
            'bus_routes',
            'train_routes',
            'cab_services',
            'travel_addons',
        ] as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'slug')) {
                Schema::table($table, function (Blueprint $t) {
                    $t->dropUnique(['slug']);
                    $t->dropColumn('slug');
                });
            }
        }
    }
};
