<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambah role baru 'admin_puskesmas' (khusus mengelola Data Anak & Stunting)
     * dan kolom faskes_id (fasilitas kesehatan yang dipegang akun tersebut).
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY role ENUM('admin_kecamatan', 'user_sekolah', 'admin_puskesmas') NOT NULL DEFAULT 'user_sekolah'");

        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('faskes_id')
                ->nullable()
                ->after('sekolah_id')
                ->constrained('faskes')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('faskes_id');
        });

        DB::statement("ALTER TABLE users MODIFY role ENUM('admin_kecamatan', 'user_sekolah') NOT NULL DEFAULT 'user_sekolah'");
    }
};
