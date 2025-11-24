@extends('layouts.company')

@section('title', 'Posting Lowongan Baru')

@section('content')
<!-- Header -->
<div class="mb-6">
    <a href="{{ route('company.jobs.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 mb-4 transition-colors duration-200">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
    <h2 class="text-2xl font-bold text-gray-800 mb-2">Posting Lowongan Baru</h2>
    <p class="text-gray-600">Lengkapi form untuk menambahkan lowongan pekerjaan</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Form Section -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6">
                <form method="POST" action="{{ route('company.jobs.store') }}" class="space-y-6">
                    @csrf

                    <!-- Informasi Dasar -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-500 text-white rounded-lg flex items-center justify-center mr-3 text-sm">1</span>
                            Informasi Dasar
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Nama Pekerjaan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                    name="nama_pekerjaan" 
                                    value="{{ old('nama_pekerjaan') }}"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('nama_pekerjaan') border-red-500 @enderror" 
                                    placeholder="Contoh: Staff Administrasi"
                                    required>
                                @error('nama_pekerjaan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Kategori -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Kategori Pekerjaan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="id_kategori" 
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('id_kategori') border-red-500 @enderror" 
                                            required>
                                        <option value="">Pilih Kategori...</option>
                                        @foreach(\App\Models\Sektor::all() as $sektor)
                                            <option value="{{ $sektor->id_sektor }}" {{ old('id_kategori') == $sektor->id_sektor ? 'selected' : '' }}>
                                                {{ $sektor->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kategori')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jenis Pekerjaan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jenis Pekerjaan <span class="text-red-500">*</span>
                                    </label>
                                    <select name="jenis_pekerjaan" 
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('jenis_pekerjaan') border-red-500 @enderror" 
                                            required>
                                        <option value="">Pilih Jenis...</option>
                                        <option value="Full-Time" {{ old('jenis_pekerjaan') == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                                        <option value="Part-Time" {{ old('jenis_pekerjaan') == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                                        <option value="Kontrak" {{ old('jenis_pekerjaan') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                                    </select>
                                    @error('jenis_pekerjaan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Kecamatan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Lokasi Kerja (Kecamatan) <span class="text-red-500">*</span>
                                    </label>
                                    <select name="id_kecamatan" 
                                            class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('id_kecamatan') border-red-500 @enderror" 
                                            required>
                                        <option value="">Pilih Kecamatan...</option>
                                        @foreach(\App\Models\Kecamatan::all() as $kec)
                                            <option value="{{ $kec->id_kecamatan }}" {{ old('id_kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                                                {{ $kec->nama_kecamatan }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_kecamatan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Jumlah Lowongan -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Jumlah Lowongan <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="jumlah_lowongan" 
                                           value="{{ old('jumlah_lowongan', 1) }}"
                                           class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('jumlah_lowongan') border-red-500 @enderror" 
                                           min="1"
                                           required>
                                    @error('jumlah_lowongan')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Gaji -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-500 text-white rounded-lg flex items-center justify-center mr-3 text-sm">2</span>
                            Informasi Gaji
                        </h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Gaji Minimum -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Gaji Minimum <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="number" 
                                           name="gaji_min" 
                                           value="{{ old('gaji_min') }}"
                                           class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('gaji_min') border-red-500 @enderror" 
                                           placeholder="3000000"
                                           min="0"
                                           required>
                                </div>
                                @error('gaji_min')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gaji Maksimum -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Gaji Maksimum <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-500">Rp</span>
                                    <input type="number" 
                                           name="gaji_max" 
                                           value="{{ old('gaji_max') }}"
                                           class="w-full pl-12 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('gaji_max') border-red-500 @enderror" 
                                           placeholder="5000000"
                                           min="0"
                                           required>
                                </div>
                                @error('gaji_max')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Gaji maksimum harus lebih besar atau sama dengan gaji minimum</p>
                    </div>

                    <!-- Detail Pekerjaan -->
                    <div class="border-b border-gray-200 pb-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-500 text-white rounded-lg flex items-center justify-center mr-3 text-sm">3</span>
                            Detail Pekerjaan
                        </h3>
                        
                        <div class="space-y-4">
                            <!-- Deskripsi -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Deskripsi Pekerjaan <span class="text-red-500">*</span>
                                </label>
                                <textarea name="deskripsi_pekerjaan" 
                                          rows="6"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('deskripsi_pekerjaan') border-red-500 @enderror" 
                                          placeholder="Jelaskan tanggung jawab dan tugas dari posisi ini..."
                                          required>{{ old('deskripsi_pekerjaan') }}</textarea>
                                @error('deskripsi_pekerjaan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Persyaratan -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Persyaratan & Kualifikasi <span class="text-red-500">*</span>
                                </label>
                                <textarea name="persyaratan_pekerjaan" 
                                          rows="6"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('persyaratan_pekerjaan') border-red-500 @enderror" 
                                          placeholder="Tuliskan persyaratan yang harus dipenuhi oleh kandidat..."
                                          required>{{ old('persyaratan_pekerjaan') }}</textarea>
                                @error('persyaratan_pekerjaan')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Benefit -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Benefit & Fasilitas <span class="text-gray-400">(Opsional)</span>
                                </label>
                                <textarea name="benefit" 
                                          rows="4"
                                          class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('benefit') border-red-500 @enderror" 
                                          placeholder="Contoh: BPJS, THR, Bonus Kinerja, dll...">{{ old('benefit') }}</textarea>
                                @error('benefit')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Masa Aktif -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                            <span class="w-8 h-8 bg-gradient-to-br from-emerald-500 to-teal-500 text-white rounded-lg flex items-center justify-center mr-3 text-sm">4</span>
                            Masa Aktif Lowongan
                        </h3>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Tanggal Berakhir <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="tanggal_expired" 
                                   value="{{ old('tanggal_expired') }}"
                                   min="{{ date('Y-m-d') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors @error('tanggal_expired') border-red-500 @enderror" 
                                   required>
                            @error('tanggal_expired')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">Lowongan akan otomatis ditutup setelah tanggal ini</p>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-6 border-t border-gray-200">
                        <button type="submit" 
                                class="flex-1 bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 flex items-center justify-center shadow-md hover:shadow-lg">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Posting Lowongan
                        </button>
                        <a href="{{ route('company.jobs.index') }}" 
                           class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1">
        <div class="sticky top-6 space-y-6">
            <!-- Tips Card -->
            <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
                <div class="flex items-center mb-4">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-semibold text-blue-900">Tips Posting Lowongan</h3>
                </div>
                <ul class="space-y-2.5 text-sm text-blue-800">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Gunakan judul yang jelas dan spesifik</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Deskripsikan tanggung jawab pekerjaan dengan detail</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Cantumkan persyaratan yang realistis</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Berikan informasi gaji yang transparan</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-2 mt-0.5 text-blue-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Pastikan tanggal berakhir cukup untuk mendapat pelamar</span>
                    </li>
                </ul>
            </div>

            <!-- Warning Card -->
            <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-xl border border-amber-200 p-6">
                <div class="flex items-start">
                    <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-amber-900 mb-2">Pemberitahuan</h3>
                        <p class="text-sm text-amber-800">Lowongan akan direview oleh admin sebelum dipublikasikan. Proses review membutuhkan waktu maksimal 1x24 jam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
