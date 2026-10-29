<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasColumn('ppdb_registrations', 'has_alumni_siblings')) {
            Schema::table('ppdb_registrations', function (Blueprint $table) {
                $table->boolean('has_alumni_siblings')->nullable()->after('referral_note');
            });
        }

        if (! Schema::hasColumn('ppdb_registrations', 'alumni_sibling_names')) {
            Schema::table('ppdb_registrations', function (Blueprint $table) {
                $table->text('alumni_sibling_names')->nullable()->after('has_alumni_siblings');
            });
        }

        $now = now();

        $settings = [
            'tahun_ajaran' => '2027/2028',
            'biaya_tk' => '250000',
            'bank_tk' => 'Bank Syariah Indonesia (BSI)',
            'rek_tk' => '3115917772',
            'rek_nama_tk' => 'TK Islam Cahaya Ilmu',
        ];

        if (Schema::hasTable('school_settings')) {
            foreach ($settings as $key => $value) {
                $existing = DB::table('school_settings')->where('key', $key)->first();

                if ($existing) {
                    DB::table('school_settings')->where('key', $key)->update([
                        'value' => $value,
                        'updated_at' => $now,
                    ]);
                } else {
                    DB::table('school_settings')->insert([
                        'key' => $key,
                        'value' => $value,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        }

        if (Schema::hasTable('referral_codes')) {
            // Referral codes are now event vouchers only. Keep historical
            // registrations intact but stop legacy sibling codes from being used.
            DB::table('referral_codes')
                ->where('type', 'sibling_alumni')
                ->update([
                    'is_active' => false,
                    'updated_at' => $now,
                ]);

            DB::table('referral_codes')
                ->where('code', 'ALUMNI26-01')
                ->delete();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data revisions are intentionally not reverted to avoid restoring
        // obsolete promo codes or fee values on production.
    }
};
