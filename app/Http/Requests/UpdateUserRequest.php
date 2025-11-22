<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $userId = $this->route('id') ?? auth()->guard('web')->id();

        return [
            'nama' => 'sometimes|required|string|max:255',
            'username' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('user', 'username')->ignore($userId, 'id_user'),
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('user', 'email')->ignore($userId, 'id_user'),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'jenis_kelamin' => 'sometimes|required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'sometimes|required|date|before:today',
            'alamat' => 'sometimes|required|string',
            'id_kecamatan' => 'sometimes|required|exists:kecamatan,id_kecamatan',
            'no_telepon' => 'sometimes|required|string|min:10|max:15',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'foto.image' => 'File harus berupa gambar',
            'foto.mimes' => 'Foto harus berformat JPG, JPEG, atau PNG',
            'foto.max' => 'Ukuran foto maksimal 2MB',
        ];
    }
}
