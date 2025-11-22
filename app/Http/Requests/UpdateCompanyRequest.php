<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCompanyRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $companyId = $this->route('id') ?? auth()->guard('company')->id();

        return [
            'username' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('perusahaan', 'username')->ignore($companyId, 'id_perusahaan'),
            ],
            'email' => [
                'sometimes',
                'required',
                'email',
                Rule::unique('perusahaan', 'email')->ignore($companyId, 'id_perusahaan'),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'nama_perusahaan' => 'sometimes|required|string|max:255',
            'id_kecamatan' => 'sometimes|required|exists:kecamatan,id_kecamatan',
            'alamat' => 'nullable|string',
            'no_telepon' => 'nullable|string|min:10|max:15',
            'deskripsi' => 'nullable|string',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'username.unique' => 'Username sudah digunakan',
            'email.unique' => 'Email sudah terdaftar',
            'password.min' => 'Password minimal 8 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',
            'logo.image' => 'File harus berupa gambar',
            'logo.mimes' => 'Logo harus berformat JPG, JPEG, atau PNG',
            'logo.max' => 'Ukuran logo maksimal 2MB',
        ];
    }
}
