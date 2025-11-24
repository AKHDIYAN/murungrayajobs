@extends('layouts.admin')

@section('title', 'Data Statistik Ketenagakerjaan')

@section('content')
<div class="mb-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Data Statistik Ketenagakerjaan</h1>
    <p class="text-gray-600">Kelola data statistik pencari kerja dan tenaga kerja</p>
</div>

<!-- Action Buttons -->
<div class="flex flex-wrap gap-3 mb-6">
    <a href="{{ route('admin.statistics.create') }}" class="px-6 py-3 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-medium rounded-lg hover:shadow-lg hover:shadow-orange-500/50 transition-all">
        <span class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Tambah Data Manual
        </span>
    </a>
</div>

<!-- Filter Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
    <form action="{{ route('admin.statistics.data.index') }}" method="GET" class="space-y-4">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Status</option>
                    <option value="Bekerja" {{ request('status') == 'Bekerja' ? 'selected' : '' }}>Bekerja</option>
                    <option value="Menganggur" {{ request('status') == 'Menganggur' ? 'selected' : '' }}>Menganggur</option>
                </select>
            </div>

            <!-- Gender Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin</label>
                <select name="gender" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua</option>
                    <option value="Laki-laki" {{ request('gender') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                    <option value="Perempuan" {{ request('gender') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                </select>
            </div>

            <!-- Kecamatan Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan</label>
                <select name="kecamatan" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
                    <option value="">Semua Kecamatan</option>
                    @foreach($kecamatanList as $kec)
                        <option value="{{ $kec->id_kecamatan }}" {{ request('kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                            {{ $kec->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Search -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Nama..." 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500">
            </div>
        </div>

        <div class="flex items-center gap-3">
            <button type="submit" class="px-6 py-2 bg-gradient-to-r from-orange-500 to-amber-600 text-white font-medium rounded-lg hover:shadow-lg hover:shadow-orange-500/50 transition-all">
                Filter
            </button>
            @if(request()->hasAny(['status', 'gender', 'kecamatan', 'search']))
                <a href="{{ route('admin.statistics.data.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    Reset Filter
                </a>
            @endif
        </div>
    </form>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-blue-900">Total Data</h3>
            <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center shadow-lg shadow-blue-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-blue-900">{{ $totalData }}</p>
        <p class="text-xs text-blue-700 mt-1">Semua data statistik</p>
    </div>

    <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-green-900">Bekerja</h3>
            <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center shadow-lg shadow-green-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-green-900">{{ $bekerja }}</p>
        <p class="text-xs text-green-700 mt-1">Sedang bekerja</p>
    </div>

    <div class="bg-gradient-to-br from-red-50 to-red-100 border border-red-200 rounded-xl p-6">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-sm font-medium text-red-900">Menganggur</h3>
            <div class="w-12 h-12 bg-red-500 rounded-full flex items-center justify-center shadow-lg shadow-red-500/50">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <p class="text-3xl font-bold text-red-900">{{ $menganggur }}</p>
        <p class="text-xs text-red-700 mt-1">Belum bekerja</p>
    </div>
</div>

<!-- Data Table -->
<div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gradient-to-r from-orange-500 to-amber-600 text-white">
                <tr>
                    <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Gender</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Kecamatan</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Pendidikan</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Usia</th>
                    <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                    <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($statistik as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ ($statistik->currentPage() - 1) * $statistik->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900">{{ $item->nama }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->jenis_kelamin }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->kecamatan->nama_kecamatan ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->pendidikan->tingkatan_pendidikan ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->usia->kelompok_usia ?? '-' }}</td>
                        <td class="px-6 py-4">
                            @if($item->status === 'Bekerja')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Bekerja
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Menganggur
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.statistics.edit', $item->id_statistik) }}" 
                                   class="p-2 bg-orange-500 hover:bg-orange-600 text-white rounded-lg transition-colors"
                                   title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button onclick="confirmDelete({{ $item->id_statistik }})" 
                                        class="p-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors"
                                        title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                            <form id="delete-form-{{ $item->id_statistik }}" 
                                  action="{{ route('admin.statistics.destroy', $item->id_statistik) }}" 
                                  method="POST" class="hidden">
                                @csrf
                                @method('DELETE')
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-gray-500 text-lg font-medium">Belum ada data statistik</p>
                            <p class="text-gray-400 text-sm">Upload file Excel atau tambahkan data manual</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
@if($statistik->hasPages())
    <div class="mt-6">
        {{ $statistik->links() }}
    </div>
@endif

<script src="{{ asset('js/admin-modals.js') }}"></script>
<script>
function confirmDelete(id) {
    adminCommon.confirmDelete(id, 'Yakin ingin menghapus data ini?');
}
</script>
@endsection
