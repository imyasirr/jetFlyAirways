<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('google_id')->nullable()->unique()->after('remember_token');
            $table->string('referral_code', 16)->nullable()->unique()->after('google_id');
            $table->foreignId('referred_by_user_id')->nullable()->after('referral_code')->constrained('users')->nullOnDelete();
        });

        foreach (DB::table('users')->whereNull('referral_code')->cursor() as $row) {
            do {
                $code = strtoupper(Str::random(8));
            } while (DB::table('users')->where('referral_code', $code)->exists());
            DB::table('users')->where('id', $row->id)->update(['referral_code' => $code]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('referred_by_user_id');
            $table->dropColumn(['google_id', 'referral_code']);
        });
    }
};
