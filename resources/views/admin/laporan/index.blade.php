@extends('layouts.admin')

@section('title', 'Laporan Ketenagakerjaan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Laporan Ketenagakerjaan</h1>
    <p class="text-gray-600">Generate laporan PDF untuk berbagai kebutuhan analisis</p>
</div>

<!-- Laporan Cards -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- Laporan Kondisi Ketenagakerjaan -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border-t-4 border-blue-500">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Laporan Kondisi Ketenagakerjaan</h3>
                    <p class="text-sm text-gray-500">Statistik umum ketenagakerjaan</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.laporan.ketenagakerjaan') }}" method="GET" target="_blank" class="space-y-3">
            <div class="grid grid-cols-2 gap-3">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Bulan</label>
                    <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        @for($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $i == now()->month ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create(null, $i)->format('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Tahun</label>
                    <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        @for($i = 2020; $i <= now()->year; $i++)
                        <option value="{{ $i }}" {{ $i == now()->year ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            
            <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 font-semibold flex items-center justify-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </button>
        </form>
    </div>

    <!-- Laporan Lowongan Kerja -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border-t-4 border-orange-500">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Laporan Lowongan Kerja</h3>
                    <p class="text-sm text-gray-500">Daftar lowongan per periode</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.laporan.lowongan') }}" method="GET" target="_blank" class="space-y-3">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ now()->endOfMonth()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent text-sm">
            </div>
            
            <button type="submit" class="w-full bg-orange-600 text-white px-4 py-2.5 rounded-lg hover:bg-orange-700 font-semibold flex items-center justify-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </button>
        </form>
    </div>

    <!-- Laporan Lamaran -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border-t-4 border-green-500">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Laporan Lamaran</h3>
                    <p class="text-sm text-gray-500">Daftar lamaran per periode</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.laporan.lamaran') }}" method="GET" target="_blank" class="space-y-3">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ now()->endOfMonth()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent text-sm">
            </div>
            
            <button type="submit" class="w-full bg-green-600 text-white px-4 py-2.5 rounded-lg hover:bg-green-700 font-semibold flex items-center justify-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </button>
        </form>
    </div>

    <!-- Laporan Pelatihan -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border-t-4 border-purple-500">
        <div class="flex items-start justify-between mb-4">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Laporan Pelatihan</h3>
                    <p class="text-sm text-gray-500">Daftar pelatihan per periode</p>
                </div>
            </div>
        </div>
        
        <form action="{{ route('admin.laporan.pelatihan') }}" method="GET" target="_blank" class="space-y-3">
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" value="{{ now()->startOfMonth()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ now()->endOfMonth()->format('Y-m-d') }}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
            </div>
            
            <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2.5 rounded-lg hover:bg-purple-700 font-semibold flex items-center justify-center gap-2 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Download PDF
            </button>
        </form>
    </div>

</div>

<!-- Info Section -->
<div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-6">
    <div class="flex items-start gap-3">
        <svg class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <h3 class="text-sm font-bold text-blue-900 mb-1">Informasi Laporan</h3>
            <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                <li>Laporan akan dibuka di tab baru dalam format PDF</li>
                <li>Laporan Ketenagakerjaan menampilkan statistik umum per bulan</li>
                <li>Laporan Lowongan, Lamaran, dan Pelatihan dapat difilter berdasarkan rentang tanggal</li>
                <li>Semua laporan mencakup visualisasi data dan grafik untuk analisis</li>
            </ul>
        </div>
    </div>
</div>

@endsection
