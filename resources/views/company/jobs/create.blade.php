@extends('layouts.company')

@section('title', 'Posting Lowongan Baru')

@section('content')
<div class="mb-4">
    <a href="{{ route('company.jobs.index') }}" class="btn btn-outline-secondary mb-3">
        <i class="fas fa-arrow-left me-2"></i>Kembali
    </a>
    <h2 class="fw-bold">Posting Lowongan Baru</h2>
    <p class="text-muted">Lengkapi form untuk menambahkan lowongan pekerjaan</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('company.jobs.store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nama_pekerjaan') is-invalid @enderror" 
                               name="nama_pekerjaan" value="{{ old('nama_pekerjaan') }}" required>
                        @error('nama_pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kecamatan <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_kecamatan') is-invalid @enderror" 
                                    name="id_kecamatan" required>
                                <option value="">Pilih Kecamatan...</option>
                                @foreach(\App\Models\Kecamatan::all() as $kec)
                                    <option value="{{ $kec->id_kecamatan }}" {{ old('id_kecamatan') == $kec->id_kecamatan ? 'selected' : '' }}>
                                        {{ $kec->nama_kecamatan }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kecamatan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kategori <span class="text-danger">*</span></label>
                            <select class="form-select @error('id_kategori') is-invalid @enderror" 
                                    name="id_kategori" required>
                                <option value="">Pilih Kategori...</option>
                                @foreach(\App\Models\Sektor::all() as $sektor)
                                    <option value="{{ $sektor->id_sektor }}" {{ old('id_kategori') == $sektor->id_sektor ? 'selected' : '' }}>
                                        {{ $sektor->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gaji Minimum <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('gaji_min') is-invalid @enderror" 
                                   name="gaji_min" value="{{ old('gaji_min') }}" required min="0">
                            @error('gaji_min')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Gaji Maksimum <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('gaji_max') is-invalid @enderror" 
                                   name="gaji_max" value="{{ old('gaji_max') }}" required min="0">
                            @error('gaji_max')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi Pekerjaan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('deskripsi_pekerjaan') is-invalid @enderror" 
                                  name="deskripsi_pekerjaan" rows="5" required>{{ old('deskripsi_pekerjaan') }}</textarea>
                        @error('deskripsi_pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Persyaratan Pekerjaan <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('persyaratan_pekerjaan') is-invalid @enderror" 
                                  name="persyaratan_pekerjaan" rows="5" required>{{ old('persyaratan_pekerjaan') }}</textarea>
                        @error('persyaratan_pekerjaan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jumlah Lowongan <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('jumlah_lowongan') is-invalid @enderror" 
                                   name="jumlah_lowongan" value="{{ old('jumlah_lowongan') }}" required min="1">
                            @error('jumlah_lowongan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Jenis Pekerjaan <span class="text-danger">*</span></label>
                            <select class="form-select @error('jenis_pekerjaan') is-invalid @enderror" 
                                    name="jenis_pekerjaan" required>
                                <option value="">Pilih Jenis...</option>
                                <option value="Full-Time" {{ old('jenis_pekerjaan') == 'Full-Time' ? 'selected' : '' }}>Full-Time</option>
                                <option value="Part-Time" {{ old('jenis_pekerjaan') == 'Part-Time' ? 'selected' : '' }}>Part-Time</option>
                                <option value="Kontrak" {{ old('jenis_pekerjaan') == 'Kontrak' ? 'selected' : '' }}>Kontrak</option>
                            </select>
                            @error('jenis_pekerjaan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Berakhir <span class="text-danger">*</span></label>
                        <input type="date" class="form-control @error('tanggal_expired') is-invalid @enderror" 
                               name="tanggal_expired" value="{{ old('tanggal_expired') }}" 
                               min="{{ date('Y-m-d') }}" required>
                        @error('tanggal_expired')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="fas fa-save me-2"></i>Posting Lowongan
                        </button>
                        <a href="{{ route('company.jobs.index') }}" class="btn btn-outline-secondary">
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card bg-light">
            <div class="card-body">
                <h5 class="mb-3"><i class="fas fa-info-circle me-2"></i>Tips Posting Lowongan</h5>
                <ul class="small">
                    <li>Gunakan judul yang jelas dan spesifik</li>
                    <li>Deskripsikan tanggung jawab pekerjaan dengan detail</li>
                    <li>Cantumkan persyaratan yang realistis</li>
                    <li>Berikan informasi gaji yang transparan</li>
                    <li>Pastikan tanggal berakhir cukup untuk mendapat pelamar</li>
                </ul>

                <div class="alert alert-warning small mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Lowongan akan direview oleh admin sebelum dipublikasikan
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
