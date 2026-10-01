<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faskes extends Model
{
    use HasFactory;

    protected $table = 'faskes';

    protected $fillable = [
        'desa_id',
        'nama_faskes',
        'jenis',
        'jumlah_tenaga_medis',
    ];

    protected $casts = [
        'jumlah_tenaga_medis' => 'integer',
    ];

    public function desa(): BelongsTo
    {
        return $this->belongsTo(Desa::class);
    }

    /**
     * Data anak/stunting yang dipantau fasilitas kesehatan ini.
     */
    public function anaks(): HasMany
    {
        return $this->hasMany(Anak::class);
    }

    /**
     * Akun admin_puskesmas yang mengelola fasilitas ini.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
