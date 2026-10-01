<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Desa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_desa',
    ];

    public function sekolahs(): HasMany
    {
        return $this->hasMany(Sekolah::class);
    }

    public function anaks(): HasMany
    {
        return $this->hasMany(Anak::class);
    }

    public function faskes(): HasMany
    {
        return $this->hasMany(Faskes::class);
    }

    /**
     * Total siswa di semua sekolah pada desa ini = jumlah_siswa_l + jumlah_siswa_p
     * dari seluruh sekolah di desa ini (untuk widget dashboard).
     */
    public function getTotalSiswaAttribute(): int
    {
        return (int) $this->sekolahs()->sum('jumlah_siswa_l')
            + (int) $this->sekolahs()->sum('jumlah_siswa_p');
    }

    /**
     * Persentase anak stunting terhadap total anak terdata di desa ini.
     */
    public function getPersentaseStuntingAttribute(): float
    {
        $total = $this->anaks()->count();
        if ($total === 0) {
            return 0;
        }

        $stunting = $this->anaks()->where('status_stunting', 'Stunting')->count();

        return round(($stunting / $total) * 100, 2);
    }
}
