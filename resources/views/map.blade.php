@extends('layouts.app')

@section('title', 'Peta Sebaran Lowongan Kerja - Murung Raya')

@push('styles')
<!-- Leaflet CSS - Local -->
<link rel="stylesheet" href="{{ asset('assets/leaflet/leaflet.css') }}">
<!-- Map Page CSS -->
<link rel="stylesheet" href="{{ asset('css/map.css') }}">
@endpush

@section('content')
<!-- Hero Section -->
<div class="relative text-white py-12 overflow-hidden" style="background: linear-gradient(135deg, #1e3a8a 0%, #1e40af 50%, #3b82f6 100%);">
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-64 h-64 bg-white rounded-full -ml-32 -mt-32"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-white rounded-full -mr-48 -mb-48"></div>
    </div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm px-4 py-2 rounded-full mb-4">
                <svg class="w-5 h-5 text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <span class="font-semibold text-sm">Visualisasi Data Real-Time</span>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-black mb-3 leading-tight">
                Peta Sebaran Lowongan Kerja
            </h1>
            <p class="text-lg md:text-xl text-blue-100">
                Kabupaten Murung Raya, Kalimantan Tengah
            </p>
            
            <div class="flex flex-wrap items-center justify-center gap-4 mt-6 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>10 Kecamatan</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    <span>Data Akurat</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                    </svg>
                    <span>Update Harian</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="bg-white border-b border-gray-200 py-6">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Lowongan -->
            <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Total Lowongan</p>
                        <p class="text-3xl font-black">{{ $totalLowongan }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs mt-2 opacity-75">di seluruh Murung Raya</p>
            </div>

            <!-- Total Kecamatan -->
            <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Kecamatan</p>
                        <p class="text-3xl font-black">10</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs mt-2 opacity-75">tersebar di peta</p>
            </div>

            <!-- Kecamatan Terbanyak -->
            <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Terbanyak</p>
                        <p class="text-xl font-black truncate">{{ $kecamatanTerbanyak->nama_kecamatan ?? 'N/A' }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs mt-2 opacity-75">{{ $kecamatanTerbanyak->total ?? 0 }} lowongan</p>
            </div>

            <!-- Update Terakhir -->
            <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-4 text-white shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm opacity-90 mb-1">Update Terakhir</p>
                        <p class="text-lg font-black">{{ now()->format('d M Y') }}</p>
                    </div>
                    <div class="bg-white/20 p-3 rounded-lg">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs mt-2 opacity-75">data real-time</p>
            </div>
        </div>
    </div>
</div>

<!-- Map Container with Card -->
<div class="map-container">
    <div class="container mx-auto px-4">
        <!-- Map Title -->
        <div class="mb-6 text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">Visualisasi Peta Interaktif</h2>
            <p class="text-gray-600">Klik marker untuk melihat detail lowongan per kecamatan</p>
        </div>
        
        <!-- Map Card -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div id="map"></div>
        </div>

        <!-- Legend & Info Section -->
        <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Legend Card -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    Legenda Peta
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center gap-3 p-3 bg-red-50 rounded-lg">
                        <div class="w-5 h-5 rounded-full bg-red-500 shadow"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Sangat Banyak</p>
                            <p class="text-sm text-gray-600">Lebih dari 30 lowongan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-orange-50 rounded-lg">
                        <div class="w-4 h-4 rounded-full bg-orange-500 shadow"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Banyak</p>
                            <p class="text-sm text-gray-600">16 - 30 lowongan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-yellow-50 rounded-lg">
                        <div class="w-3 h-3 rounded-full bg-yellow-500 shadow"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Sedang</p>
                            <p class="text-sm text-gray-600">6 - 15 lowongan</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-2 h-2 rounded-full bg-gray-500 shadow"></div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-900">Sedikit</p>
                            <p class="text-sm text-gray-600">0 - 5 lowongan</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="bg-gradient-to-br from-blue-600 to-indigo-600 rounded-xl shadow-lg p-6 text-white">
                <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM15.657 5.757a1 1 0 00-1.414-1.414l-.707.707a1 1 0 001.414 1.414l.707-.707zM18 10a1 1 0 01-1 1h-1a1 1 0 110-2h1a1 1 0 011 1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zM5 10a1 1 0 01-1 1H3a1 1 0 110-2h1a1 1 0 011 1zM8 16v-1h4v1a2 2 0 11-4 0zM12 14c.015-.34.208-.646.477-.859a4 4 0 10-4.954 0c.27.213.462.519.476.859h4.002z"/>
                    </svg>
                    Aksi Cepat
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('jobs.index') }}" class="block bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg p-4 transition-all transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-lg">Lihat Semua Lowongan</p>
                                <p class="text-sm opacity-90">{{ $totalLowongan }} lowongan tersedia</p>
                            </div>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                    </a>
                    <button onclick="resetMapView()" class="w-full bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg p-4 transition-all transform hover:scale-105 text-left">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-lg">Reset Tampilan Peta</p>
                                <p class="text-sm opacity-90">Kembali ke zoom awal</p>
                            </div>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                    </button>
                    <a href="{{ route('statistics.index') }}" class="block bg-white/20 hover:bg-white/30 backdrop-blur-sm rounded-lg p-4 transition-all transform hover:scale-105">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="font-bold text-lg">Lihat Statistik Detail</p>
                                <p class="text-sm opacity-90">Data ketenagakerjaan lengkap</p>
                            </div>
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Leaflet JS - Local -->
<script src="{{ asset('assets/leaflet/leaflet.js') }}"></script>
<!-- Map Page JS -->
<script src="{{ asset('js/map.js') }}"></script>
<script>
// Initialize map on page load
document.addEventListener('DOMContentLoaded', function() {
    const kecamatanStatsFromServer = @json($kecamatanStats);
    const jobsRoute = "{{ route('jobs.index') }}";
    initializeMap(kecamatanStatsFromServer, jobsRoute);
});
</script>
@endpush
