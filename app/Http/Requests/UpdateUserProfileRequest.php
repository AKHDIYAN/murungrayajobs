<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserProfileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('web')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        $userId = auth('web')->id();

        return [
            'nama' => ['required', 'string', 'max:255'],
            'nik' => [
                'required',
                'numeric',
                'digits:16',
                Rule::unique('user', 'nik')->ignore($userId, 'id_user')
            ],
            'foto' => [
                $this->hasFile('foto') ? 'required' : 'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:2048' // 2MB
            ],
            'ktp' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120' // 5MB
            ],
            'sertifikat.*' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120' // 5MB per file
            ],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'nama.required' => 'Nama wajib diisi.',
            'nama.string' => 'Nama harus berupa teks.',
            'nama.max' => 'Nama maksimal 255 karakter.',
            
            'nik.required' => 'NIK wajib diisi.',
            'nik.numeric' => 'NIK harus berupa angka.',
            'nik.digits' => 'NIK harus 16 digit angka.',
            'nik.unique' => 'NIK sudah terdaftar.',
            
            'foto.required' => 'Foto profil wajib diupload.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus JPG, JPEG, atau PNG.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
            
            'ktp.file' => 'KTP harus berupa file.',
            'ktp.mimes' => 'Format KTP harus JPG, JPEG, PNG, atau PDF.',
            'ktp.max' => 'Ukuran file KTP maksimal 5MB.',
            
            'sertifikat.*.file' => 'Sertifikat harus berupa file.',
            'sertifikat.*.mimes' => 'Format sertifikat harus JPG, JPEG, PNG, atau PDF.',
            'sertifikat.*.max' => 'Ukuran file sertifikat maksimal 5MB per file.',
        ];
    }
}
