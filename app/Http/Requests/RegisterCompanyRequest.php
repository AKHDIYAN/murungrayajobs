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
            'username' => 'required|string|max:50|unique:perusahaan,username|alpha_dash',
            'password' => 'required|string|min:8|confirmed',
            'nama_perusahaan' => 'required|string|max:255',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'alamat' => 'nullable|string|max:500',
            'no_telepon' => 'nullable|string|regex:/^[0-9]{10,15}$/',
            'email' => 'required|email|max:255|unique:perusahaan,email',
            'deskripsi' => 'nullable|string|max:1000',
            'logo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'terms' => 'accepted',
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan, silakan pilih username lain.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dash, dan underscore.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'nama_perusahaan.required' => 'Nama perusahaan wajib diisi.',
            'id_kecamatan.required' => 'Kecamatan wajib dipilih.',
            'id_kecamatan.exists' => 'Kecamatan yang dipilih tidak valid.',
            'no_telepon.regex' => 'Nomor telepon harus berisi 10-15 digit angka.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain.',
            'logo.required' => 'Logo perusahaan wajib diupload.',
            'logo.image' => 'File yang diupload harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPG, JPEG, atau PNG.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan.',
        ];
    }
}
