@extends('layouts.company')

@section('title', 'Profil Perusahaan')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- Profile Form -->
    <form action="{{ route('company.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Company Logo Preview -->
        <div class="mb-6">
            <label class="block text-sm font-bold text-gray-700 mb-3">Logo Perusahaan Saat Ini</label>
            <div class="flex items-center gap-6">
                @if($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" 
                         alt="Logo {{ $company->nama_perusahaan }}" 
                         class="w-32 h-32 object-cover rounded-xl border-2 border-gray-200 shadow-md">
                @else
                    <div class="w-32 h-32 bg-gradient-to-br from-emerald-100 to-teal-100 rounded-xl flex items-center justify-center border-2 border-gray-200">
                        <svg class="w-16 h-16 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                @endif
                <div class="flex-1">
                    <p class="text-sm text-gray-600">Logo akan ditampilkan pada lowongan kerja dan halaman profil publik.</p>
                    <p class="text-sm text-gray-500 mt-1">Ukuran optimal: 200x200 piksel</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Perusahaan (Disabled) -->
            <div class="md:col-span-2">
                <label for="nama_perusahaan" class="block text-sm font-bold text-gray-700 mb-2">
                    Nama Perusahaan
                </label>
                <input type="text" 
                       id="nama_perusahaan" 
                       value="{{ $company->nama_perusahaan }}" 
                       disabled
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                <p class="mt-1 text-xs text-gray-500">Nama perusahaan tidak dapat diubah</p>
            </div>

            <!-- Username (Disabled) -->
            <div>
                <label for="username" class="block text-sm font-bold text-gray-700 mb-2">
                    Username
                </label>
                <input type="text" 
                       id="username" 
                       value="{{ $company->username }}" 
                       disabled
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed">
                <p class="mt-1 text-xs text-gray-500">Username tidak dapat diubah</p>
            </div>

            <!-- Kecamatan -->
            <div>
                <label for="id_kecamatan" class="block text-sm font-bold text-gray-700 mb-2">
                    Kecamatan <span class="text-red-500">*</span>
                </label>
                <select name="id_kecamatan" 
                        id="id_kecamatan" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('id_kecamatan') border-red-500 @enderror"
                        required>
                    <option value="">Pilih Kecamatan</option>
                    @foreach($kecamatanList as $kec)
                        <option value="{{ $kec->id_kecamatan }}" {{ old('id_kecamatan', $company->id_kecamatan) == $kec->id_kecamatan ? 'selected' : '' }}>
                            {{ $kec->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
                @error('id_kecamatan')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-bold text-gray-700 mb-2">
                    Email Perusahaan <span class="text-red-500">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       id="email" 
                       value="{{ old('email', $company->email) }}" 
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- No Telepon -->
            <div>
                <label for="no_telepon" class="block text-sm font-bold text-gray-700 mb-2">
                    No. Telepon
                </label>
                <input type="text" 
                       name="no_telepon" 
                       id="no_telepon" 
                       value="{{ old('no_telepon', $company->no_telepon) }}" 
                       placeholder="08123456789"
                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('no_telepon') border-red-500 @enderror">
                @error('no_telepon')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Minimal 10 digit, maksimal 15 digit</p>
            </div>

            <!-- Alamat Kantor -->
            <div class="md:col-span-2">
                <label for="alamat" class="block text-sm font-bold text-gray-700 mb-2">
                    Alamat Kantor
                </label>
                <textarea name="alamat" 
                          id="alamat" 
                          rows="3"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('alamat') border-red-500 @enderror"
                          placeholder="Masukkan alamat lengkap kantor perusahaan">{{ old('alamat', $company->alamat) }}</textarea>
                @error('alamat')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi Perusahaan -->
            <div class="md:col-span-2">
                <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">
                    Deskripsi Perusahaan
                </label>
                <textarea name="deskripsi" 
                          id="deskripsi" 
                          rows="4"
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                          placeholder="Ceritakan tentang perusahaan Anda, bidang usaha, visi misi, dll.">{{ old('deskripsi', $company->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Deskripsi akan ditampilkan pada halaman detail lowongan</p>
            </div>

            <!-- Logo Upload -->
            <div class="md:col-span-2">
                <label for="logo" class="block text-sm font-bold text-gray-700 mb-2">
                    Update Logo Perusahaan
                </label>
                <input type="file" 
                       name="logo" 
                       id="logo"
                       accept="image/jpeg,image/jpg,image/png"
                       class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 p-2.5 @error('logo') border-red-500 @enderror">
                @error('logo')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-xs text-gray-500">Format: JPG, JPEG, PNG. Maksimal 2MB. Upload file baru untuk mengubah logo.</p>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
            <a href="{{ route('company.dashboard') }}" 
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

    <!-- Informasi Tambahan -->
    <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-2xl shadow-lg p-6 border border-blue-200">
        <div class="flex items-start gap-4">
            <div class="bg-blue-100 p-3 rounded-lg flex-shrink-0">
                <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <div class="flex-1">
                <h4 class="font-bold text-gray-900 mb-2">Informasi Penting</h4>
                <ul class="space-y-1 text-sm text-gray-700">
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Pastikan informasi profil selalu update dan akurat
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Logo yang jelas dan profesional meningkatkan kepercayaan pelamar
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Deskripsi perusahaan yang lengkap membantu menarik kandidat terbaik
                    </li>
                    <li class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Untuk mengubah password, kunjungi halaman Pengaturan
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
