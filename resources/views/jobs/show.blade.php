@extends('layouts.app')

@section('title', $job->nama_pekerjaan)

@section('content')
<div class="container py-4">
    <div class="row">
        <!-- Job Details -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-body">
                    <!-- Company Info -->
                    <div class="d-flex align-items-start mb-4">
                        <div class="me-3">
                            @if($job->perusahaan->logo)
                                <img src="{{ Storage::url($job->perusahaan->logo) }}" 
                                     alt="{{ $job->perusahaan->nama_perusahaan }}"
                                     class="rounded" 
                                     style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" 
                                     style="width: 100px; height: 100px;">
                                    <i class="fas fa-building fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1">
                            <h2 class="mb-2">{{ $job->nama_pekerjaan }}</h2>
                            <h5 class="text-muted mb-3">{{ $job->perusahaan->nama_perusahaan }}</h5>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-primary">{{ $job->jenis_pekerjaan }}</span>
                                <span class="badge bg-info">{{ $job->kategori->nama_kategori }}</span>
                                @if($job->is_aktif)
                                    <span class="badge bg-success">Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Berakhir</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Job Info -->
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-map-marker-alt fa-lg text-primary me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Lokasi</small>
                                    <strong>{{ $job->kecamatan->nama_kecamatan }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-money-bill-wave fa-lg text-success me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Gaji</small>
                                    <strong>Rp {{ number_format($job->gaji_min) }} - Rp {{ number_format($job->gaji_max) }}</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users fa-lg text-info me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Jumlah Lowongan</small>
                                    <strong>{{ $job->jumlah_lowongan }} orang</strong>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar fa-lg text-warning me-3"></i>
                                <div>
                                    <small class="text-muted d-block">Batas Lamaran</small>
                                    <strong>{{ $job->tanggal_expired->format('d M Y') }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- Job Description -->
                    <div class="mb-4">
                        <h5 class="mb-3"><i class="fas fa-file-alt me-2"></i>Deskripsi Pekerjaan</h5>
                        <div class="text-muted" style="white-space: pre-line;">{{ $job->deskripsi_pekerjaan }}</div>
                    </div>

                    <!-- Requirements -->
                    <div class="mb-4">
                        <h5 class="mb-3"><i class="fas fa-list-check me-2"></i>Persyaratan</h5>
                        <div class="text-muted" style="white-space: pre-line;">{{ $job->persyaratan_pekerjaan }}</div>
                    </div>

                    <!-- Posted Date -->
                    <div class="text-muted small">
                        <i class="fas fa-clock me-1"></i>
                        Diposting {{ $job->tanggal_posting->diffForHumans() }}
                    </div>
                </div>
            </div>

            <!-- Company Profile -->
            <div class="card">
                <div class="card-header bg-white">
                    <h5 class="mb-0"><i class="fas fa-building me-2"></i>Tentang Perusahaan</h5>
                </div>
                <div class="card-body">
                    <h6>{{ $job->perusahaan->nama_perusahaan }}</h6>
                    @if($job->perusahaan->deskripsi)
                        <p class="text-muted">{{ $job->perusahaan->deskripsi }}</p>
                    @endif
                    <div class="text-muted small">
                        <p class="mb-1">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            {{ $job->perusahaan->alamat }}, {{ $job->perusahaan->kecamatan->nama_kecamatan }}
                        </p>
                        @if($job->perusahaan->no_telepon)
                            <p class="mb-1">
                                <i class="fas fa-phone me-2"></i>
                                {{ $job->perusahaan->no_telepon }}
                            </p>
                        @endif
                        @if($job->perusahaan->email)
                            <p class="mb-0">
                                <i class="fas fa-envelope me-2"></i>
                                {{ $job->perusahaan->email }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Apply Sidebar -->
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-body">
                    @auth('web')
                        @php
                            $hasApplied = \App\Models\Lamaran::where('id_user', Auth::guard('web')->user()->id_user)
                                                             ->where('id_pekerjaan', $job->id_pekerjaan)
                                                             ->exists();
                        @endphp

                        @if($hasApplied)
                            <div class="alert alert-info">
                                <i class="fas fa-check-circle me-2"></i>
                                Anda sudah melamar pekerjaan ini
                            </div>
                            <a href="{{ route('user.applications.index') }}" class="btn btn-outline-primary w-100">
                                Lihat Status Lamaran
                            </a>
                        @elseif(!$job->is_aktif)
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                Lowongan ini sudah berakhir
                            </div>
                        @else
                            <h5 class="mb-3">Tertarik dengan pekerjaan ini?</h5>
                            <p class="text-muted small mb-3">
                                Kirimkan lamaran Anda sekarang dan tingkatkan peluang diterima
                            </p>
                            <a href="{{ route('jobs.show', $job->id_pekerjaan) }}#apply-form" 
                               class="btn btn-primary w-100 mb-2">
                                <i class="fas fa-paper-plane me-2"></i>Lamar Sekarang
                            </a>
                            <small class="text-muted d-block text-center">
                                Pastikan CV dan dokumen Anda sudah lengkap
                            </small>
                        @endif
                    @else
                        <h5 class="mb-3">Tertarik dengan pekerjaan ini?</h5>
                        <p class="text-muted small mb-3">
                            Login atau daftar untuk melamar pekerjaan ini
                        </p>
                        <a href="{{ route('auth.login') }}" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </a>
                        <a href="{{ route('auth.register') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-user-plus me-2"></i>Daftar
                        </a>
                    @endauth

                    <hr>

                    <h6 class="mb-3">Bagikan Lowongan</h6>
                    <div class="d-grid gap-2">
                        <a href="https://wa.me/?text=Lihat lowongan ini: {{ route('jobs.show', $job->id_pekerjaan) }}" 
                           target="_blank" class="btn btn-success btn-sm">
                            <i class="fab fa-whatsapp me-2"></i>WhatsApp
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ route('jobs.show', $job->id_pekerjaan) }}" 
                           target="_blank" class="btn btn-primary btn-sm">
                            <i class="fab fa-facebook me-2"></i>Facebook
                        </a>
                        <button class="btn btn-outline-secondary btn-sm" onclick="copyLink()">
                            <i class="fas fa-link me-2"></i>Salin Link
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Application Form (Only for authenticated users) -->
    @auth('web')
        @if(!$hasApplied && $job->is_aktif)
            <div id="apply-form" class="row mt-5">
                <div class="col-md-8 offset-md-2">
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-paper-plane me-2"></i>Form Lamaran</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('user.applications.store') }}" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="id_pekerjaan" value="{{ $job->id_pekerjaan }}">

                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Pastikan semua dokumen yang Anda upload valid dan terbaru
                                </div>

                                <!-- Upload CV -->
                                <div class="mb-3">
                                    <label class="form-label">Upload CV <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('cv') is-invalid @enderror" 
                                           name="cv" required accept=".pdf,.doc,.docx">
                                    <small class="text-muted">Format: PDF, DOC, DOCX. Maksimal 2MB</small>
                                    @error('cv')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Upload KTP -->
                                <div class="mb-3">
                                    <label class="form-label">Upload KTP <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('ktp') is-invalid @enderror" 
                                           name="ktp" required accept="image/*,.pdf">
                                    <small class="text-muted">Format: JPG, PNG, PDF. Maksimal 2MB</small>
                                    @error('ktp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Upload Sertifikat (Optional) -->
                                <div class="mb-3">
                                    <label class="form-label">Upload Sertifikat (Opsional)</label>
                                    <input type="file" class="form-control @error('sertifikat') is-invalid @enderror" 
                                           name="sertifikat" accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">Format: PDF, JPG, PNG. Maksimal 2MB</small>
                                    @error('sertifikat')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Upload Foto Diri -->
                                <div class="mb-3">
                                    <label class="form-label">Upload Foto Diri <span class="text-danger">*</span></label>
                                    <input type="file" class="form-control @error('foto_diri') is-invalid @enderror" 
                                           name="foto_diri" required accept="image/*">
                                    <small class="text-muted">Format: JPG, PNG. Maksimal 2MB</small>
                                    @error('foto_diri')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="fas fa-paper-plane me-2"></i>Kirim Lamaran
                                    </button>
                                    <a href="{{ route('jobs.index') }}" class="btn btn-outline-secondary">
                                        Batal
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth
</div>

@push('scripts')
<script>
function copyLink() {
    const url = "{{ route('jobs.show', $job->id_pekerjaan) }}";
    navigator.clipboard.writeText(url).then(() => {
        alert('Link berhasil disalin!');
    });
}
</script>
@endpush
@endsection
