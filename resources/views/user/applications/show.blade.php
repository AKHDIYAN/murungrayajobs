@extends('layouts.user')

@section('title', 'Detail Lamaran')

@section('content')
<div class="mb-6">
    <a href="{{ route('user.applications.index') }}" class="inline-flex items-center text-gray-600 hover:text-blue-600">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali ke Daftar Lamaran
    </a>
    <h2 class="text-2xl font-bold text-gray-900 mt-2">Detail Lamaran</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Info -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Informasi Lamaran -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-900">Informasi Lamaran</h3>
            <div class="mb-4">
                <span class="text-sm text-gray-600">Status Lamaran:</span>
                @if($application->status == 'Pending')
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold ml-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Menunggu Review
                    </span>
                @elseif($application->status == 'Diterima')
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold ml-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Lamaran Diterima
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full bg-red-100 text-red-800 text-xs font-semibold ml-2">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Lamaran Ditolak
                    </span>
                @endif
            </div>
            <div class="mb-4">
                <span class="text-sm text-gray-600">Tanggal Melamar:</span>
                <span class="ml-2 text-gray-900">{{ $application->tanggal_terkirim->format('d F Y, H:i') }} WIB</span>
            </div>
            @if($application->status == 'Diterima')
                <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span><strong>Selamat!</strong> Lamaran Anda diterima. Silakan tunggu untuk tahap selanjutnya.</span>
                </div>
            @elseif($application->status == 'Ditolak')
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Maaf, lamaran Anda belum berhasil kali ini. Jangan menyerah dan coba lowongan lainnya!</span>
                </div>
            @else
                <div class="bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-lg mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Lamaran Anda sedang dalam proses review oleh perusahaan.</span>
                </div>
            @endif
        </div>

        <!-- Informasi Pekerjaan -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-900">Informasi Pekerjaan</h3>
            <div class="mb-2 text-xl font-bold text-blue-700">{{ $application->pekerjaan->nama_pekerjaan }}</div>
            <div class="mb-2 text-gray-600">{{ $application->pekerjaan->perusahaan->nama_perusahaan }}</div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                <div>
                    <span class="text-sm text-gray-600">Lokasi:</span>
                    <div class="text-gray-900">{{ $application->pekerjaan->kecamatan->nama_kecamatan }}</div>
                </div>
                <div>
                    <span class="text-sm text-gray-600">Jenis Pekerjaan:</span>
                    <div class="text-gray-900">{{ $application->pekerjaan->jenis_pekerjaan }}</div>
                </div>
                <div>
                    <span class="text-sm text-gray-600">Gaji:</span>
                    <div class="text-gray-900">Rp {{ number_format($application->pekerjaan->gaji_min) }} - Rp {{ number_format($application->pekerjaan->gaji_max) }}</div>
                </div>
                <div>
                    <span class="text-sm text-gray-600">Kategori:</span>
                    <div class="text-gray-900">{{ $application->pekerjaan->kategori->nama_kategori }}</div>
                </div>
            </div>
            <a href="{{ route('jobs.show', $application->pekerjaan->id_pekerjaan) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-semibold hover:bg-blue-100 transition" target="_blank">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Lihat Detail Lowongan
            </a>
        </div>

        <!-- Dokumen yang Diunggah -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h3 class="text-lg font-bold mb-4 text-gray-900">Dokumen yang Diunggah</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-semibold">CV</span>
                    </div>
                    <a href="{{ Storage::url($application->cv) }}" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-semibold hover:bg-blue-100 transition" target="_blank">Unduh</a>
                </div>
                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" clip-rule="evenodd"/></svg>
                        <span class="font-semibold">KTP</span>
                    </div>
                    <a href="{{ Storage::url($application->ktp) }}" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-semibold hover:bg-blue-100 transition" target="_blank">Unduh</a>
                </div>
                @if($application->sertifikat)
                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-semibold">Sertifikat</span>
                    </div>
                    <a href="{{ Storage::url($application->sertifikat) }}" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-semibold hover:bg-blue-100 transition" target="_blank">Unduh</a>
                </div>
                @endif
                <div class="flex items-center justify-between bg-gray-50 rounded-lg px-4 py-3">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-semibold">Foto Diri</span>
                    </div>
                    <a href="{{ Storage::url($application->foto_diri) }}" class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg font-semibold hover:bg-blue-100 transition" target="_blank">Lihat</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Info -->
    <div class="space-y-6">
        <div class="bg-white rounded-xl shadow-lg p-6 text-center">
            @if($application->pekerjaan->perusahaan->logo)
                <img src="{{ Storage::url($application->pekerjaan->perusahaan->logo) }}" alt="Logo" class="mx-auto mb-3 rounded-lg object-cover" style="max-height: 100px;">
            @else
                <div class="bg-gray-200 rounded-lg flex items-center justify-center mx-auto mb-3" style="width: 100px; height: 100px;">
                    <svg class="w-10 h-10 text-gray-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                </div>
            @endif
            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $application->pekerjaan->perusahaan->nama_perusahaan }}</h3>
            <div class="text-left mt-4 space-y-2">
                <div class="flex items-center text-gray-700 text-sm">
                    <svg class="w-4 h-4 mr-2 text-red-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                    {{ $application->pekerjaan->perusahaan->alamat }}
                </div>
                @if($application->pekerjaan->perusahaan->no_telepon)
                <div class="flex items-center text-gray-700 text-sm">
                    <svg class="w-4 h-4 mr-2 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/></svg>
                    {{ $application->pekerjaan->perusahaan->no_telepon }}
                </div>
                @endif
                @if($application->pekerjaan->perusahaan->email)
                <div class="flex items-center text-gray-700 text-sm">
                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/></svg>
                    {{ $application->pekerjaan->perusahaan->email }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
