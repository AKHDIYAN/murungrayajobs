<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class StoreJobRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_kategori' => 'required|exists:sektor,id_sektor',
            'nama_pekerjaan' => 'required|string|max:255',
            'gaji_min' => 'required|numeric|min:0',
            'gaji_max' => 'required|numeric|min:0|gte:gaji_min',
            'deskripsi_pekerjaan' => 'required|string',
            'persyaratan_pekerjaan' => 'required|string',
            'benefit' => 'nullable|string',
            'jumlah_lowongan' => 'required|integer|min:1',
            'jenis_pekerjaan' => 'required|in:Full-Time,Part-Time,Kontrak',
            'tanggal_expired' => 'required|date|after_or_equal:today',
            'status' => 'nullable|in:Diterima,Pending,Ditolak',
        ];
    }

    public function messages()
    {
        return [
            'id_kecamatan.required' => 'Kecamatan wajib dipilih',
            'id_kategori.required' => 'Kategori/sektor wajib dipilih',
            'nama_pekerjaan.required' => 'Nama pekerjaan wajib diisi',
            'gaji_min.required' => 'Gaji minimum wajib diisi',
            'gaji_max.required' => 'Gaji maksimum wajib diisi',
            'gaji_max.gte' => 'Gaji maksimum harus lebih besar atau sama dengan gaji minimum',
            'deskripsi_pekerjaan.required' => 'Deskripsi pekerjaan wajib diisi',
            'persyaratan_pekerjaan.required' => 'Persyaratan pekerjaan wajib diisi',
            'jumlah_lowongan.required' => 'Jumlah lowongan wajib diisi',
            'jenis_pekerjaan.required' => 'Jenis pekerjaan wajib dipilih',
            'tanggal_expired.required' => 'Tanggal berakhir lowongan wajib diisi',
            'tanggal_expired.after_or_equal' => 'Tanggal berakhir harus hari ini atau setelahnya',
        ];
    }
}
