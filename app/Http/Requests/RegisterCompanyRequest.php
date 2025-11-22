<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'username' => 'required|string|max:255|unique:perusahaan,username',
            'password' => 'required|string|min:8|confirmed',
            'nama_perusahaan' => 'required|string|max:255',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|min:10|max:15',
            'email' => 'required|email|unique:perusahaan,email',
            'deskripsi' => 'nullable|string',
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048', // WAJIB, max 2MB
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi',
            'id_kecamatan.required' => 'Kecamatan wajib dipilih',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'logo.required' => 'Logo perusahaan wajib diupload',
            'logo.image' => 'File harus berupa gambar',
            'logo.mimes' => 'Logo harus berformat JPG, JPEG, atau PNG',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ];
    }
}
