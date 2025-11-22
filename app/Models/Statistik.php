<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statistik extends Model
{
    use HasFactory;

    protected $table = 'statistik';
    protected $primaryKey = 'id_statistik';

    protected $fillable = [
        'id_kecamatan',
        'id_pendidikan',
        'id_usia',
        'nama',
        'jenis_kelamin',
        'status',
        'id_sektor',
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

    public function sektor()
    {
        return $this->belongsTo(Sektor::class, 'id_sektor', 'id_sektor');
    }

    // Scopes
    public function scopeBekerja($query)
    {
        return $query->where('status', 'Bekerja');
    }

    public function scopeMenganggur($query)
    {
        return $query->where('status', 'Menganggur');
    }

    public function scopeByKecamatan($query, $idKecamatan)
    {
        return $query->where('id_kecamatan', $idKecamatan);
    }

    public function scopeByPendidikan($query, $idPendidikan)
    {
        return $query->where('id_pendidikan', $idPendidikan);
    }

    public function scopeByUsia($query, $idUsia)
    {
        return $query->where('id_usia', $idUsia);
    }
}
