@extends('layouts.app')

@section('title', 'Program Pelatihan Kerja - Murung Raya')

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
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
                <span class="font-semibold text-sm">Tingkatkan Kompetensi Anda</span>
            </div>
            
            <h1 class="text-3xl md:text-5xl font-black mb-3 leading-tight">
                Program Pelatihan Kerja
            </h1>
            <p class="text-lg md:text-xl text-blue-100">
                Tingkatkan Skill & Sertifikasi untuk Karir yang Lebih Baik
            </p>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="bg-white border-b border-gray-200 py-6">
    <div class="container mx-auto px-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <input type="text" name="search" placeholder="Cari pelatihan..." 
                   value="{{ request('search') }}"
                   class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            
            <select name="sektor" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Sektor</option>
                @foreach($sektorList as $sektor)
                    <option value="{{ $sektor->id_sektor }}" {{ request('sektor') == $sektor->id_sektor ? 'selected' : '' }}>
                        {{ $sektor->nama_kategori }}
                    </option>
                @endforeach
            </select>

            <select name="jenis" class="px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Semua Jenis</option>
                <option value="Online" {{ request('jenis') == 'Online' ? 'selected' : '' }}>Online</option>
                <option value="Offline" {{ request('jenis') == 'Offline' ? 'selected' : '' }}>Offline</option>
                <option value="Hybrid" {{ request('jenis') == 'Hybrid' ? 'selected' : '' }}>Hybrid</option>
            </select>

            <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 font-semibold">
                Cari Pelatihan
            </button>
        </form>
    </div>
</div>

<!-- Pelatihan List -->
<div class="container mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @forelse($pelatihan as $item)
        <div class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow flex flex-col h-full">
            @if($item->foto_banner)
            <div class="h-48 overflow-hidden flex-shrink-0">
                <img src="{{ asset('storage/' . $item->foto_banner) }}" alt="{{ $item->nama_pelatihan }}" class="w-full h-full object-cover">
            </div>
            @else
            <div class="h-48 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center flex-shrink-0">
                <svg class="w-20 h-20 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                </svg>
            </div>
            @endif

            <div class="p-6 flex flex-col flex-grow">
                <div class="flex items-center gap-2 mb-3">
                    <span class="px-3 py-1 text-xs font-semibold rounded-full 
                        @if($item->status == 'Dibuka') bg-green-100 text-green-700
                        @elseif($item->status == 'Berlangsung') bg-blue-100 text-blue-700
                        @else bg-gray-100 text-gray-700 @endif">
                        {{ $item->status }}
                    </span>
                    <span class="px-3 py-1 text-xs font-semibold bg-purple-100 text-purple-700 rounded-full">
                        {{ $item->jenis_pelatihan }}
                    </span>
                </div>

                <h3 class="text-lg font-bold text-gray-900 mb-3 line-clamp-2">
                    {{ $item->nama_pelatihan }}
                </h3>
                
                <p class="text-sm text-gray-600 mb-4 line-clamp-2">
                    {{ $item->deskripsi }}
                </p>

                <div class="space-y-2 flex-grow mb-4">
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        {{ $item->tanggal_mulai->format('d M Y') }} - {{ $item->tanggal_selesai->format('d M Y') }}
                    </div>
                    <div class="flex items-center text-sm text-gray-600">
                        <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                        Kuota: {{ $item->sisa_kuota }}/{{ $item->kuota_peserta }} tersedia
                    </div>
                </div>

                <a href="{{ route('pelatihan.show', $item->id_pelatihan) }}" 
                   class="block w-full text-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold transition-colors mt-auto">
                    Lihat Detail
                </a>
            </div>
        </div>
        @empty
        <div class="col-span-3 text-center py-12">
            <svg class="w-20 h-20 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-600 text-lg">Belum ada program pelatihan tersedia</p>
        </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $pelatihan->links() }}
    </div>
</div>
@endsection
