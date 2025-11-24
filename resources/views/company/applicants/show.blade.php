@extends('layouts.company')

@section('title', 'Detail Pelamar')

@section('content')
<!-- Header -->
<div class="mb-6">
    <a href="{{ route('company.applicants.index') }}" class="inline-flex items-center text-emerald-600 hover:text-emerald-700 mb-4 transition-colors duration-200">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Pelamar
    </a>
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 mb-2">Detail Pelamar</h2>
            <p class="text-gray-600">Informasi lengkap tentang pelamar</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('company.applicants.downloadPDF', $applicant->id_lamaran) }}" 
               class="inline-flex items-center px-6 py-3 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors shadow-md hover:shadow-lg">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10"/>
                </svg>
                Download PDF
            </a>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Profil Pelamar -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Profil Pelamar
                </h3>
            </div>
            <div class="p-6">
                <div class="flex items-start mb-6">
                    <div class="w-20 h-20 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-full flex items-center justify-center text-white text-3xl font-bold mr-6 flex-shrink-0">
                        {{ strtoupper(substr($applicant->user->nama_lengkap, 0, 1)) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-2xl font-bold text-gray-900 mb-2">{{ $applicant->user->nama_lengkap }}</h4>
                        <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                {{ $applicant->user->email }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                {{ $applicant->user->no_telepon ?? '-' }}
                            </div>
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $applicant->user->kecamatan->nama_kecamatan ?? '-' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                        <p class="text-gray-900 font-medium">{{ $applicant->user->tanggal_lahir ? \Carbon\Carbon::parse($applicant->user->tanggal_lahir)->format('d F Y') : '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</label>
                        <p class="text-gray-900 font-medium">{{ $applicant->user->jenis_kelamin ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Pendidikan Terakhir</label>
                        <p class="text-gray-900 font-medium">{{ $applicant->user->pendidikan->tingkat_pendidikan ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Pengalaman Kerja</label>
                        <p class="text-gray-900 font-medium">{{ $applicant->user->pengalaman_kerja ?? '-' }}</p>
                    </div>
                </div>

                @if($applicant->user->alamat)
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Lengkap</label>
                        <p class="text-gray-900">{{ $applicant->user->alamat }}</p>
                    </div>
                @endif

                @if($applicant->user->tentang_saya)
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tentang Saya</label>
                        <p class="text-gray-900">{{ $applicant->user->tentang_saya }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Informasi Lowongan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="bg-gradient-to-r from-blue-500 to-indigo-500 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-semibold text-white flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Lowongan yang Dilamar
                </h3>
            </div>
            <div class="p-6">
                <h4 class="text-xl font-bold text-gray-900 mb-4">{{ $applicant->pekerjaan->nama_pekerjaan }}</h4>
                
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Perusahaan</label>
                        <p class="text-gray-900 font-medium">{{ $applicant->pekerjaan->perusahaan->nama_perusahaan }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Kategori</label>
                        <p class="text-gray-900 font-medium">{{ $applicant->pekerjaan->kategori->nama_kategori ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Pekerjaan</label>
                        <p class="text-gray-900 font-medium">{{ $applicant->pekerjaan->jenis_pekerjaan }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Lokasi</label>
                        <p class="text-gray-900 font-medium">{{ $applicant->pekerjaan->kecamatan->nama_kecamatan ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Range Gaji</label>
                        <p class="text-gray-900 font-medium">Rp {{ number_format($applicant->pekerjaan->gaji_min, 0, ',', '.') }} - Rp {{ number_format($applicant->pekerjaan->gaji_max, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Posting</label>
                        <p class="text-gray-900 font-medium">{{ $applicant->pekerjaan->tanggal_posting->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Surat Lamaran -->
        @if($applicant->surat_lamaran)
            <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 px-6 py-4 rounded-t-xl">
                    <h3 class="text-lg font-semibold text-white flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Surat Lamaran
                    </h3>
                </div>
                <div class="p-6">
                    <div class="prose max-w-none">
                        <p class="text-gray-700 whitespace-pre-line">{{ $applicant->surat_lamaran }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Status Lamaran</h3>
            
            <div class="mb-6">
                @if($applicant->status == 'Pending')
                    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center justify-center mb-2">
                            <div class="w-16 h-16 bg-amber-500 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-white animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-center text-amber-800 font-semibold">Menunggu Review</p>
                    </div>
                @elseif($applicant->status == 'Diterima')
                    <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center justify-center mb-2">
                            <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-center text-green-800 font-semibold">Diterima</p>
                    </div>
                @else
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4">
                        <div class="flex items-center justify-center mb-2">
                            <div class="w-16 h-16 bg-red-500 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-center text-red-800 font-semibold">Ditolak</p>
                    </div>
                @endif
            </div>

            <!-- Update Status Form -->
            <form action="{{ route('company.applicants.updateStatus', $applicant->id_lamaran) }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Ubah Status</label>
                    <select name="status" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors" required>
                        <option value="Pending" {{ $applicant->status == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Diterima" {{ $applicant->status == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                        <option value="Ditolak" {{ $applicant->status == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-6 py-3 rounded-lg font-medium transition-all duration-200 shadow-md hover:shadow-lg">
                    Update Status
                </button>
            </form>
        </div>

        <!-- Timeline Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Timeline
            </h3>
            
            <div class="space-y-4">
                <div class="flex items-start">
                    <div class="w-2 h-2 bg-emerald-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Tanggal Melamar</p>
                        <p class="text-sm text-gray-600">{{ $applicant->tanggal_lamaran->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                
                @if($applicant->updated_at != $applicant->tanggal_lamaran)
                    <div class="flex items-start">
                        <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 mr-3 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Terakhir Diupdate</p>
                            <p class="text-sm text-gray-600">{{ $applicant->updated_at->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Info Card -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
            <div class="flex items-start">
                <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3 flex-shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h4 class="font-semibold text-blue-900 mb-2">Informasi</h4>
                    <p class="text-sm text-blue-800">Pastikan untuk menghubungi pelamar jika statusnya diubah menjadi "Diterima" untuk proses selanjutnya.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection