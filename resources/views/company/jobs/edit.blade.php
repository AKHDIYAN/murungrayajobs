@extends('layouts.company')

@section('title', 'Edit Lowongan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Edit Lowongan Pekerjaan</h2>
                <p class="text-gray-600 mt-1">Perbarui informasi lowongan pekerjaan Anda</p>
            </div>
            <a href="{{ route('company.jobs.index') }}" 
               class="px-4 py-2 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('company.jobs.update', $job->id_pekerjaan) }}" method="POST" class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Job Basic Info -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Informasi Dasar</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama Pekerjaan -->
                <div class="md:col-span-2">
                    <label for="nama_pekerjaan" class="block text-sm font-bold text-gray-700 mb-2">
                        Nama Pekerjaan / Judul Lowongan <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama_pekerjaan" 
                           id="nama_pekerjaan" 
                           value="{{ old('nama_pekerjaan', $job->nama_pekerjaan) }}" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('nama_pekerjaan') border-red-500 @enderror"
                           placeholder="Contoh: Staff Administrasi"
                           required>
                    @error('nama_pekerjaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kategori -->
                <div>
                    <label for="id_kategori" class="block text-sm font-bold text-gray-700 mb-2">
                        Kategori Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <select name="id_kategori" 
                            id="id_kategori" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('id_kategori') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Kategori</option>
                        @foreach($kategoriList as $kategori)
                            <option value="{{ $kategori->id_sektor }}" {{ old('id_kategori', $job->id_kategori) == $kategori->id_sektor ? 'selected' : '' }}>
                                {{ $kategori->nama_sektor }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kategori')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Pekerjaan -->
                <div>
                    <label for="jenis_pekerjaan" class="block text-sm font-bold text-gray-700 mb-2">
                        Jenis Pekerjaan <span class="text-red-500">*</span>
                    </label>
                    <select name="jenis_pekerjaan" 
                            id="jenis_pekerjaan" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('jenis_pekerjaan') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Jenis</option>
                        <option value="Full-Time" {{ old('jenis_pekerjaan', $job->jenis_pekerjaan) == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                        <option value="Part-Time" {{ old('jenis_pekerjaan', $job->jenis_pekerjaan) == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                        <option value="Kontrak" {{ old('jenis_pekerjaan', $job->jenis_pekerjaan) == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                    </select>
                    @error('jenis_pekerjaan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="id_kecamatan" class="block text-sm font-bold text-gray-700 mb-2">
                        Lokasi Kerja (Kecamatan) <span class="text-red-500">*</span>
                    </label>
                    <select name="id_kecamatan" 
                            id="id_kecamatan" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('id_kecamatan') border-red-500 @enderror"
                            required>
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatanList as $kecamatan)
                            <option value="{{ $kecamatan->id_kecamatan }}" {{ old('id_kecamatan', $job->id_kecamatan) == $kecamatan->id_kecamatan ? 'selected' : '' }}>
                                {{ $kecamatan->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kecamatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jumlah Lowongan -->
                <div>
                    <label for="jumlah_lowongan" class="block text-sm font-bold text-gray-700 mb-2">
                        Jumlah Lowongan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="jumlah_lowongan" 
                           id="jumlah_lowongan" 
                           value="{{ old('jumlah_lowongan', $job->jumlah_lowongan) }}" 
                           min="1"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('jumlah_lowongan') border-red-500 @enderror"
                           required>
                    @error('jumlah_lowongan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Salary Range -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Gaji</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Gaji Minimum -->
                <div>
                    <label for="gaji_min" class="block text-sm font-bold text-gray-700 mb-2">
                        Gaji Minimum (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="gaji_min" 
                           id="gaji_min" 
                           value="{{ old('gaji_min', $job->gaji_min) }}" 
                           min="0"
                           step="100000"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('gaji_min') border-red-500 @enderror"
                           placeholder="Contoh: 3000000"
                           required>
                    @error('gaji_min')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Gaji Maximum -->
                <div>
                    <label for="gaji_max" class="block text-sm font-bold text-gray-700 mb-2">
                        Gaji Maksimum (Rp) <span class="text-red-500">*</span>
                    </label>
                    <input type="number" 
                           name="gaji_max" 
                           id="gaji_max" 
                           value="{{ old('gaji_max', $job->gaji_max) }}" 
                           min="0"
                           step="100000"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('gaji_max') border-red-500 @enderror"
                           placeholder="Contoh: 5000000"
                           required>
                    @error('gaji_max')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Gaji maksimum harus lebih besar atau sama dengan gaji minimum</p>
                </div>
            </div>
        </div>

        <!-- Job Details -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Detail Pekerjaan</h3>

            <!-- Deskripsi Pekerjaan -->
            <div>
                <label for="deskripsi_pekerjaan" class="block text-sm font-bold text-gray-700 mb-2">
                    Deskripsi Pekerjaan <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi_pekerjaan" 
                          id="deskripsi_pekerjaan" 
                          rows="6"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('deskripsi_pekerjaan') border-red-500 @enderror"
                          placeholder="Jelaskan deskripsi pekerjaan, tanggung jawab, dan tugas yang akan dilakukan..."
                          required>{{ old('deskripsi_pekerjaan', $job->deskripsi_pekerjaan) }}</textarea>
                @error('deskripsi_pekerjaan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Persyaratan Pekerjaan -->
            <div>
                <label for="persyaratan_pekerjaan" class="block text-sm font-bold text-gray-700 mb-2">
                    Persyaratan / Kualifikasi <span class="text-red-500">*</span>
                </label>
                <textarea name="persyaratan_pekerjaan" 
                          id="persyaratan_pekerjaan" 
                          rows="6"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('persyaratan_pekerjaan') border-red-500 @enderror"
                          placeholder="Tuliskan persyaratan kandidat seperti pendidikan, pengalaman, keahlian, dll..."
                          required>{{ old('persyaratan_pekerjaan', $job->persyaratan_pekerjaan) }}</textarea>
                @error('persyaratan_pekerjaan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Benefit -->
            <div>
                <label for="benefit" class="block text-sm font-bold text-gray-700 mb-2">
                    Benefit / Fasilitas (Opsional)
                </label>
                <textarea name="benefit" 
                          id="benefit" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('benefit') border-red-500 @enderror"
                          placeholder="Sebutkan benefit yang ditawarkan seperti BPJS, tunjangan, bonus, dll...">{{ old('benefit', $job->benefit) }}</textarea>
                @error('benefit')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Expiry Date -->
        <div class="space-y-6">
            <h3 class="text-lg font-bold text-gray-900 border-b pb-2">Masa Aktif Lowongan</h3>

            <div>
                <label for="tanggal_expired" class="block text-sm font-bold text-gray-700 mb-2">
                    Tanggal Berakhir <span class="text-red-500">*</span>
                </label>
                <input type="date" 
                       name="tanggal_expired" 
                       id="tanggal_expired" 
                       value="{{ old('tanggal_expired', $job->tanggal_expired ? $job->tanggal_expired->format('Y-m-d') : '') }}" 
                       min="{{ date('Y-m-d') }}"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('tanggal_expired') border-red-500 @enderror"
                       required>
                @error('tanggal_expired')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Tanggal berakhir harus hari ini atau setelahnya. Lowongan akan otomatis tidak aktif setelah tanggal ini.</p>
            </div>
        </div>

        <!-- Important Notice -->
        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-4 border border-amber-200">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-900 mb-1">Perhatian:</p>
                    <ul class="text-sm text-gray-700 space-y-1">
                        <li>• Pastikan semua informasi yang diisi akurat dan lengkap</li>
                        <li>• Tanggal expired tidak dapat diatur ke masa lalu</li>
                        <li>• Perubahan data akan memerlukan persetujuan ulang dari admin (jika diperlukan)</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('company.jobs.index') }}" 
               class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-semibold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
