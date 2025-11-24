@extends('layouts.admin')

@section('title', 'Detail Perusahaan')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.companies.index') }}" class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Detail Perusahaan</h1>
    </div>
    <p class="text-gray-600">Informasi lengkap perusahaan</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Company Profile Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="bg-gradient-to-r from-orange-500 to-amber-500 h-32"></div>
            <div class="px-6 pb-6">
                <div class="relative -mt-16 mb-4">
                    @if($company->logo)
                        <img src="{{ asset('storage/'.$company->logo) }}" alt="Logo" class="w-32 h-32 rounded-xl object-cover border-4 border-white shadow-lg">
                    @else
                        <div class="w-32 h-32 bg-gradient-to-br from-green-400 to-emerald-500 rounded-xl flex items-center justify-center text-white text-4xl font-bold border-4 border-white shadow-lg">
                            {{ strtoupper(substr($company->nama_perusahaan, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <div class="flex items-start justify-between mb-6">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $company->nama_perusahaan }}</h2>
                        <p class="text-gray-600">{{ $company->username }}</p>
                    </div>
                    @if($company->is_verified)
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Terverifikasi
                        </span>
                    @else
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Belum Verifikasi
                        </span>
                    @endif
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Email</h3>
                        <p class="text-gray-900">{{ $company->email }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">No. Telepon</h3>
                        <p class="text-gray-900">{{ $company->no_telepon ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Kecamatan</h3>
                        <p class="text-gray-900">{{ $company->kecamatan->nama_kecamatan ?? '-' }}</p>
                    </div>
                    <div>
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Tahun Berdiri</h3>
                        <p class="text-gray-900">{{ $company->tahun_berdiri ?? '-' }}</p>
                    </div>
                </div>

                @if($company->alamat)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Alamat</h3>
                        <p class="text-gray-900">{{ $company->alamat }}</p>
                    </div>
                @endif

                @if($company->deskripsi)
                    <div class="mt-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-1">Deskripsi</h3>
                        <p class="text-gray-900 leading-relaxed">{{ $company->deskripsi }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Job Listings -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Lowongan Kerja ({{ $company->pekerjaan->count() }})
            </h3>

            @forelse($company->pekerjaan as $job)
                <div class="border border-gray-200 rounded-lg p-4 mb-3 hover:border-orange-300 transition-colors">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900 mb-1">{{ $job->nama_pekerjaan }}</h4>
                            <div class="flex flex-wrap gap-2 text-sm text-gray-600 mb-2">
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                    {{ $job->kecamatan->nama_kecamatan ?? '-' }}
                                </span>
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Rp {{ number_format($job->gaji_min, 0, ',', '.') }} - Rp {{ number_format($job->gaji_max, 0, ',', '.') }}
                                </span>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($job->status === 'Diterima')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Aktif
                                    </span>
                                @elseif($job->status === 'Pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @endif
                                <span class="text-xs text-gray-500">{{ $job->lamaran->count() }} Pelamar</span>
                            </div>
                        </div>
                        <div class="text-sm text-gray-500 text-right">
                            <div>{{ \Carbon\Carbon::parse($job->tanggal_posting)->format('d M Y') }}</div>
                            <div class="text-xs">Exp: {{ \Carbon\Carbon::parse($job->tanggal_expired)->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <p>Belum ada lowongan</p>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>
            <div class="space-y-3">
                @if(!$company->is_verified)
                    <form action="{{ route('admin.companies.verify', $company->id_perusahaan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Verifikasi Perusahaan
                        </button>
                    </form>
                @else
                    <form action="{{ route('admin.companies.unverify', $company->id_perusahaan) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full px-4 py-2 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                            </svg>
                            Batalkan Verifikasi
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.companies.edit', $company->id_perusahaan) }}" class="w-full block px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors text-center">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Data
                    </span>
                </a>

                <button onclick="confirmDelete()" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Perusahaan
                </button>

                <form id="delete-form" action="{{ route('admin.companies.destroy', $company->id_perusahaan) }}" method="POST" class="hidden">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        </div>

        <!-- Stats Card -->
        <div class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-200 rounded-xl p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Statistik</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Total Lowongan</span>
                    <span class="font-bold text-orange-600">{{ $company->pekerjaan->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Lowongan Aktif</span>
                    <span class="font-bold text-green-600">{{ $company->pekerjaan->where('status', 'Diterima')->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Lowongan Pending</span>
                    <span class="font-bold text-amber-600">{{ $company->pekerjaan->where('status', 'Pending')->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Total Pelamar</span>
                    <span class="font-bold text-blue-600">{{ $company->pekerjaan->sum(fn($job) => $job->lamaran->count()) }}</span>
                </div>
            </div>
        </div>

        <!-- Account Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Akun</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-gray-500">Terdaftar</span>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($company->created_at)->format('d F Y') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Update Terakhir</span>
                    <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($company->updated_at)->format('d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin-modals.js') }}"></script>
<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus perusahaan ini? Semua lowongan terkait juga akan dihapus.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
