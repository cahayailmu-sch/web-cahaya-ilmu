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
        Schema::create('referral_codes_new', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('type');
            $table->unsignedInteger('discount_amount');
            $table->unsignedInteger('max_uses')->default(1);
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->foreignId('used_by_registration_id')
                ->nullable()
                ->constrained('ppdb_registrations')
                ->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        /*
         * The previous referral table was an unused prototype with a different
         * schema. Preserve its rows as inactive legacy codes instead of
         * touching any registrations or admin data.
         */
        foreach (DB::table('referral_codes')->get() as $legacy) {
            $isFreeCode = $legacy->discount_type === 'percentage' && (int) $legacy->discount_value >= 100;
            $type = $isFreeCode ? 'event_free_reg' : 'sibling_alumni';

            DB::table('referral_codes_new')->insert([
                'code' => strtoupper((string) $legacy->code),
                'type' => $type,
                'discount_amount' => $isFreeCode ? 250000 : 500000,
                'max_uses' => max(1, (int) ($legacy->quota ?? 1)),
                'used_count' => max(0, (int) $legacy->used_count),
                'is_active' => false,
                'used_by_registration_id' => null,
                'notes' => 'Kode lama: '.$legacy->name,
                'created_at' => $legacy->created_at,
                'updated_at' => $legacy->updated_at,
            ]);
        }

        Schema::drop('referral_codes');
        Schema::rename('referral_codes_new', 'referral_codes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referral_codes');

        Schema::create('referral_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('discount_type', ['percentage', 'fixed']);
            $table->unsignedInteger('discount_value');
            $table->enum('level', ['Semua', 'TK', 'SD', 'SMP', 'SMA'])->default('Semua');
            $table->unsignedInteger('quota')->nullable();
            $table->unsignedInteger('used_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
};
