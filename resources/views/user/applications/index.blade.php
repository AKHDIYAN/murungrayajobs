@extends('layouts.user')

@section('title', 'Lamaran Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-1">Lamaran Saya</h2>
        <p class="text-muted mb-0">Kelola dan pantau status lamaran Anda</p>
    </div>
</div>

<!-- Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('user.applications.index') }}" class="row g-3">
            <div class="col-md-4">
                <select name="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                    <option value="Diterima" {{ request('status') == 'Diterima' ? 'selected' : '' }}>Diterima</option>
                    <option value="Ditolak" {{ request('status') == 'Ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                </select>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter me-2"></i>Filter
                </button>
                <a href="{{ route('user.applications.index') }}" class="btn btn-outline-secondary">
                    Reset
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Applications List -->
@forelse($applications as $application)
    <div class="card mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col-md-2 text-center mb-3 mb-md-0">
                    @if($application->pekerjaan->perusahaan->logo)
                        <img src="{{ Storage::url($application->pekerjaan->perusahaan->logo) }}" 
                             alt="Logo" class="img-fluid rounded" style="max-height: 80px;">
                    @else
                        <div class="bg-secondary rounded d-inline-flex align-items-center justify-content-center" 
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-building fa-2x text-white"></i>
                        </div>
                    @endif
                </div>
                <div class="col-md-7 mb-3 mb-md-0">
                    <h5 class="mb-2">{{ $application->pekerjaan->nama_pekerjaan }}</h5>
                    <p class="text-muted mb-2">
                        <i class="fas fa-building me-1"></i>
                        {{ $application->pekerjaan->perusahaan->nama_perusahaan }}
                    </p>
                    <p class="text-muted small mb-2">
                        <i class="fas fa-map-marker-alt me-1"></i>
                        {{ $application->pekerjaan->kecamatan->nama_kecamatan }}
                    </p>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-calendar me-1"></i>
                        Dikirim: {{ $application->tanggal_terkirim->format('d M Y, H:i') }}
                    </p>
                </div>
                <div class="col-md-3 text-md-end">
                    @if($application->status == 'Pending')
                        <span class="badge bg-warning mb-2">
                            <i class="fas fa-clock me-1"></i>Pending
                        </span>
                    @elseif($application->status == 'Diterima')
                        <span class="badge bg-success mb-2">
                            <i class="fas fa-check-circle me-1"></i>Diterima
                        </span>
                    @else
                        <span class="badge bg-danger mb-2">
                            <i class="fas fa-times-circle me-1"></i>Ditolak
                        </span>
                    @endif
                    <br>
                    <a href="{{ route('user.applications.show', $application->id_lamaran) }}" 
                       class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-eye me-1"></i>Lihat Detail
                    </a>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="card">
        <div class="card-body text-center py-5">
            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
            <h5>Belum ada lamaran</h5>
            <p class="text-muted">Mulai cari lowongan dan kirimkan lamaran Anda</p>
            <a href="{{ route('jobs.index') }}" class="btn btn-primary">
                <i class="fas fa-search me-2"></i>Cari Lowongan
            </a>
        </div>
    </div>
@endforelse

<!-- Pagination -->
<div class="d-flex justify-content-center mt-4">
    {{ $applications->links() }}
</div>
@endsection
