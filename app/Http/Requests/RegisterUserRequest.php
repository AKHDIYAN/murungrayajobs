<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'nama' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:user,username',
            'password' => 'required|string|min:8|confirmed',
            'nik' => 'required|digits:16|unique:user,nik',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string',
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'no_telepon' => 'required|string|min:10|max:15',
            'email' => 'required|email|unique:user,email',
            'foto' => 'required|image|mimes:jpg,jpeg,png|max:2048', // WAJIB, max 2MB
        ];
    }

    public function messages()
    {
        return [
            'nama.required' => 'Nama lengkap wajib diisi',
            'username.required' => 'Username wajib diisi',
            'username.unique' => 'Username sudah digunakan',
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'nik.required' => 'NIK wajib diisi',
            'nik.digits' => 'NIK harus 16 digit',
            'nik.unique' => 'NIK sudah terdaftar',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi',
            'tanggal_lahir.before' => 'Tanggal lahir tidak valid',
            'alamat.required' => 'Alamat wajib diisi',
            'id_kecamatan.required' => 'Kecamatan wajib dipilih',
            'no_telepon.required' => 'No. telepon wajib diisi',
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.unique' => 'Email sudah terdaftar',
            'foto.required' => 'Foto profil wajib diupload',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ];
    }
}
