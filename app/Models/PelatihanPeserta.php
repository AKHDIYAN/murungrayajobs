<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelatihanPeserta extends Model
{
    use HasFactory;

    protected $table = 'pelatihan_peserta';
    protected $primaryKey = 'id_peserta_pelatihan';

    protected $fillable = [
        'id_pelatihan',
        'id_user',
        'status_pendaftaran',
        'alasan_mengikuti',
        'status_kehadiran',
        'persentase_kehadiran',
        'lulus',
        'nilai',
        'nomor_sertifikat',
        'tanggal_sertifikat',
        'catatan',
    ];

    protected $casts = [
        'lulus' => 'boolean',
        'tanggal_sertifikat' => 'date',
    ];

    // Relationships
    public function pelatihan()
    {
        return $this->belongsTo(Pelatihan::class, 'id_pelatihan', 'id_pelatihan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Scopes
    public function scopeDiterima($query)
    {
        return $query->where('status_pendaftaran', 'Diterima');
    }

    public function scopeLulus($query)
    {
        return $query->where('lulus', true);
    }
}
