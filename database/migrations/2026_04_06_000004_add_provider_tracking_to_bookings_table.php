<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('provider_service', 80)->nullable()->after('payment_status');
            $table->string('provider_sync_status', 120)->nullable()->after('provider_service');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['provider_service', 'provider_sync_status']);
        });
    }
};

