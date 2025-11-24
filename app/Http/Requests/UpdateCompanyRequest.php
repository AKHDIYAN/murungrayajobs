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
        $companyId = auth()->guard('company')->id();

        return [
            'email' => [
                'required',
                'email',
                Rule::unique('perusahaan', 'email')->ignore($companyId, 'id_perusahaan'),
            ],
            'id_kecamatan' => 'required|exists:kecamatan,id_kecamatan',
            'alamat' => 'nullable|string|max:500',
            'no_telepon' => 'nullable|string|min:10|max:15',
            'deskripsi' => 'nullable|string|max:1000',
            'logo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan oleh perusahaan lain.',
            'id_kecamatan.required' => 'Kecamatan wajib dipilih.',
            'id_kecamatan.exists' => 'Kecamatan yang dipilih tidak valid.',
            'alamat.max' => 'Alamat maksimal 500 karakter.',
            'no_telepon.min' => 'Nomor telepon minimal 10 digit.',
            'no_telepon.max' => 'Nomor telepon maksimal 15 digit.',
            'deskripsi.max' => 'Deskripsi maksimal 1000 karakter.',
            'logo.image' => 'File harus berupa gambar.',
            'logo.mimes' => 'Logo harus berformat JPG, JPEG, atau PNG.',
            'logo.max' => 'Ukuran logo maksimal 2MB.',
        ];
    }
}
