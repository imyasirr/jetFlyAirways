<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cab_services', function (Blueprint $table) {
            $table->id();
            $table->string('service_type');
            $table->string('from_location');
            $table->string('to_location')->nullable();
            $table->decimal('base_fare', 12, 2);
            $table->decimal('per_km_rate', 12, 2)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cab_services');
    }
};
