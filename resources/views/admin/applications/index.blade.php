@extends('layouts.admin')

@section('title', 'Manajemen Lamaran')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Manajemen Lamaran</h1>
    <p class="text-gray-600">Kelola semua lamaran pekerjaan dari pencari kerja</p>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form action="{{ route('admin.applications.index') }}" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <!-- Company Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Perusahaan</label>
                <select name="company" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Perusahaan</option>
                    @foreach($companyList as $comp)
                        <option value="{{ $comp->id_perusahaan }}" {{ request('company') == $comp->id_perusahaan ? 'selected' : '' }}>
                            {{ $comp->nama_perusahaan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Job Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Lowongan</label>
                <select name="job" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Lowongan</option>
                    @foreach($jobList as $job)
                        <option value="{{ $job->id_pekerjaan }}" {{ request('job') == $job->id_pekerjaan ? 'selected' : '' }}>
                            {{ $job->nama_pekerjaan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Pelamar</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nama atau NIK..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-medium rounded-lg hover:shadow-lg hover:shadow-orange-500/50 transition-all">
                <span class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filter
                </span>
            </button>
            @if(request()->hasAny(['status', 'company', 'job', 'search']))
                <a href="{{ route('admin.applications.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Reset Filter
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <!-- Total Applications -->
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-blue-900">Total Lamaran</h3>
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center shadow-lg shadow-blue-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-blue-900">{{ $totalApplications }}</p>
        <p class="text-xs text-blue-700 mt-1">Semua lamaran masuk</p>
    </div>

    <!-- Pending Applications -->
    <div class="bg-gradient-to-br from-amber-50 to-amber-100 border border-amber-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-amber-900">Pending</h3>
            <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center shadow-lg shadow-amber-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-amber-900">{{ $pendingCount }}</p>
        <p class="text-xs text-amber-700 mt-1">Menunggu review</p>
    </div>

    <!-- Accepted Applications -->
    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-green-900">Diterima</h3>
            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center shadow-lg shadow-green-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-green-900">{{ $acceptedCount }}</p>
        <p class="text-xs text-green-700 mt-1">Lolos seleksi</p>
    </div>

    <!-- Rejected Applications -->
    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-red-900">Ditolak</h3>
            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center shadow-lg shadow-red-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-red-900">{{ $rejectedCount }}</p>
        <p class="text-xs text-red-700 mt-1">Tidak lolos</p>
    </div>
</div>

<!-- Applications Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-orange-500 to-amber-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Pelamar</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Lowongan</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Perusahaan</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($applications as $application)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ ($applications->currentPage() - 1) * $applications->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gradient-to-br from-orange-400 to-amber-500 rounded-full flex items-center justify-center text-white font-bold shadow-lg shadow-orange-500/30">
                                    {{ substr($application->user->nama, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900">{{ $application->user->nama }}</p>
                                    <p class="text-xs text-gray-500">NIK: {{ $application->user->nik }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $application->pekerjaan->nama_pekerjaan }}</p>
                            <p class="text-xs text-gray-500">{{ $application->pekerjaan->kecamatan->nama_kecamatan ?? '-' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $application->pekerjaan->perusahaan->nama_perusahaan }}</p>
                            <p class="text-xs text-gray-500">{{ $application->pekerjaan->perusahaan->email }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ \Carbon\Carbon::parse($application->tanggal_terkirim)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($application->status === 'Diterima')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Diterima
                                </span>
                            @elseif($application->status === 'Pending')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                    </svg>
                                    Pending
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Ditolak
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.applications.show', $application->id_lamaran) }}" 
                                   class="p-2 bg-blue-500 hover:bg-blue-600 text-white rounded-lg transition-colors"
                                   title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <button onclick="confirmDelete({{ $application->id_lamaran }})" 
                                        class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors"
                                        title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                            <form id="delete-form-{{ $application->id_lamaran }}" 
                                  action="{{ route('admin.applications.destroy', $application->id_lamaran) }}" 
                                  method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">Tidak ada lamaran</p>
                            <p class="text-gray-400 text-sm">Belum ada lamaran yang masuk</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($applications->hasPages())
    <div class="mt-6">
        {{ $applications->links() }}
    </div>
@endif

<script src="{{ asset('js/admin-modals.js') }}"></script>
<script>
function confirmDelete(id) {
    adminCommon.confirmDelete(id, 'Yakin ingin menghapus lamaran ini?');
}
</script>
@endsection
