@extends('layouts.admin')

@section('title', 'Edit Data Statistik')

@section('content')
<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Data Statistik</h1>
            <p class="text-gray-600 mt-1">Perbarui data ketenagakerjaan</p>
        </div>
        <a href="{{ route('admin.statistics.data.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
            <span class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </span>
        </a>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <form action="{{ route('admin.statistics.update', $data->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div class="md:col-span-2">
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="nama" 
                           name="nama" 
                           value="{{ old('nama', $data->nama) }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                           required>
                    @error('nama')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Jenis Kelamin -->
                <div>
                    <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-2">
                        Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis_kelamin" 
                            name="jenis_kelamin" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            required>
                        <option value="">Pilih Jenis Kelamin</option>
                        <option value="Laki-laki" {{ old('jenis_kelamin', $data->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                        <option value="Perempuan" {{ old('jenis_kelamin', $data->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                    </select>
                    @error('jenis_kelamin')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select id="status" 
                            name="status" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            required>
                        <option value="">Pilih Status</option>
                        <option value="Bekerja" {{ old('status', $data->status) == 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                        <option value="Menganggur" {{ old('status', $data->status) == 'Menganggur' ? 'selected' : '' }}>Menganggur</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kecamatan -->
                <div>
                    <label for="id_kecamatan" class="block text-sm font-medium text-gray-700 mb-2">
                        Kecamatan <span class="text-red-500">*</span>
                    </label>
                    <select id="id_kecamatan" 
                            name="id_kecamatan" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            required>
                        <option value="">Pilih Kecamatan</option>
                        @foreach($kecamatanList as $kecamatan)
                            <option value="{{ $kecamatan->id_kecamatan }}" {{ old('id_kecamatan', $data->id_kecamatan) == $kecamatan->id_kecamatan ? 'selected' : '' }}>
                                {{ $kecamatan->nama_kecamatan }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_kecamatan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pendidikan -->
                <div>
                    <label for="id_pendidikan" class="block text-sm font-medium text-gray-700 mb-2">
                        Pendidikan <span class="text-red-500">*</span>
                    </label>
                    <select id="id_pendidikan" 
                            name="id_pendidikan" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            required>
                        <option value="">Pilih Pendidikan</option>
                        @foreach($pendidikanList as $pendidikan)
                            <option value="{{ $pendidikan->id_pendidikan }}" {{ old('id_pendidikan', $data->id_pendidikan) == $pendidikan->id_pendidikan ? 'selected' : '' }}>
                                {{ $pendidikan->tingkatan_pendidikan }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_pendidikan')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Kelompok Usia -->
                <div>
                    <label for="id_usia" class="block text-sm font-medium text-gray-700 mb-2">
                        Kelompok Usia <span class="text-red-500">*</span>
                    </label>
                    <select id="id_usia" 
                            name="id_usia" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            required>
                        <option value="">Pilih Kelompok Usia</option>
                        @foreach($usiaList as $usia)
                            <option value="{{ $usia->id_usia }}" {{ old('id_usia', $data->id_usia) == $usia->id_usia ? 'selected' : '' }}>
                                {{ $usia->kelompok_usia }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_usia')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Sektor -->
                <div>
                    <label for="id_sektor" class="block text-sm font-medium text-gray-700 mb-2">
                        Sektor <span class="text-red-500">*</span>
                    </label>
                    <select id="id_sektor" 
                            name="id_sektor" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent"
                            required>
                        <option value="">Pilih Sektor</option>
                        @foreach($sektorList as $sektor)
                            <option value="{{ $sektor->id_sektor }}" {{ old('id_sektor', $data->id_sektor) == $sektor->id_sektor ? 'selected' : '' }}>
                                {{ $sektor->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_sektor')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.statistics.data.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg hover:from-amber-600 hover:to-orange-700 transition-all shadow-lg shadow-amber-500/50">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Data
                    </span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
