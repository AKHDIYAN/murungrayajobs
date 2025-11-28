<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WorkforceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nama' => $this->nama,
            'jenis_kelamin' => $this->jenis_kelamin,
            'status_kerja' => $this->status_kerja,
            'pekerjaan_saat_ini' => $this->pekerjaan_saat_ini,
            'pengalaman_kerja' => $this->pengalaman_kerja . ' tahun',
            'skills' => $this->skills ? explode(',', $this->skills) : [],
            'sertifikasi' => [
                'jenis' => $this->jenis_sertifikasi,
                'verified' => $this->sertifikat_verified ?? false
            ],
            'lokasi' => [
                'kecamatan' => $this->whenLoaded('kecamatan', function () {
                    return $this->kecamatan->nama_kecamatan;
                })
            ],
            'pendidikan' => [
                'tingkat' => $this->whenLoaded('pendidikan', function () {
                    return $this->pendidikan->tingkatan_pendidikan;
                })
            ],
            'usia' => [
                'kelompok' => $this->whenLoaded('usia', function () {
                    return $this->usia->kelompok_usia;
                })
            ],
            'created_at' => $this->created_at?->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at?->format('Y-m-d H:i:s')
        ];
    }
}