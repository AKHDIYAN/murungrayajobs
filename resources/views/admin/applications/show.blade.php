@extends('layouts.admin')

@section('title', 'Detail Lamaran')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.applications.index') }}" class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Detail Lamaran</h1>
    </div>
    <p class="text-gray-600">Informasi lengkap lamaran pekerjaan</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Application Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ $application->pekerjaan->nama_pekerjaan }}</h2>
                    <p class="text-gray-600 mt-1">{{ $application->pekerjaan->perusahaan->nama_perusahaan }}</p>
                </div>
                @if($application->status === 'Diterima')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Diterima
                    </span>
                @elseif($application->status === 'Pending')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-amber-100 text-amber-800">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                        Pending
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        Ditolak
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-4 mt-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Kategori</p>
                    <p class="font-medium text-gray-900">{{ $application->pekerjaan->kategori->nama_kategori ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Lokasi</p>
                    <p class="font-medium text-gray-900">{{ $application->pekerjaan->kecamatan->nama_kecamatan ?? '-' }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Tanggal Melamar</p>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($application->tanggal_terkirim)->format('d F Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Tanggal Expired</p>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($application->pekerjaan->tanggal_expired)->format('d F Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Applicant Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Data Pelamar
            </h3>

            <div class="space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-orange-400 to-amber-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg shadow-orange-500/30">
                        {{ substr($application->user->nama, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <h4 class="text-lg font-bold text-gray-900">{{ $application->user->nama }}</h4>
                        <p class="text-sm text-gray-600">{{ $application->user->email }}</p>
                        <p class="text-sm text-gray-600">{{ $application->user->no_telepon ?? '-' }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">NIK</p>
                        <p class="font-medium text-gray-900">{{ $application->user->nik }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jenis Kelamin</p>
                        <p class="font-medium text-gray-900">{{ $application->user->jenis_kelamin ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Tanggal Lahir</p>
                        <p class="font-medium text-gray-900">
                            {{ $application->user->tanggal_lahir ? \Carbon\Carbon::parse($application->user->tanggal_lahir)->format('d F Y') : '-' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Pendidikan</p>
                        <p class="font-medium text-gray-900">{{ $application->user->pendidikan->nama_pendidikan ?? '-' }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Alamat</p>
                        <p class="font-medium text-gray-900">{{ $application->user->alamat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Kecamatan</p>
                        <p class="font-medium text-gray-900">{{ $application->user->kecamatan->nama_kecamatan ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Agama</p>
                        <p class="font-medium text-gray-900">{{ $application->user->agama ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Job Details Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Detail Lowongan
            </h3>

            <div class="space-y-4">
                <div>
                    <h4 class="text-sm text-gray-600 mb-2">Deskripsi Pekerjaan</h4>
                    <div class="bg-gray-50 rounded-lg p-4 text-gray-800 text-sm leading-relaxed">
                        {!! nl2br(e($application->pekerjaan->deskripsi_pekerjaan)) !!}
                    </div>
                </div>

                <div>
                    <h4 class="text-sm text-gray-600 mb-2">Persyaratan</h4>
                    <div class="bg-gray-50 rounded-lg p-4 text-gray-800 text-sm leading-relaxed">
                        {!! nl2br(e($application->pekerjaan->persyaratan_pekerjaan)) !!}
                    </div>
                </div>

                @if($application->pekerjaan->benefit)
                    <div>
                        <h4 class="text-sm text-gray-600 mb-2">Benefit</h4>
                        <div class="bg-gray-50 rounded-lg p-4 text-gray-800 text-sm leading-relaxed">
                            {!! nl2br(e($application->pekerjaan->benefit)) !!}
                        </div>
                    </div>
                @endif

                <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jenis Pekerjaan</p>
                        <p class="font-medium text-gray-900">{{ $application->pekerjaan->jenis_pekerjaan }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jumlah Lowongan</p>
                        <p class="font-medium text-gray-900">{{ $application->pekerjaan->jumlah_lowongan }} orang</p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-sm text-gray-600 mb-1">Gaji</p>
                        <p class="font-medium text-gray-900">{{ $application->pekerjaan->gaji_range }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>
            <div class="space-y-3">
                @if($application->status !== 'Diterima')
                    <form action="{{ route('admin.applications.update-status', $application->id_lamaran) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Diterima">
                        <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Terima Lamaran
                        </button>
                    </form>
                @endif

                @if($application->status !== 'Ditolak')
                    <form action="{{ route('admin.applications.update-status', $application->id_lamaran) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak lamaran ini?')">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Ditolak">
                        <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak Lamaran
                        </button>
                    </form>
                @endif

                @if($application->status === 'Pending')
                    <form action="{{ route('admin.applications.update-status', $application->id_lamaran) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="Pending">
                        <button type="submit" class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Set Pending
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.applications.download-pdf', $application->id_lamaran) }}" 
                   class="w-full block px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition-colors text-center">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download PDF
                    </span>
                </a>

                <button onclick="confirmDelete()" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Lamaran
                </button>

                <form id="delete-form" action="{{ route('admin.applications.destroy', $application->id_lamaran) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

        <!-- Company Info Card -->
        <div class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Perusahaan</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-gray-600">Nama</span>
                    <p class="font-medium text-gray-900">{{ $application->pekerjaan->perusahaan->nama_perusahaan }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Email</span>
                    <p class="font-medium text-gray-900">{{ $application->pekerjaan->perusahaan->email }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Telepon</span>
                    <p class="font-medium text-gray-900">{{ $application->pekerjaan->perusahaan->no_telepon ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-600">Lokasi</span>
                    <p class="font-medium text-gray-900">{{ $application->pekerjaan->perusahaan->kecamatan->nama_kecamatan ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Timeline Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Timeline</h3>
            <div class="space-y-4">
                <div class="flex items-start gap-3">
                    <div class="w-2 h-2 mt-2 bg-orange-500 rounded-full"></div>
                    <div>
                        <p class="font-medium text-gray-900">Lamaran Dikirim</p>
                        <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($application->tanggal_terkirim)->format('d F Y, H:i') }}</p>
                    </div>
                </div>
                @if($application->status !== 'Pending')
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 mt-2 {{ $application->status === 'Diterima' ? 'bg-green-500' : 'bg-red-500' }} rounded-full"></div>
                        <div>
                            <p class="font-medium text-gray-900">Status: {{ $application->status }}</p>
                            <p class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($application->updated_at)->format('d F Y, H:i') }}</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin-modals.js') }}"></script>
<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus lamaran ini?')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
