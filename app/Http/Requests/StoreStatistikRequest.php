<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStatistikRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'id_pendidikan' => 'required|exists:pendidikan,id_pendidikan',
            'id_usia' => 'required|exists:usia,id_usia',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status' => 'required|in:Bekerja,Menganggur',
            'id_sektor' => 'nullable|exists:sektor,id_sektor',
        ];
    }

    public function messages()
    {
        return [
            'id_kecamatan.required' => 'Kecamatan wajib dipilih',
            'id_pendidikan.required' => 'Pendidikan wajib dipilih',
            'id_usia.required' => 'Kelompok usia wajib dipilih',
            'nama.required' => 'Nama wajib diisi',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'status.required' => 'Status wajib dipilih',
        ];
    }
}
