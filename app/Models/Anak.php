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
        'faskes_id',
        'desa_id',
        'nama_anak',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_stunting',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected $appends = [
        'umur',
    ];

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function faskes(): BelongsTo
    {
        return $this->belongsTo(Faskes::class);
    }

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * UMUR DIHITUNG DINAMIS — tidak pernah disimpan sebagai angka statis.
     */
    public function getUmurAttribute(): int
    {
        if (! $this->tanggal_lahir) {
            return 0;
        }

        return Carbon::parse($this->tanggal_lahir)->age;
    }

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

    public function scopeKelompokUmurAntara(Builder $query, int $umurMin, int $umurMax): Builder
    {
        $batasAtas  = now()->subYears($umurMin)->endOfDay();
        $batasBawah = now()->subYears($umurMax + 1)->addDay()->startOfDay();

        return $query->whereBetween('tanggal_lahir', [$batasBawah, $batasAtas]);
    }
}
