<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama',
        'username',
        'password',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'id_kecamatan',
        'no_telepon',
        'email',
        'foto', // REQUIRED
        'cv',
        'ktp',
        'sertifikat',
        'foto_diri',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_registrasi' => 'datetime',
    ];

    // Relationships
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function lamaran()
    {
        return $this->hasMany(Lamaran::class, 'id_user', 'id_user');
    }

    // Accessors
    public function getFotoUrlAttribute()
    {
        return $this->foto ? asset('storage/' . $this->foto) : null;
    }

    public function getCvUrlAttribute()
    {
        return $this->cv ? asset('storage/' . $this->cv) : null;
    }

    public function getKtpUrlAttribute()
    {
        return $this->ktp ? asset('storage/' . $this->ktp) : null;
    }

    public function getSertifikatUrlAttribute()
    {
        return $this->sertifikat ? asset('storage/' . $this->sertifikat) : null;
    }

    public function getFotoDiriUrlAttribute()
    {
        return $this->foto_diri ? asset('storage/' . $this->foto_diri) : null;
    }

    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : null;
    }
}
