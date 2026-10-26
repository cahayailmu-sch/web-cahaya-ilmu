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
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->string('full_name');
            $table->enum('gender', ['L', 'P']);
            $table->enum('level', ['TK', 'SD', 'SMP', 'SMA']);
            $table->string('previous_school')->nullable();
            $table->string('parent_name');
            $table->string('whatsapp');
            $table->text('address');
            $table->enum('status', ['Baru', 'Diverifikasi', 'Diterima', 'Ditolak'])->default('Baru');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};
