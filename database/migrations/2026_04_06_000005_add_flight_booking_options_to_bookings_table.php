<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('trip_type', 32)->nullable()->after('travel_date');
            $table->date('return_date')->nullable()->after('trip_type');
            $table->string('seat_preference', 80)->nullable()->after('return_date');
            $table->string('meal_preference', 120)->nullable()->after('seat_preference');
            $table->text('multi_city_notes')->nullable()->after('meal_preference');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'trip_type',
                'return_date',
                'seat_preference',
                'meal_preference',
                'multi_city_notes',
            ]);
        });
    }
};
