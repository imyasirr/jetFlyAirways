<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->json('support_emails')->nullable()->after('support_email');
            $table->string('office_address_label', 120)->nullable()->after('support_emails');
            $table->text('office_address')->nullable()->after('office_address_label');
        });

        if (Schema::hasTable('site_settings') && Schema::hasColumn('site_settings', 'support_email')) {
            DB::table('site_settings')
                ->whereNotNull('support_email')
                ->where('support_email', '!=', '')
                ->orderBy('id')
                ->each(function ($row) {
                    $emails = json_decode($row->support_emails ?? 'null', true);
                    if (is_array($emails) && count($emails) > 0) {
                        return;
                    }

                    DB::table('site_settings')->where('id', $row->id)->update([
                        'support_emails' => json_encode([
                            ['label' => 'Support', 'email' => $row->support_email],
                        ]),
                    ]);
                });
        }
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['support_emails', 'office_address_label', 'office_address']);
        });
    }
};
