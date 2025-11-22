<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_kecamatan' => 'sometimes|required|exists:kecamatan,id_kecamatan',
            'id_kategori' => 'sometimes|required|exists:sektor,id_sektor',
            'nama_pekerjaan' => 'sometimes|required|string|max:255',
            'gaji_min' => 'sometimes|required|numeric|min:0',
            'gaji_max' => 'sometimes|required|numeric|min:0|gte:gaji_min',
            'deskripsi_pekerjaan' => 'sometimes|required|string',
            'persyaratan_pekerjaan' => 'sometimes|required|string',
            'benefit' => 'nullable|string',
            'jumlah_lowongan' => 'sometimes|required|integer|min:1',
            'jenis_pekerjaan' => 'sometimes|required|in:Full-Time,Part-Time,Kontrak',
            'tanggal_expired' => 'sometimes|required|date|after_or_equal:today',
            'status' => 'sometimes|required|in:Diterima,Pending,Ditolak',
        ];
    }

    public function messages()
    {
        return [
            'gaji_max.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum',
            'tanggal_expired.after_or_equal' => 'Tanggal berakhir harus hari ini atau setelahnya',
        ];
    }
}
