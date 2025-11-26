@extends('layouts.admin')

@section('title', 'Edit Pelatihan')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.pelatihan.show', $pelatihan->id_pelatihan) }}" class="text-amber-600 hover:text-amber-700 font-medium mb-2 inline-flex items-center">
            <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <h1 class="text-3xl font-bold text-gray-800 mb-2">Edit Pelatihan</h1>
        <p class="text-gray-600">{{ $pelatihan->nama }}</p>
    </div>

    <form action="{{ route('admin.pelatihan.update', $pelatihan->id_pelatihan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Basic Information -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h2 class="text-xl font-bold text-gray-800 mb-6">Informasi Dasar</h2>
                    
                    <div class="space-y-5">
                        <!-- Nama Pelatihan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Pelatihan <span class="text-red-500">*</span></label>
                            <input type="text" name="nama" value="{{ old('nama', $pelatihan->nama) }}" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                                placeholder="Masukkan nama pelatihan" required>
                            @error('nama')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sektor -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sektor <span class="text-red-500">*</span></label>
                            <select name="id_sektor" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('id_sektor') border-red-500 @enderror" required>
                                <option value="">Pilih Sektor</option>
                                @foreach($sektorList as $sektor)
                                    <option value="{{ $sektor->id_sektor }}" {{ old('id_sektor', $pelatihan->id_sektor) == $sektor->id_sektor ? 'selected' : '' }}>
                                        {{ $sektor->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_sektor')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Deskripsi -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                            <textarea name="deskripsi" rows="5"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('deskripsi') border-red-500 @enderror"
                                placeholder="Jelaskan tentang pelatihan ini..." required>{{ old('deskripsi', $pelatihan->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Penyelenggara & Instruktur -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Penyelenggara <span class="text-red-500">*</span></label>
                                <input type="text" name="penyelenggara" value="{{ old('penyelenggara', $pelatihan->penyelenggara) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('penyelenggara') border-red-500 @enderror"
                                    placeholder="Nama penyelenggara" required>
                                @error('penyelenggara')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Instruktur</label>
                                <input type="text" name="instruktur" value="{{ old('instruktur', $pelatihan->instruktur) }}"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                    placeholder="Nama instruktur">
                            </div>
                        </div>

                        <!-- Persyaratan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Persyaratan</label>
                            <textarea name="persyaratan" rows="3"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                placeholder="Persyaratan untuk mengikuti pelatihan...">{{ old('persyaratan', $pelatihan->persyaratan) }}</textarea>
                        </div>

                        <!-- Foto Banner -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Foto Banner</label>
                            @if($pelatihan->foto_banner)
                            <div class="mb-3">
                                <img src="{{ asset('storage/' . $pelatihan->foto_banner) }}" alt="Current Banner" class="w-full h-48 object-cover rounded-xl">
                                <p class="mt-2 text-sm text-gray-500">Banner saat ini</p>
                            </div>
                            @endif
                            <input type="file" name="foto_banner" accept="image/jpeg,image/png,image/jpg"
                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('foto_banner') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB. Kosongkan jika tidak ingin mengubah</p>
                            @error('foto_banner')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Form -->
            <div class="space-y-6">
                <!-- Schedule Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Jadwal</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Mulai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_mulai" value="{{ old('tanggal_mulai', $pelatihan->tanggal_mulai) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('tanggal_mulai') border-red-500 @enderror" required>
                            @error('tanggal_mulai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Selesai <span class="text-red-500">*</span></label>
                            <input type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', $pelatihan->tanggal_selesai) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('tanggal_selesai') border-red-500 @enderror" required>
                            @error('tanggal_selesai')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi (hari) <span class="text-red-500">*</span></label>
                            <input type="number" name="durasi_hari" value="{{ old('durasi_hari', $pelatihan->durasi_hari) }}" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('durasi_hari') border-red-500 @enderror" required>
                            @error('durasi_hari')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Details Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <h3 class="font-bold text-gray-800 mb-4">Detail</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Pelatihan <span class="text-red-500">*</span></label>
                            <select name="jenis_pelatihan"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('jenis_pelatihan') border-red-500 @enderror" required>
                                <option value="">Pilih Jenis</option>
                                <option value="Online" {{ old('jenis_pelatihan', $pelatihan->jenis_pelatihan) == 'Online' ? 'selected' : '' }}>Online</option>
                                <option value="Offline" {{ old('jenis_pelatihan', $pelatihan->jenis_pelatihan) == 'Offline' ? 'selected' : '' }}>Offline</option>
                                <option value="Hybrid" {{ old('jenis_pelatihan', $pelatihan->jenis_pelatihan) == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
                            </select>
                            @error('jenis_pelatihan')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Lokasi</label>
                            <input type="text" name="lokasi" value="{{ old('lokasi', $pelatihan->lokasi) }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                                placeholder="Lokasi pelatihan">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Kuota Peserta <span class="text-red-500">*</span></label>
                            <input type="number" name="kuota_peserta" value="{{ old('kuota_peserta', $pelatihan->kuota_peserta) }}" min="1"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('kuota_peserta') border-red-500 @enderror" required>
                            @error('kuota_peserta')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                            <select name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent @error('status') border-red-500 @enderror" required>
                                <option value="Dibuka" {{ old('status', $pelatihan->status) == 'Dibuka' ? 'selected' : '' }}>Dibuka</option>
                                <option value="Ditutup" {{ old('status', $pelatihan->status) == 'Ditutup' ? 'selected' : '' }}>Ditutup</option>
                                <option value="Berlangsung" {{ old('status', $pelatihan->status) == 'Berlangsung' ? 'selected' : '' }}>Berlangsung</option>
                                <option value="Selesai" {{ old('status', $pelatihan->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center">
                            <input type="checkbox" name="sertifikat_tersedia" value="1" id="sertifikat" {{ old('sertifikat_tersedia', $pelatihan->sertifikat_tersedia) ? 'checked' : '' }}
                                class="w-4 h-4 text-amber-600 border-gray-300 rounded focus:ring-amber-500">
                            <label for="sertifikat" class="ml-2 text-sm text-gray-700">Sertifikat tersedia</label>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white rounded-2xl shadow-lg p-6">
                    <button type="submit" class="w-full px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all duration-300">
                        <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Pelatihan
                    </button>
                    <a href="{{ route('admin.pelatihan.show', $pelatihan->id_pelatihan) }}" class="block w-full mt-3 px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold text-center hover:bg-gray-300 transition-all duration-300">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
