<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel Fasilitas Kesehatan (Puskesmas/Pustu/Posyandu) per Desa.
     */
    public function up(): void
    {
        Schema::create('faskes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')
                  ->constrained('desas')
                  ->cascadeOnDelete();
            $table->string('nama_faskes');
            $table->enum('jenis', ['Puskesmas', 'Pustu', 'Posyandu']);
            $table->unsignedInteger('jumlah_tenaga_medis')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('faskes');
    }
};
