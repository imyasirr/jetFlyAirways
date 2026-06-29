<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('popular_destinations', function (Blueprint $table) {
            $table->longText('details')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('popular_destinations', function (Blueprint $table) {
            $table->dropColumn('details');
        });
    }
};
