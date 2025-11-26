@extends('layouts.admin')

@section('title', 'Manajemen Pelatihan')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🎓 Manajemen Pelatihan</h1>
            <p class="text-gray-600">Kelola program pelatihan dan peserta</p>
        </div>
        <a href="{{ route('admin.pelatihan.create') }}" class="px-6 py-3 bg-gradient-to-r from-amber-500 to-orange-600 text-white rounded-xl font-semibold hover:shadow-lg transition-all duration-300">
            <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Pelatihan
        </a>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl relative" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    <!-- Pelatihan List -->
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-amber-500 to-orange-600 text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nama Pelatihan</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Sektor</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Jenis</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Jadwal</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Peserta</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelatihan as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="font-semibold text-gray-800">{{ $item->nama }}</div>
                            <div class="text-sm text-gray-500">{{ $item->penyelenggara }}</div>
                        </td>
                        <td class="px-6 py-4 text-gray-600">
                            {{ $item->sektor->nama_kategori ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                @if($item->jenis_pelatihan == 'Online') bg-blue-100 text-blue-700
                                @elseif($item->jenis_pelatihan == 'Offline') bg-green-100 text-green-700
                                @else bg-purple-100 text-purple-700
                                @endif">
                                {{ $item->jenis_pelatihan }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center text-sm text-gray-600">
                            <div>{{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d M Y') }}</div>
                            <div class="text-xs text-gray-400">s/d {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d M Y') }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="text-gray-800 font-semibold">{{ $item->jumlah_peserta }}/{{ $item->kuota_peserta }}</div>
                            <div class="text-xs text-gray-500">{{ $item->sisa_kuota }} tersisa</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold
                                @if($item->status == 'Dibuka') bg-green-100 text-green-700
                                @elseif($item->status == 'Berlangsung') bg-blue-100 text-blue-700
                                @elseif($item->status == 'Selesai') bg-gray-100 text-gray-700
                                @else bg-red-100 text-red-700
                                @endif">
                                {{ $item->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <a href="{{ route('admin.pelatihan.show', $item->id_pelatihan) }}" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('admin.pelatihan.edit', $item->id_pelatihan) }}" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form action="{{ route('admin.pelatihan.destroy', $item->id_pelatihan) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus pelatihan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Hapus">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                                <p class="text-lg font-medium">Belum ada pelatihan</p>
                                <p class="text-sm mt-1">Klik tombol "Tambah Pelatihan" untuk menambahkan pelatihan baru</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pelatihan->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $pelatihan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
