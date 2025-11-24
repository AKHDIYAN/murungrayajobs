{{-- resources/views/components/card.blade.php --}}
@props(['job'])

@php
    $logo = $job->perusahaan->logo 
        ? asset('storage/' . $job->perusahaan->logo) 
        : 'https://ui-avatars.com/api/?name=' . urlencode($job->perusahaan->nama_perusahaan) . '&background=2563eb&color=fff&bold=true&size=128';
    
    $gaji = $job->gaji_min && $job->gaji_max 
        ? 'Rp ' . number_format($job->gaji_min, 0, ',', '.') . ' - Rp ' . number_format($job->gaji_max, 0, ',', '.') 
        : ($job->gaji_min ? 'Rp ' . number_format($job->gaji_min, 0, ',', '.') . '+' : 'Kompetitif');
@endphp

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden border border-gray-100 group']) }}>
    <div class="p-6">
        <div class="flex items-start gap-4">
            <!-- Logo Perusahaan -->
            <div class="flex-shrink-0">
                <img src="{{ $logo }}" alt="{{ $job->perusahaan->nama_perusahaan }}" 
                     class="w-16 h-16 rounded-xl object-cover ring-4 ring-blue-100 group-hover:ring-blue-300 transition">
            </div>

            <div class="flex-1 min-w-0">
                <!-- Judul & Perusahaan -->
                <h3 class="text-xl font-bold text-gray-900 group-hover:text-blue-600 transition line-clamp-2">
                    {{ $job->judul }}
                </h3>
                <p class="text-lg font-medium text-blue-600 mt-1">
                    {{ $job->perusahaan->nama_perusahaan }}
                </p>

                <!-- Info Singkat -->
                <div class="flex flex-wrap gap-3 mt-3 text-sm text-gray-600">
                    <span class="flex items-center gap-1">
                        Lokasi {{ $job->kecamatan->nama_kecamatan ?? 'Murung Raya' }}
                    </span>
                    <span class="flex items-center gap-1">
                        {{ $job->jenis_pekerjaan }}
                    </span>
                    <span class="flex items-center gap-1 font-semibold text-green-600">
                        {{ $gaji }}
                    </span>
                </div>

                <!-- Deadline & Posted -->
                <div class="mt-4 flex items-center justify-between text-sm">
                    <div class="text-gray-500">
                        <span class="text-red-600 font-medium">
                            @if($job->tanggal_expired->isPast())
                                Expired
                            @else
                                Berakhir {{ $job->tanggal_expired?->diffForHumans() }}
                            @endif
                        </span>
                        <span class="mx-2">•</span>
                        Diposting {{ $job->tanggal_posting->diffForHumans() }}
                    </div>

                    <!-- Tombol -->
                    <a href="{{ route('jobs.show', $job) }}" 
                       class="bg-blue-600 text-white px-6 py-3 rounded-full font-semibold hover:bg-blue-700 hover:shadow-lg transform hover:scale-105 transition whitespace-nowrap">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>