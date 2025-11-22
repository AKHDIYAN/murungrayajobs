<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;

class ImageService
{
    /**
     * Upload dan resize foto user
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return string Path to uploaded file
     * @throws Exception
     */
    public function uploadUserPhoto($file)
    {
        try {
            $filename = 'user_' . Str::random(20) . '_' . time() . '.jpg';
            $path = 'uploads/foto/' . $filename;
            
            // Resize dan optimize image
            $image = Image::make($file)
                         ->resize(300, 400, function ($constraint) {
                             $constraint->aspectRatio();
                             $constraint->upsize();
                         })
                         ->encode('jpg', 80);
            
            Storage::disk('public')->put($path, $image);
            
            return $path;
        } catch (Exception $e) {
            throw new Exception('Gagal mengupload foto: ' . $e->getMessage());
        }
    }
    
    /**
     * Upload dan resize logo perusahaan
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @return string Path to uploaded file
     * @throws Exception
     */
    public function uploadCompanyLogo($file)
    {
        try {
            $filename = 'logo_' . Str::random(20) . '_' . time() . '.jpg';
            $path = 'uploads/logos/' . $filename;
            
            // Resize dan optimize image
            $image = Image::make($file)
                         ->resize(200, 200, function ($constraint) {
                             $constraint->aspectRatio();
                             $constraint->upsize();
                         })
                         ->encode('jpg', 85);
            
            Storage::disk('public')->put($path, $image);
            
            return $path;
        } catch (Exception $e) {
            throw new Exception('Gagal mengupload logo: ' . $e->getMessage());
        }
    }

    /**
     * Upload dokumen (CV, KTP, Sertifikat)
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $type cv|ktp|sertifikat|foto_diri
     * @return string Path to uploaded file
     * @throws Exception
     */
    public function uploadDocument($file, $type = 'cv')
    {
        try {
            $allowedTypes = ['cv', 'ktp', 'sertifikat', 'foto_diri'];
            
            if (!in_array($type, $allowedTypes)) {
                throw new Exception('Tipe dokumen tidak valid');
            }

            $extension = $file->getClientOriginalExtension();
            $filename = $type . '_' . Str::random(20) . '_' . time() . '.' . $extension;
            $path = 'uploads/' . $type . '/' . $filename;
            
            Storage::disk('public')->put($path, file_get_contents($file));
            
            return $path;
        } catch (Exception $e) {
            throw new Exception('Gagal mengupload dokumen: ' . $e->getMessage());
        }
    }
    
    /**
     * Delete image
     * 
     * @param string $path
     * @return bool
     */
    public function deleteImage($path)
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Update user photo
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $oldPath
     * @return string
     */
    public function updateUserPhoto($file, $oldPath = null)
    {
        // Delete old photo if exists
        if ($oldPath) {
            $this->deleteImage($oldPath);
        }

        // Upload new photo
        return $this->uploadUserPhoto($file);
    }

    /**
     * Update company logo
     * 
     * @param \Illuminate\Http\UploadedFile $file
     * @param string|null $oldPath
     * @return string
     */
    public function updateCompanyLogo($file, $oldPath = null)
    {
        // Delete old logo if exists
        if ($oldPath) {
            $this->deleteImage($oldPath);
        }

        // Upload new logo
        return $this->uploadCompanyLogo($file);
    }
}
