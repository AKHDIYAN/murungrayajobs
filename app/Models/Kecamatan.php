<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kecamatan extends Model
{
    use HasFactory;

    protected $table = 'kecamatan';
    protected $primaryKey = 'id_kecamatan';

    protected $fillable = [
        'nama_kecamatan',
    ];

    // Relationships
    public function users()
    {
        return $this->hasMany(User::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function perusahaan()
    {
        return $this->hasMany(Perusahaan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function pekerjaan()
    {
        return $this->hasMany(Pekerjaan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function statistik()
    {
        return $this->hasMany(Statistik::class, 'id_kecamatan', 'id_kecamatan');
    }
}
