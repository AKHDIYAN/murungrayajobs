@extends('layouts.admin')

@section('title', 'Detail Pelatihan')

@section('content')
<div class="container-fluid px-6 py-8">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div>
            <a href="{{ route('admin.pelatihan.index') }}" class="text-amber-600 hover:text-amber-700 font-medium mb-2 inline-flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">Detail Pelatihan</h1>
            <p class="text-gray-600">{{ $pelatihan->nama }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.pelatihan.export-peserta', $pelatihan->id_pelatihan) }}" class="px-6 py-3 bg-green-500 text-white rounded-xl font-semibold hover:bg-green-600 transition-all duration-300">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                Export Peserta
            </a>
            <a href="{{ route('admin.pelatihan.edit', $pelatihan->id_pelatihan) }}" class="px-6 py-3 bg-amber-500 text-white rounded-xl font-semibold hover:bg-amber-600 transition-all duration-300">
                <svg class="w-5 h-5 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Informasi Pelatihan</h2>
                <h3 class="text-2xl font-bold text-blue-600 mb-6">{{ $pelatihan->nama_pelatihan }}</h3>
                
                @if($pelatihan->foto_banner)
                <div class="mb-6 rounded-xl overflow-hidden">
                    <img src="{{ asset('storage/' . $pelatihan->foto_banner) }}" alt="Banner" class="w-full h-64 object-cover">
                </div>
                @endif

                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-32 text-sm font-semibold text-gray-600">Nama</div>
                        <div class="flex-1 text-gray-800">{{ $pelatihan->nama }}</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-32 text-sm font-semibold text-gray-600">Sektor</div>
                        <div class="flex-1">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                {{ $pelatihan->sektor->nama_kategori ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-32 text-sm font-semibold text-gray-600">Penyelenggara</div>
                        <div class="flex-1 text-gray-800">{{ $pelatihan->penyelenggara }}</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-32 text-sm font-semibold text-gray-600">Instruktur</div>
                        <div class="flex-1 text-gray-800">{{ $pelatihan->instruktur ?? '-' }}</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-32 text-sm font-semibold text-gray-600">Deskripsi</div>
                        <div class="flex-1 text-gray-600 leading-relaxed">{{ $pelatihan->deskripsi }}</div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-32 text-sm font-semibold text-gray-600">Persyaratan</div>
                        <div class="flex-1 text-gray-600 leading-relaxed">{{ $pelatihan->persyaratan ?? '-' }}</div>
                    </div>
                </div>
            </div>

            <!-- Peserta List -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Peserta ({{ $pelatihan->peserta->count() }})</h2>
                
                @if($pelatihan->peserta->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Nama</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Tanggal Daftar</th>
                                <th class="px-4 py-3 text-center text-sm font-semibold text-gray-700">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelatihan->peserta as $peserta)
                            <tr class="border-b border-gray-100 hover:bg-gray-50">
                                <td class="px-4 py-3">
                                    <div class="font-semibold text-gray-800">{{ $peserta->user->nama }}</div>
                                    <div class="text-sm text-gray-500">{{ $peserta->user->email }}</div>
                                </td>
                                <td class="px-4 py-3 text-center text-sm text-gray-600">
                                    {{ \Carbon\Carbon::parse($peserta->tanggal_daftar)->format('d M Y') }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="flex items-center justify-center gap-2">
                                        @if($peserta->file_sertifikat)
                                        <!-- Download -->
                                        <a href="{{ asset('storage/' . $peserta->file_sertifikat) }}" target="_blank" class="inline-flex items-center px-3 py-1 text-sm bg-green-600 text-white rounded-lg hover:bg-green-700" title="Lihat Sertifikat">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>
                                        <!-- Delete -->
                                        <form action="{{ route('admin.pelatihan.peserta.deleteSertifikat', [$pelatihan->id_pelatihan, $peserta->id_peserta_pelatihan]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirm('Hapus sertifikat?')" class="inline-flex items-center px-3 py-1 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700" title="Hapus Sertifikat">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                        @endif
                                        <!-- Upload -->
                                        <button onclick="openUploadModal({{ $peserta->id_peserta_pelatihan }}, '{{ $peserta->user->nama }}')" class="inline-flex items-center px-3 py-1 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700" title="Upload Sertifikat">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                            </svg>
                                            {{ $peserta->file_sertifikat ? 'Ganti' : 'Upload' }}
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-8 text-gray-400">
                    <p>Belum ada peserta yang mendaftar</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">Status</h3>
                <span class="px-4 py-2 rounded-xl text-sm font-semibold inline-block
                    @if($pelatihan->status == 'Dibuka') bg-green-100 text-green-700
                    @elseif($pelatihan->status == 'Berlangsung') bg-blue-100 text-blue-700
                    @elseif($pelatihan->status == 'Selesai') bg-gray-100 text-gray-700
                    @else bg-red-100 text-red-700
                    @endif">
                    {{ $pelatihan->status }}
                </span>
            </div>

            <!-- Schedule Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">Jadwal</h3>
                <div class="space-y-3">
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-600">Mulai: <strong>{{ \Carbon\Carbon::parse($pelatihan->tanggal_mulai)->format('d M Y') }}</strong></span>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-gray-600">Selesai: <strong>{{ \Carbon\Carbon::parse($pelatihan->tanggal_selesai)->format('d M Y') }}</strong></span>
                    </div>
                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-gray-600">Durasi: <strong>{{ $pelatihan->durasi_hari }} hari</strong></span>
                    </div>
                </div>
            </div>

            <!-- Details Card -->
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="font-bold text-gray-800 mb-4">Detail Lainnya</h3>
                <div class="space-y-3">
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Jenis</span>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($pelatihan->jenis_pelatihan == 'Online') bg-blue-100 text-blue-700
                            @elseif($pelatihan->jenis_pelatihan == 'Offline') bg-green-100 text-green-700
                            @else bg-purple-100 text-purple-700
                            @endif">
                            {{ $pelatihan->jenis_pelatihan }}
                        </span>
                    </div>
                    @if($pelatihan->lokasi)
                    <div class="flex justify-between items-start">
                        <span class="text-sm text-gray-600">Lokasi</span>
                        <span class="text-sm text-gray-800 text-right">{{ $pelatihan->lokasi }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Kuota</span>
                        <span class="text-sm font-semibold text-gray-800">{{ $pelatihan->jumlah_peserta }}/{{ $pelatihan->kuota_peserta }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Sisa Kuota</span>
                        <span class="text-sm font-semibold text-green-600">{{ $pelatihan->sisa_kuota }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-gray-600">Sertifikat</span>
                        <span class="text-sm font-semibold {{ $pelatihan->sertifikat_tersedia ? 'text-green-600' : 'text-gray-400' }}">
                            {{ $pelatihan->sertifikat_tersedia ? 'Tersedia' : 'Tidak' }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Sertifikat Modal -->
<div id="uploadModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md mx-4">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-900">Upload Sertifikat</h3>
                <button onclick="closeUploadModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <p class="text-sm text-gray-600 mb-4">Upload sertifikat untuk: <strong id="pesertaNama"></strong></p>
            
            <form id="uploadForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">File Sertifikat (PDF/Image)</label>
                    <input type="file" name="file_sertifikat" accept=".pdf,.jpg,.jpeg,.png" required
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">Max 5MB. Format: PDF, JPG, PNG</p>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeUploadModal()" 
                            class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-semibold">
                        Batal
                    </button>
                    <button type="submit" 
                            class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openUploadModal(pesertaId, pesertaNama) {
    document.getElementById('pesertaNama').textContent = pesertaNama;
    document.getElementById('uploadForm').action = `/admin/pelatihan/{{ $pelatihan->id_pelatihan }}/peserta/${pesertaId}/upload-sertifikat`;
    document.getElementById('uploadModal').classList.remove('hidden');
    document.getElementById('uploadModal').classList.add('flex');
}

function closeUploadModal() {
    document.getElementById('uploadModal').classList.add('hidden');
    document.getElementById('uploadModal').classList.remove('flex');
}
</script>
@endsection
