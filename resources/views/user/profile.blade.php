@extends('layouts.user')

@section('title', 'Edit Profil')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-3xl font-bold text-gray-900">Edit Profil</h2>
        <p class="text-gray-600 mt-1">Perbarui informasi profil dan dokumen Anda</p>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl flex items-center gap-3">
            <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <span class="font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Profile Form -->
    <form action="{{ route('user.profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-2xl shadow-lg p-6 space-y-6">
        @csrf
        @method('PUT')

        <!-- Nama -->
        <div>
            <label for="nama" class="block text-sm font-bold text-gray-700 mb-2">
                Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nama" id="nama" 
                value="{{ old('nama', $user->nama) }}" 
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nama') border-red-500 @enderror"
                required>
            @error('nama')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- NIK -->
        <div>
            <label for="nik" class="block text-sm font-bold text-gray-700 mb-2">
                NIK (16 Digit) <span class="text-red-500">*</span>
            </label>
            <input type="text" name="nik" id="nik" 
                value="{{ old('nik', $user->nik) }}" 
                maxlength="16"
                pattern="[0-9]{16}"
                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('nik') border-red-500 @enderror"
                required>
            @error('nik')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Masukkan 16 digit angka NIK Anda</p>
        </div>

        <!-- Foto Profil -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">
                Foto Profil <span class="text-red-500">*</span>
            </label>
            
            <!-- Current Photo Preview -->
            @if($user->foto)
                <div class="mb-4">
                    <p class="text-sm text-gray-600 mb-2">Foto saat ini:</p>
                    <img src="{{ asset('storage/' . $user->foto) }}" 
                         alt="Current Photo" 
                         id="currentFoto"
                         class="w-32 h-32 object-cover rounded-lg border-2 border-gray-200">
                </div>
            @endif

            <!-- Drag & Drop Zone -->
            <div id="fotoDropZone" 
                 class="border-2 border-dashed border-orange-300 rounded-xl p-6 text-center cursor-pointer hover:border-orange-500 transition-all duration-300 bg-gradient-to-br from-orange-50 to-red-50">
                <input type="file" 
                       name="foto" 
                       id="foto"
                       accept="image/jpeg,image/jpg,image/png"
                       class="hidden">
                
                <div id="fotoPlaceholder">
                    <svg class="mx-auto h-16 w-16 text-orange-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <p class="text-base text-gray-600 font-semibold mb-2">Drag & Drop foto baru atau klik untuk browse</p>
                    <p class="text-sm text-gray-500">JPG, PNG (Max. 2MB)</p>
                </div>

                <div id="fotoPreview" class="hidden">
                    <img id="fotoPreviewImage" src="" alt="Preview" class="mx-auto h-40 rounded-lg shadow-md mb-3">
                    <p id="fotoFileName" class="text-sm text-gray-700 font-semibold mb-1"></p>
                    <p id="fotoFileSize" class="text-xs text-gray-500 mb-3"></p>
                    <button type="button" onclick="removeFoto()" 
                            class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
            
            @error('foto')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Upload foto baru untuk mengubah foto profil. Format: JPG, PNG. Maksimal 2MB.</p>
        </div>

        <!-- KTP -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">
                Scan KTP (Opsional)
            </label>
            
            <!-- Current KTP Preview -->
            @if($user->ktp)
                <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-sm text-blue-800 font-semibold mb-2">KTP sudah terupload:</p>
                    @if(str_ends_with($user->ktp, '.pdf'))
                        <a href="{{ asset('storage/' . $user->ktp) }}" target="_blank" 
                           class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </svg>
                            Lihat KTP (PDF)
                        </a>
                    @else
                        <img src="{{ asset('storage/' . $user->ktp) }}" 
                             alt="KTP" 
                             class="w-64 h-auto rounded-lg border-2 border-gray-200">
                    @endif
                </div>
            @endif

            <input type="file" 
                   name="ktp" 
                   id="ktp"
                   accept="image/jpeg,image/jpg,image/png,application/pdf"
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 p-2">
            
            @error('ktp')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, PDF. Maksimal 5MB.</p>
        </div>

        <!-- Sertifikat -->
        <div>
            <label class="block text-sm font-bold text-gray-700 mb-2">
                Sertifikat (Opsional, Bisa Multiple)
            </label>
            
            <!-- Current Certificates Preview -->
            @if($user->sertifikat)
                @php
                    $sertifikats = is_array($user->sertifikat) 
                        ? $user->sertifikat 
                        : json_decode($user->sertifikat, true) ?? [];
                @endphp
                
                @if(count($sertifikats) > 0)
                    <div class="mb-4 p-4 bg-green-50 rounded-lg border border-green-200">
                        <p class="text-sm text-green-800 font-semibold mb-3">Sertifikat yang sudah diupload ({{ count($sertifikats) }}):</p>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @foreach($sertifikats as $index => $cert)
                                <div class="relative group">
                                    @if(str_ends_with($cert, '.pdf'))
                                        <a href="{{ asset('storage/' . $cert) }}" target="_blank" 
                                           class="block p-4 bg-white rounded-lg border-2 border-gray-200 hover:border-blue-500 transition-colors">
                                            <svg class="w-12 h-12 mx-auto text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <p class="text-xs text-center mt-2 text-gray-600">Sertifikat {{ $index + 1 }}</p>
                                        </a>
                                    @else
                                        <img src="{{ asset('storage/' . $cert) }}" 
                                             alt="Sertifikat {{ $index + 1 }}" 
                                             class="w-full h-32 object-cover rounded-lg border-2 border-gray-200">
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endif

            <input type="file" 
                   name="sertifikat[]" 
                   id="sertifikat"
                   accept="image/jpeg,image/jpg,image/png,application/pdf"
                   multiple
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 p-2">
            
            @error('sertifikat.*')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
            <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG, PDF. Maksimal 5MB per file. Bisa upload lebih dari satu.</p>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
            <a href="{{ route('user.dashboard') }}" 
               class="px-6 py-3 bg-gray-200 text-gray-700 font-semibold rounded-lg hover:bg-gray-300 transition-colors">
                Batal
            </a>
            <button type="submit" 
                    class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white font-semibold rounded-lg hover:shadow-lg transform hover:-translate-y-0.5 transition-all">
                <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/ui-utils.js') }}"></script>
<script>
    // Initialize file upload for foto
    uiUtils.initFileUpload({
        inputId: 'foto',
        dropZoneId: 'fotoDropZone',
        placeholderId: 'fotoPlaceholder',
        previewId: 'fotoPreview',
        previewImageId: 'fotoPreviewImage',
        fileNameId: 'fotoFileName',
        fileSizeId: 'fotoFileSize',
        validTypes: ['image/jpeg', 'image/jpg', 'image/png'],
        maxSizeMB: 2
    });

    // Keep removeFoto function for backward compatibility
    function removeFoto() {
        removeFoto();
    }

    // NIK validation - only allow numbers
    uiUtils.numbersOnly('nik');
</script>
@endpush
