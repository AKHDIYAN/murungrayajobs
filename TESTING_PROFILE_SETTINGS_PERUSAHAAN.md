# Testing Profil & Pengaturan Perusahaan

## 📋 Checklist Fitur yang Sudah Dibuat

### ✅ 1. Halaman Profil Perusahaan (company/profile.blade.php)

-   [x] Layout menggunakan layouts.company
-   [x] Form edit profil dengan field:
    -   [x] Nama perusahaan (disabled, tidak bisa diubah)
    -   [x] Username (disabled, tidak bisa diubah)
    -   [x] Kecamatan (dropdown dari database)
    -   [x] Email perusahaan (required, unique)
    -   [x] No. telepon (nullable, 10-15 digit)
    -   [x] Alamat kantor (textarea)
    -   [x] Deskripsi perusahaan (textarea)
    -   [x] Upload logo baru (JPG/JPEG/PNG, max 2MB)
-   [x] Preview logo perusahaan saat ini
-   [x] Validasi error per field dengan @error directive
-   [x] Flash message sukses/error
-   [x] Form dengan enctype multipart/form-data
-   [x] Method PUT ke route company.profile.update
-   [x] Informasi tips di bagian bawah

### ✅ 2. Halaman Pengaturan (company/settings.blade.php)

-   [x] Layout menggunakan layouts.company
-   [x] Form ganti password dengan field:
    -   [x] Password saat ini (required)
    -   [x] Password baru (required, min 8 karakter)
    -   [x] Konfirmasi password baru (required, must match)
-   [x] Password toggle (show/hide)
-   [x] Validasi error per field
-   [x] Flash message sukses/error
-   [x] Method PUT ke route company.settings.password.update
-   [x] Informasi akun (username, email, status verifikasi)
-   [x] Tips keamanan akun

### ✅ 3. Controller Logic (CompanyDashboardController.php)

-   [x] Method profile() - menampilkan form profil dengan data perusahaan dan list kecamatan
-   [x] Method updateProfile(UpdateCompanyRequest) - update profil:
    -   [x] Upload logo via ImageService
    -   [x] Delete old logo jika ada
    -   [x] Update data: email, kecamatan, alamat, telepon, deskripsi
    -   [x] Tidak mengubah username dan nama_perusahaan
    -   [x] Redirect dengan flash message
    -   [x] Error handling
-   [x] Method settings() - menampilkan halaman pengaturan
-   [x] Method updatePassword(Request) - ganti password:
    -   [x] Validasi password lama benar (Hash::check)
    -   [x] Validasi password baru min 8 karakter
    -   [x] Validasi konfirmasi password
    -   [x] Hash password baru
    -   [x] Redirect dengan flash message
    -   [x] Error handling

### ✅ 4. Form Request (UpdateCompanyRequest.php)

-   [x] Rules validasi:
    -   [x] email: required, email, unique (ignore current company)
    -   [x] id_kecamatan: required, exists in kecamatan table
    -   [x] alamat: nullable, string, max 500
    -   [x] no_telepon: nullable, string, min 10, max 15
    -   [x] deskripsi: nullable, string, max 1000
    -   [x] logo: nullable, image, mimes jpg/jpeg/png, max 2048
-   [x] Custom error messages dalam Bahasa Indonesia
-   [x] Authorize menggunakan guard 'company'

### ✅ 5. Routes (web.php)

-   [x] GET /company/profile - company.profile
-   [x] PUT /company/profile - company.profile.update
-   [x] GET /company/settings - company.settings
-   [x] PUT /company/settings/password - company.settings.password.update
-   [x] Middleware 'company' untuk semua route

## 🧪 Skenario Testing

### Test Case 1: Akses Halaman Profil ✅

**Steps:**

1. Login sebagai perusahaan
2. Klik menu "Profil Perusahaan" di sidebar
3. Buka URL: `http://localhost:8000/company/profile`

**Expected Result:**

-   Halaman profil terbuka
-   Form terisi dengan data perusahaan saat ini
-   Logo perusahaan ditampilkan (jika ada)
-   Nama perusahaan dan username disabled
-   Dropdown kecamatan terisi dengan pilihan saat ini

### Test Case 2: Update Profil Sukses ✅

**Input:**

-   Ubah kecamatan
-   Ubah email: newemail@perusahaan.com
-   Ubah no telepon: 081234567890
-   Ubah alamat: Jl. Baru No. 123
-   Ubah deskripsi: Deskripsi baru perusahaan
-   Tidak upload logo baru (skip)

**Expected Result:**

-   Data tersimpan ke database
-   Redirect ke halaman profil
-   Flash message sukses: "Profil perusahaan berhasil diperbarui."
-   Data yang diubah muncul di form

**Verifikasi:**

```bash
php artisan tinker
>>> $company = App\Models\Perusahaan::find(<id_perusahaan>);
>>> $company->email;
>>> $company->no_telepon;
>>> $company->alamat;
>>> $company->deskripsi;
```

### Test Case 3: Update Logo Sukses ✅

**Input:**

-   Upload logo baru (JPG, < 2MB)
-   Field lain tidak diubah

**Expected Result:**

-   Logo lama dihapus dari storage (jika ada)
-   Logo baru diupload dan resize ke 200x200px
-   Path logo baru tersimpan di database
-   Logo baru muncul di preview
-   Logo baru muncul di dashboard dan sidebar
-   Flash message sukses

**Verifikasi:**

```bash
# Cek file logo baru
ls storage/app/public/uploads/logos/

# Cek database
php artisan tinker
>>> $company = App\Models\Perusahaan::find(<id_perusahaan>);
>>> $company->logo;
>>> Storage::disk('public')->exists($company->logo);
```

### Test Case 4: Validasi Email Duplikat ❌

**Input:**

-   Ubah email ke email yang sudah digunakan perusahaan lain

**Expected Result:**

-   Form tidak tersimpan
-   Error message: "Email sudah digunakan oleh perusahaan lain."
-   Input lain tetap terisi (old input)

### Test Case 5: Validasi Logo Invalid ❌

**Input:**

-   Upload file PDF/DOC (bukan gambar)

**Expected Result:**

-   Form tidak tersimpan
-   Error message: "Logo harus berformat JPG, JPEG, atau PNG."

**Input:**

-   Upload gambar > 2MB

**Expected Result:**

-   Form tidak tersimpan
-   Error message: "Ukuran logo maksimal 2MB."

### Test Case 6: Validasi No Telepon Invalid ❌

**Input:**

-   No telepon: "12345" (< 10 digit)

**Expected Result:**

-   Error message: "Nomor telepon minimal 10 digit."

**Input:**

-   No telepon: "12345678901234567890" (> 15 digit)

**Expected Result:**

-   Error message: "Nomor telepon maksimal 15 digit."

### Test Case 7: Ganti Password Sukses ✅

**Steps:**

1. Buka halaman settings: `http://localhost:8000/company/settings`
2. Input password saat ini: password123
3. Input password baru: newpassword123
4. Input konfirmasi password: newpassword123
5. Submit

**Expected Result:**

-   Password tersimpan di database (hashed)
-   Redirect ke halaman settings
-   Flash message sukses: "Password berhasil diperbarui."
-   Logout otomatis atau tetap login
-   Coba login dengan password lama → gagal
-   Coba login dengan password baru → berhasil

**Verifikasi:**

```bash
php artisan tinker
>>> $company = App\Models\Perusahaan::find(<id_perusahaan>);
>>> Hash::check('password123', $company->password); // false
>>> Hash::check('newpassword123', $company->password); // true
```

### Test Case 8: Password Lama Salah ❌

**Input:**

-   Password saat ini: wrongpassword (salah)
-   Password baru: newpassword123
-   Konfirmasi: newpassword123

**Expected Result:**

-   Form tidak tersimpan
-   Error message di field current_password: "Password saat ini tidak sesuai."
-   Password tidak berubah di database

### Test Case 9: Konfirmasi Password Tidak Cocok ❌

**Input:**

-   Password saat ini: password123 (benar)
-   Password baru: newpassword123
-   Konfirmasi: differentpassword456

**Expected Result:**

-   Form tidak tersimpan
-   Error message: "Konfirmasi password tidak cocok."

### Test Case 10: Password Baru Kurang dari 8 Karakter ❌

**Input:**

-   Password saat ini: password123
-   Password baru: pass123 (7 karakter)
-   Konfirmasi: pass123

**Expected Result:**

-   Error message: "Password baru minimal 8 karakter."

### Test Case 11: Nama dan Username Tidak Berubah ✅

**Scenario:**

-   Coba ubah nama_perusahaan dan username di form (via browser dev tools)
-   Submit form

**Expected Result:**

-   nama_perusahaan dan username tetap tidak berubah di database
-   Controller mengabaikan field tersebut (unset)
-   Field lain yang valid tetap tersimpan

### Test Case 12: Akses Tanpa Login ❌

**Steps:**

1. Logout dari akun perusahaan
2. Coba akses: `http://localhost:8000/company/profile`

**Expected Result:**

-   Redirect ke halaman login perusahaan
-   Middleware 'company' mencegah akses

## 🔍 Manual Testing Steps

### 1. Persiapan

```bash
# Pastikan server running
php artisan serve

# Pastikan storage link dibuat
php artisan storage:link

# Clear cache
php artisan optimize:clear
```

### 2. Login Perusahaan

-   Buka: `http://localhost:8000/company/auth/login`
-   Login dengan credentials perusahaan test (dari TESTING_REGISTRASI_PERUSAHAAN.md)
-   Username: perusahaan_test
-   Password: password123

### 3. Test Edit Profil

**A. Test Update Data Profil**

1. Klik menu "Profil Perusahaan" di sidebar
2. Ubah beberapa field (email, telepon, alamat, deskripsi)
3. Submit
4. Verifikasi data berubah di database dan ditampilkan di form

**B. Test Update Logo**

1. Buka halaman profil
2. Upload logo baru (gambar JPG < 2MB)
3. Submit
4. Verifikasi logo baru muncul di:
    - Preview profil
    - Sidebar dashboard
    - Header dashboard
5. Cek file logo baru di `storage/app/public/uploads/logos/`

**C. Test Validasi**

1. Coba upload logo > 2MB → harus ditolak
2. Coba upload file PDF → harus ditolak
3. Coba email yang sudah ada → harus error
4. Coba no telepon < 10 digit → harus error

### 4. Test Ganti Password

**A. Test Sukses**

1. Klik menu "Pengaturan" di sidebar
2. Input password lama: password123
3. Input password baru: newpassword123
4. Input konfirmasi: newpassword123
5. Submit
6. Logout
7. Login dengan password baru → harus berhasil
8. Coba login dengan password lama → harus gagal

**B. Test Validasi**

1. Password lama salah → harus error "Password saat ini tidak sesuai"
2. Konfirmasi tidak cocok → harus error "Konfirmasi password tidak cocok"
3. Password baru < 8 karakter → harus error "Password baru minimal 8 karakter"

### 5. Verifikasi UI/UX

**A. Halaman Profil**

-   Layout konsisten dengan dashboard
-   Form rapi dan responsive
-   Label jelas
-   Error message muncul di bawah field yang error
-   Flash message muncul di atas
-   Button disabled selama loading (optional)
-   Preview logo terlihat jelas

**B. Halaman Settings**

-   Password toggle berfungsi (show/hide)
-   Informasi akun ditampilkan
-   Status verifikasi ditampilkan dengan badge
-   Tips keamanan informatif
-   Link ke profil untuk edit email berfungsi

## 📁 File yang Dibuat/Dimodifikasi

```
resources/
└── views/
    └── company/
        ├── profile.blade.php ✅ (dibuat lengkap)
        └── settings.blade.php ✅ (dibuat lengkap)

app/
├── Http/
│   ├── Controllers/
│   │   └── Company/
│   │       └── CompanyDashboardController.php ✅ (ditambah updatePassword)
│   └── Requests/
│       └── UpdateCompanyRequest.php ✅ (dimodifikasi rules)
└── Services/
    └── ImageService.php ✅ (sudah ada updateCompanyLogo)

routes/
└── web.php ✅ (ditambah route settings.password.update)
```

## 🚀 Ready to Test!

Semua fitur sudah lengkap dan siap ditest:

1. ✅ Halaman profil dengan form edit lengkap
2. ✅ Upload dan update logo dengan ImageService
3. ✅ Validasi form dengan UpdateCompanyRequest
4. ✅ Halaman settings untuk ganti password
5. ✅ Validasi password lama, baru, dan konfirmasi
6. ✅ Routes sudah terdaftar dengan middleware
7. ✅ Flash messages untuk feedback
8. ✅ Error handling lengkap
9. ✅ UI konsisten dengan design system perusahaan

## 📝 Catatan Penting

-   **Username dan Nama Perusahaan** tidak bisa diubah (ditampilkan disabled di form)
-   **Logo** diupload via ImageService yang otomatis resize ke 200x200px
-   **Password** di halaman settings, tidak di profil (untuk keamanan)
-   **Email** bisa diubah di profil, harus unique
-   **No Telepon** opsional, 10-15 digit jika diisi
-   **Middleware 'company'** melindungi semua route
-   **Guard 'company'** digunakan untuk authentication
-   **Flash messages** untuk feedback user-friendly
-   **Validation errors** per field dengan Blade @error

## 🔧 Troubleshooting

### Error "Logo tidak bisa diupload"

-   Pastikan storage link sudah dibuat: `php artisan storage:link`
-   Cek permission folder: `storage/app/public/uploads/logos/`
-   Cek error log: `storage/logs/laravel.log`

### Error "Route not found"

-   Clear cache: `php artisan route:cache`
-   Verifikasi routes: `php artisan route:list --name=company`

### Error "Email sudah digunakan" padahal email sendiri

-   Pastikan UpdateCompanyRequest ignore current company ID
-   Cek rule: `Rule::unique()->ignore(auth()->guard('company')->id(), 'id_perusahaan')`

### Password tidak bisa login setelah update

-   Pastikan password di-hash dengan `Hash::make()`
-   Verifikasi di tinker: `Hash::check('password', $company->password)`

### Logo tidak muncul setelah upload

-   Cek path logo di database
-   Pastikan storage link dibuat
-   Verifikasi file ada di storage: `Storage::disk('public')->exists($path)`
-   Cek di browser: `http://localhost:8000/storage/uploads/logos/<filename>`
