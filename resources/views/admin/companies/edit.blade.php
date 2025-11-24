@extends('layouts.admin')

@section('title', 'Edit Perusahaan')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.companies.show', $company->id_perusahaan) }}" class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Perusahaan</h1>
    </div>
    <p class="text-gray-600">Perbarui informasi perusahaan</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form action="{{ route('admin.companies.update', $company->id_perusahaan) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Logo Upload -->
        <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Logo Perusahaan</label>
            <div class="flex items-center gap-4">
                <div class="shrink-0">
                    @if($company->logo)
                        <img src="{{ asset('storage/'.$company->logo) }}" alt="Logo" class="w-20 h-20 rounded-lg object-cover border-2 border-gray-200" id="preview">
                    @else
                        <div class="w-20 h-20 bg-gradient-to-br from-green-400 to-emerald-500 rounded-lg flex items-center justify-center text-white text-2xl font-bold" id="preview">
                            {{ strtoupper(substr($company->nama_perusahaan, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="flex-1">
                    <input type="file" name="logo" id="logo" accept="image/*" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG. Max: 2MB</p>
                </div>
            </div>
            @error('logo')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Nama Perusahaan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Perusahaan <span class="text-red-500">*</span>
                </label>
                <input type="text" name="nama_perusahaan" value="{{ old('nama_perusahaan', $company->nama_perusahaan) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('nama_perusahaan') border-red-500 @enderror"
                       required>
                @error('nama_perusahaan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Username -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Username <span class="text-red-500">*</span>
                </label>
                <input type="text" name="username" value="{{ old('username', $company->username) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('username') border-red-500 @enderror"
                       required>
                @error('username')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email" value="{{ old('email', $company->email) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- No. Telepon -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon</label>
                <input type="text" name="no_telepon" value="{{ old('no_telepon', $company->no_telepon) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('no_telepon') border-red-500 @enderror">
                @error('no_telepon')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kecamatan -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                <select name="id_kecamatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('id_kecamatan') border-red-500 @enderror">
                    <option value="">Pilih Kecamatan</option>
                    @foreach($kecamatanList as $kec)
                        <option value="{{ $kec->id_kecamatan }}" {{ old('id_kecamatan', $company->id_kecamatan) == $kec->id_kecamatan ? 'selected' : '' }}>
                            {{ $kec->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
                @error('id_kecamatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tahun Berdiri -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Berdiri</label>
                <input type="number" name="tahun_berdiri" value="{{ old('tahun_berdiri', $company->tahun_berdiri) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('tahun_berdiri') border-red-500 @enderror"
                       min="1900" max="{{ date('Y') }}">
                @error('tahun_berdiri')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Website -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                <input type="url" name="website" value="{{ old('website', $company->website) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('website') border-red-500 @enderror"
                       placeholder="https://example.com">
                @error('website')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status Verifikasi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Verifikasi</label>
                <select name="is_verified" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('is_verified') border-red-500 @enderror">
                    <option value="0" {{ old('is_verified', $company->is_verified) == 0 ? 'selected' : '' }}>Belum Verifikasi</option>
                    <option value="1" {{ old('is_verified', $company->is_verified) == 1 ? 'selected' : '' }}>Terverifikasi</option>
                </select>
                @error('is_verified')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Alamat -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap</label>
            <textarea name="alamat" rows="3" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('alamat') border-red-500 @enderror">{{ old('alamat', $company->alamat) }}</textarea>
            @error('alamat')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Deskripsi -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi Perusahaan</label>
            <textarea name="deskripsi" rows="5" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('deskripsi') border-red-500 @enderror"
                      placeholder="Jelaskan tentang perusahaan...">{{ old('deskripsi', $company->deskripsi) }}</textarea>
            @error('deskripsi')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password Change -->
        <div class="mt-6 p-4 bg-amber-50 border border-amber-200 rounded-lg">
            <h3 class="text-sm font-semibold text-amber-900 mb-3">Ubah Password (Opsional)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru</label>
                    <input type="password" name="password" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('password') border-red-500 @enderror"
                           placeholder="Kosongkan jika tidak ingin mengubah">
                    @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" 
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                           placeholder="Ulangi password baru">
                </div>
            </div>
            <p class="text-xs text-amber-700 mt-2">Kosongkan jika tidak ingin mengubah password</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 mt-8">
            <button type="submit" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.companies.show', $company->id_perusahaan) }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

<script src="{{ asset('js/admin-modals.js') }}"></script>
<script>
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('preview');
            preview.outerHTML = '<img src="' + e.target.result + '" alt="Preview" class="w-20 h-20 rounded-lg object-cover border-2 border-gray-200" id="preview">';
        }
        reader.readAsDataURL(file);
    }
});
</script>
@endsection
