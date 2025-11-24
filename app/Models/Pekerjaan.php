<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pekerjaan extends Model
{
    use HasFactory;

    protected $table = 'pekerjaan';
    protected $primaryKey = 'id_pekerjaan';

    protected $fillable = [
        'id_perusahaan',
        'id_kecamatan',
        'id_kategori',
        'nama_pekerjaan',
        'nama_perusahaan',
        'gaji_min',
        'gaji_max',
        'deskripsi_pekerjaan',
        'persyaratan_pekerjaan',
        'benefit',
        'jumlah_lowongan',
        'jenis_pekerjaan',
        'tanggal_expired',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'gaji_min' => 'decimal:2',
        'gaji_max' => 'decimal:2',
        'tanggal_expired' => 'date',
        'tanggal_posting' => 'datetime',
    ];

    // Relationships
    public function perusahaan()
    {
        return $this->belongsTo(Perusahaan::class, 'id_perusahaan', 'id_perusahaan');
    }

    public function kecamatan()
    {
        return $this->belongsTo(Kecamatan::class, 'id_kecamatan', 'id_kecamatan');
    }

    public function kategori()
    {
        return $this->belongsTo(Sektor::class, 'id_kategori', 'id_sektor');
    }

    public function lamaran()
    {
        return $this->hasMany(Lamaran::class, 'id_pekerjaan', 'id_pekerjaan');
    }

    // Scopes
    public function scopeAktif($query)
    {
        return $query->where('status', 'Diterima')
                     ->where('tanggal_expired', '>=', Carbon::today());
    }

    public function scopeBerakhir($query)
    {
        return $query->where('tanggal_expired', '<', Carbon::today());
    }

    public function scopeByKecamatan($query, $idKecamatan)
    {
        return $query->where('id_kecamatan', $idKecamatan);
    }

    public function scopeByKategori($query, $idKategori)
    {
        return $query->where('id_kategori', $idKategori);
    }

    public function scopeByJenis($query, $jenis)
    {
        return $query->where('jenis_pekerjaan', $jenis);
    }

    public function scopeSearch($query, $keyword)
    {
        return $query->where(function ($q) use ($keyword) {
            $q->where('nama_pekerjaan', 'LIKE', "%{$keyword}%")
              ->orWhere('nama_perusahaan', 'LIKE', "%{$keyword}%")
              ->orWhere('deskripsi_pekerjaan', 'LIKE', "%{$keyword}%");
        });
    }

    // Accessors
    public function getIsAktifAttribute()
    {
        return $this->status === 'Diterima' && $this->tanggal_expired >= Carbon::today();
    }

    public function getIsBerakhirAttribute()
    {
        return $this->tanggal_expired < Carbon::today();
    }

    public function getGajiRangeAttribute()
    {
        return 'Rp ' . number_format($this->gaji_min, 0, ',', '.') . ' - Rp ' . number_format($this->gaji_max, 0, ',', '.');
    }

    public function getHariSisaAttribute()
    {
        $diff = Carbon::today()->diffInDays($this->tanggal_expired, false);
        return $diff >= 0 ? $diff : 0;
    }

    public function getJumlahPelamarAttribute()
    {
        return $this->lamaran()->count();
    }
}
