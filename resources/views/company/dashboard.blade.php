@extends('layouts.company')

@section('title', 'Dashboard Perusahaan')

@section('content')
<div class="mb-4">
    <h2 class="fw-bold">Dashboard Perusahaan</h2>
    <p class="text-muted">{{ $company->nama_perusahaan }}</p>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Lowongan</p>
                        <h3 class="fw-bold">{{ $totalJobs }}</h3>
                    </div>
                    <i class="fas fa-briefcase fa-2x text-primary"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Lowongan Aktif</p>
                        <h3 class="fw-bold">{{ $activeJobs }}</h3>
                    </div>
                    <i class="fas fa-check-circle fa-2x text-success"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Pelamar</p>
                        <h3 class="fw-bold">{{ $totalApplications }}</h3>
                    </div>
                    <i class="fas fa-users fa-2x text-info"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3 col-sm-6 mb-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Pending</p>
                        <h3 class="fw-bold">{{ $pendingApplications }}</h3>
                    </div>
                    <i class="fas fa-clock fa-2x text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <a href="{{ route('company.jobs.create') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="fas fa-plus-circle fa-3x text-success mb-3"></i>
                <h5>Posting Lowongan Baru</h5>
                <p class="text-muted">Tambahkan lowongan kerja baru</p>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <a href="{{ route('company.applicants.index') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h5>Lihat Pelamar</h5>
                <p class="text-muted">Kelola lamaran yang masuk</p>
            </div>
        </a>
    </div>

    <div class="col-md-4 mb-3">
        <a href="{{ route('company.jobs.index') }}" class="card text-decoration-none text-dark h-100">
            <div class="card-body text-center">
                <i class="fas fa-briefcase fa-3x text-info mb-3"></i>
                <h5>Kelola Lowongan</h5>
                <p class="text-muted">Edit dan hapus lowongan</p>
            </div>
        </a>
    </div>
</div>

<!-- Recent Jobs -->
<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Lowongan Terbaru</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Posisi</th>
                        <th>Lokasi</th>
                        <th>Status</th>
                        <th>Pelamar</th>
                        <th>Tanggal Posting</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentJobs as $job)
                        <tr>
                            <td>{{ $job->nama_pekerjaan }}</td>
                            <td>{{ $job->kecamatan->nama_kecamatan }}</td>
                            <td>
                                @if($job->is_aktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Berakhir</span>
                                @endif
                            </td>
                            <td>{{ $job->lamaran->count() }} orang</td>
                            <td>{{ $job->tanggal_posting->format('d M Y') }}</td>
                            <td>
                                <a href="{{ route('company.jobs.show', $job->id_pekerjaan) }}" 
                                   class="btn btn-sm btn-primary">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada lowongan</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
