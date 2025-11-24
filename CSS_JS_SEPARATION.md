# CSS & JavaScript Assets - Pemisahan dari Blade Files

## Overview

Semua CSS dan JavaScript yang sebelumnya embedded di dalam file Blade telah dipisahkan ke file terpisah untuk:

-   **Maintainability**: Lebih mudah dikelola dan di-update
-   **Reusability**: Dapat digunakan kembali di berbagai halaman
-   **Performance**: Browser caching lebih efektif
-   **Clean Code**: File Blade lebih bersih dan fokus pada markup

## Struktur File

### CSS Files (public/css/)

```
public/css/
├── map.css              # Styles untuk halaman peta (map.blade.php)
├── jobs.css             # Styles untuk halaman daftar lowongan (jobs/index.blade.php)
├── job-detail.css       # Styles untuk halaman detail lowongan (jobs/show.blade.php)
├── home.css             # Styles untuk homepage
├── admin.css            # Styles untuk admin dashboard
├── admin-login.css      # Styles untuk admin login
├── user.css             # Styles untuk user pages
├── animations.css       # Reusable animations
└── app.css              # Global styles
```

### JavaScript Files (public/js/)

```
public/js/
├── map.js                           # Map initialization & kecamatan markers
├── job-detail.js                    # Job detail page interactions
├── statistics-charts.js             # Chart.js initialization untuk statistics
├── admin-dashboard.js               # Admin dashboard functionality
├── admin-modals.js                  # Admin modal handlers
├── admin-statistics-dashboard.js    # Admin statistics dengan charts
└── ui-utils.js                      # Reusable UI utilities
```

## Implementasi di Blade Files

### 1. Map Page (resources/views/map.blade.php)

**Sebelum:**

-   130+ baris embedded CSS
-   200+ baris embedded JavaScript

**Sesudah:**

```php
@push('styles')
<link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">
<link rel="stylesheet" href="{{ asset('css/map.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
<script src="{{ asset('js/map.js') }}"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const kecamatanStatsFromServer = @json($kecamatanStats);
    const jobsRoute = "{{ route('jobs.index') }}";
    initializeMap(kecamatanStatsFromServer, jobsRoute);
});
</script>
@endpush
```

**Fungsi JavaScript yang tersedia:**

-   `initializeMap(kecamatanStats, jobsRoute)` - Initialize peta dengan markers
-   `resetMapView()` - Reset view ke posisi default
-   `getMarkerStyle(jumlah)` - Get marker styling berdasarkan jumlah lowongan
-   `createPopupContent(kec, jobsRoute)` - Generate HTML popup

### 2. Jobs Index (resources/views/jobs/index.blade.php)

**Sebelum:**

-   50+ baris embedded CSS untuk scrollbar & range slider

**Sesudah:**

```php
@push('styles')
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
<link rel="stylesheet" href="{{ asset('css/jobs.css') }}">
@endpush

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush
```

### 3. Job Detail (resources/views/jobs/show.blade.php)

**Sebelum:**

-   30+ baris embedded CSS untuk animations
-   60+ baris embedded JavaScript

**Sesudah:**

```php
@push('styles')
<link rel="stylesheet" href="{{ asset('css/job-detail.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/job-detail.js') }}"></script>
<script>
window.jobUrl = "{{ route('jobs.show', $job->id_pekerjaan) }}";
window.copyJobLink = function() {
    copyJobLink(window.jobUrl);
};
</script>
@endpush
```

**Fungsi JavaScript yang tersedia:**

-   `copyJobLink(url)` - Copy job URL ke clipboard
-   `showFileName(input, targetId)` - Show selected filename & validation
-   `showToast(message, type)` - Display toast notification
-   `initSmoothScroll()` - Smooth scroll untuk apply form links

## Auth Pages (Sudah Menggunakan Vite)

Auth pages sudah menggunakan Vite asset bundling:

### User Login

```php
@push('styles')
@vite('resources/css/user-login.css')
@endpush

@push('scripts')
@vite('resources/js/user-login.js')
@endpush
```

### Company Login

```php
@push('styles')
@vite('resources/css/company-login.css')
@endpush
```

### Company Register

```php
@push('styles')
@vite('resources/css/company-register.css')
@endpush
```

## Best Practices

### 1. CSS Organization

-   File CSS dipisahkan per halaman/fitur
-   Reusable styles (animations, utilities) di file terpisah
-   Menggunakan Tailwind CSS untuk utility classes
-   Custom CSS hanya untuk hal yang tidak bisa dilakukan Tailwind

### 2. JavaScript Organization

-   Function modular dan reusable
-   Dokumentasi JSDoc untuk setiap function
-   Global functions minimal, prefer scoped functions
-   Event listeners di-initialize setelah DOM ready

### 3. Loading Assets

-   CSS di `@push('styles')` agar load di `<head>`
-   JS di `@push('scripts')` agar load sebelum `</body>`
-   External libraries (Leaflet, Alpine.js) load duluan
-   Custom scripts load setelah dependencies

## Performance Optimizations

1. **Browser Caching**: Separate files lebih baik di-cache
2. **Parallel Loading**: Multiple small files bisa parallel download
3. **Lazy Loading**: Bisa implementasi lazy load untuk pages jarang diakses
4. **Minification**: Ready untuk minification di production

## Production Checklist

Untuk production, tambahkan ke build process:

-   [ ] Minify CSS dengan `cssnano` atau `postcss`
-   [ ] Minify JS dengan `terser` atau `uglify-js`
-   [ ] Add cache busting dengan version/hash di filename
-   [ ] Combine beberapa small files jika perlu
-   [ ] Compress dengan gzip/brotli di server

## File Size Comparison

### Before (Embedded)

-   map.blade.php: ~18 KB (dengan CSS & JS)
-   jobs/show.blade.php: ~21 KB (dengan CSS & JS)

### After (Separated)

-   map.blade.php: ~10 KB (markup only)
-   map.css: ~2.5 KB
-   map.js: ~7.7 KB
-   jobs/show.blade.php: ~17 KB (markup only)
-   job-detail.css: ~0.5 KB
-   job-detail.js: ~2.9 KB

**Total size sama, tapi:**

-   CSS & JS dapat di-cache secara terpisah
-   Markup blade lebih mudah dibaca dan di-maintain
-   Assets bisa digunakan di multiple pages

## Maintenance Guide

### Menambah Style Baru

1. Cek apakah style sudah ada di Tailwind utilities
2. Jika custom, tambahkan ke file CSS yang sesuai
3. Jika general reusable, tambahkan ke `animations.css` atau `app.css`

### Menambah JavaScript Baru

1. Buat function modular di file JS yang sesuai
2. Tambahkan JSDoc comment
3. Export function jika perlu digunakan di tempat lain
4. Test di browser untuk memastikan tidak ada error

### Update Existing Code

1. Edit file CSS/JS yang sesuai
2. Hard refresh browser (Ctrl+F5) untuk bypass cache
3. Test semua halaman yang menggunakan file tersebut
4. Commit changes dengan message yang jelas

## Notes

-   File auth (login/register) tetap menggunakan Vite karena sudah setup dari awal
-   Admin pages sudah menggunakan separate files sejak awal
-   Statistics page menggunakan Chart.js dari CDN (bisa pindah ke local jika perlu)
-   Leaflet.js sudah local di `public/assets/leaflet/`

## Related Files

-   Layout master: `resources/views/layouts/app.blade.php`
-   Admin layout: `resources/views/layouts/admin.blade.php`
-   Vite config: `vite.config.js`
-   Package.json: Dependencies untuk Vite & build tools
