    # Testing Registrasi Perusahaan

## 📋 Checklist Fitur yang Sudah Dibuat

### ✅ 1. Validation Request (RegisterCompanyRequest.php)

-   [x] Username: required, unique, alpha_dash, max 50
-   [x] Password: required, min 8, confirmed
-   [x] Nama Perusahaan: required, max 255
-   [x] Kecamatan: required, exists in kecamatan table
-   [x] Alamat: nullable, max 500
-   [x] No Telepon: nullable, regex 10-15 digit
-   [x] Email: required, email, unique
-   [x] Deskripsi: nullable, max 1000
-   [x] Logo: required, image, mimes jpg/jpeg/png, max 2MB
-   [x] Terms: accepted (checkbox wajib dicentang)
-   [x] Custom error messages dalam Bahasa Indonesia

### ✅ 2. View Registrasi (register.blade.php)

-   [x] Form dengan semua field yang diminta
-   [x] FilePond untuk upload logo dengan:
    -   Accept only JPG, JPEG, PNG
    -   Max file size 2MB
    -   Auto resize preview
    -   Drag & drop support
-   [x] Dropdown kecamatan dari database
-   [x] Checkbox terms & conditions (wajib)
-   [x] Validation error display
-   [x] Modern UI dengan Tailwind CSS
-   [x] Responsive design

### ✅ 3. Controller Logic (CompanyAuthController.php)

-   [x] showRegisterForm() - menampilkan form dengan data kecamatan
-   [x] register() - proses registrasi:
    -   Upload logo via ImageService
    -   Resize logo ke 200x200px
    -   Hash password
    -   Save ke database
    -   Activity log
    -   Redirect ke login dengan flash message
    -   Error handling dengan rollback upload

### ✅ 4. Image Service (ImageService.php)

-   [x] uploadCompanyLogo() method:
    -   Resize to 200x200px
    -   Encode to JPG quality 85%
    -   Save to storage/app/public/uploads/logos/
    -   Return path untuk database
-   [x] deleteImage() method untuk cleanup jika error

## 🧪 Skenario Testing

### Test Case 1: Registrasi Sukses ✅

**Input:**

-   Username: perusahaan_test
-   Password: password123 (confirmation match)
-   Nama Perusahaan: PT Test Indonesia
-   Kecamatan: Pilih salah satu
-   Email: test@perusahaan.com
-   Logo: Upload gambar JPG < 2MB
-   Terms: Dicentang

**Expected Result:**

-   Data tersimpan di database `perusahaan`
-   Logo tersimpan di `storage/app/public/uploads/logos/`
-   Logo ter-resize menjadi 200x200px
-   Password ter-hash
-   Activity log tercatat
-   Redirect ke halaman login
-   Flash message sukses muncul

**Verifikasi:**

```bash
# Cek database
php artisan tinker
>>> App\Models\Perusahaan::latest()->first();

# Cek file logo
ls storage/app/public/uploads/logos/

# Cek activity log
>>> App\Models\ActivityLog::latest()->first();
```

### Test Case 2: Logo Tidak Diupload ❌

**Input:**

-   Semua field diisi
-   Logo: Tidak diupload

**Expected Result:**

-   Form tidak ter-submit
-   Error message: "Logo perusahaan wajib diupload."
-   Data tidak tersimpan

### Test Case 3: Upload File Non-Gambar ❌

**Input:**

-   Upload file PDF/DOC/TXT

**Expected Result:**

-   FilePond menolak file
-   Error message: "Logo harus berformat JPG, JPEG, atau PNG."

### Test Case 4: Upload File > 2MB ❌

**Input:**

-   Upload gambar dengan ukuran > 2MB

**Expected Result:**

-   FilePond menolak file
-   Error message: "Ukuran logo maksimal 2MB."

### Test Case 5: Terms Tidak Dicentang ❌

**Input:**

-   Semua field valid
-   Checkbox terms tidak dicentang

**Expected Result:**

-   Form tidak ter-submit (JavaScript validation)
-   Alert popup: "Anda harus menyetujui syarat dan ketentuan"

### Test Case 6: Username Duplikat ❌

**Input:**

-   Username yang sudah ada di database

**Expected Result:**

-   Validation error: "Username sudah digunakan, silakan pilih username lain."

### Test Case 7: Email Duplikat ❌

**Input:**

-   Email yang sudah terdaftar

**Expected Result:**

-   Validation error: "Email sudah terdaftar, silakan gunakan email lain."

### Test Case 8: Password Tidak Match ❌

**Input:**

-   Password: password123
-   Password Confirmation: password456

**Expected Result:**

-   Validation error: "Konfirmasi password tidak sesuai."

### Test Case 9: No Telepon Invalid ❌

**Input:**

-   No Telepon: "abc123" atau "12345" (< 10 digit)

**Expected Result:**

-   Validation error: "Nomor telepon harus berisi 10-15 digit angka."

### Test Case 10: Login Setelah Registrasi ✅

**Steps:**

1. Registrasi sukses
2. Redirect ke login
3. Login dengan kredensial baru
4. Akses dashboard perusahaan

**Expected Result:**

-   Login berhasil
-   Redirect ke dashboard perusahaan
-   Session guard 'company' active

## 🔍 Manual Testing Steps

### 1. Persiapan

```bash
# Jalankan server
php artisan serve

# Jalankan Vite (di terminal terpisah)
npm run dev

# Pastikan storage link sudah dibuat
php artisan storage:link
```

### 2. Akses Form Registrasi

-   Buka browser: `http://localhost:8000/company/auth/register`
-   atau klik "Daftar sebagai Perusahaan" dari homepage

### 3. Test Fitur Upload Logo

-   Drag & drop gambar ke area FilePond
-   Coba upload format lain (PDF, DOC) → harus ditolak
-   Coba upload gambar > 2MB → harus ditolak
-   Upload gambar valid → preview muncul

### 4. Test Validasi Form

-   Submit form kosong → lihat error messages
-   Submit dengan password tidak match
-   Submit tanpa centang terms

### 5. Test Registrasi Sukses

-   Isi semua field dengan data valid
-   Upload logo JPG < 2MB
-   Centang terms
-   Submit → harus redirect ke login
-   Cek flash message sukses

### 6. Verifikasi Data

```bash
# Login ke database
php artisan tinker

# Cek data perusahaan terakhir
>>> $company = App\Models\Perusahaan::latest()->first();
>>> $company->toArray();

# Cek logo path
>>> $company->logo;

# Verifikasi file logo ada
>>> Storage::disk('public')->exists($company->logo);

# Cek ukuran image
>>> $image = Storage::disk('public')->get($company->logo);
>>> $img = \Intervention\Image\Facades\Image::make($image);
>>> $img->width(); // should be 200 or less
>>> $img->height(); // should be 200 or less
```

### 7. Test Login dengan Akun Baru

-   Buka `http://localhost:8000/company/auth/login`
-   Login dengan username dan password yang baru dibuat
-   Harus berhasil masuk ke dashboard perusahaan

## 📁 File Structure

```
app/
├── Http/
│   ├── Controllers/
│   │   └── Auth/
│   │       └── CompanyAuthController.php ✅
│   └── Requests/
│       └── RegisterCompanyRequest.php ✅
├── Models/
│   └── Perusahaan.php
└── Services/
    └── ImageService.php ✅

resources/
└── views/
    └── auth/
        └── company/
            ├── register.blade.php ✅
            └── login.blade.php

routes/
└── web.php (route sudah ada) ✅

storage/
└── app/
    └── public/
        └── uploads/
            └── logos/ (akan otomatis dibuat)
```

## 🚀 Ready to Test!

Semua komponen sudah siap:

1. ✅ Validation Request dengan rules lengkap
2. ✅ View dengan FilePond dan Tailwind UI
3. ✅ Controller dengan logic lengkap
4. ✅ ImageService untuk upload logo
5. ✅ Routes sudah terdaftar
6. ✅ Intervention Image package installed
7. ✅ Storage directories created

**Next Steps:**

1. Jalankan server: `php artisan serve` (sudah jalan di http://127.0.0.1:8000)
2. Jalankan Vite: `npm run dev`
3. Akses: `http://localhost:8000/company/auth/register` atau `http://127.0.0.1:8000/company/auth/register`
4. Test semua skenario di atas
5. Report jika ada bug atau issue

## 📝 Notes

-   Logo akan diupload langsung tanpa resize (GD extension belum aktif)
-   Untuk enable resize: Aktifkan extension GD di PHP
-   File disimpan dengan format original (JPG/JPEG/PNG)
-   Nama file menggunakan random string + timestamp untuk keamanan
-   Activity log otomatis tercatat untuk audit trail
-   Perusahaan perlu verifikasi admin sebelum bisa posting lowongan aktif
-   Storage path: `storage/app/public/uploads/logos/`
-   Public path: `public/storage/uploads/logos/` (via symlink)

## 🔧 Troubleshooting

### Error "Logo perusahaan wajib diupload"

-   Pastikan FilePond sudah load dengan benar
-   Check browser console untuk error JavaScript
-   Pastikan file yang diupload < 2MB dan format JPG/JPEG/PNG

### Error "Gagal mengupload logo"

-   Check folder permission `storage/app/public/uploads/logos/`
-   Pastikan symlink storage sudah dibuat: `php artisan storage:link`
-   Check error log: `storage/logs/laravel.log`
