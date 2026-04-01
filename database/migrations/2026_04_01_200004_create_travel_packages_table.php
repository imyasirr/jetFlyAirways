<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('travel_packages', function (Blueprint $table) {
            $table->id();
            $table->string('category')->index();
            $table->string('name');
            $table->string('destination')->index();
            $table->unsignedInteger('duration_days');
            $table->decimal('price', 12, 2);
            $table->decimal('offer_price', 12, 2)->nullable();
            $table->longText('itinerary')->nullable();
            $table->longText('details')->nullable();
            $table->longText('inclusions')->nullable();
            $table->longText('exclusions')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('travel_packages');
    }
};
