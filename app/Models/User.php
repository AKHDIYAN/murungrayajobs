<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'user';
    protected $primaryKey = 'id_user';

    protected $fillable = [
        'nama_lengkap',
        'nama',
        'username',
        'password',
        'nik',
        'jenis_kelamin',
        'tanggal_lahir',
        'alamat',
        'id_kecamatan',
        'id_pendidikan',
        'no_telepon',
        'email',
        'foto', // REQUIRED
        'cv',
        'ktp',
        'sertifikat',
        'foto_diri',
        // New workforce data fields
        'status_kerja',
        'pekerjaan_saat_ini',
        'pengalaman_kerja',
        'jenis_sertifikasi',
        'skills',
        'sertifikat_verified',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_registrasi' => 'datetime',
        'jenis_sertifikasi' => 'array',
        'skills' => 'array',
        'sertifikat_verified' => 'boolean',
    ];

    // Relationships
    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function pendidikan()
    {
        return $this->belongsTo(Pendidikan::class, 'id_pendidikan', 'id_pendidikan');
    }

    public function usia()
    {
        return $this->belongsTo(Usia::class, 'id_usia', 'id_usia');
    }

    public function lamaran()
    {
        return $this->hasMany(Lamaran::class, 'id_user', 'id_user');
    }

    public function pelatihanPeserta()
    {
        return $this->hasMany(PelatihanPeserta::class, 'id_user', 'id_user');
    }

    public function pelatihanDiikuti()
    {
        return $this->belongsToMany(Pelatihan::class, 'pelatihan_peserta', 'id_user', 'id_pelatihan')
                    ->withPivot('status_pendaftaran', 'lulus', 'nomor_sertifikat')
                    ->withTimestamps();
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
