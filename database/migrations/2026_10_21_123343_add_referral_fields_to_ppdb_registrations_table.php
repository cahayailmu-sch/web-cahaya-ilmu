<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->string('referral_code')->nullable()->after('payment_proof');
            $table->unsignedInteger('discount_amount')->default(0)->after('referral_code');
            $table->unsignedInteger('final_fee')->default(0)->after('discount_amount');
            $table->string('referral_note')->nullable()->after('final_fee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropColumn(['referral_code', 'discount_amount', 'final_fee', 'referral_note']);
        });
    }
};
