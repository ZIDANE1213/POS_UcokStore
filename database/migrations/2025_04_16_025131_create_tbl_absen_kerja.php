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
        Schema::create('absen_kerjas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->date('tanggal_masuk')->nullable();
            $table->enum('status_masuk', ['masuk', 'sakit', 'cuti'])->default('masuk');
            $table->time('waktu_mulai_kerja')->nullable();
            $table->time('waktu_akhir_kerja')->nullable();
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_absen_kerja');
    }
};

