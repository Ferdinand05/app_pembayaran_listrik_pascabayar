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
        Schema::create('penggunaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pelanggan_id')->references('id')->on('pelanggan')->cascadeOnDelete();
            $table->integer('bulan');
            $table->char('tahun', 5);
            $table->integer('meter_awal');
            $table->integer('meter_akhir');
            $table->integer('tarif_per_kwh');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penggunaan');
    }
};
