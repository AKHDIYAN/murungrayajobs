@extends('layouts.admin')

@section('title', 'DSS Analytics - Decision Support System')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">📊 Decision Support System</h1>
                <p class="text-gray-600">Analisis data tenaga kerja untuk pengambilan keputusan strategis</p>
            </div>
            <a href="{{ route('admin.dss.export') }}" class="px-6 py-3 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all duration-300">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Report
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <!-- Total Lowongan -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold">{{ number_format($totalLowongan) }}</span>
            </div>
            <p class="text-blue-100 font-medium">Total Lowongan Aktif</p>
        </div>

        <!-- Total Pelamar -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold">{{ number_format($totalPelamar) }}</span>
            </div>
            <p class="text-purple-100 font-medium">Total Pelamar</p>
        </div>

        <!-- Total Diterima -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold">{{ number_format($totalDiterima) }}</span>
            </div>
            <p class="text-green-100 font-medium">Diterima Bekerja</p>
        </div>

        <!-- Tingkat Serapan -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-2xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <div class="p-3 bg-white/20 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                    </svg>
                </div>
                <span class="text-3xl font-bold">{{ number_format($tingkatSerapan, 1) }}%</span>
            </div>
            <p class="text-orange-100 font-medium">Tingkat Serapan</p>
        </div>
    </div>

    <!-- Supply vs Demand per Sektor -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">📈 Gap Analysis - Supply vs Demand per Sektor</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Sektor</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Lowongan</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Demand</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Supply</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Gap</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($supplyDemandPerSektor as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->nama_kategori }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ number_format($item->demand_lowongan) }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ number_format($item->demand_total) }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ number_format($item->supply_pencari_kerja) }}</td>
                        <td class="px-6 py-4 text-center font-bold {{ $item->gap < 0 ? 'text-red-600' : 'text-green-600' }}">
                            {{ number_format($item->gap) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $item->gap_status == 'Surplus' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                {{ $item->gap_status }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Unemployment Rate per Kecamatan -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">🗺️ Tingkat Pengangguran per Kecamatan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-4 text-left text-sm font-semibold text-gray-700">Kecamatan</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Total Pencari Kerja</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Menganggur</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Bekerja</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold text-gray-700">Tingkat Pengangguran</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengangguranPerKecamatan->sortByDesc('tingkat_pengangguran') as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-800">{{ $item->nama_kecamatan }}</td>
                        <td class="px-6 py-4 text-center text-gray-600">{{ number_format($item->total_pencari_kerja) }}</td>
                        <td class="px-6 py-4 text-center text-red-600 font-semibold">{{ number_format($item->total_menganggur) }}</td>
                        <td class="px-6 py-4 text-center text-green-600 font-semibold">{{ number_format($item->total_bekerja) }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center">
                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-3">
                                    <div class="bg-gradient-to-r from-red-500 to-red-600 h-2 rounded-full" style="width: {{ min($item->tingkat_pengangguran, 100) }}%"></div>
                                </div>
                                <span class="font-bold text-gray-800">{{ number_format($item->tingkat_pengangguran, 1) }}%</span>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Demografi Usia -->
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">👥 Demografi Usia Pencari Kerja</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @foreach($demografiUsia as $usia)
            <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 rounded-xl p-6 border-2 border-indigo-200">
                <div class="text-center">
                    <div class="text-4xl font-bold text-indigo-600 mb-2">{{ number_format($usia->jumlah) }}</div>
                    <div class="text-sm font-semibold text-gray-700">{{ $usia->kelompok_usia }}</div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Sertifikasi Stats -->
    @if(isset($sertifikasiStats) && $sertifikasiStats->total_user > 0)
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">🎓 Status Sertifikasi</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border-2 border-green-200">
                <div class="text-center">
                    <div class="text-3xl font-bold text-green-600 mb-2">{{ number_format($sertifikasiStats->total_bersertifikat) }}</div>
                    <div class="text-sm font-semibold text-gray-700">Memiliki Sertifikat</div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 border-2 border-yellow-200">
                <div class="text-center">
                    <div class="text-3xl font-bold text-yellow-600 mb-2">{{ number_format($sertifikasiStats->total_verified) }}</div>
                    <div class="text-sm font-semibold text-gray-700">Terverifikasi</div>
                </div>
            </div>
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border-2 border-blue-200">
                <div class="text-center">
                    <div class="text-3xl font-bold text-blue-600 mb-2">{{ number_format($sertifikasiStats->persentase_coverage, 1) }}%</div>
                    <div class="text-sm font-semibold text-gray-700">Coverage Rate</div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
