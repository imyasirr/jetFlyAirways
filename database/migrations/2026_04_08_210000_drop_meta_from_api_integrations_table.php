<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('api_integrations') && Schema::hasColumn('api_integrations', 'meta')) {
            Schema::table('api_integrations', function (Blueprint $table) {
                $table->dropColumn('meta');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('api_integrations') && ! Schema::hasColumn('api_integrations', 'meta')) {
            Schema::table('api_integrations', function (Blueprint $table) {
                $table->json('meta')->nullable()->after('notes');
            });
        }
    }
};
