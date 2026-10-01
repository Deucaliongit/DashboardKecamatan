<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambahkan kolom RBAC ke tabel users bawaan Laravel:
     * - role: admin_kecamatan | user_sekolah
     * - sekolah_id: relasi ke sekolah tempat user_sekolah bertugas (null untuk admin_kecamatan)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin_kecamatan', 'user_sekolah'])
                  ->default('user_sekolah')
                  ->after('email');

            $table->foreignId('sekolah_id')
                  ->nullable()
                  ->after('role')
                  ->constrained('sekolahs')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('sekolah_id');
            $table->dropColumn('role');
        });
    }
};
