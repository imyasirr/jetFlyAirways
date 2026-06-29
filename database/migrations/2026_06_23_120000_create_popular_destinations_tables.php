<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('popular_destinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('banner')->nullable();
            $table->text('description')->nullable();
            $table->string('tag_line')->nullable();
            $table->string('best_season')->nullable();
            $table->string('package_destination')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('popular_destination_gallery', function (Blueprint $table) {
            $table->id();
            $table->foreignId('popular_destination_id')->constrained('popular_destinations')->cascadeOnDelete();
            $table->string('image');
            $table->string('caption')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('popular_destination_gallery');
        Schema::dropIfExists('popular_destinations');
    }
};
