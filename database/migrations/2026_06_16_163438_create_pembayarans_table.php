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
        Schema::create('pembayaran', function (Blueprint $table) {
            $table->id('id_pembayaran');
            
            // Relasi Eksplisit 1
            $table->unsignedBigInteger('id_siswa');
            $table->foreign('id_siswa')->references('id_siswa')->on('siswa')->onDelete('cascade');
            
            // Relasi Eksplisit 2
            $table->unsignedBigInteger('id_tagihan');
            $table->foreign('id_tagihan')->references('id_tagihan')->on('tagihan')->onDelete('cascade');
            
            $table->enum('status_bayar', ['Belum Lunas', 'Lunas'])->default('Belum Lunas');
            $table->date('tanggal_bayar')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayarans');
    }
};
