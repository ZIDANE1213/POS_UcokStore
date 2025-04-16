<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('absen', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            // Kolom-kolom lainnya yang dibutuhkan
        });
    }
    
    public function down()
    {
        Schema::dropIfExists('absen');
    }
};
