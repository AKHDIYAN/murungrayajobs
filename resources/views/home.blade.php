{{-- resources/views/home.blade.php --}}
@extends('layouts.app')
@section('title', 'Portal Kerja Murung Raya - Kerja Dekat, Hidup Sejahtera')

@section('content')
<div class="min-h-screen bg-gray-50">

    {{-- HERO SECTION - Super Keren dengan Background Image --}}
    <section class="relative text-white overflow-hidden min-h-[600px] flex items-center">
        {{-- Background Image dengan Overlay --}}
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
            style="background-image: url('/images/bg_murungraya.jpg');"
        </div>
        <div class="absolute inset-0 bg-gradient-to-br from-blue-900/60 via-blue-800/50 to-indigo-900/60"></div>
        
        <div class="relative container mx-auto px-6 py-24 lg:py-32 z-10">
            <div class="text-center max-w-5xl mx-auto">
                <h1 class="text-5xl md:text-7xl font-black mb-6 leading-tight animate-fade-in">
                    Kerja Dekat,<br><span class="text-yellow-400">Hidup Sejahtera</span>
                </h1>
                <p class="text-xl md:text-2xl mb-10 opacity-90 max-w-3xl mx-auto">
                    Temukan ratusan lowongan kerja di Kabupaten Murung Raya tanpa harus merantau jauh dari keluarga
                </p>

                {{-- Search Bar Premium --}}
                <form action="{{ route('jobs.index') }}" method="GET" 
                      class="max-w-4xl mx-auto bg-white rounded-2xl shadow-2xl p-4 flex flex-col md:flex-row gap-4 transform hover:scale-[1.02] transition">
                    <div class="flex-1">
                        <input type="text" name="search" placeholder="Cari pekerjaan, perusahaan, atau skill..."
                               class="w-full px-6 py-5 text-gray-800 text-lg rounded-xl border-0 focus:ring-4 focus:ring-blue-300 outline-none">
                    </div>
                    <button type="submit" 
                            class="bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 text-white font-bold px-12 py-5 rounded-xl text-lg shadow-lg hover:shadow-xl transition">
                        🔍 Cari Lowongan
                    </button>
                </form>

                <div class="mt-10 flex flex-wrap justify-center gap-6 text-sm">
                    <span>✅ Tanpa Biaya</span>
                    <span>✅ Verifikasi Perusahaan</span>
                    <span>✅ Data Dijamin Aman</span>
                </div>
            </div>
        </div>
    </section>

    {{-- STATISTIK RINGKASAN - Card Modern --}}
    <section class="py-16 bg-gradient-to-b from-white to-gray-50">
        <div class="container mx-auto px-6">
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                {{-- Card Lowongan Aktif --}}
                <div class="group bg-white border-2 border-blue-100 hover:border-blue-400 p-6 rounded-2xl shadow-md hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 group-hover:bg-blue-500 p-4 rounded-xl transition-colors duration-300">
                            <svg class="w-8 h-8 text-blue-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div class="bg-blue-50 group-hover:bg-blue-100 px-3 py-1 rounded-full transition-colors duration-300">
                            <span class="text-xs font-semibold text-blue-700">Aktif</span>
                        </div>
                    </div>
                    <div class="text-4xl font-black text-gray-900 mb-2">{{ $totalJobs ?? 0 }}</div>
                    <div class="text-gray-600 font-medium">Lowongan Kerja</div>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <span class="text-xs text-green-600 font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd"></path>
                            </svg>
                            Terbuka untuk umum
                        </span>
                    </div>
                </div>

                {{-- Card Perusahaan --}}
                <div class="group bg-white border-2 border-green-100 hover:border-green-400 p-6 rounded-2xl shadow-md hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 group-hover:bg-green-500 p-4 rounded-xl transition-colors duration-300">
                            <svg class="w-8 h-8 text-green-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div class="bg-green-50 group-hover:bg-green-100 px-3 py-1 rounded-full transition-colors duration-300">
                            <span class="text-xs font-semibold text-green-700">Verified</span>
                        </div>
                    </div>
                    <div class="text-4xl font-black text-gray-900 mb-2">{{ $totalCompanies ?? 0 }}</div>
                    <div class="text-gray-600 font-medium">Perusahaan</div>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <span class="text-xs text-green-600 font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Terverifikasi resmi
                        </span>
                    </div>
                </div>

                {{-- Card Total Lamaran --}}
                <div class="group bg-white border-2 border-purple-100 hover:border-purple-400 p-6 rounded-2xl shadow-md hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-purple-100 group-hover:bg-purple-500 p-4 rounded-xl transition-colors duration-300">
                            <svg class="w-8 h-8 text-purple-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="bg-purple-50 group-hover:bg-purple-100 px-3 py-1 rounded-full transition-colors duration-300">
                            <span class="text-xs font-semibold text-purple-700">Total</span>
                        </div>
                    </div>
                    <div class="text-4xl font-black text-gray-900 mb-2">{{ $totalApplicants ?? 0 }}</div>
                    <div class="text-gray-600 font-medium">Total Lamaran</div>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <span class="text-xs text-purple-600 font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                            </svg>
                            Pelamar terdaftar
                        </span>
                    </div>
                </div>

                {{-- Card Kecamatan --}}
                <div class="group bg-white border-2 border-orange-100 hover:border-orange-400 p-6 rounded-2xl shadow-md hover:shadow-2xl transform hover:scale-105 transition-all duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-orange-100 group-hover:bg-orange-500 p-4 rounded-xl transition-colors duration-300">
                            <svg class="w-8 h-8 text-orange-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="bg-orange-50 group-hover:bg-orange-100 px-3 py-1 rounded-full transition-colors duration-300">
                            <span class="text-xs font-semibold text-orange-700">Area</span>
                        </div>
                    </div>
                    <div class="text-4xl font-black text-gray-900 mb-2">10</div>
                    <div class="text-gray-600 font-medium">Kecamatan</div>
                    <div class="mt-3 pt-3 border-t border-gray-100">
                        <span class="text-xs text-orange-600 font-semibold flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                            </svg>
                            Kabupaten Murung Raya
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- LOWONGAN TERBARU - Card Premium --}}
    <section class="py-20 container mx-auto px-6">
        <div class="flex justify-between items-center mb-12">
            <div>
                <h2 class="text-4xl font-black text-gray-900">Lowongan Terbaru</h2>
                <p class="text-gray-600 mt-2">Diperbarui setiap hari • Hanya lowongan aktif</p>
            </div>
            <a href="{{ route('jobs.index') }}" class="text-blue-600 font-bold text-lg hover:text-blue-800 flex items-center gap-2">
                Lihat Semua → 
            </a>
        </div>

        @if($latestJobs->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($latestJobs as $job)
                    <x-card :job="$job" />
                @endforeach
            </div>
        @else
            <div class="text-center py-20">
                <div class="text-6xl mb-6">📭</div>
                <h3 class="text-2xl font-bold text-gray-700 mb-4">Belum ada lowongan aktif saat ini</h3>
                <p class="text-gray-600 text-lg">Ayo perusahaan segera posting lowongan!</p>
                <a href="{{ route('company.register') }}" 
                   class="mt-8 inline-block bg-blue-600 text-white px-8 py-4 rounded-full font-bold hover:bg-blue-700 shadow-lg">
                    Daftar sebagai Perusahaan
                </a>
            </div>
        @endif
    </section>

    {{-- JOBSTREET PROMO CARD --}}
    <section class="py-12 container mx-auto px-6">
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-emerald-500 via-teal-500 to-cyan-600 shadow-2xl transform hover:scale-[1.02] transition-all duration-300">
            <!-- Decorative Elements -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full -mr-32 -mt-32"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/10 rounded-full -ml-24 -mb-24"></div>
            
            <div class="relative z-10 flex flex-col md:flex-row items-center justify-between p-8 md:p-12 gap-8">
                <!-- Left Content -->
                <div class="flex-1 text-white">
                    <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-4">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span class="font-semibold text-sm">Partner Resmi</span>
                    </div>
                    
                    <h3 class="text-3xl md:text-4xl font-black mb-3 leading-tight">
                        Cari Lebih Banyak Lowongan?
                    </h3>
                    <p class="text-lg text-white/90 mb-6 max-w-lg">
                        Jelajahi <span class="font-bold text-yellow-300">ribuan lowongan kerja</span> dari seluruh Indonesia di Jobstreet.com - Platform pencarian kerja terpercaya!
                    </p>
                    
                    <div class="flex flex-wrap gap-4 mb-6">
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>100% Gratis</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Update Harian</span>
                        </div>
                        <div class="flex items-center gap-2 text-sm">
                            <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span>Perusahaan Verified</span>
                        </div>
                    </div>

                    <a href="https://www.jobstreet.co.id" target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center gap-3 bg-white text-emerald-600 font-bold px-8 py-4 rounded-xl shadow-xl hover:shadow-2xl hover:bg-gray-50 transform hover:scale-105 transition-all duration-200">
                        <span class="text-lg">Kunjungi Jobstreet</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                </div>

                <!-- Right Content - Illustration/Icon -->
                <div class="flex-shrink-0">
                    <div class="relative">
                        <div class="w-48 h-48 md:w-56 md:h-56 bg-white/20 backdrop-blur-lg rounded-3xl flex items-center justify-center transform rotate-6 hover:rotate-12 transition-transform duration-300">
                            <div class="transform -rotate-6 hover:-rotate-12 transition-transform duration-300">
                                <svg class="w-32 h-32 md:w-40 md:h-40 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        </div>
                        <!-- Floating badge -->
                        <div class="absolute -top-4 -right-4 bg-yellow-400 text-emerald-900 font-black px-4 py-2 rounded-full text-sm shadow-lg transform rotate-12 animate-bounce">
                            🔥 Popular!
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA SECTION --}}
    <section class="bg-gradient-to-r from-indigo-600 to-blue-700 py-20">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-6">
                Siap Memulai Karir di Murung Raya?
            </h2>
            <div class="flex flex-col md:flex-row gap-6 justify-center mt-10">
                <a href="{{ route('auth.register') }}" 
                   class="bg-white text-blue-700 px-10 py-5 rounded-full font-bold text-xl hover:bg-gray-100 shadow-xl transform hover:scale-105 transition">
                    Daftar sebagai Pencari Kerja
                </a>
                <a href="{{ route('company.register') }}" 
                   class="bg-transparent border-4 border-white text-white px-10 py-5 rounded-full font-bold text-xl hover:bg-white/20 transform hover:scale-105 transition">
                    Daftar sebagai Perusahaan
                </a>
            </div>
        </div>
    </section>
</div>

{{-- Tambahkan animasi halus di head layout atau di sini --}}
<link rel="stylesheet" href="{{ asset('css/home.css') }}">
@endsection