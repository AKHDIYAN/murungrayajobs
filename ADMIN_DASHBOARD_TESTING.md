# Dashboard Admin - Portal Kerja Murung Raya

## ✅ Fitur yang Sudah Dibuat

### 1. Layout Admin (`resources/views/layouts/admin.blade.php`)

-   ✅ Sidebar fixed dan responsif
-   ✅ Menu navigasi lengkap:
    -   Dashboard
    -   Manajemen User
    -   Manajemen Perusahaan
    -   Lowongan
    -   Lamaran
    -   Statistik
    -   Master Data
    -   Logs
-   ✅ Top bar dengan nama admin, notifikasi, dan tombol logout
-   ✅ Content wrapper dengan `@yield('content')`
-   ✅ Menggunakan Tailwind CSS (via Vite)
-   ✅ Integrasi Chart.js untuk grafik

### 2. Halaman Dashboard (`resources/views/admin/dashboard.blade.php`)

-   ✅ Summary Cards (4 kartu):
    -   Total User
    -   Total Perusahaan
    -   Total Lowongan Aktif
    -   Total Lamaran
-   ✅ Grafik Bar: Lowongan per Perusahaan (Top 10)
-   ✅ Grafik Line: Pendaftaran User per Bulan (6 bulan terakhir)
-   ✅ Section Aktivitas Terbaru

### 3. Controller (`app/Http/Controllers/AdminDashboardController.php`)

-   ✅ Method `index()` dengan logika:
    -   Mengambil total user dari tabel `users`
    -   Mengambil total perusahaan dari tabel `perusahaan`
    -   Mengambil total lowongan aktif dari tabel `pekerjaan`
    -   Mengambil total lamaran dari tabel `lamaran`
    -   Menyiapkan data untuk Chart.js (Top 10 perusahaan & registrasi user)

### 4. Middleware (`app/Http/Middleware/AdminMiddleware.php`)

-   ✅ Validasi akses hanya untuk admin
-   ✅ Redirect ke login jika bukan admin
-   ✅ Menggunakan guard `auth:admin`

### 5. Routes (`routes/web.php`)

-   ✅ Route admin dengan middleware `['admin']`
-   ✅ Route dashboard: `/admin/dashboard`
-   ✅ Route logout admin: `/admin/logout`

---

## 🧪 Testing Flow

### 1. Akses Dashboard Admin

**Skenario**: Admin berhasil login → diarahkan ke `/admin/dashboard`

```bash
# Pastikan server berjalan
php artisan serve

# Akses di browser:
http://127.0.0.1:8000/admin/login
```

**Expected Result**:

-   Admin login dengan username & password
-   Redirect ke `/admin/dashboard`
-   Tampil summary cards dengan data real-time
-   Grafik muncul dengan benar

---

### 2. Validasi Akses Non-Admin

**Skenario**: Jika bukan admin → redirect ke halaman login admin

```bash
# Coba akses langsung tanpa login:
http://127.0.0.1:8000/admin/dashboard
```

**Expected Result**:

-   Redirect ke `/admin/login`
-   Muncul pesan error: "Silakan login sebagai admin terlebih dahulu"

---

### 3. Summary Cards

**Skenario**: Menampilkan total user, perusahaan, lowongan, lamaran

**Test Database**:

```sql
-- Check data di database
SELECT COUNT(*) FROM users;              -- Total User
SELECT COUNT(*) FROM perusahaan;         -- Total Perusahaan
SELECT COUNT(*) FROM pekerjaan WHERE status = 'aktif';  -- Lowongan Aktif
SELECT COUNT(*) FROM lamaran;            -- Total Lamaran
```

**Expected Result**:

-   Angka di dashboard sesuai dengan query database
-   Card hover effect bekerja
-   Icon dan warna sesuai (blue, green, purple, orange)

---

### 4. Grafik Statistik

**Skenario**: Grafik muncul dan data sesuai dengan database

**Test Grafik Bar (Lowongan per Perusahaan)**:

```sql
-- Top 10 perusahaan dengan lowongan terbanyak
SELECT
    p.nama_perusahaan,
    COUNT(pk.id_pekerjaan) as total_lowongan
FROM perusahaan p
LEFT JOIN pekerjaan pk ON p.id_perusahaan = pk.id_perusahaan
GROUP BY p.id_perusahaan
ORDER BY total_lowongan DESC
LIMIT 10;
```

**Test Grafik Line (Pendaftaran User)**:

```sql
-- User yang mendaftar 6 bulan terakhir
SELECT
    DATE_FORMAT(created_at, '%Y-%m') as month,
    COUNT(*) as count
FROM users
WHERE created_at >= DATE_SUB(NOW(), INTERVAL 6 MONTH)
GROUP BY month
ORDER BY month ASC;
```

**Expected Result**:

-   Grafik bar menampilkan 10 perusahaan teratas
-   Grafik line menampilkan tren pendaftaran 6 bulan terakhir
-   Hover tooltip menampilkan detail data
-   Tidak ada error di console browser

---

### 5. Responsiveness

**Skenario**: Sidebar dan dashboard tampil baik di desktop & mobile

**Test Breakpoints**:

-   Desktop: 1920px (sidebar full, grid 4 kolom)
-   Tablet: 768px (sidebar collapse?, grid 2 kolom)
-   Mobile: 375px (sidebar hamburger?, grid 1 kolom)

**Expected Result**:

-   Layout tidak patah
-   Semua elemen terbaca dengan baik
-   Menu sidebar tetap accessible

---

### 6. Navigasi Admin

**Skenario**: Semua menu sidebar berfungsi

**Test Menu**:

```
✅ Dashboard → /admin/dashboard
⏳ Manajemen User → (belum dibuat)
⏳ Manajemen Perusahaan → (belum dibuat)
⏳ Lowongan → (belum dibuat)
⏳ Lamaran → (belum dibuat)
⏳ Statistik → (belum dibuat)
⏳ Master Data → (belum dibuat)
⏳ Logs → (belum dibuat)
✅ Kembali ke Beranda → /
✅ Logout → POST /admin/logout
```

**Expected Result**:

-   Menu yang sudah ada berfungsi
-   Menu yang belum ada bisa dikembangkan kemudian
-   Active state menu berjalan dengan benar

---

### 7. Logout Admin

**Skenario**: Admin dapat logout tanpa error

```bash
# Klik tombol logout di sidebar
# Confirm dialog muncul: "Yakin ingin keluar?"
# Klik OK
```

**Expected Result**:

-   Session admin dihapus
-   Redirect ke `/admin/login`
-   Tidak bisa akses `/admin/dashboard` lagi tanpa login ulang

---

## 🐛 Debugging Tips

### Error: Class 'AdminDashboardController' not found

```bash
composer dump-autoload
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Chart.js tidak muncul

```bash
# Check console browser (F12)
# Pastikan Chart.js loaded
# Check data chartData di blade: {{ dd($chartData) }}
```

### Middleware admin tidak bekerja

```bash
# Check apakah middleware terdaftar di Kernel.php
# File: app/Http/Kernel.php
protected $middlewareAliases = [
    'admin' => \App\Http\Middleware\AdminMiddleware::class,
];
```

### Data tidak muncul di summary cards

```bash
# Check database connection
php artisan tinker
>>> User::count();
>>> Perusahaan::count();
>>> Pekerjaan::where('status', 'aktif')->count();
>>> Lamaran::count();
```

---

## 📊 Data Sample untuk Testing

Jika database kosong, gunakan seeder atau insert manual:

```sql
-- Insert sample users
INSERT INTO users (nama, email, password, no_hp, created_at)
VALUES
    ('User 1', 'user1@test.com', bcrypt('password'), '08123456789', NOW()),
    ('User 2', 'user2@test.com', bcrypt('password'), '08123456790', NOW());

-- Insert sample companies
INSERT INTO perusahaan (nama_perusahaan, email, password, no_telp, created_at)
VALUES
    ('PT ABC', 'abc@test.com', bcrypt('password'), '0211234567', NOW()),
    ('CV XYZ', 'xyz@test.com', bcrypt('password'), '0211234568', NOW());

-- Insert sample jobs
INSERT INTO pekerjaan (id_perusahaan, judul, status, created_at)
VALUES
    (1, 'Programmer', 'aktif', NOW()),
    (1, 'Designer', 'aktif', NOW()),
    (2, 'Marketing', 'aktif', NOW());

-- Insert sample applications
INSERT INTO lamaran (id_user, id_pekerjaan, status, created_at)
VALUES
    (1, 1, 'pending', NOW()),
    (2, 2, 'pending', NOW());
```

---

## ✅ Checklist Final

-   [x] Layout admin dibuat
-   [x] Dashboard admin dibuat
-   [x] Controller dengan logika statistik
-   [x] Middleware admin validation
-   [x] Routes terdaftar
-   [x] Summary cards responsive
-   [x] Chart.js terintegrasi
-   [x] Data real-time dari database
-   [x] Logout berfungsi
-   [ ] Testing di browser (perlu dilakukan user)

---

## 🚀 Next Steps (Optional)

1. **Manajemen User**: CRUD user dengan tabel pagination
2. **Manajemen Perusahaan**: Verifikasi perusahaan, suspend/activate
3. **Manajemen Lowongan**: Moderasi lowongan, approve/reject
4. **Manajemen Lamaran**: Lihat semua lamaran, export to Excel
5. **Statistik Lanjutan**: Grafik pie, export PDF report
6. **Master Data**: Kelola sektor, pendidikan, kecamatan
7. **Logs**: Activity log admin, audit trail
8. **Notifications**: Real-time notification untuk admin

---

## 📝 Notes

-   Dashboard menggunakan warna **amber/orange** untuk membedakan dari company (green) dan user (blue)
-   Chart.js menggunakan **bar chart** untuk lowongan per perusahaan dan **line chart** untuk pendaftaran user
-   Data grafik mengambil **6 bulan terakhir** untuk menghindari data terlalu padat
-   Top bar menampilkan **tanggal & waktu real-time** dengan JavaScript
-   Semua komponen menggunakan **Tailwind CSS** via Vite (no CDN)

---

**Created**: 2025-01-23  
**Version**: 1.0  
**Status**: ✅ Ready for Testing
