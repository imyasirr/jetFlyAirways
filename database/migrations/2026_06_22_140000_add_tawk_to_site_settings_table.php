<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('tawk_property_id', 64)->nullable()->after('live_chat_url');
            $table->string('tawk_widget_id', 64)->nullable()->after('tawk_property_id');
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['tawk_property_id', 'tawk_widget_id']);
        });
    }
};
