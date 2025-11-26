@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('pelatihan.index') }}" class="inline-flex items-center text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Pelatihan
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Banner -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    @if($pelatihan->foto_banner)
                    <img src="{{ asset('storage/' . $pelatihan->foto_banner) }}" alt="{{ $pelatihan->nama_pelatihan }}" class="w-full h-96 object-cover">
                    @else
                    <div class="w-full h-96 bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center">
                        <svg class="w-32 h-32 text-white opacity-50" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Header Info -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <div class="flex items-center gap-3 mb-4">
                        <span class="px-4 py-2 text-sm font-semibold rounded-full 
                            @if($pelatihan->status == 'Dibuka') bg-green-100 text-green-700
                            @elseif($pelatihan->status == 'Berlangsung') bg-blue-100 text-blue-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $pelatihan->status }}
                        </span>
                        <span class="px-4 py-2 text-sm font-semibold bg-purple-100 text-purple-700 rounded-full">
                            {{ $pelatihan->jenis_pelatihan }}
                        </span>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $pelatihan->nama_pelatihan }}</h1>
                    <p class="text-gray-600 mb-4">{{ $pelatihan->sektor->nama_kategori }}</p>

                    <div class="flex items-center text-gray-600 mb-6">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ $pelatihan->penyelenggara }}
                    </div>

                    <p class="text-gray-700 leading-relaxed">{{ $pelatihan->deskripsi }}</p>
                </div>

                <!-- Detail Information -->
                <div class="bg-white rounded-xl shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi Detail</h2>

                    <div class="space-y-6">
                        <!-- Instruktur -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-semibold text-gray-900">Instruktur</h3>
                                <p class="text-gray-700">{{ $pelatihan->instruktur }}</p>
                            </div>
                        </div>

                        <!-- Lokasi -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-semibold text-gray-900">Lokasi Pelatihan</h3>
                                <p class="text-gray-700">{{ $pelatihan->lokasi }}</p>
                            </div>
                        </div>

                        <!-- Persyaratan -->
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-semibold text-gray-900 mb-2">Persyaratan</h3>
                                <div class="text-gray-700 whitespace-pre-line">{{ $pelatihan->persyaratan }}</div>
                            </div>
                        </div>

                        <!-- Sertifikat -->
                        @if($pelatihan->sertifikat_tersedia)
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-sm font-semibold text-gray-900">Sertifikat</h3>
                                <p class="text-gray-700">Peserta akan mendapatkan sertifikat resmi setelah menyelesaikan pelatihan</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Schedule Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 sticky top-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Jadwal Pelatihan</h3>
                    
                    <div class="space-y-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Mulai</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pelatihan->tanggal_mulai->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Tanggal Selesai</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pelatihan->tanggal_selesai->format('d M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600 mb-1">Durasi</p>
                            <p class="text-lg font-semibold text-gray-900">{{ $pelatihan->durasi_hari }} Hari</p>
                        </div>
                    </div>

                    <div class="border-t pt-4 mb-6">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-gray-600">Kuota Tersedia</span>
                            <span class="text-lg font-bold text-blue-600">{{ $pelatihan->sisa_kuota }}/{{ $pelatihan->kuota_peserta }}</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: {{ ($pelatihan->sisa_kuota / $pelatihan->kuota_peserta) * 100 }}%"></div>
                        </div>
                    </div>

                    @if($pelatihan->status == 'Dibuka' && $pelatihan->sisa_kuota > 0)
                        @auth
                            @if($sudahDaftar)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-center">
                                <svg class="w-12 h-12 text-green-600 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-sm font-semibold text-green-800">Anda sudah terdaftar</p>
                                <p class="text-xs text-green-600 mt-1">Status: {{ $sudahDaftar->status }}</p>
                            </div>
                            @else
                            <button onclick="document.getElementById('daftarModal').classList.remove('hidden')" 
                                    class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all">
                                Daftar Sekarang
                            </button>
                            @endif
                        @else
                        <a href="{{ route('login') }}" 
                           class="block w-full text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-3 rounded-lg font-semibold hover:from-blue-700 hover:to-indigo-700 transition-all">
                            Login untuk Mendaftar
                        </a>
                        @endauth
                    @else
                        <div class="bg-gray-100 text-center py-3 rounded-lg">
                            <p class="text-sm font-semibold text-gray-600">
                                @if($pelatihan->sisa_kuota == 0)
                                    Kuota Penuh
                                @else
                                    Pendaftaran Ditutup
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pendaftaran -->
@auth
@if(!$sudahDaftar && $pelatihan->status == 'Dibuka' && $pelatihan->sisa_kuota > 0)
<div id="daftarModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xl font-bold text-gray-900">Daftar Pelatihan</h3>
            <button onclick="document.getElementById('daftarModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form action="{{ route('pelatihan.daftar', $pelatihan->id_pelatihan) }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Alasan Mengikuti Pelatihan *</label>
                <textarea name="alasan_mengikuti" rows="4" required
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                          placeholder="Jelaskan alasan Anda mengikuti pelatihan ini..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('daftarModal').classList.add('hidden')"
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">
                    Batal
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg hover:from-blue-700 hover:to-indigo-700 font-semibold">
                    Daftar
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endauth
@endsection
