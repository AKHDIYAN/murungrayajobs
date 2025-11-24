<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\CompanyAuthController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\User\UserDashboardController;
use App\Http\Controllers\User\ApplicationController as UserApplicationController;
use App\Http\Controllers\Company\CompanyDashboardController;
use App\Http\Controllers\Company\CompanyJobController;
use App\Http\Controllers\Company\ApplicantController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\AdminCompanyController;
use App\Http\Controllers\Admin\AdminJobController;
use App\Http\Controllers\Admin\AdminApplicationController;
use App\Http\Controllers\Admin\AdminStatisticsController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Auth\UserForgotPasswordController;
use App\Http\Controllers\Auth\CompanyForgotPasswordController;
use App\Http\Controllers\Auth\AdminForgotPasswordController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Job Listings (Public)
Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [JobController::class, 'index'])->name('index');
    Route::get('/{id}', [JobController::class, 'show'])->name('show');
});

// Map
Route::get('/map', [HomeController::class, 'map'])->name('map');

// Statistics (Public)
Route::get('/statistics', [StatisticsController::class, 'index'])->name('statistics.index');

/*
|--------------------------------------------------------------------------
| User (Pelamar) Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->name('auth.')->group(function () {
    // Registration
    Route::get('/register', [UserAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserAuthController::class, 'register'])->name('register.submit');
    
    // Login
    Route::get('/login', [UserAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserAuthController::class, 'login'])->name('login.submit');
    
    // Logout
    Route::post('/logout', [UserAuthController::class, 'logout'])->name('logout');
});

// User Password Reset Routes
Route::prefix('password')->name('user.password.')->group(function () {
    Route::get('/forgot', [UserForgotPasswordController::class, 'showForgotForm'])->name('forgot');
    Route::post('/email', [UserForgotPasswordController::class, 'sendResetLink'])->name('email');
    Route::get('/reset/{token}', [UserForgotPasswordController::class, 'showResetForm'])->name('reset');
    Route::post('/reset', [UserForgotPasswordController::class, 'resetPassword'])->name('update');
});

/*
|--------------------------------------------------------------------------
| User (Pelamar) Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('user')->name('user.')->middleware(['user'])->group(function () {
    Route::get('/dashboard', [UserDashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('/profile', [UserDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [UserDashboardController::class, 'updateProfileNew'])->name('profile.update');
    
    // Applications
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [UserApplicationController::class, 'index'])->name('index');
        Route::get('/{id}', [UserApplicationController::class, 'show'])->name('show');
        Route::post('/', [UserApplicationController::class, 'store'])->name('store');
    });
    
    // Settings
    Route::get('/settings', [UserDashboardController::class, 'settings'])->name('settings');
    Route::put('/password', [UserDashboardController::class, 'updatePassword'])->name('password.update');
    Route::put('/email', [UserDashboardController::class, 'updateEmail'])->name('email.update');
});

/*
|--------------------------------------------------------------------------
| Company Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('company/auth')->group(function () {
    // Registration
    Route::get('/register', [CompanyAuthController::class, 'showRegisterForm'])->name('company.register');
    Route::post('/register', [CompanyAuthController::class, 'register'])->name('company.register.submit');
    
    // Login
    Route::get('/login', [CompanyAuthController::class, 'showLoginForm'])->name('company.login');
    Route::post('/login', [CompanyAuthController::class, 'login'])->name('company.login.submit');
    
    // Logout
    Route::post('/logout', [CompanyAuthController::class, 'logout'])->name('company.logout');
});

// Company Password Reset Routes
Route::prefix('company/password')->name('company.password.')->group(function () {
    Route::get('/forgot', [CompanyForgotPasswordController::class, 'showForgotForm'])->name('forgot');
    Route::post('/email', [CompanyForgotPasswordController::class, 'sendResetLink'])->name('email');
    Route::get('/reset/{token}', [CompanyForgotPasswordController::class, 'showResetForm'])->name('reset');
    Route::post('/reset', [CompanyForgotPasswordController::class, 'resetPassword'])->name('update');
});

/*
|--------------------------------------------------------------------------
| Company Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('company')->name('company.')->middleware(['company', 'check.job.expiry'])->group(function () {
    Route::get('/dashboard', [CompanyDashboardController::class, 'index'])->name('dashboard');
    
    // Jobs Management
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [CompanyJobController::class, 'index'])->name('index');
        Route::get('/create', [CompanyJobController::class, 'create'])->name('create');
        Route::post('/', [CompanyJobController::class, 'store'])->name('store');
        Route::get('/{id}', [CompanyJobController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [CompanyJobController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CompanyJobController::class, 'update'])->name('update');
        Route::delete('/{id}', [CompanyJobController::class, 'destroy'])->name('destroy');
    });
    
    // Applicants Management (FITUR UTAMA)
    Route::prefix('applicants')->name('applicants.')->group(function () {
        Route::get('/', [ApplicantController::class, 'index'])->name('index');
        Route::get('/{id}', [ApplicantController::class, 'show'])->name('show');
        Route::get('/{id}/download-pdf', [ApplicantController::class, 'downloadPDF'])->name('download-pdf');
        Route::get('/download-all-pdf', [ApplicantController::class, 'downloadAllPDF'])->name('download-all-pdf');
        Route::put('/{id}/status', [ApplicantController::class, 'updateStatus'])->name('update-status');
    });
    
    // Profile
    Route::get('/profile', [CompanyDashboardController::class, 'profile'])->name('profile');
    Route::put('/profile', [CompanyDashboardController::class, 'updateProfile'])->name('profile.update');
    
    // Settings
    Route::get('/settings', [CompanyDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings/password', [CompanyDashboardController::class, 'updatePassword'])->name('settings.password.update');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin/auth')->name('admin.')->group(function () {
    // Login
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    
    // Logout
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
});

// Admin Password Reset Routes
Route::prefix('admin/password')->name('admin.password.')->group(function () {
    Route::get('/forgot', [AdminForgotPasswordController::class, 'showForgotForm'])->name('forgot');
    Route::post('/email', [AdminForgotPasswordController::class, 'sendResetLink'])->name('email');
    Route::get('/reset/{token}', [AdminForgotPasswordController::class, 'showResetForm'])->name('reset');
    Route::post('/reset', [AdminForgotPasswordController::class, 'resetPassword'])->name('update');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->middleware(['admin', 'admin.logger'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminUserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/suspend', [AdminUserController::class, 'suspend'])->name('suspend');
        Route::put('/{id}/activate', [AdminUserController::class, 'activate'])->name('activate');
    });
    
    // Company Management
    Route::prefix('companies')->name('companies.')->group(function () {
        Route::get('/', [AdminCompanyController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminCompanyController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminCompanyController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminCompanyController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminCompanyController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/verify', [AdminCompanyController::class, 'verify'])->name('verify');
        Route::post('/{id}/unverify', [AdminCompanyController::class, 'unverify'])->name('unverify');
        Route::put('/{id}/suspend', [AdminCompanyController::class, 'suspend'])->name('suspend');
    });
    
    // Admin Management
    Route::prefix('admins')->name('admins.')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'adminList'])->name('index');
        Route::get('/create', [AdminDashboardController::class, 'createAdmin'])->name('create');
        Route::post('/', [AdminDashboardController::class, 'storeAdmin'])->name('store');
        Route::delete('/{id}', [AdminDashboardController::class, 'destroyAdmin'])->name('destroy');
    });
    
    // Job Management (All Companies)
    Route::prefix('jobs')->name('jobs.')->group(function () {
        Route::get('/', [AdminJobController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminJobController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminJobController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminJobController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminJobController::class, 'destroy'])->name('destroy');
        Route::put('/{id}/approve', [AdminJobController::class, 'approve'])->name('approve');
        Route::put('/{id}/reject', [AdminJobController::class, 'reject'])->name('reject');
    });
    
    // Application Management (All Applications)
    Route::prefix('applications')->name('applications.')->group(function () {
        Route::get('/', [AdminApplicationController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminApplicationController::class, 'show'])->name('show');
        Route::get('/{id}/download-pdf', [AdminApplicationController::class, 'downloadPDF'])->name('download-pdf');
        Route::get('/export-excel', [AdminApplicationController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-pdf', [AdminApplicationController::class, 'exportPDF'])->name('export-pdf');
        Route::put('/{id}/status', [AdminApplicationController::class, 'updateStatus'])->name('update-status');
        Route::delete('/{id}', [AdminApplicationController::class, 'destroy'])->name('destroy');
    });
    
    // Statistics Management
    Route::prefix('statistics')->name('statistics.')->group(function () {
        Route::get('/', [AdminStatisticsController::class, 'dashboard'])->name('index');
        Route::get('/data', [AdminStatisticsController::class, 'dataIndex'])->name('data.index');
        Route::get('/data/create', [AdminStatisticsController::class, 'create'])->name('create');
        Route::post('/data', [AdminStatisticsController::class, 'store'])->name('store');
        Route::get('/data/{id}/edit', [AdminStatisticsController::class, 'edit'])->name('edit');
        Route::put('/data/{id}', [AdminStatisticsController::class, 'update'])->name('update');
        Route::delete('/data/{id}', [AdminStatisticsController::class, 'destroy'])->name('destroy');
        Route::post('/import', [AdminStatisticsController::class, 'import'])->name('import');
        Route::get('/download-template', [AdminStatisticsController::class, 'downloadTemplate'])->name('download-template');
        Route::get('/export-excel', [AdminStatisticsController::class, 'exportExcel'])->name('export-excel');
        Route::get('/export-pdf', [AdminStatisticsController::class, 'exportPDF'])->name('export-pdf');
    });
    
    // Master Data Management
    Route::prefix('master-data')->name('master-data.')->group(function () {
        // Kecamatan
        Route::prefix('kecamatan')->name('kecamatan.')->group(function () {
            Route::get('/', [MasterDataController::class, 'kecamatanIndex'])->name('index');
            Route::post('/', [MasterDataController::class, 'kecamatanStore'])->name('store');
            Route::put('/{id}', [MasterDataController::class, 'kecamatanUpdate'])->name('update');
            Route::delete('/{id}', [MasterDataController::class, 'kecamatanDestroy'])->name('destroy');
        });
        
        // Sektor
        Route::prefix('sektor')->name('sektor.')->group(function () {
            Route::get('/', [MasterDataController::class, 'sektorIndex'])->name('index');
            Route::post('/', [MasterDataController::class, 'sektorStore'])->name('store');
            Route::put('/{id}', [MasterDataController::class, 'sektorUpdate'])->name('update');
            Route::delete('/{id}', [MasterDataController::class, 'sektorDestroy'])->name('destroy');
        });
        
        // Pendidikan
        Route::prefix('pendidikan')->name('pendidikan.')->group(function () {
            Route::get('/', [MasterDataController::class, 'pendidikanIndex'])->name('index');
            Route::post('/', [MasterDataController::class, 'pendidikanStore'])->name('store');
            Route::put('/{id}', [MasterDataController::class, 'pendidikanUpdate'])->name('update');
            Route::delete('/{id}', [MasterDataController::class, 'pendidikanDestroy'])->name('destroy');
        });
        
        // Usia
        Route::prefix('usia')->name('usia.')->group(function () {
            Route::get('/', [MasterDataController::class, 'usiaIndex'])->name('index');
            Route::post('/', [MasterDataController::class, 'usiaStore'])->name('store');
            Route::put('/{id}', [MasterDataController::class, 'usiaUpdate'])->name('update');
            Route::delete('/{id}', [MasterDataController::class, 'usiaDestroy'])->name('destroy');
        });
    });
    
    // Activity Logs
    Route::prefix('logs')->name('logs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdminLogsController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\AdminLogsController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\AdminLogsController::class, 'destroy'])->name('destroy');
        Route::post('/clear', [\App\Http\Controllers\Admin\AdminLogsController::class, 'clear'])->name('clear');
    });
    Route::get('/logs', [\App\Http\Controllers\Admin\AdminLogsController::class, 'index'])->name('logs');
    
    // System Settings
    Route::get('/settings', [AdminDashboardController::class, 'settings'])->name('settings');
    Route::put('/settings', [AdminDashboardController::class, 'updateSettings'])->name('settings.update');
});

/*
|--------------------------------------------------------------------------
| API Routes (Optional - for AJAX/JSON responses)
|--------------------------------------------------------------------------
*/

Route::prefix('api')->name('api.')->middleware(['check.job.expiry'])->group(function () {
    // Public API
    Route::get('/jobs', [JobController::class, 'apiIndex'])->name('jobs.index');
    Route::get('/jobs/{id}', [JobController::class, 'apiShow'])->name('jobs.show');
    Route::get('/kecamatan', [HomeController::class, 'apiKecamatan'])->name('kecamatan');
    Route::get('/sektor', [HomeController::class, 'apiSektor'])->name('sektor');
    Route::get('/statistics/data', [StatisticsController::class, 'apiData'])->name('statistics.data');
    
    // Map data for Leaflet
    Route::get('/map/data', [HomeController::class, 'apiMapData'])->name('map.data');
});
