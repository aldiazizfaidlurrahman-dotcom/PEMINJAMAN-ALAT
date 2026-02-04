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
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->date('tanggal_dikembalikan')->nullable()->after('catatan');
            $table->integer('hari_keterlambatan')->default(0)->after('tanggal_dikembalikan');
            $table->decimal('denda', 10, 2)->default(0)->after('hari_keterlambatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('peminjaman', function (Blueprint $table) {
            $table->dropColumn(['tanggal_dikembalikan', 'hari_keterlambatan', 'denda']);
        });
    }
};
