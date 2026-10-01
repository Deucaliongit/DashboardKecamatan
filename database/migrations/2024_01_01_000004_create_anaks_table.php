<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Data Anak — dipakai lintas modul Pendidikan (siswa) & Kesehatan (stunting).
     * sekolah_id nullable karena tidak semua anak terdata (misal balita) berada di sekolah.
     * Umur TIDAK disimpan statis — dihitung dinamis lewat accessor getUmurAttribute() di Model Anak.
     */
    public function up(): void
    {
        Schema::create('anaks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sekolah_id')
                  ->nullable()
                  ->constrained('sekolahs')
                  ->nullOnDelete();
            $table->foreignId('desa_id')
                  ->constrained('desas')
                  ->cascadeOnDelete();
            $table->string('nama_anak');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->enum('status_stunting', ['Stunting', 'Normal'])->default('Normal');
            $table->timestamps();

            // Index untuk mempercepat query dashboard (rekap stunting per desa)
            $table->index(['desa_id', 'status_stunting']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('anaks');
    }
};
