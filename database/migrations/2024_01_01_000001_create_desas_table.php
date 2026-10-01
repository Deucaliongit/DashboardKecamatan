<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel master Desa/Kelurahan.
     * Menjadi induk relasi untuk Sekolah, Anak, dan Faskes.
     */
    public function up(): void
    {
        Schema::create('desas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_desa');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('desas');
    }
};
