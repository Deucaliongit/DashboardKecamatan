<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sekolah extends Model
{
    use HasFactory;

    protected $fillable = [
        'desa_id',
        'nama_sekolah',
        'jenjang',
        'jumlah_guru',
        'jumlah_siswa_l',
        'jumlah_siswa_p',
    ];

    protected $casts = [
        'jumlah_guru' => 'integer',
        'jumlah_siswa_l' => 'integer',
        'jumlah_siswa_p' => 'integer',
    ];

    protected $appends = [
        'jumlah_siswa',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Data anak/siswa individual yang tercatat di sekolah ini
     * (dipakai untuk kebutuhan tracking kesehatan/stunting, BUKAN sumber Total Siswa).
     */
    public function anaks(): HasMany
    {
        return $this->hasMany(Anak::class);
    }

    /**
     * Akun user_sekolah yang mengelola sekolah ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * TOTAL SISWA = Laki-laki + Perempuan (diinput langsung di form Sekolah).
     * Ini yang dipakai di semua widget & rekap dashboard pendidikan.
     */
    public function getJumlahSiswaAttribute(): int
    {
        return (int) ($this->jumlah_siswa_l ?? 0) + (int) ($this->jumlah_siswa_p ?? 0);
    }
}
