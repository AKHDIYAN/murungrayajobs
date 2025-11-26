@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl shadow-lg p-8 mb-8 text-white">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold mb-2">Selamat Datang Kembali! 👋</h1>
            <p class="text-blue-100 text-lg">{{ $user->nama }}</p>
            <p class="text-blue-200 text-sm mt-2">Mari tingkatkan karir Anda hari ini</p>
        </div>
        <div class="hidden md:block">
            <svg class="w-32 h-32 text-blue-300 opacity-50" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
            </svg>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Total Lamaran -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Total Lamaran</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $totalApplications }}</h3>
                </div>
                <div class="bg-blue-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="bg-blue-50 h-2 rounded-full overflow-hidden">
                    <div class="bg-blue-500 h-full rounded-full" style="width: 100%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Pending</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $pendingApplications }}</h3>
                </div>
                <div class="bg-yellow-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="bg-yellow-50 h-2 rounded-full overflow-hidden">
                    <div class="bg-yellow-500 h-full rounded-full" style="width: {{ $totalApplications > 0 ? ($pendingApplications / $totalApplications * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Diterima -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Diterima</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $acceptedApplications }}</h3>
                </div>
                <div class="bg-green-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="bg-green-50 h-2 rounded-full overflow-hidden">
                    <div class="bg-green-500 h-full rounded-full" style="width: {{ $totalApplications > 0 ? ($acceptedApplications / $totalApplications * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Ditolak -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Ditolak</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $rejectedApplications }}</h3>
                </div>
                <div class="bg-red-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <div class="bg-red-50 h-2 rounded-full overflow-hidden">
                    <div class="bg-red-500 h-full rounded-full" style="width: {{ $totalApplications > 0 ? ($rejectedApplications / $totalApplications * 100) : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Pelatihan Statistics -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <!-- Total Pelatihan -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Pelatihan Diikuti</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $totalPelatihan }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Total pendaftaran</p>
                </div>
                <div class="bg-amber-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('user.pelatihan.riwayat') }}" class="text-amber-600 hover:text-amber-700 text-sm font-semibold flex items-center gap-1">
                    Lihat Riwayat
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>

    <!-- Pelatihan Diterima -->
    <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-semibold mb-1">Pelatihan Diterima</p>
                    <h3 class="text-3xl font-bold text-gray-900">{{ $pelatihanDiterima }}</h3>
                    <p class="text-xs text-gray-500 mt-1">Siap untuk diikuti</p>
                </div>
                <div class="bg-purple-100 p-4 rounded-full">
                    <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4">
                <a href="{{ route('pelatihan.index') }}" class="text-purple-600 hover:text-purple-700 text-sm font-semibold flex items-center gap-1">
                    Cari Pelatihan
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="bg-white rounded-xl shadow-md mb-8 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold text-gray-900">Lamaran Terbaru</h2>
                <p class="text-sm text-gray-500 mt-1">Pantau status lamaran Anda</p>
            </div>
            <a href="{{ route('user.applications.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-semibold flex items-center gap-2">
                <span>Lihat Semua</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        </div>
    </div>
    <div class="p-6">
        @forelse($recentApplications as $application)
            <div class="flex items-center justify-between p-4 hover:bg-gray-50 rounded-xl transition-colors duration-200 {{ !$loop->last ? 'border-b border-gray-100' : '' }}">
                <div class="flex items-start gap-4 flex-1">
                    <div class="bg-gradient-to-br from-blue-500 to-indigo-600 p-3 rounded-xl">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="font-bold text-gray-900 mb-1">{{ $application->pekerjaan->nama_pekerjaan }}</h3>
                        <p class="text-gray-600 text-sm mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            {{ $application->pekerjaan->perusahaan->nama_perusahaan }}
                        </p>
                        <p class="text-gray-500 text-xs flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            {{ $application->tanggal_terkirim->diffForHumans() }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($application->status == 'Pending')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm font-semibold">Pending</span>
                    @elseif($application->status == 'Diterima')
                        <span class="px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">Diterima</span>
                    @else
                        <span class="px-3 py-1 bg-red-100 text-red-800 rounded-full text-sm font-semibold">Ditolak</span>
                    @endif
                    <a href="{{ route('user.applications.show', $application->id_lamaran) }}" class="px-4 py-2 text-blue-600 hover:bg-blue-50 rounded-lg font-semibold transition-colors">
                        Detail
                    </a>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 font-semibold mb-2">Belum ada lamaran</p>
                <p class="text-gray-400 text-sm">Mulai lamar pekerjaan untuk melihat daftar lamaran Anda</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Recommended Jobs -->
<div class="bg-white rounded-xl shadow-md overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-200 bg-gradient-to-r from-purple-50 to-pink-50">
        <div class="flex items-center gap-3">
            <div class="bg-purple-500 p-2 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                </svg>
            </div>
            <div>
                <h2 class="text-xl font-bold text-gray-900">Lowongan Rekomendasi</h2>
                <p class="text-sm text-gray-600">Berdasarkan lokasi Anda</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($recommendedJobs as $job)
                <div class="bg-gradient-to-br from-gray-50 to-white border border-gray-200 rounded-xl p-6 hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
                    <div class="flex items-start justify-between mb-4">
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <span class="px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Baru</span>
                    </div>
                    <h3 class="font-bold text-gray-900 text-lg mb-3 line-clamp-2">{{ $job->nama_pekerjaan }}</h3>
                    <div class="space-y-2 mb-4">
                        <p class="text-gray-600 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span class="line-clamp-1">{{ $job->perusahaan->nama_perusahaan }}</span>
                        </p>
                        <p class="text-gray-600 text-sm flex items-center gap-2">
                            <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span>{{ $job->kecamatan->nama_kecamatan }}</span>
                        </p>
                    </div>
                    <a href="{{ route('jobs.show', $job->id_pekerjaan) }}" class="block w-full px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white text-center rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all font-semibold">
                        Lihat Detail
                    </a>
                </div>
            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <p class="text-gray-500 font-semibold mb-2">Tidak ada rekomendasi lowongan</p>
                    <p class="text-gray-400 text-sm">Coba perbarui profil Anda untuk mendapatkan rekomendasi yang lebih baik</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
