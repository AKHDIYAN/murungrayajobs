@extends('layouts.app')

@section('title', 'Statistik Ketenagakerjaan')

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
                    <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"/>
                </svg>
                <span class="font-semibold text-sm">Data Real-Time</span>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-black mb-3 leading-tight">
                Statistik Ketenagakerjaan
            </h1>
            <p class="text-lg md:text-xl text-blue-100">
                Kabupaten Murung Raya, Kalimantan Tengah
            </p>
            
            <div class="flex flex-wrap items-center justify-center gap-4 mt-6 text-sm">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>Update Berkala</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/>
                    </svg>
                    <span>Data Terverifikasi</span>
                </div>
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    <span>Analisis Lengkap</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container mx-auto px-4 pb-12">
    <!-- Filter Section -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 mb-8">
        <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
            <svg class="w-6 h-6 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
            </svg>
            Filter Data
        </h2>
        <form method="GET" action="{{ route('statistics.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                <select name="kecamatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatanList as $kecamatan)
                        <option value="{{ $kecamatan->id_kecamatan }}" {{ $selectedKecamatan == $kecamatan->id_kecamatan ? 'selected' : '' }}>
                            {{ $kecamatan->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Pendidikan</label>
                <select name="pendidikan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="">Semua Pendidikan</option>
                    @foreach($pendidikanList as $pendidikan)
                        <option value="{{ $pendidikan->id_pendidikan }}" {{ $selectedPendidikan == $pendidikan->id_pendidikan ? 'selected' : '' }}>
                            {{ $pendidikan->tingkatan_pendidikan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kelompok Usia</label>
                <select name="usia" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500">
                    <option value="">Semua Usia</option>
                    @foreach($usiaList as $usia)
                        <option value="{{ $usia->id_usia }}" {{ $selectedUsia == $usia->id_usia ? 'selected' : '' }}>
                            {{ $usia->kelompok_usia }} Tahun
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-6 py-2 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-lg hover:from-amber-600 hover:to-orange-700 transition-all shadow-lg shadow-amber-500/50">
                    <span class="flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filter
                    </span>
                </button>
                @if($selectedKecamatan || $selectedPendidikan || $selectedUsia)
                    <a href="{{ route('statistics.index') }}" class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-lg transition-colors">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Total Data -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Data</p>
                    <h3 class="text-4xl font-bold">{{ number_format($totalData) }}</h3>
                    <p class="text-blue-100 text-sm mt-2">Angkatan Kerja</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Bekerja -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-1">Bekerja</p>
                    <h3 class="text-4xl font-bold">{{ number_format($bekerja) }}</h3>
                    <p class="text-green-100 text-sm mt-2">{{ $totalData > 0 ? number_format(($bekerja / $totalData) * 100, 1) : 0 }}% dari total</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Menganggur -->
        <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-red-100 text-sm font-medium mb-1">Menganggur</p>
                    <h3 class="text-4xl font-bold">{{ number_format($menganggur) }}</h3>
                    <p class="text-red-100 text-sm mt-2">{{ $totalData > 0 ? number_format(($menganggur / $totalData) * 100, 1) : 0 }}% dari total</p>
                </div>
                <div class="bg-white/20 p-4 rounded-xl">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Gender Distribution -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                Berdasarkan Jenis Kelamin
            </h3>
            <div class="h-64">
                <canvas id="genderChart"></canvas>
            </div>
        </div>

        <!-- Employment Status -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Status Ketenagakerjaan
            </h3>
            <div class="h-64">
                <canvas id="statusChart"></canvas>
            </div>
        </div>

        <!-- By Kecamatan -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                Berdasarkan Kecamatan
            </h3>
            <div class="h-80">
                <canvas id="kecamatanChart"></canvas>
            </div>
        </div>

        <!-- By Pendidikan -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Berdasarkan Pendidikan
            </h3>
            <div class="h-64">
                <canvas id="pendidikanChart"></canvas>
            </div>
        </div>

        <!-- By Usia -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Berdasarkan Kelompok Usia
            </h3>
            <div class="h-64">
                <canvas id="usiaChart"></canvas>
            </div>
        </div>

        <!-- By Sektor (only for employed) -->
        @if($bySektor->count() > 0)
        <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-6 lg:col-span-2">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 mr-2 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Sektor Pekerjaan (Yang Bekerja)
            </h3>
            <div class="h-80">
                <canvas id="sektorChart"></canvas>
            </div>
        </div>
        @endif
    </div>

    @if($totalData == 0)
    <!-- Empty State -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-12 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Belum Ada Data</h3>
        <p class="text-gray-600">Data statistik ketenagakerjaan belum tersedia.</p>
    </div>
    @endif
</div>

@push('scripts')
<!-- Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<!-- Statistics Charts JS -->
<script src="{{ asset('js/statistics-charts.js') }}"></script>

<!-- Initialize Charts with Data from Backend -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Wait for charts module to be available
    if (typeof window.statisticsCharts !== 'undefined') {
        const charts = window.statisticsCharts;
        
        // Gender Chart
        @if($byGender->count() > 0)
        charts.initGenderChart(
            {!! json_encode($byGender->pluck('jenis_kelamin')) !!},
            {!! json_encode($byGender->pluck('total')) !!}
        );
        @endif

        // Status Chart
        charts.initStatusChart({{ $bekerja }}, {{ $menganggur }});

        // Kecamatan Chart
        @if($byKecamatan->count() > 0)
        charts.initKecamatanChart(
            {!! json_encode($byKecamatan->map(fn($item) => $item->kecamatan->nama_kecamatan ?? 'N/A')) !!},
            {!! json_encode($byKecamatan->pluck('bekerja')) !!},
            {!! json_encode($byKecamatan->pluck('menganggur')) !!}
        );
        @endif

        // Pendidikan Chart
        @if($byPendidikan->count() > 0)
        charts.initPendidikanChart(
            {!! json_encode($byPendidikan->map(fn($item) => $item->pendidikan->tingkatan_pendidikan ?? 'N/A')) !!},
            {!! json_encode($byPendidikan->pluck('bekerja')) !!},
            {!! json_encode($byPendidikan->pluck('menganggur')) !!}
        );
        @endif

        // Usia Chart
        @if($byUsia->count() > 0)
        charts.initUsiaChart(
            {!! json_encode($byUsia->map(fn($item) => $item->usia->kelompok_usia ?? 'N/A')) !!},
            {!! json_encode($byUsia->pluck('total')) !!}
        );
        @endif

        // Sektor Chart
        @if($bySektor->count() > 0)
        charts.initSektorChart(
            {!! json_encode($bySektor->map(fn($item) => $item->sektor->nama_kategori ?? 'N/A')) !!},
            {!! json_encode($bySektor->pluck('total')) !!}
        );
        @endif
    }
});
</script>
@endpush
@endsection
