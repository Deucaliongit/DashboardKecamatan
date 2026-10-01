<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',        // admin_kecamatan | user_sekolah | admin_puskesmas
        'sekolah_id',  // diisi jika role = user_sekolah
        'faskes_id',   // diisi jika role = admin_puskesmas
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function sekolah(): BelongsTo
    {
        return $this->belongsTo(Sekolah::class);
    }

    public function faskes(): BelongsTo
    {
        return $this->belongsTo(Faskes::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin_kecamatan';
    }

    public function isUserSekolah(): bool
    {
        return $this->role === 'user_sekolah';
    }

    public function isPuskesmas(): bool
    {
        return $this->role === 'admin_puskesmas';
    }
}
