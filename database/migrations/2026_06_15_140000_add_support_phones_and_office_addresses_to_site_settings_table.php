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
            $table->json('support_phones')->nullable()->after('support_emails');
            $table->json('office_addresses')->nullable()->after('office_address');
        });

        if (! Schema::hasTable('site_settings')) {
            return;
        }

        DB::table('site_settings')->orderBy('id')->each(function ($row) {
            $updates = [];

            $phones = json_decode($row->support_phones ?? 'null', true);
            if (! is_array($phones) || $phones === []) {
                if (filled($row->support_phone ?? null)) {
                    $updates['support_phones'] = json_encode([
                        ['label' => 'Support', 'phone' => $row->support_phone],
                    ]);
                }
            }

            $addresses = json_decode($row->office_addresses ?? 'null', true);
            if (! is_array($addresses) || $addresses === []) {
                if (filled($row->office_address ?? null)) {
                    $updates['office_addresses'] = json_encode([
                        [
                            'label' => filled($row->office_address_label ?? null)
                                ? $row->office_address_label
                                : 'Registered Office',
                            'address' => $row->office_address,
                        ],
                    ]);
                }
            }

            if ($updates !== []) {
                DB::table('site_settings')->where('id', $row->id)->update($updates);
            }
        });
    }

    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['support_phones', 'office_addresses']);
        });
    }
};
