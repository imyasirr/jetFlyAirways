<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->json('tags')->nullable()->after('description');
            $table->boolean('show_tags')->default(true)->after('tags');
            $table->boolean('show_button')->default(true)->after('button_text');
        });
    }

    public function down(): void
    {
        Schema::table('banners', function (Blueprint $table) {
            $table->dropColumn(['tags', 'show_tags', 'show_button']);
        });
    }
};
