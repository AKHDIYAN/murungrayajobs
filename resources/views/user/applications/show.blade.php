@extends('layouts.user')

@section('title', 'Detail Lamaran')

@section('content')
<div class="mb-4">
    <a href="{{ route('user.applications.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
    <h2 class="fw-bold">Detail Lamaran</h2>
</div>

<div class="row">
    <!-- Application Info -->
    <div class="col-md-8 mb-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Lamaran</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="text-muted small">Status Lamaran</label>
                    <div>
                        @if($application->status == 'Pending')
                            <span class="badge bg-warning">
                                <i class="fas fa-clock me-1"></i>Menunggu Review
                            </span>
                        @elseif($application->status == 'Diterima')
                            <span class="badge bg-success">
                                <i class="fas fa-check-circle me-1"></i>Lamaran Diterima
                            </span>
                        @else
                            <span class="badge bg-danger">
                                <i class="fas fa-times-circle me-1"></i>Lamaran Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="text-muted small">Tanggal Melamar</label>
                    <div>{{ $application->tanggal_terkirim->format('d F Y, H:i') }} WIB</div>
                </div>

                @if($application->status == 'Diterima')
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Selamat!</strong> Lamaran Anda diterima. Silakan tunggu untuk tahap selanjutnya.
                    </div>
                @elseif($application->status == 'Ditolak')
                    <div class="alert alert-danger">
                        <i class="fas fa-info-circle me-2"></i>
                        Maaf, lamaran Anda belum berhasil kali ini. Jangan menyerah dan coba lowongan lainnya!
                    </div>
                @else
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        Lamaran Anda sedang dalam proses review oleh perusahaan.
                    </div>
                @endif
            </div>
        </div>

        <!-- Job Info -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informasi Pekerjaan</h5>
            </div>
            <div class="card-body">
                <h5 class="mb-2">{{ $application->pekerjaan->nama_pekerjaan }}</h5>
                <p class="text-muted mb-3">
                    {{ $application->pekerjaan->perusahaan->nama_perusahaan }}
                </p>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Lokasi</small>
                        <div>{{ $application->pekerjaan->kecamatan->nama_kecamatan }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Jenis Pekerjaan</small>
                        <div>{{ $application->pekerjaan->jenis_pekerjaan }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Gaji</small>
                        <div>Rp {{ number_format($application->pekerjaan->gaji_min) }} - Rp {{ number_format($application->pekerjaan->gaji_max) }}</div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <small class="text-muted">Kategori</small>
                        <div>{{ $application->pekerjaan->kategori->nama_kategori }}</div>
                    </div>
                </div>

                <a href="{{ route('jobs.show', $application->pekerjaan->id_pekerjaan) }}" 
                   class="btn btn-outline-primary btn-sm" target="_blank">
                    <i class="fas fa-external-link-alt me-1"></i>Lihat Detail Lowongan
                </a>
            </div>
        </div>

        <!-- Documents Uploaded -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Dokumen yang Diunggah</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush">
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-file-pdf text-danger me-2"></i>
                            <strong>CV</strong>
                        </div>
                        <a href="{{ Storage::url($application->cv) }}" 
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-download me-1"></i>Unduh
                        </a>
                    </div>
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-id-card text-primary me-2"></i>
                            <strong>KTP</strong>
                        </div>
                        <a href="{{ Storage::url($application->ktp) }}" 
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-download me-1"></i>Unduh
                        </a>
                    </div>
                    @if($application->sertifikat)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-certificate text-warning me-2"></i>
                                <strong>Sertifikat</strong>
                            </div>
                            <a href="{{ Storage::url($application->sertifikat) }}" 
                               class="btn btn-sm btn-outline-primary" target="_blank">
                                <i class="fas fa-download me-1"></i>Unduh
                            </a>
                        </div>
                    @endif
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-image text-info me-2"></i>
                            <strong>Foto Diri</strong>
                        </div>
                        <a href="{{ Storage::url($application->foto_diri) }}" 
                           class="btn btn-sm btn-outline-primary" target="_blank">
                            <i class="fas fa-eye me-1"></i>Lihat
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Company Info -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                @if($application->pekerjaan->perusahaan->logo)
                    <img src="{{ Storage::url($application->pekerjaan->perusahaan->logo) }}" 
                         alt="Logo" class="img-fluid mb-3" style="max-height: 100px;">
                @else
                    <div class="bg-secondary rounded d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 100px; height: 100px;">
                        <i class="fas fa-building fa-3x text-white"></i>
                    </div>
                @endif

                <h5>{{ $application->pekerjaan->perusahaan->nama_perusahaan }}</h5>
                
                <hr>

                <div class="text-start">
                    <p class="small mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        {{ $application->pekerjaan->perusahaan->alamat }}
                    </p>
                    @if($application->pekerjaan->perusahaan->no_telepon)
                        <p class="small mb-2">
                            <i class="fas fa-phone me-2"></i>
                            {{ $application->pekerjaan->perusahaan->no_telepon }}
                        </p>
                    @endif
                    @if($application->pekerjaan->perusahaan->email)
                        <p class="small mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            {{ $application->pekerjaan->perusahaan->email }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
