<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Anak extends Model
{
    use HasFactory;

    protected $fillable = [
        'sekolah_id',
        'desa_id',
        'nama_anak',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_stunting',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    /**
     * Ikut disertakan otomatis saat model di-serialize ke array/JSON
     * agar tabel & API dashboard langsung mendapat kolom "umur".
     */
    protected $appends = [
        'umur',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * UMUR DIHITUNG DINAMIS — tidak pernah disimpan sebagai angka statis.
     * Selalu akurat terhadap now(), otomatis bertambah tiap ulang tahun.
     */
    public function getUmurAttribute(): int
    {
        if (! $this->tanggal_lahir) {
            return 0;
        }

        return Carbon::parse($this->tanggal_lahir)->age;
    }

    /**
     * Label kelompok umur untuk ditampilkan di tabel (mis. Balita, Anak, Remaja).
     */
    public function getKelompokUmurAttribute(): string
    {
        $umur = $this->umur;

        return match (true) {
            $umur <= 5   => 'Balita (0-5 th)',
            $umur <= 12  => 'Anak (6-12 th)',
            $umur <= 18  => 'Remaja (13-18 th)',
            default      => 'Dewasa (>18 th)',
        };
    }

    public function scopeStunting(Builder $query): Builder
    {
        return $query->where('status_stunting', 'Stunting');
    }

    public function scopeDesa(Builder $query, ?int $desaId): Builder
    {
        return $desaId ? $query->where('desa_id', $desaId) : $query;
    }

    /**
     * Filter berdasarkan rentang umur (dalam tahun), dihitung dari tanggal_lahir.
     */
    public function scopeKelompokUmurAntara(Builder $query, int $umurMin, int $umurMax): Builder
    {
        $batasAtas  = now()->subYears($umurMin)->endOfDay();          // ultah termuda yg lolos umurMin
        $batasBawah = now()->subYears($umurMax + 1)->addDay()->startOfDay(); // ultah tertua yg lolos umurMax

        return $query->whereBetween('tanggal_lahir', [$batasBawah, $batasAtas]);
    }
}
