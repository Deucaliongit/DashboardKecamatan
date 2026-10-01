<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel data sekolah (SD/SMP/SMA) di bawah suatu Desa.
     */
    public function up(): void
    {
        Schema::create('sekolahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('desa_id')
                  ->constrained('desas')
                  ->cascadeOnDelete();
            $table->string('nama_sekolah');
            $table->enum('jenjang', ['SD', 'SMP', 'SMA']);
            $table->unsignedInteger('jumlah_guru')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sekolahs');
    }
};
