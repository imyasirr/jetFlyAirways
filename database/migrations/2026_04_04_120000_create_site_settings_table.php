<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('topstrip_left', 600)->nullable();
            $table->string('support_phone', 80)->nullable();
            $table->string('support_email', 160)->nullable();
            $table->string('brand_name', 120)->nullable();
            $table->string('brand_tagline', 180)->nullable();
            $table->text('footer_about')->nullable();
            $table->string('footer_badges', 600)->nullable();
            $table->string('footer_copyright_name', 160)->nullable();
            $table->string('social_facebook_url', 500)->nullable();
            $table->string('social_instagram_url', 500)->nullable();
            $table->string('social_linkedin_url', 500)->nullable();
            $table->string('social_twitter_url', 500)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
