@extends('layouts.app')

@section('title', $job->nama_pekerjaan . ' - ' . $job->perusahaan->nama_perusahaan)

@section('content')
<!-- Breadcrumb -->
<div class="bg-gray-50 border-b">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
        <nav class="flex items-center text-sm text-gray-500">
            <a href="{{ route('home') }}" class="hover:text-blue-600 transition">Beranda</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <a href="{{ route('jobs.index') }}" class="hover:text-blue-600 transition">Lowongan Kerja</a>
            <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
            <span class="text-gray-900 font-medium truncate">{{ Str::limit($job->nama_pekerjaan, 50) }}</span>
        </nav>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="lg:grid lg:grid-cols-3 lg:gap-8">
        <!-- Main Content -->
        <div class="lg:col-span-2">
            <!-- Company Header Card -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-start gap-4">
                    <!-- Company Logo -->
                    <div class="flex-shrink-0">
                        @if($job->perusahaan->logo)
                            <img src="{{ Storage::url($job->perusahaan->logo) }}" 
                                 alt="{{ $job->perusahaan->nama_perusahaan }}"
                                 class="w-20 h-20 rounded-lg object-cover border border-gray-200">
                        @else
                            <div class="w-20 h-20 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-2xl font-bold">
                                {{ strtoupper(substr($job->perusahaan->nama_perusahaan, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <!-- Company Info -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-2">
                                    {{ $job->nama_pekerjaan }}
                                </h1>
                                <div class="flex items-center gap-2 mb-3">
                                    <p class="text-lg text-gray-700 font-medium">
                                        {{ $job->perusahaan->nama_perusahaan }}
                                    </p>
                                    @if($job->perusahaan->terverifikasi)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            Terverifikasi
                                        </span>
                                    @endif
                                </div>
                                
                                <!-- Badges -->
                                <div class="flex flex-wrap gap-2 mb-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                        {{ $job->jenis_pekerjaan }}
                                    </span>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                        {{ $job->kategori->nama_kategori }}
                                    </span>
                                    @php
                                        $daysLeft = now()->diffInDays($job->tanggal_expired, false);
                                    @endphp
                                    @if($daysLeft >= 0 && $daysLeft <= 7)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 animate-pulse">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $daysLeft }} hari lagi
                                        </span>
                                    @elseif($daysLeft < 0)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                            Berakhir
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            Aktif
                                        </span>
                                    @endif
                                </div>

                                <!-- Quick Info -->
                                <div class="flex flex-wrap items-center gap-4 text-sm text-gray-600">
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                        {{ $job->kecamatan->nama_kecamatan }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $job->created_at->diffForHumans() }}
                                    </div>
                                    <div class="flex items-center">
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        {{ $job->lamaran_count ?? 0 }} pelamar
                                    </div>
                                </div>
                            </div>

                            <!-- Share Button (Desktop) -->
                            <button onclick="toggleShareMenu()" class="hidden lg:block p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-lg transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Salary Highlight -->
                <div class="mt-6 pt-6 border-t border-gray-200">
                    <div class="flex items-center justify-between flex-wrap gap-4">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Gaji</p>
                            <p class="text-2xl font-bold text-green-600">
                                Rp {{ number_format($job->gaji_min, 0, ',', '.') }} - Rp {{ number_format($job->gaji_max, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1">Per bulan</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm text-gray-500 mb-1">Lowongan Tersedia</p>
                            <p class="text-3xl font-bold text-blue-600">{{ $job->jumlah_lowongan }}</p>
                            <p class="text-xs text-gray-500 mt-1">Posisi</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Job Details Sections -->
            <!-- Job Description -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Deskripsi Pekerjaan
                </h2>
                <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ $job->deskripsi_pekerjaan }}
                </div>
            </div>

            <!-- Requirements -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                    Persyaratan
                </h2>
                <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ $job->persyaratan_pekerjaan }}
                </div>
            </div>

            <!-- Benefits (if exists) -->
            @if($job->benefit)
            <div class="bg-gradient-to-br from-green-50 to-blue-50 rounded-lg shadow-sm border border-green-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/>
                    </svg>
                    Benefit & Fasilitas
                </h2>
                <div class="prose prose-sm max-w-none text-gray-700 whitespace-pre-line leading-relaxed">
                    {{ $job->benefit }}
                </div>
            </div>
            @endif

            <!-- Company Profile -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Tentang Perusahaan
                </h2>
                
                <div class="flex items-start gap-4 mb-4">
                    @if($job->perusahaan->logo)
                        <img src="{{ Storage::url($job->perusahaan->logo) }}" 
                             alt="{{ $job->perusahaan->nama_perusahaan }}"
                             class="w-16 h-16 rounded-lg object-cover border border-gray-200">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-content-center text-white text-xl font-bold">
                            {{ strtoupper(substr($job->perusahaan->nama_perusahaan, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">{{ $job->perusahaan->nama_perusahaan }}</h3>
                        <p class="text-sm text-gray-500">{{ $job->perusahaan->sektor->nama_kategori ?? 'Perusahaan' }}</p>
                    </div>
                </div>

                @if($job->perusahaan->deskripsi)
                    <p class="text-gray-700 mb-4 leading-relaxed">{{ $job->perusahaan->deskripsi }}</p>
                @endif

                <div class="space-y-3 pt-4 border-t border-gray-200">
                    <div class="flex items-start text-sm">
                        <svg class="w-5 h-5 mr-3 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-gray-700">
                            {{ $job->perusahaan->alamat }}, {{ $job->perusahaan->kecamatan->nama_kecamatan ?? 'Murung Raya' }}
                        </span>
                    </div>
                    
                    @if($job->perusahaan->no_telepon)
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <span class="text-gray-700">{{ $job->perusahaan->no_telepon }}</span>
                        </div>
                    @endif
                    
                    @if($job->perusahaan->email)
                        <div class="flex items-center text-sm">
                            <svg class="w-5 h-5 mr-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-700">{{ $job->perusahaan->email }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Apply Sidebar -->
        <div class="lg:col-span-1">
            <div class="lg:sticky lg:top-8">
                <!-- Apply Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    @auth('web')
                        @php
                            $hasApplied = \App\Models\Lamaran::where('id_user', Auth::guard('web')->user()->id_user)
                                                             ->where('id_pekerjaan', $job->id_pekerjaan)
                                                             ->exists();
                        @endphp

                        @if($hasApplied)
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-blue-900">Lamaran Terkirim</p>
                                        <p class="text-xs text-blue-700 mt-1">Anda sudah melamar pekerjaan ini</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('user.applications.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-blue-600 text-sm font-medium rounded-lg text-blue-600 bg-white hover:bg-blue-50 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                Lihat Status Lamaran
                            </a>
                        @elseif(now()->gt($job->tanggal_expired))
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                                <div class="flex items-start">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-yellow-900">Lowongan Berakhir</p>
                                        <p class="text-xs text-yellow-700 mt-1">Batas waktu lamaran sudah lewat</p>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('jobs.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                Cari Lowongan Lain
                            </a>
                        @else
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Tertarik dengan pekerjaan ini?</h3>
                            <p class="text-sm text-gray-600 mb-4">
                                Kirimkan lamaran Anda sekarang dan tingkatkan peluang diterima
                            </p>
                            
                            <!-- Deadline Countdown -->
                            @php
                                $daysLeft = now()->diffInDays($job->tanggal_expired, false);
                            @endphp
                            @if($daysLeft <= 7)
                                <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                                    <p class="text-xs font-medium text-red-900 mb-1">⏰ Segera Berakhir!</p>
                                    <p class="text-lg font-bold text-red-600">{{ $daysLeft }} hari lagi</p>
                                </div>
                            @endif
                            
                            <a href="#apply-form" 
                               class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 via-blue-600 to-blue-700 text-sm font-medium rounded-lg text-white hover:from-purple-700 hover:via-blue-700 hover:to-blue-800 transition transform hover:scale-105 shadow-lg mb-2">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Lamar Sekarang
                            </a>
                            <p class="text-xs text-center text-gray-500">
                                ✓ Pastikan CV dan dokumen Anda sudah lengkap
                            </p>
                        @endif
                    @else
                        <h3 class="text-lg font-bold text-gray-900 mb-2">Tertarik dengan pekerjaan ini?</h3>
                        <p class="text-sm text-gray-600 mb-4">
                            Login atau daftar untuk melamar pekerjaan ini
                        </p>
                        <a href="{{ route('auth.login') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 bg-gradient-to-r from-purple-600 via-blue-600 to-blue-700 text-sm font-medium rounded-lg text-white hover:from-purple-700 hover:via-blue-700 hover:to-blue-800 transition shadow-lg mb-2">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                            </svg>
                            Login
                        </a>
                        <a href="{{ route('auth.register') }}" 
                           class="w-full inline-flex items-center justify-center px-4 py-3 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                            </svg>
                            Daftar Akun Baru
                        </a>
                    @endauth
                </div>

                <!-- Share Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                        Bagikan Lowongan
                    </h3>
                    <div class="space-y-2">
                        <a href="https://wa.me/?text=Lihat lowongan {{ $job->nama_pekerjaan }} di {{ route('jobs.show', $job->id_pekerjaan) }}" 
                           target="_blank"
                           class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-green-500 text-sm font-medium rounded-lg text-white hover:bg-green-600 transition">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp
                        </a>
                        <button onclick="copyJobLink()" 
                                class="w-full inline-flex items-center justify-center px-4 py-2.5 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            Salin Link
                        </button>
                    </div>
                </div>

                <!-- Job Info Summary -->
                <div class="bg-gray-50 rounded-lg border border-gray-200 p-6">
                    <h3 class="text-base font-bold text-gray-900 mb-4">Informasi Lowongan</h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-gray-500">Diposting</p>
                                <p class="font-medium text-gray-900">{{ $job->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-gray-500">Berakhir</p>
                                <p class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($job->tanggal_expired)->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-gray-500">Jenis Pekerjaan</p>
                                <p class="font-medium text-gray-900">{{ $job->jenis_pekerjaan }}</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-gray-400 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <div>
                                <p class="text-gray-500">Kategori</p>
                                <p class="font-medium text-gray-900">{{ $job->kategori->nama_kategori }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Application Form (Only for authenticated users) -->
    @auth('web')
        @if(!$hasApplied && now()->lte($job->tanggal_expired))
            <div id="apply-form" class="mt-12 scroll-mt-24">
                <div class="max-w-3xl mx-auto">
                    <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden">
                        <!-- Form Header -->
                        <div class="bg-gradient-to-r from-purple-600 via-blue-600 to-blue-700 px-6 py-5">
                            <h2 class="text-2xl font-bold text-white flex items-center">
                                <svg class="w-7 h-7 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                Form Lamaran Kerja
                            </h2>
                            <p class="text-blue-100 text-sm mt-1">
                                Lengkapi form di bawah ini untuk melamar posisi {{ $job->nama_pekerjaan }}
                            </p>
                        </div>

                        <!-- Form Body -->
                        <div class="p-6 sm:p-8">
                            <form method="POST" action="{{ route('user.applications.store') }}" enctype="multipart/form-data" id="applicationForm">
                                @csrf
                                <input type="hidden" name="id_pekerjaan" value="{{ $job->id_pekerjaan }}">

                                <!-- Info Alert -->
                                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6">
                                    <div class="flex">
                                        <svg class="w-5 h-5 text-blue-400 mr-3 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                        <div class="text-sm text-blue-700">
                                            <p class="font-medium mb-1">Perhatian!</p>
                                            <ul class="list-disc list-inside space-y-1 text-xs">
                                                <li>Pastikan semua dokumen yang Anda upload valid dan terbaru</li>
                                                <li>Ukuran maksimal file: 2MB per dokumen</li>
                                                <li>Format file yang didukung: PDF, DOC, DOCX, JPG, PNG</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload CV -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Curriculum Vitae (CV) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="file" 
                                               name="cv" 
                                               id="cv"
                                               required 
                                               accept=".pdf,.doc,.docx"
                                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition @error('cv') border-red-500 @enderror"
                                               onchange="showFileName(this, 'cv-name')">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Format: PDF, DOC, DOCX. Maksimal 2MB</p>
                                    <p id="cv-name" class="mt-1 text-xs text-blue-600 font-medium"></p>
                                    @error('cv')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Upload KTP -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Kartu Tanda Penduduk (KTP) <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="file" 
                                               name="ktp" 
                                               id="ktp"
                                               required 
                                               accept="image/*,.pdf"
                                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition @error('ktp') border-red-500 @enderror"
                                               onchange="showFileName(this, 'ktp-name')">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG, PDF. Maksimal 2MB</p>
                                    <p id="ktp-name" class="mt-1 text-xs text-blue-600 font-medium"></p>
                                    @error('ktp')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Upload Foto Diri -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Foto Diri <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="file" 
                                               name="foto_diri" 
                                               id="foto_diri"
                                               required 
                                               accept="image/*"
                                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition @error('foto_diri') border-red-500 @enderror"
                                               onchange="showFileName(this, 'foto-name')">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Format: JPG, PNG. Maksimal 2MB</p>
                                    <p id="foto-name" class="mt-1 text-xs text-blue-600 font-medium"></p>
                                    @error('foto_diri')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Upload Sertifikat (Optional) -->
                                <div class="mb-6">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Sertifikat / Portofolio <span class="text-gray-400 text-xs">(Opsional)</span>
                                    </label>
                                    <div class="relative">
                                        <input type="file" 
                                               name="sertifikat" 
                                               id="sertifikat"
                                               accept=".pdf,.jpg,.jpeg,.png"
                                               class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none focus:border-blue-500 focus:ring-2 focus:ring-blue-200 transition @error('sertifikat') border-red-500 @enderror"
                                               onchange="showFileName(this, 'sertifikat-name')">
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Format: PDF, JPG, PNG. Maksimal 2MB</p>
                                    <p id="sertifikat-name" class="mt-1 text-xs text-blue-600 font-medium"></p>
                                    @error('sertifikat')
                                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-3 mt-8 pt-6 border-t border-gray-200">
                                    <button type="submit" 
                                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gradient-to-r from-purple-600 via-blue-600 to-blue-700 text-base font-medium rounded-lg text-white hover:from-purple-700 hover:via-blue-700 hover:to-blue-800 transition transform hover:scale-105 shadow-lg">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        Kirim Lamaran
                                    </button>
                                    <a href="{{ route('jobs.index') }}" 
                                       class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-base font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition">
                                        Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/job-detail.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/job-detail.js') }}"></script>
<script>
// Initialize with job URL
window.jobUrl = "{{ route('jobs.show', $job->id_pekerjaan) }}";

// Override copyJobLink to use window.jobUrl
window.copyJobLink = function() {
    copyJobLink(window.jobUrl);
};
</script>

@endpush
@endsection
