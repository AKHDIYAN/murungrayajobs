<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lamaran extends Model
{
    use HasFactory;

    protected $table = 'lamaran';
    protected $primaryKey = 'id_lamaran';

    protected $fillable = [
        'id_pekerjaan',
        'id_user',
        'cv',
        'status',
    ];

    protected $casts = [
        'tanggal_terkirim' => 'datetime',
    ];

    // Relationships
    public function pekerjaan()
    {
        return $this->belongsTo(Pekerjaan::class, 'id_pekerjaan', 'id_pekerjaan');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPelamar($query, $idUser)
    {
        return $query->where('id_user', $idUser);
    }

    public function scopeByPekerjaan($query, $idPekerjaan)
    {
        return $query->where('id_pekerjaan', $idPekerjaan);
    }

    public function scopeByPerusahaan($query, $idPerusahaan)
    {
        return $query->whereHas('pekerjaan', function ($q) use ($idPerusahaan) {
            $q->where('id_perusahaan', $idPerusahaan);
        });
    }

    // Accessors
    public function getCvUrlAttribute()
    {
        return $this->cv ? asset('storage/' . $this->cv) : null;
    }
}
