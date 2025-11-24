# Testing CRUD Lowongan Perusahaan

## 📋 Checklist Fitur yang Sudah Dibuat

### ✅ 1. Halaman Daftar Lowongan (company/jobs/index.blade.php)

-   [x] Layout menggunakan layouts.company
-   [x] Header dengan tombol "Posting Lowongan Baru"
-   [x] Filter status lowongan (Aktif, Pending, Berakhir, Ditolak)
-   [x] Tabel daftar lowongan dengan kolom:
    -   [x] No (nomor urut dengan pagination)
    -   [x] Judul Lowongan (dengan lokasi)
    -   [x] Posisi/Jenis Pekerjaan
    -   [x] Tanggal Dibuat
    -   [x] Tanggal Expired (highlight merah jika expired)
    -   [x] Status (badge dengan warna: Aktif=hijau, Pending=orange, Expired=abu, Ditolak=merah)
    -   [x] Jumlah Pelamar
    -   [x] Aksi (Detail, Edit, Hapus)
-   [x] Pagination
-   [x] Empty state dengan tombol posting lowongan
-   [x] Tips mengelola lowongan

### ✅ 2. Halaman Edit Lowongan (company/jobs/edit.blade.php)

-   [x] Layout menggunakan layouts.company
-   [x] Form edit dengan field lengkap:
    -   [x] Nama Pekerjaan (required)
    -   [x] Kategori Pekerjaan (dropdown, required)
    -   [x] Jenis Pekerjaan (Full-Time/Part-Time/Kontrak, required)
    -   [x] Lokasi Kerja/Kecamatan (dropdown, required)
    -   [x] Jumlah Lowongan (number, min 1, required)
    -   [x] Gaji Minimum (number, required)
    -   [x] Gaji Maksimum (number, harus >= gaji min, required)
    -   [x] Deskripsi Pekerjaan (textarea, required)
    -   [x] Persyaratan/Kualifikasi (textarea, required)
    -   [x] Benefit/Fasilitas (textarea, optional)
    -   [x] Tanggal Expired (date, required, min=today)
-   [x] Form populated dengan data lowongan
-   [x] Method PUT ke route update
-   [x] Validasi error per field
-   [x] Notice box dengan informasi penting
-   [x] Tombol Simpan dan Batal

### ✅ 3. Halaman Detail Lowongan (company/jobs/show.blade.php)

-   [x] Layout menggunakan layouts.company
-   [x] Header dengan judul lowongan dan status badge
-   [x] Tombol Edit dan Kembali
-   [x] Statistik cards:
    -   [x] Total Pelamar
    -   [x] Menunggu Review
    -   [x] Diterima
-   [x] Main content area:
    -   [x] Deskripsi Pekerjaan
    -   [x] Persyaratan & Kualifikasi
    -   [x] Benefit & Fasilitas (jika ada)
-   [x] Sidebar dengan:
    -   [x] Detail Lowongan (Jenis, Lokasi, Gaji, Jumlah, Kategori)
    -   [x] Timeline (Posting, Update, Expired)
    -   [x] Aksi Cepat (Lihat Pelamar, Edit)

### ✅ 4. Controller (CompanyJobController.php)

-   [x] Constructor dengan middleware 'company'
-   [x] Method index(Request $request):
    -   [x] Filter by status (aktif, berakhir, pending, ditolak)
    -   [x] Pagination (10 per page)
    -   [x] Hanya lowongan milik perusahaan login
    -   [x] Order by tanggal_posting desc
-   [x] Method show($id):
    -   [x] Verify ownership (hanya milik perusahaan login)
    -   [x] Load relasi (kecamatan, kategori, lamaran)
    -   [x] Hitung statistik pelamar
-   [x] Method edit($id):
    -   [x] Verify ownership
    -   [x] Load kecamatan dan kategori list
    -   [x] Kirim data ke view
-   [x] Method update(UpdateJobRequest, $id):
    -   [x] Verify ownership
    -   [x] Validasi dengan UpdateJobRequest
    -   [x] Update via JobService
    -   [x] Redirect dengan flash message
-   [x] Method destroy($id):
    -   [x] Verify ownership
    -   [x] Konfirmasi hapus dengan JavaScript
    -   [x] Delete via JobService

### ✅ 5. Validasi (UpdateJobRequest.php)

-   [x] Rules:
    -   [x] id_kecamatan: required, exists in kecamatan
    -   [x] id_kategori: required, exists in sektor
    -   [x] nama_pekerjaan: required, string, max 255
    -   [x] gaji_min: required, numeric, min 0
    -   [x] gaji_max: required, numeric, min 0, gte gaji_min
    -   [x] deskripsi_pekerjaan: required, string
    -   [x] persyaratan_pekerjaan: required, string
    -   [x] benefit: nullable, string
    -   [x] jumlah_lowongan: required, integer, min 1
    -   [x] jenis_pekerjaan: required, in Full-Time/Part-Time/Kontrak
    -   [x] tanggal_expired: required, date, after_or_equal today
-   [x] Custom error messages

### ✅ 6. Middleware CheckJobExpiry

-   [x] Terdaftar di Kernel.php sebagai 'check.job.expiry'
-   [x] Dipasang pada route group 'company'
-   [x] Auto-update status lowongan yang expired
-   [x] Update query: tanggal_expired < today → status = 'Ditolak'

### ✅ 7. Routes (web.php)

-   [x] Middleware ['company', 'check.job.expiry']
-   [x] GET /company/jobs - company.jobs.index
-   [x] GET /company/jobs/create - company.jobs.create
-   [x] POST /company/jobs - company.jobs.store
-   [x] GET /company/jobs/{id} - company.jobs.show
-   [x] GET /company/jobs/{id}/edit - company.jobs.edit
-   [x] PUT /company/jobs/{id} - company.jobs.update
-   [x] DELETE /company/jobs/{id} - company.jobs.destroy

## 🧪 Skenario Testing

### Test Case 1: Akses Halaman Daftar Lowongan ✅

**Steps:**

1. Login sebagai perusahaan
2. Klik menu "Lowongan Saya" di sidebar
3. Atau buka URL: `http://localhost:8000/company/jobs`

**Expected Result:**

-   Halaman daftar lowongan terbuka
-   Tombol "Posting Lowongan Baru" terlihat
-   Filter status tersedia
-   Jika ada lowongan, tampil dalam tabel
-   Jika belum ada, tampil empty state

### Test Case 2: Filter Lowongan by Status ✅

**Steps:**

1. Buka halaman daftar lowongan
2. Pilih filter "Aktif" dari dropdown
3. Klik tombol "Filter"

**Expected Result:**

-   Hanya lowongan dengan status Diterima dan belum expired yang tampil
-   URL berubah menjadi `/company/jobs?status=aktif`

**Test untuk status lain:**

-   Filter "Menunggu Persetujuan" → hanya status Pending
-   Filter "Berakhir/Expired" → hanya yang tanggal_expired < today
-   Filter "Ditolak" → hanya status Ditolak

### Test Case 3: View Detail Lowongan ✅

**Steps:**

1. Di halaman daftar, klik tombol "Detail" (icon mata)
2. Atau buka URL: `http://localhost:8000/company/jobs/{id}`

**Expected Result:**

-   Halaman detail terbuka
-   Menampilkan semua informasi lowongan
-   Statistik pelamar terlihat (Total, Pending, Diterima)
-   Sidebar menampilkan detail lengkap
-   Timeline menampilkan tanggal posting, update, expired
-   Tombol Edit dan Kembali berfungsi

### Test Case 4: Edit Lowongan Sukses ✅

**Input:**

-   Ubah nama pekerjaan: "Senior Staff Administrasi"
-   Ubah gaji min: 4000000
-   Ubah gaji max: 6000000
-   Ubah tanggal expired: 30 hari dari sekarang
-   Field lain tetap

**Expected Result:**

-   Data tersimpan ke database
-   Redirect ke halaman detail lowongan
-   Flash message sukses: "Lowongan pekerjaan berhasil diperbarui."
-   Data yang diubah muncul di detail

**Verifikasi:**

```bash
php artisan tinker
>>> $job = App\Models\Pekerjaan::find(<id_pekerjaan>);
>>> $job->nama_pekerjaan;
>>> $job->gaji_min;
>>> $job->tanggal_expired;
```

### Test Case 5: Validasi Tanggal Expired di Masa Lalu ❌

**Input:**

-   Set tanggal expired: kemarin (masa lalu)
-   Submit form

**Expected Result:**

-   Form tidak tersimpan
-   Error message: "Tanggal berakhir harus hari ini atau setelahnya"
-   Input lain tetap terisi (old input)

### Test Case 6: Validasi Gaji Max < Gaji Min ❌

**Input:**

-   Gaji min: 5000000
-   Gaji max: 3000000 (lebih kecil)
-   Submit form

**Expected Result:**

-   Form tidak tersimpan
-   Error message: "Gaji maksimum harus lebih besar atau sama dengan gaji minimum"

### Test Case 7: Validasi Field Required ❌

**Steps:**

1. Buka halaman edit
2. Kosongkan field "Nama Pekerjaan"
3. Submit form

**Expected Result:**

-   Error message: "The nama pekerjaan field is required."
-   Form tidak tersimpan

**Test untuk field lain:**

-   Kosongkan Kategori → error
-   Kosongkan Jenis Pekerjaan → error
-   Kosongkan Tanggal Expired → error
-   Kosongkan Deskripsi → error
-   Kosongkan Persyaratan → error

### Test Case 8: Hapus Lowongan ✅

**Steps:**

1. Di halaman daftar, klik tombol "Hapus" (icon trash)
2. Konfirmasi popup JavaScript
3. Form di-submit dengan method DELETE

**Expected Result:**

-   Lowongan terhapus dari database
-   Redirect ke halaman daftar lowongan
-   Flash message sukses: "Lowongan pekerjaan berhasil dihapus."
-   Lowongan tidak muncul lagi di daftar

**Warning:**

-   Semua data lamaran untuk lowongan ini juga akan terhapus (cascade)

### Test Case 9: Auto-Expiry Middleware ✅

**Setup:**

1. Buat lowongan dengan tanggal expired besok
2. Edit lowongan, ubah tanggal expired ke kemarin (via database atau edit form dengan bypass validasi)

**Steps:**

1. Akses halaman daftar lowongan: `/company/jobs`
2. Middleware CheckJobExpiry akan dijalankan

**Expected Result:**

-   Lowongan yang expired otomatis berubah status menjadi "Ditolak"
-   Badge status berubah menjadi "Expired" (abu-abu)
-   Lowongan tidak muncul di filter "Aktif"

**Verifikasi:**

```bash
php artisan tinker
>>> $job = App\Models\Pekerjaan::find(<id_pekerjaan>);
>>> $job->status; // should be 'Ditolak'
>>> $job->tanggal_expired < now(); // should be true
```

### Test Case 10: Ownership Verification ✅

**Scenario: Coba akses lowongan perusahaan lain**

**Steps:**

1. Login sebagai Perusahaan A
2. Catat ID lowongan milik Perusahaan B
3. Coba akses: `/company/jobs/{id_lowongan_perusahaan_B}/edit`

**Expected Result:**

-   Error 404 Not Found
-   Controller method `findOrFail()` dengan filter `id_perusahaan` gagal menemukan data
-   Tidak dapat mengedit lowongan perusahaan lain

**Test juga untuk:**

-   View detail lowongan perusahaan lain → 404
-   Update lowongan perusahaan lain → 404
-   Delete lowongan perusahaan lain → 404

### Test Case 11: Pagination ✅

**Setup:**

1. Buat lebih dari 10 lowongan untuk perusahaan yang sama

**Steps:**

1. Buka halaman daftar lowongan
2. Scroll ke bawah tabel

**Expected Result:**

-   Hanya 10 lowongan ditampilkan per halaman
-   Pagination links muncul di bawah tabel
-   Klik halaman 2 → menampilkan lowongan 11-20
-   Nomor urut tetap berurutan (11, 12, 13, ...)

### Test Case 12: Empty State ✅

**Scenario: Perusahaan belum punya lowongan**

**Steps:**

1. Login sebagai perusahaan baru
2. Akses halaman daftar lowongan

**Expected Result:**

-   Tabel tidak muncul
-   Empty state tampil dengan:
    -   Icon briefcase besar
    -   Teks: "Belum Ada Lowongan"
    -   Deskripsi singkat
    -   Tombol "Posting Lowongan Baru"

### Test Case 13: Status Badge Display ✅

**Skenario berbagai status lowongan:**

1. **Lowongan Aktif:**

    - Status DB: 'Diterima'
    - tanggal_expired >= today
    - Badge: Hijau (emerald), icon checkmark, teks "Aktif"

2. **Lowongan Pending:**

    - Status DB: 'Pending'
    - Badge: Orange, icon loading spin, teks "Menunggu"

3. **Lowongan Expired:**

    - Status DB: any (atau 'Ditolak' setelah middleware)
    - tanggal_expired < today
    - Badge: Abu-abu, icon X, teks "Expired"

4. **Lowongan Ditolak:**
    - Status DB: 'Ditolak'
    - tanggal_expired >= today
    - Badge: Merah, icon X, teks "Ditolak"

### Test Case 14: Statistik Pelamar di Detail ✅

**Setup:**

1. Buat beberapa lamaran untuk lowongan ini dengan status berbeda:
    - 2 lamaran status "Pending"
    - 1 lamaran status "Diterima"
    - 1 lamaran status "Ditolak"

**Steps:**

1. Buka detail lowongan

**Expected Result:**

-   Card "Total Pelamar" → 4
-   Card "Menunggu Review" → 2
-   Card "Diterima" → 1

## 🔍 Manual Testing Steps

### 1. Persiapan

```bash
# Pastikan server running
php artisan serve

# Clear cache
php artisan optimize:clear

# Cek middleware terdaftar
php artisan route:list --name=company.jobs
```

### 2. Login Perusahaan

-   Buka: `http://localhost:8000/company/auth/login`
-   Login dengan credentials perusahaan test
-   Username: perusahaan_test
-   Password: password123

### 3. Test Daftar Lowongan

**A. View Daftar**

1. Klik menu "Lowongan Saya" di sidebar
2. Verifikasi tabel muncul dengan data lowongan
3. Cek nomor urut, tanggal, status, jumlah pelamar

**B. Test Filter**

1. Pilih filter "Aktif" → submit
2. Verifikasi hanya lowongan aktif yang muncul
3. Test filter lain (Pending, Berakhir, Ditolak)
4. Klik "Reset" → kembali ke semua status

**C. Test Pagination**

1. Jika ada > 10 lowongan, verifikasi pagination muncul
2. Klik halaman 2
3. Verifikasi nomor urut melanjutkan dari halaman 1

### 4. Test Detail Lowongan

**A. Akses Detail**

1. Klik tombol "Detail" (icon mata biru)
2. Verifikasi semua informasi tampil:
    - Judul lowongan
    - Status badge
    - Statistik pelamar
    - Deskripsi pekerjaan
    - Persyaratan
    - Benefit
    - Detail di sidebar (gaji, lokasi, dll)
    - Timeline (posting, update, expired)

**B. Verifikasi Status**

1. Cek warna badge sesuai dengan status
2. Jika expired, tanggal harus highlight merah
3. Statistik pelamar harus akurat

### 5. Test Edit Lowongan

**A. Akses Form Edit**

1. Dari halaman detail, klik tombol "Edit"
2. Atau dari daftar, klik tombol "Edit" (icon pensil orange)
3. Verifikasi form terisi dengan data lowongan saat ini

**B. Test Update Sukses**

1. Ubah beberapa field:
    - Nama pekerjaan
    - Gaji min & max
    - Tanggal expired (set ke 30 hari dari sekarang)
    - Deskripsi
2. Submit form
3. Verifikasi redirect ke detail
4. Cek flash message sukses
5. Verifikasi data berubah di database

**C. Test Validasi**

1. **Tanggal Expired di Masa Lalu:**
    - Set tanggal expired = kemarin
    - Submit → harus error
2. **Gaji Max < Gaji Min:**
    - Set gaji min = 5jt, gaji max = 3jt
    - Submit → harus error
3. **Field Required Kosong:**
    - Kosongkan nama pekerjaan
    - Submit → harus error
4. **Tanggal Expired Hari Ini:**
    - Set tanggal expired = today
    - Submit → harus sukses (after_or_equal:today)

### 6. Test Hapus Lowongan

**A. Hapus dari Daftar**

1. Klik tombol "Hapus" (icon trash merah)
2. Konfirmasi popup JavaScript
3. Verifikasi lowongan terhapus
4. Cek flash message sukses
5. Verifikasi tidak muncul di daftar

**Warning Test:**

1. Cek database `lamaran` table
2. Lamaran untuk lowongan yang dihapus juga harus terhapus (cascade)

### 7. Test Auto-Expiry Middleware

**A. Setup Data Test**

```bash
php artisan tinker
>>> $job = App\Models\Pekerjaan::find(<id>);
>>> $job->tanggal_expired = now()->subDays(1); // set ke kemarin
>>> $job->status = 'Diterima';
>>> $job->save();
```

**B. Trigger Middleware**

1. Akses halaman mana saja di company (akan trigger middleware)
2. Buka daftar lowongan
3. Verifikasi status lowongan berubah menjadi "Ditolak"
4. Badge berubah menjadi "Expired" (abu-abu)

**C. Verifikasi Database**

```bash
php artisan tinker
>>> $job = App\Models\Pekerjaan::find(<id>);
>>> $job->status; // should be 'Ditolak'
```

### 8. Test Ownership Protection

**A. Setup**

1. Login sebagai Perusahaan A
2. Catat ID lowongan milik Perusahaan A
3. Logout

**B. Test Akses Ilegal**

1. Login sebagai Perusahaan B
2. Coba akses URL:
    - `/company/jobs/{id_perusahaan_A}` → harus 404
    - `/company/jobs/{id_perusahaan_A}/edit` → harus 404
3. Coba submit form update via Postman/curl → harus 404
4. Coba hapus via Postman/curl → harus 404

### 9. Verifikasi UI/UX

**A. Halaman Daftar**

-   Layout konsisten dengan dashboard perusahaan
-   Filter mudah digunakan
-   Tabel responsive
-   Status badge jelas
-   Action buttons terlihat
-   Pagination smooth

**B. Halaman Detail**

-   Informasi lengkap
-   Statistik cards menarik
-   Sidebar informatif
-   Timeline jelas
-   Quick actions berguna

**C. Halaman Edit**

-   Form rapi dan terstruktur
-   Field grouping jelas (Basic Info, Salary, Details, Expiry)
-   Validation messages helpful
-   Notice box memberikan informasi penting
-   Buttons positioned correctly

## 📁 File yang Dibuat/Dimodifikasi

```
resources/
└── views/
    └── company/
        └── jobs/
            ├── index.blade.php ✅ (dibuat lengkap)
            ├── edit.blade.php ✅ (dibuat lengkap)
            └── show.blade.php ✅ (dibuat lengkap)

app/
├── Http/
│   ├── Controllers/
│   │   └── Company/
│   │       └── CompanyJobController.php ✅ (sudah ada, lengkap)
│   ├── Requests/
│   │   └── UpdateJobRequest.php ✅ (sudah ada, validasi lengkap)
│   └── Middleware/
│       └── CheckJobExpiry.php ✅ (sudah ada, auto-update expired)
└── Models/
    └── Pekerjaan.php ✅ (sudah ada dengan scopes)

routes/
└── web.php ✅ (ditambah middleware check.job.expiry)
```

## 🚀 Ready to Test!

Semua fitur sudah lengkap:

1. ✅ Halaman daftar lowongan dengan filter dan pagination
2. ✅ Halaman edit lowongan dengan validasi lengkap
3. ✅ Halaman detail lowongan dengan statistik
4. ✅ Controller dengan ownership verification
5. ✅ Middleware auto-expiry untuk lowongan
6. ✅ Routes dengan middleware lengkap
7. ✅ Validasi tanggal expired (after_or_equal:today)
8. ✅ UI/UX konsisten dengan design system perusahaan

## 📝 Catatan Penting

### Tanggal Expired

-   **Wajib diisi** pada setiap lowongan
-   **Minimal hari ini** (tidak boleh masa lalu)
-   **Auto-update** oleh middleware CheckJobExpiry
-   Lowongan expired otomatis berubah status menjadi "Ditolak"
-   Tampilan badge berubah menjadi "Expired" (abu-abu)

### Ownership Protection

-   Semua operasi (view, edit, update, delete) **dicek ownership**
-   Controller menggunakan query: `where('id_perusahaan', $company->id_perusahaan)`
-   Method `findOrFail()` akan throw 404 jika bukan milik perusahaan login
-   **Tidak bisa akses** lowongan perusahaan lain

### Status Lowongan

1. **Diterima** + belum expired = Badge "Aktif" (hijau)
2. **Pending** = Badge "Menunggu" (orange, spin icon)
3. **Sudah expired** (any status) = Badge "Expired" (abu-abu)
4. **Ditolak** + belum expired = Badge "Ditolak" (merah)

### Auto-Expiry Middleware

-   **CheckJobExpiry** terdaftar sebagai 'check.job.expiry'
-   **Dipasang** pada route group 'company'
-   **Berjalan** setiap request ke halaman perusahaan
-   **Update query:** `tanggal_expired < today AND status != 'Ditolak'` → set status = 'Ditolak'
-   **Efek:** Lowongan expired otomatis tidak aktif

### Validasi Form

-   **Tanggal expired:** after_or_equal:today
-   **Gaji max:** gte:gaji_min
-   **Field required:** nama, kategori, jenis, lokasi, deskripsi, persyaratan, jumlah, tanggal_expired
-   **Field optional:** benefit

## 🔧 Troubleshooting

### Error "Route not found"

```bash
# Clear cache
php artisan route:cache

# Verify routes
php artisan route:list --name=company.jobs
```

### Lowongan tidak auto-expired

```bash
# Cek middleware terdaftar
php artisan route:list --name=company.jobs

# Pastikan middleware 'check.job.expiry' muncul di kolom Middleware
# Jika tidak, tambahkan di routes/web.php
```

### Error 404 saat akses lowongan

-   Pastikan lowongan adalah milik perusahaan yang login
-   Cek id_perusahaan di database
-   Verifikasi query: `where('id_perusahaan', auth()->guard('company')->id())`

### Validasi tanggal expired gagal

```bash
# Cek format tanggal di database
php artisan tinker
>>> $job = App\Models\Pekerjaan::find(<id>);
>>> $job->tanggal_expired; // harus Carbon instance
>>> $job->tanggal_expired->format('Y-m-d');
```

### Status tidak berubah setelah expired

```bash
# Manual trigger middleware
php artisan tinker
>>> App\Models\Pekerjaan::where('tanggal_expired', '<', now())
       ->where('status', '!=', 'Ditolak')
       ->update(['status' => 'Ditolak']);

# Cek hasilnya
>>> App\Models\Pekerjaan::where('tanggal_expired', '<', now())->get();
```

### Flash message tidak muncul

-   Pastikan layout company memiliki section untuk flash messages
-   Cek file `layouts/company.blade.php`
-   Verifikasi session flash sudah di-render

## ✨ Fitur Tambahan yang Bisa Dikembangkan

1. **Duplikasi Lowongan** - Tombol "Duplikasi" untuk copy lowongan existing
2. **Export Data** - Download daftar lowongan ke Excel/PDF
3. **Statistik Dashboard** - Chart lowongan aktif vs expired per bulan
4. **Notifikasi Email** - Email otomatis 3 hari sebelum expired
5. **Perpanjang Expired** - Tombol "Perpanjang" untuk extend tanggal expired
6. **Bulk Action** - Checkbox untuk hapus multiple lowongan sekaligus
7. **Search** - Pencarian lowongan by nama atau deskripsi
8. **Sort** - Sort table by kolom (nama, tanggal, status)
