<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Perusahaan extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'perusahaan';
    protected $primaryKey = 'id_perusahaan';

    protected $fillable = [
        'username',
        'password',
        'nama_perusahaan',
        'id_kecamatan',
        'alamat',
        'no_telepon',
        'email',
        'deskripsi',
        'logo', // REQUIRED
        'is_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
        'tanggal_registrasi' => 'datetime',
    ];

    // Relationships
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function pekerjaan()
    {
        return $this->hasMany(Pekerjaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    // Accessors
    public function getLogoUrlAttribute()
    {
        return $this->logo ? asset('storage/' . $this->logo) : null;
    }

    // Scopes
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }
}
