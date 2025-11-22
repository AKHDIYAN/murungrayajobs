@extends('layouts.app')

@section('title', 'Login Perusahaan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm mt-5">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-building fa-4x text-success"></i>
                        <h4 class="mt-3">Login Perusahaan</h4>
                        <p class="text-muted">Masuk ke akun perusahaan Anda</p>
                    </div>

                    <form method="POST" action="{{ route('company.auth.login.submit') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Username atau Email</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror" 
                                   name="username" value="{{ old('username') }}" required autofocus>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 mb-3">
                            <i class="fas fa-sign-in-alt me-2"></i>Login
                        </button>

                        <div class="text-center">
                            <p class="mb-0">Belum terdaftar? 
                                <a href="{{ route('company.auth.register') }}">Daftar Perusahaan</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
