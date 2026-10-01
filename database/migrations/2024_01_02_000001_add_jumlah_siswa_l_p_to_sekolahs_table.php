<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Total Siswa sekarang dihitung dari penjumlahan Laki-laki + Perempuan
     * yang diinput di level Sekolah (bukan lagi dari hitung baris Data Anak).
     */
    public function up(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->unsignedInteger('jumlah_siswa_l')->default(0)->after('jumlah_guru');
            $table->unsignedInteger('jumlah_siswa_p')->default(0)->after('jumlah_siswa_l');
        });
    }

    public function down(): void
    {
        Schema::table('sekolahs', function (Blueprint $table) {
            $table->dropColumn(['jumlah_siswa_l', 'jumlah_siswa_p']);
        });
    }
};
