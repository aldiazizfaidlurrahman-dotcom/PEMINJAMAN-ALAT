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
        Schema::create('peminjaman', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pengguna_id')
          ->constrained('pengguna')
          ->cascadeOnDelete();
    $table->foreignId('alat_id')
          ->constrained('alat')
          ->cascadeOnDelete();
    $table->date('tanggal_pinjam');
    $table->date('tanggal_kembali');
    $table->string('status', 30); // menunggu, disetujui, ditolak, dikembalikan
    $table->text('catatan')->nullable(); // Catatan dari petugas (alasan penolakan, dll)
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peminjaman');
    }
};
