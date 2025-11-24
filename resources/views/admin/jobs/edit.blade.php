@extends('layouts.admin')

@section('title', 'Edit Lowongan')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.jobs.show', $job->id_pekerjaan) }}" class="text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <h1 class="text-3xl font-bold text-gray-800">Edit Lowongan</h1>
    </div>
    <p class="text-gray-600">Update informasi lowongan pekerjaan</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <form action="{{ route('admin.jobs.update', $job->id_pekerjaan) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('status') border-red-500 @enderror" required>
                    <option value="Pending" {{ old('status', $job->status) == 'Pending' ? 'selected' : '' }}>Pending (Menunggu Persetujuan)</option>
                    <option value="Diterima" {{ old('status', $job->status) == 'Diterima' ? 'selected' : '' }}>Diterima (Publish)</option>
                    <option value="Ditolak" {{ old('status', $job->status) == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Tanggal Expired -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Expired <span class="text-red-500">*</span>
                </label>
                <input type="date" name="tanggal_expired" value="{{ old('tanggal_expired', $job->tanggal_expired) }}" 
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 @error('tanggal_expired') border-red-500 @enderror"
                       required>
                @error('tanggal_expired')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-xs text-gray-500 mt-1">Perpanjang tanggal jika lowongan masih aktif</p>
            </div>
        </div>

        <!-- Info Section -->
        <div class="mt-6 p-4 bg-blue-50 border border-blue-200 rounded-lg">
            <h3 class="text-sm font-semibold text-blue-900 mb-2">Informasi Lowongan</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-blue-700 font-medium">Nama Lowongan:</span>
                    <p class="text-blue-900">{{ $job->nama_pekerjaan }}</p>
                </div>
                <div>
                    <span class="text-blue-700 font-medium">Perusahaan:</span>
                    <p class="text-blue-900">{{ $job->perusahaan->nama_perusahaan }}</p>
                </div>
                <div>
                    <span class="text-blue-700 font-medium">Kategori:</span>
                    <p class="text-blue-900">{{ $job->sektor->nama_sektor ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-blue-700 font-medium">Lokasi:</span>
                    <p class="text-blue-900">{{ $job->kecamatan->nama_kecamatan ?? '-' }}</p>
                </div>
                <div>
                    <span class="text-blue-700 font-medium">Gaji:</span>
                    <p class="text-blue-900">Rp {{ number_format($job->gaji_min, 0, ',', '.') }} - Rp {{ number_format($job->gaji_max, 0, ',', '.') }}</p>
                </div>
                <div>
                    <span class="text-blue-700 font-medium">Tanggal Posting:</span>
                    <p class="text-blue-900">{{ \Carbon\Carbon::parse($job->tanggal_posting)->format('d F Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Catatan Admin (Optional) -->
        <div class="mt-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin (Opsional)</label>
            <textarea name="catatan_admin" rows="3" 
                      class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500"
                      placeholder="Tambahkan catatan jika ada...">{{ old('catatan_admin', $job->catatan_admin ?? '') }}</textarea>
            <p class="text-xs text-gray-500 mt-1">Catatan ini tidak akan ditampilkan ke publik</p>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center gap-3 mt-8">
            <button type="submit" class="px-6 py-2 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.jobs.show', $job->id_pekerjaan) }}" class="px-6 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

<!-- Quick Actions -->
<div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
    @if($job->status === 'Pending')
        <form action="{{ route('admin.jobs.approve', $job->id_pekerjaan) }}" method="POST" class="w-full">
            @csrf
            @method('PUT')
            <button type="submit" class="w-full px-4 py-3 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Quick Approve
            </button>
        </form>
        <form action="{{ route('admin.jobs.reject', $job->id_pekerjaan) }}" method="POST" class="w-full" onsubmit="return confirm('Yakin ingin menolak lowongan ini?')">
            @csrf
            @method('PUT')
            <button type="submit" class="w-full px-4 py-3 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Quick Reject
            </button>
        </form>
    @endif
    <form action="{{ route('admin.jobs.destroy', $job->id_pekerjaan) }}" method="POST" class="w-full" onsubmit="return confirm('Apakah Anda yakin ingin menghapus lowongan ini? Semua lamaran terkait juga akan dihapus.')">
        @csrf
        @method('DELETE')
        <button type="submit" class="w-full px-4 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors flex items-center justify-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Hapus Lowongan
        </button>
    </form>
</div>
@endsection
