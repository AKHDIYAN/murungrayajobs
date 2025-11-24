@extends('layouts.admin')

@section('title', 'Statistik & Dashboard')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Statistik & Dashboard</h1>
    <p class="text-gray-600">Overview sistem dan analisis data ketenagakerjaan</p>
</div>

<!-- Summary Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <!-- Total Users -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-blue-900">Total Pencari Kerja</h3>
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center shadow-lg shadow-blue-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-blue-900">{{ $totalUsers }}</p>
        <p class="text-xs text-blue-700 mt-1">Terdaftar di sistem</p>
    </div>

    <!-- Total Companies -->
    <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-purple-900">Total Perusahaan</h3>
            <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center shadow-lg shadow-purple-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-purple-900">{{ $totalCompanies }}</p>
        <p class="text-xs text-purple-700 mt-1">Perusahaan aktif</p>
    </div>

    <!-- Total Jobs -->
    <div class="bg-gradient-to-br from-orange-50 to-orange-100 border border-orange-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-orange-900">Total Lowongan</h3>
            <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center shadow-lg shadow-orange-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-orange-900">{{ $totalJobs }}</p>
        <p class="text-xs text-orange-700 mt-1">Lowongan tersedia</p>
    </div>

    <!-- Total Applications -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-green-900">Total Lamaran</h3>
            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center shadow-lg shadow-green-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-green-900">{{ $totalApplications }}</p>
        <p class="text-xs text-green-700 mt-1">Lamaran masuk</p>
    </div>
</div>

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Applications by Month Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Lamaran per Bulan</h3>
        <div class="h-64">
            <canvas id="applicationsChart"></canvas>
        </div>
    </div>

    <!-- Jobs by Category Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Lowongan per Kategori</h3>
        <div class="h-64">
            <canvas id="categoriesChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <!-- Users by District Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Pencari Kerja per Kecamatan</h3>
        <div class="h-64">
            <canvas id="districtsChart"></canvas>
        </div>
    </div>

    <!-- Application Status Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-4">Status Lamaran</h3>
        <div class="h-64">
            <canvas id="statusChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-lg font-bold text-gray-900">Aktivitas Terbaru</h3>
        <a href="{{ route('admin.logs') }}" class="text-orange-500 hover:text-orange-600 text-sm font-medium">
            Lihat Semua →
        </a>
    </div>
    <div class="space-y-3">
        @forelse($recentActivities as $activity)
            <div class="flex items-start gap-4 p-3 bg-gray-50 rounded-lg">
                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-amber-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg shadow-orange-500/30">
                    @if($activity->user_type === 'admin')
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd"/>
                        </svg>
                    @elseif($activity->user_type === 'company')
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"/>
                        </svg>
                    @else
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="text-sm text-gray-900">{{ $activity->description }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs px-2 py-1 bg-{{ $activity->action === 'create' ? 'green' : ($activity->action === 'delete' ? 'red' : 'blue') }}-100 text-{{ $activity->action === 'create' ? 'green' : ($activity->action === 'delete' ? 'red' : 'blue') }}-800 rounded-full">
                            {{ ucfirst($activity->action) }}
                        </span>
                        <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-gray-500 text-center py-8">Belum ada aktivitas</p>
        @endforelse
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="{{ asset('js/admin-statistics-dashboard.js') }}"></script>
<script>
// Initialize all statistics charts with data from controller
document.addEventListener('DOMContentLoaded', function() {
    adminStatistics.initApplicationsChart(
        {!! json_encode($applicationsByMonth->pluck('month')) !!},
        {!! json_encode($applicationsByMonth->pluck('total')) !!}
    );

    adminStatistics.initCategoriesChart(
        {!! json_encode($jobsByCategory->pluck('kategori')) !!},
        {!! json_encode($jobsByCategory->pluck('total')) !!}
    );

    adminStatistics.initDistrictsChart(
        {!! json_encode($usersByDistrict->pluck('kecamatan')) !!},
        {!! json_encode($usersByDistrict->pluck('total')) !!}
    );

    adminStatistics.initStatusChart(
        {{ $applicationsPending }},
        {{ $applicationsAccepted }},
        {{ $applicationsRejected }}
    );
});
</script>
@endpush
