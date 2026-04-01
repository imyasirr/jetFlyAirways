<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('contact_name')->nullable()->after('notes');
            $table->string('contact_email')->nullable()->after('contact_name');
            $table->string('contact_phone', 30)->nullable()->after('contact_email');
            $table->string('razorpay_order_id')->nullable()->after('payment_status');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'contact_name',
                'contact_email',
                'contact_phone',
                'razorpay_order_id',
                'razorpay_payment_id',
            ]);
        });
    }
};
