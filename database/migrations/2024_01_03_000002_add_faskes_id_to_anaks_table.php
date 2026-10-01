<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * faskes_id menandai Puskesmas/Posyandu mana yang bertanggung jawab
     * memantau anak ini (dipakai untuk pembatasan akses Admin Puskesmas).
     */
    public function up(): void
    {
        Schema::table('anaks', function (Blueprint $table) {
            $table->foreignId('faskes_id')
                ->nullable()
                ->after('sekolah_id')
                ->constrained('faskes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('anaks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('faskes_id');
        });
    }
};
