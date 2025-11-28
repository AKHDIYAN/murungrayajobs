<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PelatihanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        $currentParticipants = $this->peserta?->count() ?? 0;
        
        return [
            'id' => $this->id_pelatihan,
            'nama_pelatihan' => $this->nama_pelatihan,
            'deskripsi' => $this->deskripsi,
            'sektor' => [
                'id' => $this->whenLoaded('sektor', fn() => $this->sektor->id_sektor),
                'nama' => $this->whenLoaded('sektor', fn() => $this->sektor->nama_kategori)
            ],
            'penyelenggara' => $this->penyelenggara,
            'instruktur' => $this->instruktur,
            'jadwal' => [
                'tanggal_mulai' => $this->tanggal_mulai?->format('Y-m-d'),
                'tanggal_selesai' => $this->tanggal_selesai?->format('Y-m-d'),
                'durasi_hari' => $this->durasi_hari
            ],
            'peserta' => [
                'kuota' => $this->kuota_peserta,
                'terdaftar' => $currentParticipants,
                'tersedia' => max(0, $this->kuota_peserta - $currentParticipants),
                'is_full' => $currentParticipants >= $this->kuota_peserta
            ],
            'jenis_pelatihan' => $this->jenis_pelatihan,
            'lokasi' => $this->lokasi,
            'persyaratan' => $this->persyaratan,
            'status' => $this->status,
            'sertifikat_tersedia' => $this->sertifikat_tersedia ?? false,
            'is_registration_open' => $this->status === 'Dibuka' && 
                                    $this->tanggal_mulai > now() && 
                                    $currentParticipants < $this->kuota_peserta,
            'days_until_start' => $this->tanggal_mulai > now() 
                ? $this->tanggal_mulai->diffInDays(now()) 
                : null,
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }
}