<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Legacy status values mapped to the two-stage registration statuses.
     *
     * @var array<string, string>
     */
    private const STATUS_MAP = [
        'Baru' => 'pending',
        'Diverifikasi' => 'verified',
        'Diterima' => 'accepted',
        'Ditolak' => 'rejected',
    ];

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->string('status', 20)->default('pending')->change();
        });

        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->string('registration_code', 64)->nullable()->unique()->after('registration_number');
            $table->json('full_form_data')->nullable()->after('status');
            $table->json('fee_agreement_data')->nullable()->after('full_form_data');
            $table->timestamp('verified_at')->nullable()->after('fee_agreement_data');
            $table->timestamp('completed_at')->nullable()->after('verified_at');
        });

        foreach (self::STATUS_MAP as $legacy => $status) {
            DB::table('ppdb_registrations')->where('status', $legacy)->update(['status' => $status]);
        }

        DB::table('ppdb_registrations')
            ->whereNull('registration_code')
            ->orderBy('id')
            ->each(function (object $registration): void {
                DB::table('ppdb_registrations')
                    ->where('id', $registration->id)
                    ->update(['registration_code' => Str::random(40)]);
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('ppdb_registrations')->where('status', 'completed')->update(['status' => 'verified']);

        foreach (self::STATUS_MAP as $legacy => $status) {
            DB::table('ppdb_registrations')->where('status', $status)->update(['status' => $legacy]);
        }

        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropUnique(['registration_code']);
            $table->dropColumn(['registration_code', 'full_form_data', 'fee_agreement_data', 'verified_at', 'completed_at']);
        });

        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->enum('status', array_keys(self::STATUS_MAP))->default('Baru')->change();
        });
    }
};
