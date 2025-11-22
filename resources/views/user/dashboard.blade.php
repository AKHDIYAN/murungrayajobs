@extends('layouts.user')

@section('title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Dashboard</h2>
    <p class="text-muted">Selamat datang kembali, {{ $user->nama }}!</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Lamaran</p>
                        <h3 class="fw-bold mb-0">{{ $totalApplications }}</h3>
                    </div>
                    <i class="fas fa-file-alt fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card" style="border-left-color: #ffc107;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Pending</p>
                        <h3 class="fw-bold mb-0">{{ $pendingApplications }}</h3>
                    </div>
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card" style="border-left-color: #198754;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Diterima</p>
                        <h3 class="fw-bold mb-0">{{ $acceptedApplications }}</h3>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card stat-card" style="border-left-color: #dc3545;">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Ditolak</p>
                        <h3 class="fw-bold mb-0">{{ $rejectedApplications }}</h3>
                    </div>
                    <i class="fas fa-times-circle fa-2x text-danger"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Lamaran Terbaru</h5>
            <a href="{{ route('user.applications.index') }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>
    </div>
    <div class="card-body">
        @forelse($recentApplications as $application)
            <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                <div>
                    <h6 class="mb-1">{{ $application->pekerjaan->nama_pekerjaan }}</h6>
                    <p class="text-muted small mb-1">
                        {{ $application->pekerjaan->perusahaan->nama_perusahaan }}
                    </p>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-clock me-1"></i>{{ $application->tanggal_terkirim->diffForHumans() }}
                    </p>
                </div>
                <div class="text-end">
                    @if($application->status == 'Pending')
                        <span class="badge bg-warning">Pending</span>
                    @elseif($application->status == 'Diterima')
                        <span class="badge bg-success">Diterima</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                    <br>
                    <a href="{{ route('user.applications.show', $application->id_lamaran) }}" 
                       class="btn btn-sm btn-link">Detail</a>
                </div>
            </div>
        @empty
            <p class="text-center text-muted">Belum ada lamaran</p>
        @endforelse
    </div>
</div>

<!-- Recommended Jobs -->
<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0">Lowongan Rekomendasi</h5>
        <small class="text-muted">Berdasarkan lokasi Anda</small>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($recommendedJobs as $job)
                <div class="col-md-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">{{ $job->nama_pekerjaan }}</h6>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-building me-1"></i>{{ $job->perusahaan->nama_perusahaan }}
                            </p>
                            <p class="text-muted small mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>{{ $job->kecamatan->nama_kecamatan }}
                            </p>
                            <a href="{{ route('jobs.show', $job->id_pekerjaan) }}" 
                               class="btn btn-sm btn-outline-primary w-100">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <p class="text-center text-muted">Tidak ada rekomendasi lowongan</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
