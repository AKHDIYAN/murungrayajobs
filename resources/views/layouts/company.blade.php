<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard Perusahaan') - Portal Kerja</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            min-height: 100vh;
            background-color: #fff;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        
        .sidebar .nav-link {
            color: #212529;
            padding: 0.75rem 1.25rem;
            border-radius: 0.375rem;
            margin-bottom: 0.25rem;
        }
        
        .sidebar .nav-link:hover {
            background-color: #e9ecef;
        }
        
        .sidebar .nav-link.active {
            background-color: #198754;
            color: #fff;
        }
        
        .sidebar .nav-link i {
            width: 20px;
            margin-right: 0.5rem;
        }
        
        .badge-verified {
            background-color: #198754;
        }
        
        .badge-pending {
            background-color: #ffc107;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 px-0 sidebar">
                <div class="p-3">
                    <h6 class="mb-2">{{ Auth::guard('company')->user()->nama_perusahaan }}</h6>
                    @if(Auth::guard('company')->user()->is_verified)
                        <span class="badge badge-verified">
                            <i class="fas fa-check-circle"></i> Terverifikasi
                        </span>
                    @else
                        <span class="badge badge-pending">
                            <i class="fas fa-clock"></i> Menunggu Verifikasi
                        </span>
                    @endif
                    <hr>
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('company.dashboard') ? 'active' : '' }}" href="{{ route('company.dashboard') }}">
                            <i class="fas fa-home"></i>Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('company.jobs.*') ? 'active' : '' }}" href="{{ route('company.jobs.index') }}">
                            <i class="fas fa-briefcase"></i>Lowongan Saya
                        </a>
                        <a class="nav-link {{ request()->routeIs('company.applicants.*') ? 'active' : '' }}" href="{{ route('company.applicants.index') }}">
                            <i class="fas fa-users"></i>Pelamar
                        </a>
                        <a class="nav-link {{ request()->routeIs('company.profile') ? 'active' : '' }}" href="{{ route('company.profile') }}">
                            <i class="fas fa-building"></i>Profil Perusahaan
                        </a>
                        <hr>
                        <a class="nav-link" href="{{ route('home') }}">
                            <i class="fas fa-arrow-left"></i>Kembali ke Beranda
                        </a>
                        <form action="{{ route('company.auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="nav-link btn btn-link text-danger w-100 text-start">
                                <i class="fas fa-sign-out-alt"></i>Logout
                            </button>
                        </form>
                    </nav>
                </div>
            </div>
            
            <div class="col-md-9 col-lg-10">
                <div class="p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    
    @stack('scripts')
</body>
</html>
