<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'id_pekerjaan' => 'required|exists:pekerjaan,id_pekerjaan',
            'cv' => 'required|file|mimes:pdf,doc,docx|max:5120', // max 5MB
            'ktp' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // max 5MB
            'sertifikat' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120', // max 5MB
            'foto_diri' => 'required|image|mimes:jpg,jpeg,png|max:2048', // max 2MB
        ];
    }

    public function messages()
    {
        return [
            'id_pekerjaan.required' => 'Pekerjaan tidak valid',
            'cv.required' => 'CV wajib diupload',
            'cv.mimes' => 'CV harus berformat PDF, DOC, atau DOCX',
            'cv.max' => 'Ukuran CV maksimal 5MB',
            'ktp.required' => 'KTP wajib diupload',
            'ktp.mimes' => 'KTP harus berformat JPG, PNG, atau PDF',
            'ktp.max' => 'Ukuran KTP maksimal 5MB',
            'sertifikat.mimes' => 'Sertifikat harus berformat PDF, JPG, atau PNG',
            'sertifikat.max' => 'Ukuran sertifikat maksimal 5MB',
            'foto_diri.required' => 'Foto diri wajib diupload',
            'foto_diri.image' => 'Foto diri harus berupa gambar',
            'foto_diri.mimes' => 'Foto diri harus berformat JPG, JPEG, atau PNG',
            'foto_diri.max' => 'Ukuran foto diri maksimal 2MB',
        ];
    }
}
