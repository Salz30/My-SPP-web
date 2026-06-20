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
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            // Menyambungkan log ini dengan siapa pelakunya (Admin/Kasir)
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->string('tindakan'); // Contoh: "Pelunasan SPP"
            $table->text('deskripsi');  // Contoh: "Menerima uang Rp 150.000 dari Agnes"
            $table->timestamps();       // Otomatis mencatat Waktu (Jam & Tanggal)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
