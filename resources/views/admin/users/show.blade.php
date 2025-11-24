@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-orange-600 hover:text-orange-700 mb-4 transition-colors">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-3xl font-bold text-gray-800">Detail User</h1>
    <p class="text-gray-600 mt-2">Informasi lengkap pencari kerja</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Profile Card -->
    <div class="lg:col-span-2">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-start mb-6">
                <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-indigo-500 rounded-full flex items-center justify-center text-white text-4xl font-bold mr-6">
                    {{ strtoupper(substr($user->nama, 0, 1)) }}
                </div>
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $user->nama }}</h2>
                    <div class="flex flex-wrap gap-4 text-sm text-gray-600">
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            {{ $user->email }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            {{ $user->no_telepon ?? '-' }}
                        </div>
                        <div class="flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            {{ $user->kecamatan->nama_kecamatan ?? '-' }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 pt-6 border-t border-gray-200">
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">NIK</label>
                    <p class="text-gray-900 font-medium">{{ $user->nik ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Jenis Kelamin</label>
                    <p class="text-gray-900 font-medium">{{ $user->jenis_kelamin ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Tanggal Lahir</label>
                    <p class="text-gray-900 font-medium">{{ $user->tanggal_lahir ? \Carbon\Carbon::parse($user->tanggal_lahir)->format('d F Y') : '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Usia</label>
                    <p class="text-gray-900 font-medium">{{ $user->usia->rentang_usia ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Pendidikan Terakhir</label>
                    <p class="text-gray-900 font-medium">{{ $user->pendidikan->jenjang_pendidikan ?? '-' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-500 mb-1">Pengalaman Kerja</label>
                    <p class="text-gray-900 font-medium">{{ $user->pengalaman_kerja ?? '-' }}</p>
                </div>
            </div>

            @if($user->alamat)
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Alamat Lengkap</label>
                    <p class="text-gray-900">{{ $user->alamat }}</p>
                </div>
            @endif

            @if($user->tentang_saya)
                <div class="pt-4 mt-4 border-t border-gray-200">
                    <label class="block text-sm font-medium text-gray-500 mb-1">Tentang Saya</label>
                    <p class="text-gray-900">{{ $user->tentang_saya }}</p>
                </div>
            @endif
        </div>

        <!-- Lamaran History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Riwayat Lamaran</h3>
            @if($user->lamaran->count() > 0)
                <div class="space-y-4">
                    @foreach($user->lamaran->take(5) as $lamaran)
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $lamaran->pekerjaan->nama_pekerjaan }}</p>
                                <p class="text-sm text-gray-600">{{ $lamaran->pekerjaan->perusahaan->nama_perusahaan }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $lamaran->tanggal_lamaran->format('d M Y') }}</p>
                            </div>
                            <div>
                                @if($lamaran->status == 'Pending')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">Pending</span>
                                @elseif($lamaran->status == 'Diterima')
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-800">Diterima</span>
                                @else
                                    <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800">Ditolak</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-8">Belum ada riwayat lamaran</p>
            @endif
        </div>
    </div>

    <!-- Sidebar -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Aksi</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.users.edit', $user->id_user) }}" 
                   class="w-full flex items-center justify-center px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-medium rounded-lg transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Data
                </a>
                <form action="{{ route('admin.users.destroy', $user->id_user) }}" method="POST" 
                      onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="w-full flex items-center justify-center px-4 py-2.5 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus User
                    </button>
                </form>
            </div>
        </div>

        <!-- Stats -->
        <div class="bg-gradient-to-br from-blue-50 to-indigo-50 rounded-xl border border-blue-200 p-6">
            <h3 class="text-lg font-semibold text-blue-900 mb-4">Statistik</h3>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-700">Total Lamaran</span>
                    <span class="text-xl font-bold text-blue-900">{{ $user->lamaran->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-700">Diterima</span>
                    <span class="text-xl font-bold text-green-600">{{ $user->lamaran->where('status', 'Diterima')->count() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-blue-700">Pending</span>
                    <span class="text-xl font-bold text-amber-600">{{ $user->lamaran->where('status', 'Pending')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <span class="text-gray-500">Terdaftar Sejak:</span>
                    <p class="font-medium text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                </div>
                <div>
                    <span class="text-gray-500">Terakhir Update:</span>
                    <p class="font-medium text-gray-900">{{ $user->updated_at->format('d F Y') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection