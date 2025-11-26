<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Pelatihan extends Model
{
    use HasFactory;

    protected $table = 'pelatihan';
    protected $primaryKey = 'id_pelatihan';

    protected $fillable = [
        'nama_pelatihan',
        'deskripsi',
        'id_sektor',
        'penyelenggara',
        'instruktur',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_hari',
        'kuota_peserta',
        'jenis_pelatihan',
        'lokasi',
        'persyaratan',
        'materi',
        'status',
        'sertifikat_tersedia',
        'foto_banner',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'materi' => 'array',
        'sertifikat_tersedia' => 'boolean',
    ];

    // Relationships
    public function sektor()
    {
        return $this->belongsTo(Sektor::class, 'id_sektor', 'id_sektor');
    }

    public function peserta()
    {
        return $this->hasMany(PelatihanPeserta::class, 'id_pelatihan', 'id_pelatihan');
    }

    public function pesertaDiterima()
    {
        return $this->hasMany(PelatihanPeserta::class, 'id_pelatihan', 'id_pelatihan')
                    ->where('status_pendaftaran', 'Diterima');
    }

    // Scopes
    public function scopeDibuka($query)
    {
        return $query->where('status', 'Dibuka')
                     ->where('tanggal_mulai', '>', Carbon::today());
    }

    public function scopeBerlangsung($query)
    {
        return $query->where('status', 'Berlangsung')
                     ->orWhere(function($q) {
                         $q->where('tanggal_mulai', '<=', Carbon::today())
                           ->where('tanggal_selesai', '>=', Carbon::today());
                     });
    }

    public function scopeSelesai($query)
    {
        return $query->where('status', 'Selesai')
                     ->orWhere('tanggal_selesai', '<', Carbon::today());
    }

    // Accessors
    public function getJumlahPesertaAttribute()
    {
        return $this->pesertaDiterima()->count();
    }

    public function getSisaKuotaAttribute()
    {
        return $this->kuota_peserta - $this->jumlah_peserta;
    }

    public function getIsPendaftaranBukaAttribute()
    {
        return $this->status === 'Dibuka' && 
               $this->tanggal_mulai > Carbon::today() && 
               $this->sisa_kuota > 0;
    }

    public function getFotoBannerUrlAttribute()
    {
        return $this->foto_banner ? asset('storage/' . $this->foto_banner) : asset('images/default-training.jpg');
    }
}
