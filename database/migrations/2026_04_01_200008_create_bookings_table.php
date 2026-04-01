<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('booking_code')->unique();
            $table->string('module')->index();
            $table->unsignedBigInteger('module_item_id');
            $table->date('travel_date');
            $table->unsignedInteger('travellers_count')->default(1);
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->string('status')->default('confirmed');
            $table->string('payment_status')->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
