<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('destination_guide_settings', function (Blueprint $table) {
            $table->id();
            $table->text('intro')->nullable();
            $table->string('spots_heading')->default('Trending destinations');
            $table->text('spots_subheading')->nullable();
            $table->string('tips_heading')->default('Quick planning tips');
            $table->string('callout_title')->nullable();
            $table->text('callout_body')->nullable();
            $table->string('callout_link')->nullable();
            $table->string('callout_link_label')->nullable();
            $table->timestamps();
        });

        Schema::create('destination_guide_features', function (Blueprint $table) {
            $table->id();
            $table->string('icon', 40)->nullable();
            $table->string('title');
            $table->text('body');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('destination_guide_spots', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('tag_line')->nullable();
            $table->string('best_season')->nullable();
            $table->string('image')->nullable();
            $table->string('package_destination')->nullable();
            $table->string('link_url')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('destination_guide_tips', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('body')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('destination_guide_tips');
        Schema::dropIfExists('destination_guide_spots');
        Schema::dropIfExists('destination_guide_features');
        Schema::dropIfExists('destination_guide_settings');
    }
};
