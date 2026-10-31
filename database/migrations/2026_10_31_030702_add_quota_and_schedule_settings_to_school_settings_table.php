<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Settings added by this migration. Existing rows are never overwritten.
     *
     * @var array<string, string>
     */
    private const DEFAULTS = [
        'quota_public_visible' => '1',
        'quota_display_mode' => 'full',
        'quota_count_mode' => 'auto',
        'registration_mode' => 'open',
        'registration_daily_hours_enabled' => '0',
        'registration_daily_open_time' => '07:00',
        'registration_daily_close_time' => '21:00',
        'registration_closed_message' => 'Pendaftaran SPMB sedang ditutup. Silakan kembali pada jadwal yang telah ditentukan atau hubungi panitia SPMB.',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('school_settings')) {
            return;
        }

        $now = now();

        foreach (self::DEFAULTS as $key => $value) {
            // insertOrIgnore keeps production values intact when an admin has
            // already stored the same key before this migration runs.
            DB::table('school_settings')->insertOrIgnore([
                'key' => $key,
                'value' => $value,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('school_settings')) {
            return;
        }

        DB::table('school_settings')
            ->whereIn('key', array_keys(self::DEFAULTS))
            ->delete();
    }
};
