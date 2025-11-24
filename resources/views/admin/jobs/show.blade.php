@extends('layouts.admin')

@section('title', 'Detail Lowongan')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.jobs.index') }}" class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Detail Lowongan</h1>
    </div>
    <p class="text-gray-600">Informasi lengkap lowongan pekerjaan</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Job Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-start justify-between mb-6">
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $job->nama_pekerjaan }}</h2>
                    <div class="flex items-center gap-4 text-sm text-gray-600">
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $job->perusahaan->nama_perusahaan }}
                        </span>
                        <span class="flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $job->kecamatan->nama_kecamatan ?? '-' }}
                        </span>
                    </div>
                </div>
                @if($job->status === 'Diterima')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-green-100 text-green-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Diterima
                    </span>
                @elseif($job->status === 'Pending')
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-amber-100 text-amber-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Pending
                    </span>
                @else
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold bg-red-100 text-red-800">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Ditolak
                    </span>
                @endif
            </div>

            <div class="grid grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Kategori</h3>
                    <p class="text-gray-900">{{ $job->sektor->nama_sektor ?? '-' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Gaji</h3>
                    <p class="text-gray-900">Rp {{ number_format($job->gaji_min, 0, ',', '.') }} - Rp {{ number_format($job->gaji_max, 0, ',', '.') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Posting</h3>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($job->tanggal_posting)->format('d F Y') }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-medium text-gray-500 mb-1">Tanggal Expired</h3>
                    <p class="text-gray-900">{{ \Carbon\Carbon::parse($job->tanggal_expired)->format('d F Y') }}</p>
                </div>
            </div>

            @if($job->persyaratan)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Persyaratan</h3>
                    <div class="text-gray-900 whitespace-pre-line">{{ $job->persyaratan }}</div>
                </div>
            @endif

            @if($job->deskripsi_pekerjaan)
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <h3 class="text-sm font-medium text-gray-500 mb-2">Deskripsi Pekerjaan</h3>
                    <div class="text-gray-900 whitespace-pre-line">{{ $job->deskripsi_pekerjaan }}</div>
                </div>
            @endif
        </div>

        <!-- Applicants List -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                Pelamar ({{ $job->lamaran->count() }})
            </h3>

            @if($job->lamaran->count() > 0)
                <div class="space-y-3">
                    @foreach($job->lamaran->take(10) as $lamaran)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-500 rounded-full flex items-center justify-center text-white font-bold mr-3">
                                    {{ strtoupper(substr($lamaran->user->nama, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $lamaran->user->nama }}</p>
                                    <p class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($lamaran->tanggal_melamar)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                @if($lamaran->status === 'Diterima')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                        Diterima
                                    </span>
                                @elseif($lamaran->status === 'Pending')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                        Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">
                                        Ditolak
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <p>Belum ada pelamar</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="space-y-6">
        <!-- Actions Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Aksi</h3>
            <div class="space-y-3">
                @if($job->status === 'Pending')
                    <form action="{{ route('admin.jobs.approve', $job->id_pekerjaan) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Setujui Lowongan
                        </button>
                    </form>
                    <form action="{{ route('admin.jobs.reject', $job->id_pekerjaan) }}" method="POST" onsubmit="return confirm('Yakin ingin menolak lowongan ini?')">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Tolak Lowongan
                        </button>
                    </form>
                @endif

                <a href="{{ route('admin.jobs.edit', $job->id_pekerjaan) }}" class="w-full block px-4 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors text-center">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Lowongan
                    </span>
                </a>

                <button onclick="confirmDelete()" class="w-full px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Hapus Lowongan
                </button>

                <form id="delete-form" action="{{ route('admin.jobs.destroy', $job->id_pekerjaan) }}" method="POST" class="hidden">
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
                    <span class="text-gray-600">Total Pelamar</span>
                    <span class="font-bold text-orange-600">{{ $job->lamaran->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Diterima</span>
                    <span class="font-bold text-green-600">{{ $job->lamaran->where('status', 'Diterima')->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Pending</span>
                    <span class="font-bold text-amber-600">{{ $job->lamaran->where('status', 'Pending')->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-600">Ditolak</span>
                    <span class="font-bold text-red-600">{{ $job->lamaran->where('status', 'Ditolak')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Company Info Card -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Informasi Perusahaan</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-gray-500">Nama</span>
                    <p class="font-medium text-gray-900">{{ $job->perusahaan->nama_perusahaan }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Email</span>
                    <p class="font-medium text-gray-900">{{ $job->perusahaan->email }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Telepon</span>
                    <p class="font-medium text-gray-900">{{ $job->perusahaan->no_telepon ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Lokasi</span>
                    <p class="font-medium text-gray-900">{{ $job->perusahaan->kecamatan->nama_kecamatan ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin-modals.js') }}"></script>
<script>
function confirmDelete() {
    if (confirm('Apakah Anda yakin ingin menghapus lowongan ini? Semua lamaran terkait juga akan dihapus.')) {
        document.getElementById('delete-form').submit();
    }
}
</script>
@endsection
